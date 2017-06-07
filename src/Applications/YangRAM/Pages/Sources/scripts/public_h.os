public {
	API: {
        HIGHBAR_HANDLERS: {
		    OpenDefault() {
                this.open('default');
            },
            OpenList() {
                if (this.tabviews.currTabName != 'default') {
                    this.open(this.tabviews.currTabName);
                }
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
            },
            OfficialWebsite() {
                this.browseWebPage('http://www.yangram.com/');
            },
            GotoForum() {
                this.browseWebPage('http://fourm.baidu.net/studio/publisher/');
            }
        },
		SMARTIAN_HELPER() {
            //
        },
        EXPLORER_CALLBACKS: {
            SRC(files) {
                return true;
            },
            PCL(contents) {
                alert(__('WORDS')("This App Not Support To Insert Preset Contents"));
            }
        },
        BROWSER_TRIGGERS: {
            CopyPageId(id) {
                if (parseInt(id)) {
                    YangRAM.API.TXT.copy(id);
                }
            },
            EditItem(id) {
                if (parseInt(id)) {
                    return self.submit(id, 'edi');
                }
                YangRAM.API.MSG.Notice({
                    appid: __thisapp__.appid,
                    title: __('WORDS')("I\'m Sorry!"),
                    content: __('WORDS')("Operation not supported!"),
                });
            },
            SaveItem(id) {
                if (YangRAM.API.isNum(parseInt(id))) {
                    return self.submit(id, 'sav');
                }
                YangRAM.API.MSG.Notice({
                    appid: __thisapp__.appid,
                    title: __('WORDS')("I\'m Sorry!"),
                    content: __('WORDS')("Operation not supported!"),
                });
            },
            RemoveItem(id) {
                if (parseInt(id)) {
                    YangRAM.API.MSG.popup({
                        title: __('WORDS')("Remove Page?"),
                        content: __('WORDS')("Are you sure to remove this page?"),
                        confirm: __('WORDS')("Sure"),
                        cancel: __('WORDS')("Cancel"),
                        done() {
                            return self.submit(id, 'del');
                        }
                    });
                } else {
                    YangRAM.API.MSG.notice({
                        appid: __thisapp__.appid,
                        title: __('WORDS')("I\'m Sorry!"),
                        content: __('WORDS')("Operation not supported!"),
                    });
                }
            },
            Cancel() {
                __thisapp__.$('section[data-tab-name].curr popup-form').remove();
                __thisapp__.refreshTopVision();
            },
            NonUse(id) {
                if (parseInt(id)) {
                    YangRAM.API.MSG.popup({
                        title: __('WORDS')("Disuse Page?"),
                        content: __('WORDS')("Are you sure to disuse this page?"),
                        confirm: __('WORDS')("Sure"),
                        cancel: __('WORDS')("Cancel"),
                        done() {
                            return self.submit(id, 'dis');
                        }
                    });
                } else {
                    YangRAM.API.MSG.notice({
                        appid: __thisapp__.appid,
                        title: __('WORDS')("I\'m Sorry!"),
                        content: __('WORDS')("Operation not supported!"),
                    });
                }
            },
            StartUse(id) {
                self.submit(id, 'use');
            }
        }
    },
    onafterresize() {
        var sideHeight = (this.$('section.curr left dl').outerHeight() || 0) + 20;
        var mainHeight = this.$('section.curr main').outerHeight();
        var contentHeightMin = mainHeight > sideHeight ? mainHeight : sideHeight;
        contentHeightMin = contentHeightMin < 350 ? 350 : contentHeightMin;
        if (this.viewstatus == 1) {
            contentHeightMin = YangRAM.API.APP.fsHeight() - 75 > contentHeightMin ? YangRAM.API.APP.fsHeight() - 75 : contentHeightMin;
            this.$('section.curr').css('min-height', contentHeightMin);
        } else {
            this.$('section.curr').css('min-height', contentHeightMin);
        }
        return this;
    }
};