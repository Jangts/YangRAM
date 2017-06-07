public {
    API: {
        HIGHBAR_HANDLERS: {
            OpenDefault() {
				this.open('default');
			},
			Cover() {
				this.setFullScreenView();
			},
			Center() {
				this.setCenteredView();
			},
			Sleep() {
				YangRAM.API.APP.sleep(this.appid);
			},
			Close() {
				YangRAM.API.APP.close(this.appid);
			}
        },
        SMARTIAN_HELPER() {
            //
        },
        EXPLORER_CALLBACKS: {},
        BROWSER_TRIGGERS: {
            CopyItem (filename, theme, type, sort, page){
				submitter(filename, 'cop', theme, type, sort, page);
			},
			RemoveItem (filename, theme, type, sort, page){
				if(parseInt(id)){
					YangRAM.API.MSG.popup({
						title: 'Remove Item?',
						content: 'Are you sure to remove this item?',
						confirm: "Sure",
						cancel: "Cancel",
						done() {
							return self.submitter(filename, 'del', theme, type, sort, page);
						}
					});
				}else{
					YangRAM.API.MSG.notice({
						appid: __thisapp__.appid,
						title: 'I\'m Sorry!',
						content: 'Operation not supported!',
					});
				}
			},
			ToList(filename, theme, type, sort, page){
					__thisapp__.open('list/'+theme+'/?type='+type+'&sort='+sort+'&page='+page, true);
			},
			ToTop (){
				__thisapp__.toTop();
			},
			CopyCode (){
				var code = __thisapp__.$('inputs[type=longtext]>textarea[name=code]').val();
				YangRAM.API.TXT.copy(code);
			},
			SaveCode (filename, theme, type, sort, page){
				self.submitter(filename, 'sav', theme, type, sort, page);
			}
        },
        onafterresize() {
            var sideHeight = (this.$('left list[type=menu]').outerHeight() || 0) + 20;
			var mainHeight = (this.$('main list[type=sheet]').outerHeight() || -40) + 40;
			var contentHeightMin = mainHeight > sideHeight ? mainHeight : sideHeight;
			contentHeightMin = contentHeightMin < 350 ? 350 : contentHeightMin;
			if(this.viewstatus==1){
				contentHeightMin = YangRAM.API.APP.fsHeight() - 51 > contentHeightMin ? YangRAM.API.APP.fsHeight() - 51 : contentHeightMin;
				this.$('view>vision').css('min-height', contentHeightMin);
				this.$('left').css('min-height', contentHeightMin);
				this.$('main').css('min-height', contentHeightMin);
			}else{
				this.$('view>vision').css('min-height', contentHeightMin);
				this.$('left').css('min-height', contentHeightMin);
				this.$('main').css('min-height', contentHeightMin);
			}
			return this;
        }
    }
};