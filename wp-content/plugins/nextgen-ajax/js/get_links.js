/*
* AUTOR: Steffen Persch
* WEBSITE: http://www.steffenpersch.de
* REQUIRES: mootools!
*/
window.addEvent('domready', function() {
    generateAjaxCompatibleSrc();
    getPageLinks();
});


function generateAjaxCompatibleSrc() {
    var ngga_Navis = $$('.ngg-navigation');
    if (ngga_Navis.length <= 0) {
        return;
    }
    
    for (var i = 0; i < ngga_Navis.length; i++) {
        var ngga_actNavi = ngga_Navis[i];
        
        ngga_container = new Element('div', {id: 'ngg_ajax_' + i});
        ngga_container.inject(ngga_actNavi, 'after');
        ngga_actNavi.getPrevious().inject(ngga_container);
        ngga_actNavi.inject(ngga_container);
    }
}

function getPageLinks() {
    var nggPageLinks = $$('.ngg-navigation a');
    nggPageLinks.removeEvents();
    if (nggPageLinks.length <= 0) {
        return;
    }
    for (var i = 0; i < nggPageLinks.length; i++) {
        var actLink = nggPageLinks[i];
        nggPageLinks[i].addEvent('click', function (e) {
            e.stop();
            
            ngga_linkurl = this.get('href');
            ngga_linkpos = ngga_linkurl.indexOf('?');
            ngga_containerid = this.getParent().getParent().get('id');
            ngga_galerie = this.getParent().getPrevious().get('id').substr(12);

            var req = new Request({url: '/wp-content/plugins/nextgen_ajax/nggajax.php' + ngga_linkurl.substr(ngga_linkpos) + '&req=true&galerieid=' + ngga_galerie + '&containerid=' + ngga_containerid, 
                onSuccess: function(html) {
                    ngga_splitter = html.indexOf('::-::');
                    containerid = html.substr(0, ngga_splitter);
                    target = $(containerid);
                    target.set('text', '');
                    target.set('html', html.substr(ngga_splitter + 5));
                    getPageLinks();
                    slimbox_scanpage();
                }
            });
            
            req.send();
            
        });
    }
    
}