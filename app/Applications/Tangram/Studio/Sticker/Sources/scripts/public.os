public {
    onload() {
		//YangRAM.API.view.highlight.highlightAll();
		return this.resize();
	},
	main (){
		__thisapp__.listenEvents(self.events).regHeadBar(TAMBAR).loadStyle(function() {
            __thisapp__.loadURI(__thisapp__.__temp.href);
        });
	}
};