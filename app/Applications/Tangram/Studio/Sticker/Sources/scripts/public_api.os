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
            RemoveItem (id, sort, page, type, group){
				if(parseInt(id)){
					YangRAM.API.MSG.popup({
						title: 'Remove Item?',
						content: 'Are you sure to remove this item?',
						confirm: "Sure",
						cancel: "Cancel",
						done() {
							return self.submit(id, 'remove', sort, page, type, group);
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
			ToList(id, sort, page, type, group){
				if(group==undefined){
					__thisapp__.open('list/?sort='+sort+'&type='+type+'&page='+page, true);
				}else{
					__thisapp__.open('list/?sort='+sort+'&type='+type+'&group='+group+'&page='+page, true);
				}
			},
			ToTop (){
				__thisapp__.toTop();
			},
			CopyCode (){
				var code = __thisapp__.$('inputs[type=longtext]>textarea[name=code]').val();
				YangRAM.API.TXT.copy(code);
			},
			SaveCode (id, sort, page, type, group){
				self.submit(id, 'save', sort, page, type, group);
			}
        },
        onafterresize() {
            var sideHeight = (this.$('left list[type=menu]').outerHeight() || 0) + 20;
			var mainHeight = (this.$('main list[type=sheet]').outerHeight() || -40) + 40;
			var contentHeightMin = mainHeight > sideHeight ? mainHeight : sideHeight;
			contentHeightMin = contentHeightMin < 350 ? 350 : contentHeightMin;
			if(this.viewstatus==1){
				contentHeightMin = YangRAM.API.APP.fsHeight() - 51 > contentHeightMin ? YangRAM.API.APP.fsHeight() - 51 : contentHeightMin;
				this.$('appcon').css('min-height', contentHeightMin);
				this.$('left').css('min-height', contentHeightMin);
				this.$('appmain').css('min-height', contentHeightMin);
			}else{
				this.$('appcon').css('min-height', contentHeightMin);
				this.$('left').css('min-height', contentHeightMin);
				this.$('appmain').css('min-height', contentHeightMin);
			}
			return this;
        }
    }
};