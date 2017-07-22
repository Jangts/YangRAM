public {
    API: {
        HIGHBAR_HANDLERS: {
            OpenDefault() {
				this.loadURI('default');
			},
			refresh() {
				this.refresh();
			},
			Cover() {
				this.setFullScreenView();
			},
			Center() {
				this.setCenteredView();
			},
			Sleep() {
				YangRAM.API.APP.sleep(this.appid);
			}
        },
        SMARTIAN_HELPER() {
            //
        },
        EXPLORER_CALLBACKS: {},
        BROWSER_TRIGGERS: {
            RecoverSelected() {
				var selects = __thisapp__.$('main section item.selected');
				var datatype = selects.attr('datatype');
				var itemtype = selects.attr('itemtype');
				if(selects.length>1){
					var ids = [];
					selects.each(function(index){
						ids.push(YangRAM.attr(this, 'itemid'));
					});
					__thisapp__.API.BROWSER_TRIGGERS.RecoverItems(datatype, itemtype, ids.join(','), selects.length)
				}else if(selects.length==1){
					var itemid = YangRAM.attr(selects[0], 'itemid')
					__thisapp__.API.BROWSER_TRIGGERS.RecoverItems(datatype, itemtype, itemid, false);
				}else{
					alert(__('WORDS')("Please select items to be recovery."));
				}
			},
			DeleteSelected() {
				var selects = __thisapp__.$('main section item.selected');
				var datatype = selects.attr('datatype');
				var itemtype = selects.attr('itemtype');
				if(selects.length>1){
					var ids = [];
					selects.each(function(index){
						ids.push(YangRAM.attr(this, 'itemid'));
					});
					__thisapp__.API.BROWSER_TRIGGERS.DeleteItems(datatype, itemtype, ids.join(','), selects.length);
				}else if(selects.length==1){
					var itemid = YangRAM.attr(selects[0], 'itemid')
					__thisapp__.API.BROWSER_TRIGGERS.DeleteItems(datatype, itemtype, itemid, false);
				}else{
					alert(__('WORDS')("Please select items to be Delete."));
				}
			},
			RecoverItems(datatype, itemtype, itemid, multiple) {
				var data = new FormData();
				data.append('datatype', datatype);
				data.append('itemtype', itemtype);
				if(multiple){
					data.append('ids', itemid);
				}else{
					data.append('id', itemid);
				}
				YangRAM.set({
					url: __thisapp__.__dirs.setter + 'submit/recover/',
					data: data,
					done: function(txt){
						if (txt == '<SUCCESS>') {
							if(multiple){
								var content = __('WORDS')("Recovery_Success", [multiple]);
							}else{
								var content = __('WORDS')("A Item Has Been recovered!");
							}
							YangRAM.API.MSG.notice({
								appId: __thisapp__.appid,
								title: __('WORDS')("Recovery Success"),
								content: content
							});
							__thisapp__.refresh();
						} else if (txt == '<CAN_NOT_FIND>') {
							alert(__('WORDS')("Item Not In Trash Can"));
						} else {
							console.log(txt);
							alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
						}
					},
					fail(txt){
						console.log(txt);
						alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
					}
				});
			},
			DeleteItems(datatype, itemtype, itemid, multiple) {
				var data = new FormData();
				data.append('datatype', datatype);
				data.append('itemtype', itemtype);
				if(multiple){
					data.append('ids', itemid);
				}else{
					data.append('id', itemid);
				}
				YangRAM.set({
					url: __thisapp__.__dirs.setter + 'submit/delete/',
					data: data,
					done: function(txt){
					//console.log(txt);
						if (txt == '<SUCCESS>') {
							if(multiple){
								var content = __('WORDS')("Delete_Success", [multiple]);
							}else{
								var content = __('WORDS')("A Item Has Been Completely Deleted!");
							}
							YangRAM.API.MSG.notice({
								appId: __thisapp__.appid,
								title: __('WORDS')("Delete Success"),
								content: content
							});
							__thisapp__.refresh();
						} else if (txt == '<CAN_NOT_FIND>') {
							alert(__('WORDS')("Item Not In Trash Can"));
						} else if (txt == '<FAILED>') {
							alert(__('WORDS')("Delete Failed Or Already Been Deleted"));
						} else {
							console.log(txt);
							alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
						}
					},
					fail(txt){
						console.log(txt);
						alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
					}
				});
			}
        }
    },
    onafterresize() {
            var sideHeight = (this.$('left list[type=menu]').outerHeight() || 0) + 20;
			var mainHeight = (this.$('main list[type=sheet]').outerHeight() || -40) + 40;
			var contentHeightMin = mainHeight > sideHeight ? mainHeight : sideHeight;
			contentHeightMin = contentHeightMin < 350 ? 350 : contentHeightMin;
			if(this.viewstatus==1){
				contentHeightMin = YangRAM.API.APP.fsHeight() - 51 > contentHeightMin ? YangRAM.API.APP.fsHeight() - 51 : contentHeightMin;
				this.$('view>v').css('min-height', contentHeightMin);
				this.$('left').css('min-height', contentHeightMin);
				this.$('main').css('min-height', contentHeightMin);
			}else{
				this.$('view>v').css('min-height', contentHeightMin);
				this.$('left').css('min-height', contentHeightMin);
				this.$('main').css('min-height', contentHeightMin);
			}
			return this;
        }
};