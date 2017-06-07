public {
    onload() {
        return this.resize();
	},
	main (){
		__thisapp__.listenEvents(self.events).regHeadBar(self.menuOnHigabar).loadStyle(function() {
            __thisapp__.loadURI(__thisapp__.__temp.href);
        });
	}
};