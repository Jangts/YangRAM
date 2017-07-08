public {
    open (href, must){
		if (typeof href=='string') {
            var tabPath=(href.split('?')[0] + '/').split(/\/+/);
            var tabName=tabPath[1];
            tabName=tabName==='' ? 'startpage':tabName;
            //console.log(tabName, href, must);
            this.tabviews && this.tabviews.open(tabName, href, must);
        }
        return this;
	},
    refreshTopVision (tabPath){
		YangRAM.$('.pub-ctrl-item', this.Topbar).addClass('unavailable');
        YangRAM.$('.pub-tool', this.Topbar).removeClass('unavailable');
        //console.log(tabPath);
        if (tabPath[0] !='' && tabPath[0] !='default') {
            if (tabPath.length > 3) {
                if (tabPath[0]=='gec') {
                    YangRAM.$('.content-view, .content-prop, .content-rele', this.Topbar).addClass('unavailable');
                } else {
                    YangRAM.$('.content-tool', this.Topbar).removeClass('unavailable');
                }
            } else if (tabPath.length > 2) {
                YangRAM.$('.preset-tool', this.Topbar).removeClass('unavailable');
            } else {
                YangRAM.$('.preset-tool', this.Topbar).removeClass('unavailable');
            }
        }
        return this;
	},
	onlaunch (href) {
		return this.open(href);
	},
	main (){
		__thisapp__.listenEvents(self.events).regHeadBar(self.menuOnHigabar).loadStyle(function() {
            __thisapp__.loadURI('default/startpage/').regBackgroundLayer('rgba(255,255,255,0.9)', true);
        });
	}
};