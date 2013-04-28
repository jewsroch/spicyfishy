/**
 * Wikinvest Stock Quotes Plugin for TinyMCE3
 * @author Wikinvest
 * @copyright Copyright © 2004-2007, Wikinvest
 */
 
(function() {
	
	tinymce.create('tinymce.plugins.wikinvestStockQuotes', {
		/**
		 * Initializes the plugin, this will be executed after the plugin has been created.
		 * This call is done before the editor instance has finished it's initialization so use the onInit event
		 * of the editor instance to intercept that event.
		 *
		 * @param {tinymce.Editor} ed Editor instance that the plugin is initialized in.
		 * @param {string} url Absolute URL to where the plugin is located.
		 */
		init : function(ed, url) {
			if(typeof wikinvestStockQuotes_buttonUrl != "undefined") {
				ed.addButton('wikinvestStockQuotes', {
					title : 'Display in-line stock quotes on your post',
					image : wikinvestStockQuotes_buttonUrl,
					onclick : function() {
						if(typeof(wikinvestStockQuotesPlugin) != "undefined") {
							window.wikinvestStockQuotesPlugin.LoadStockQuotes();
						}
						else {
							alert("Unable to load the Wikinvest Stock Quotes Plugin. Please refresh the page and try again");	
						}
					}
				});
			}
		},

		/**
		 * Returns information about the plugin as a name/value array.
		 * The current keys are longname, author, authorurl, infourl and version.
		 *
		 * @return {Object} Name/value array containing information about the plugin.
		 */
		getInfo : function() {
			return {
				longname : "Wikinvest Stock Quotes",
				author : 'Wikinvest',
				authorurl : 'http://www.wikinvest.com/',
				infourl : 'http://www.wikinvest.com/blogger/wikinvest_stockquotes',
				version : "0.1"
			};
		}
	});

	// Register plugin
	tinymce.PluginManager.add('wikinvestStockQuotes', tinymce.plugins.wikinvestStockQuotes);
})();