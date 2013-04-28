/**
 * Wikinvest LiveQuotes Plugin for TinyMCE2
 * @author Wikinvest
 * @copyright Copyright © 2004-2007, Wikinvest
 */
(function() {
	
	var TinyMCE_WikinvestLiveQuotesPlugin = {
	getInfo : function() {
		return {
			longname : 'Wikinvest Stock Quotes',
			author : 'Wikinvest',
			authorurl : 'http://www.wikinvest.com/',
			infourl : 'http://www.wikinvest.com/blogger/wikinvest_stockquotes',
			version : tinyMCE.majorVersion + "." + tinyMCE.minorVersion
		};
	},

	initInstance : function(inst) {
        
    },

    getControlHTML : function(cn) {
		if(typeof wikinvestStockQuotes_buttonUrl != "undefined") {
			switch (cn) {
				case "wikinvestStockQuotes": return tinyMCE.getButtonHTML(cn, 'Display in-line stock quotes on your post', wikinvestStockQuotes_buttonUrl, 'wikinvestStockQuotes');
			}
		}
        return '';
    },


    execCommand : function(editor_id, element, command, user_interface, value) {
        switch (command) {
            case "wikinvestStockQuotes": 
				if(typeof window.wikinvestStockQuotesPlugin != "undefined") {
					window.wikinvestStockQuotesPlugin.LoadStockQuotes();
				}
            }
            return false;
    },
};

tinyMCE.addPlugin("wikinvestStockQuotes", TinyMCE_WikinvestLiveQuotesPlugin);
	
	
})();