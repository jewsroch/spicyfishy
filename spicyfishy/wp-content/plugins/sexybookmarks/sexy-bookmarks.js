jQuery(document).ready(function() {
	if (jQuery('#iconator')) jQuery('#sexy-networks').sortable();
	if (jQuery('#sexy-bookmarks')) {
		jQuery('#autocenter-yes').click(sexyToggleCustomCss);
		jQuery('#autocenter-no').click(sexyToggleCustomCss);
		sexyToggleCustomCss();

		jQuery('#bgimg-yes').click(function() {
			if (this.checked) {
				this.checked=jQuery('#bgimgs').removeClass('hidden');
			}
			else {
				jQuery('#bgimgs').addClass('hidden');
			}
		});


		jQuery('#sexy-yahoobuzz').click(function() {
			if (this.checked) {
				this.checked=jQuery('#ybuzz-defaults').removeClass('hidden');
			}
			else {
				jQuery('#ybuzz-defaults').addClass('hidden');
			}
		});



		jQuery('#clearShortUrls').click(function() {
			if (this.checked) {
				this.checked=confirm("Selecting this option clears ALL short URLs and cannot be undone.\nAre you sure?");
			}
		});
	}
});

function sexyToggleCustomCss() {
	if (jQuery('#autocenter-yes').attr('checked')) {
		jQuery('#xtrastyle').attr('readonly', 'readonly');
	} else {
		jQuery('#xtrastyle').removeAttr('readonly');
	}
}