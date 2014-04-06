# Video Uploader
# This library has a few requirements in order to be used:
# * Button
#   * A button with a unique id (defaults to file-upload-button but can be overwritten by passing fileUploadButtonId as an option)
#   * The button must have a data attribute called swf-url which contains the url of the uploadify swf
#   * The button must be wrapped with a div with class upload-wrapper
# * Listing
#   * a bare single upload template div with class of upload-video-template which will be copied for future uploads.
#     This exists in partial your_vides/uploads/_uploads_listing
#   * The template div should be wrapped with another div with a unique id, this id by default will be uploaded-videos-listing-container
#      but can be configured by setting the listingContainerId option

$ = jQuery.noConflict()

class VideoUploader
  constructor: (options={})->
    @options = options

    @options.fileUploadButtonId     or= "file-upload-button"
    @options.listingContainerId     or= "uploaded-videos-listing-container"
    @options.buttonContainerId      or= "upload-button-container"
    @options.uploadMainPanelId      or= "upload-main-panel"
    @options.allow_replace          ?= false
    @options.postParams             or = {}
    @options.onSuccessfulFileUpload or= (row, video)->
    @options.onSelect               or= ()->
    @options.onUploadCancelled      or= (row)->
    @options.onUploadComplete       or= (row)->


    @uploadVideoTemplate    = $("##{@options.listingContainerId} .upload-video-template")
    @fileUploadButton       = $("##{@options.fileUploadButtonId}")
    @mainUploadPanel        = $("##{@options.uploadMainPanelId}")
    @pluginUrl              = @fileUploadButton.data('plugin-url')
    @uploadTokenAndEndpoint = {token: @fileUploadButton.attr("data-token"), endpoint: @fileUploadButton.attr("data-endpoint")}
    @averageUploadSpeedData = {}
    @setupEvents()
    @getFreshUploadTokenAndEndpoint(@initializeFileUpload)

    @tornDown = false
    @disabled = false

  setupEvents: ->
    $(document).on "click", ".remove-from-list", (e)->
      e.preventDefault()
      row = $(this).parents(".svi")
      if row.data('encode')
        removedIds = $.jStorage.get("upload:removed-encode-ids", [])
        removedIds.push(row.data('encode').encode_id)
        $.jStorage.set("upload:removed-encode-ids", removedIds)
        $.jStorage.setTTL("mykey", 345600000)
      row.hide("slow")

    @mainUploadPanel.bind 'dragover', =>
      @mainUploadPanel.addClass('dragover')
    @mainUploadPanel.bind 'dragleave drop', =>
      @mainUploadPanel.removeClass('dragover')

  initializeFileUpload: =>
    runtimes = 'flash'
    @uploader = new plupload.Uploader
      runtimes : runtimes
      browse_button : @options.fileUploadButtonId
      container: @options.buttonContainerId
      url: @uploadTokenAndEndpoint.endpoint
      flash_swf_url: @fileUploadButton.data('swf-url')
      multipart: true
      multipart_params : {}
      drop_element: @options.uploadMainPanelId

    @uploader.init()

    @uploader.bind 'FilesAdded', (up, files)=>
      $.each files.reverse(), (i, file)=>
        # Do not allow uploads if disabled
        if @disabled
          @uploader.removeFile(file)
          return

        @options.onSelect()
        fileName = @truncate(file.name, 50)
        row = @uploadVideoTemplate.clone()
        row.attr("id", "upload-#{file.id}")
        row.find(".encode-title").text(fileName)

        cancel_link = row.find('.cancel-upload')
        cancel_link.show()
        cancel_link.click (e)=>
          e.preventDefault()

          if !confirm("Are you sure you want to cancel this upload?")
            return false
          @uploader.removeFile(file)
          @options.onUploadCancelled()
          @runNextUpload()

          self = this
          $(e.target).parents('.svi').fadeOut 'normal', ->
            $(this).remove()
            self.fileUploadButton.trigger('resize')

        @uploadVideoTemplate.after(row)
        row.addClass("uploading")
        row.show()
        row.trigger('resize')
      @uploader.start() if @uploadTokenAndEndpoint


    @uploader.bind 'BeforeUpload', (up, file)=>
      up.settings.url = @uploadTokenAndEndpoint.endpoint.replace('.php', '.json')
      $.extend(up.settings.multipart_params, @options.postParams)
      $.extend(up.settings.multipart_params, {uploadtoken: @uploadTokenAndEndpoint.token})
      @uploadTokenAndEndpoint = undefined
      @getUploadTokenAndEndpointForNextRequest()


    @uploader.bind 'UploadProgress', (up, file)=>
      percentage = file.percent
      speed      = up.total.bytesPerSec
      row = $("#upload-#{file.id}")
      if percentage >= 99
        statusText = "Finalizing upload"
      else
        if speed > 0 && averageSpeed = @averageUploadSpeed(file.id, speed)
          bytesRemaining = (file.size - file.loaded)
          secondsRemaining = bytesRemaining / averageSpeed
          statusText = "Uploading - #{@distanceOfTimeInWords(secondsRemaining)} remaining"
        else
          statusText = "Uploading"
      row.find(".status").html(statusText)
      progress_bar = row.find(".progress-bar-inner")
      targetWidth = Math.round(progress_bar.parent().width() * (percentage / 100))

      if (progress_bar.data('targetWidth') || 0) < targetWidth and !progress_bar.is(':animated')
        progress_bar.data('targetWidth', targetWidth)
        progress_bar.animate({
          width: targetWidth
        }, 500)

    @uploader.bind 'FileUploaded', (up, file, responseObj)=>
      responseJson = JSON.parse(responseObj.response)
      row = $("#upload-#{file.id}")
      if responseJson.video
        videoId = responseJson.video.id
        row.attr("data-video-id", videoId)
        message = "Adding to encoding queue"
        @pollEncodingStatus(videoId)
      else
        row.find(".remove-from-list").show()
        message = "Upload failed - #{responseJson.error.details}"
        row.find('.progress-bar')
          .removeClass('animated')
          .addClass('transparent')
          .children()
            .fadeOut()

      row.find(".status").html(message)
      row.find(".cancel-upload").hide()
      row.addClass("completed")
      row.removeClass("uploading")

      @options.onUploadComplete(row)
      @options.onSuccessfulFileUpload(row, responseJson.video) if responseJson.video
      @runNextUpload()


  runNextUpload: ->
    # Stop the uploader and make sure we have another token ready and then start again
    @uploader.stop()
    @getUploadTokenAndEndpoint (details)=>
      @uploader.start()


  getFreshUploadTokenAndEndpoint: (callback)->
    url = @pluginUrl + "/viddlergateway.php?m=viddler.videos.prepareUpload"
    $.getJSON url, (details)=>
      @uploadTokenAndEndpoint = details
      callback(details)

  # Gets a token the fastest way possible (either a variable we stored earlier, else a fresh one.
  # Then call the callback with the token
  getUploadTokenAndEndpoint: (callback)->
    if @uploadTokenAndEndpoint && @uploadTokenAndEndpoint.token
      callback(@uploadTokenAndEndpoint)
    else
      @getFreshUploadTokenAndEndpoint(callback)

  getUploadTokenAndEndpointForNextRequest: ->
    return if @gettingSpareToken
    @gettingSpareToken = true
    @getFreshUploadTokenAndEndpoint (details)->
      @gettingSpareToken = false

  disableUploadButton: ->
    @disabled = true
    @fileUploadButton.addClass('disabled')

  reEnableUploadButton: ->
    @disabled = false
    @fileUploadButton.removeClass('disabled')

  distanceOfTimeInWords: (seconds)->
    if seconds < 60
      unit = "second"
      value = seconds
    else if seconds < 3600
      unit = "minute"
      value = seconds / 60
    else
      unit = "hour"
      value = seconds / 60 / 60
    value = Math.round(value)
    string = "#{value} #{unit}"
    string += "s" unless value == 1
    return string

  # Because the seconds remaining for an upload jumps radically in both directions as
  # speed changes, this function records the n previous values and gives a more averaged out value
  # so the progress has a more steady feel to it.
  averageUploadSpeed: (uploadId, currentSpeed)->
    values = this.averageUploadSpeedData[uploadId] or= []
    values.shift() if values.length > 20
    values.push(currentSpeed)
    if values.length > 5
      (values.reduce (x, y) -> x+y)/values.length
    else
      return null

  pollEncodingStatus: (videoId)->
    row = $(".svi[data-video-id='#{videoId}']")
    url = @pluginUrl + "/viddlergateway.php?m=viddler.encoding.getStatus2"
    $.getJSON url, (details)=>
      encode = (e for e in details.list_result.video_encoding_list when e.video.id == videoId)[0]
      mp4    = (f for f in encode.video_file_encoding_list when f.ext == 'mp4')[0]
      if mp4.encoding_status == 'success'
        url = @pluginUrl + "/viddlergateway.php?m=viddler.videos.getDetails&video_id=#{videoId}"
        $.getJSON url, (freshVideo)=>
          mp4 = (f for f in freshVideo.files when f.ext == 'mp4')[0]
          row.find(".status").html('Complete')
          row.find('.add-to-post').show().click =>
            viddlerAddToPost(videoId, mp4.width, mp4.height)
      else if mp4.encoding_status == 'error'
        row.find(".status").html('Encoding Error')
      else if mp4.encoding_status == 'new' || mp4.encoding_status == 'encoding'
        row.find(".status").html('Encoding')
        percentage = mp4.encoding_progress
        progress_bar = row.find(".progress-bar-inner")
        targetWidth = Math.round(progress_bar.parent().width() * (percentage / 100))

        if (progress_bar.data('targetWidth') || 0) < targetWidth
          unless progress_bar.is(':animated')
            progress_bar.data('targetWidth', targetWidth)
            progress_bar.animate({
              width: targetWidth
            }, 500)
        else
          progress_bar.data('targetWidth', targetWidth)
          progress_bar.css({width: targetWidth})
        setTimeout =>
          @pollEncodingStatus(videoId)
        , 100

  # Cancel any existing uploads, stop any recurring processes
  tearDown: ->
    @uploader.destroy()

  truncate: (text, limit)->
    text = text.substr(0, limit - 3) + "..." if text.length > limit
    text

jQuery(document).ready =>
  new VideoUploader()
