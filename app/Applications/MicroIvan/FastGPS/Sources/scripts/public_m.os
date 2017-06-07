public {
    open(href, must) {
        //console.log(href, must);
        if (typeof href == 'string') {
            var tabPath = (href.split('?')[0] + '/').split(/\/+/);
            var tabName = tabPath[0];
            tabName = (tabName === '') ? 'DEFAULT' : tabName;
            //console.log(tabPath);
            this.tabviews && this.tabviews.open(tabName, href, must, __('TABS')(tabName));
        }
        return this;
    },
    refreshTopVision() {
        YangRAM.$('.page-ctrl-item', this.Topbar).addClass('unavailable');
        YangRAM.$('.sys-tool', this.Topbar).removeClass('unavailable');
        setTimeout(()=> {
        
        if (__thisapp__.$('section[data-tab-name].curr popup-form').length > 0) {
            YangRAM.$('.page-edit', this.Topbar).removeClass('unavailable');
            YangRAM.$('.page-list', this.Topbar).removeClass('unavailable');
        } else if (__thisapp__.tabviews.currTabName === __thisapp__.tabviews.startTabName) {
            YangRAM.$('.page-new', this.Topbar).removeClass('unavailable');
        }
    }, 0);
        return this.resize();
    },
        onlaunch(href, folder) {
        return this.open(href);
    },
    main (){
		__thisapp__.listenEvents(self.events).regHeadBar(self.menuOnHigabar).loadStyle(function() {
            __thisapp__.loadURI('default/startpage/');
        });
	}
}