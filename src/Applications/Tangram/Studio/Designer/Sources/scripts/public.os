public {
    onload() {
			var area = __thisapp__.$('oiml>view>vsion>form-vision>.info-section>inputs[type=textarea]>textarea[name=code]');
			if(area[0]){
				var code = area.val().replace(/\<myroot/, '<html')
					.replace(/\<\/myroot\>/ig, '</html>')
					.replace(/\<myhead/ig, '<head')
					.replace(/\<\/mymeta\>/ig, '</meta>')
					.replace(/\<mymeta/ig, '<meta')
					.replace(/\<\/myhead\>/ig, '</head>')
					.replace(/\<mycss/ig, '<style')
					.replace(/\<\/mycss\>/ig, '</style>')
					.replace(/\<myjs/ig, '<script')
					.replace(/\<\/myjs\>/ig, '</script>');
				area.val(code);
			}
			return this.resize();
		},
	listenEvents() {
		return this
		.bindListener('ico.dsr-icon', 'click', self.events['ico.dsr-icon']['click'])
		.bindListener('tools.dsr-ctrl item', 'click', self.events['tools.dsr-ctrl item']['click']);
	},
	main (){
		__thisapp__.listenEvents().regHeadBar(TAMBAR).loadStyle(function() {
            __thisapp__.loadURI(__thisapp__.__temp.href);
        });
	}
};