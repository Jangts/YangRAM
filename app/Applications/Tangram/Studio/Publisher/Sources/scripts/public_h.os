public {
	API: {
        HIGHBAR_HANDLERS: {
		    OpenDefault() {
                this.open('default/startpage/', true);
            },
            OpenList() {
                if (__thisapp__.tabviews.currTabName == __thisapp__.tabviews.startTabName) {
                    // do nothing
                } else if (__thisapp__.tabviews.currTabName == 'GENERAL') {
                    __thisapp__.open('gec' + __thisapp__.tabviews.currTabName);
                } else {
                    __thisapp__.open('spc' + __thisapp__.tabviews.currTabName);
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
            var ctrllr = arguments[0];
            var preset = arguments[1];
            var itemid = arguments[2];
            if (ctrllr && preset && itemid) {
                this.open(ctrllr + '/' + preset + '/' + itemid + '/');
            }
        },
        EXPLORER_CALLBACKS: {
            SRC(files) {
                if (this.Input && YangRAM.API.hasChildNode(this.$('section.curr')[0], this.Input)) {
                    if (this.Input.tagName == 'INPUT' || this.Input.tagName == 'TEXTAREA') {
                        this.Input.value = this.Input.value + files[0].src;
                    } else {
                        var editor = YangRAM.API.form.getEditorById(YangRAM.attr(this.Input, 'data-editor-id'));
                        if (editor) {
                            var html = '';
                            for (var i = 0; i < files.length; i++) {
                                switch (files[i].type) {
                                    case 'img':
                                        html += '<img src="' + files[i].src + '" class="ib-editor-emoticon" alt="' + files[i].name + '"/>';
                                        break;
                                    case 'wav':
                                        html += '<audio src="' + files[i].src + '" class="ib-editor-emoticon" ><a href="' + files[i].src + '" target="_blank" >' + files[i].name + '</a></audio>';
                                        break;
                                    default:
                                        html += 'Attachment:<a href="' + files[i].src + '" target="_blank" title="click to download" class="ib-editor-attachment">' + files[i].name + '</a><br />';
                                }
                            }
                            editor.insertHTML(html);
                        } else {
                            alert('Something Wrong');
                        }
                    }
                } else {
                    alert('No Insert Area');
                }
            },
            SPC(contents) {
                var Relates = [];
                YangRAM.ForEach(contents, function(index, elem) {
                    var Item = '{';
                    Item += "preset:" + this.itemPreset + ",";
                    Item += "id:" + this.id + ",";
                    Item += "title:" + this.title + "}";
                    Relates.push(Item);
                });
                var src = this.$('tab-options list item.curr').attr('src');
                var tabPath = (src.split('?')[0] + '/').split(/\/+/);
                if (tabPath[0] != '' && tabPath[0] != 'default' && tabPath.length > 2) {
                    var CTT_Relates = this.$('section.curr').find('textarea[name=CTT_Relates]')[0];
                    if (CTT_Relates && CTT_Relates.parentNode.parentNode.style.display != 'none') {
                        if (CTT_Relates.value == '') {
                            CTT_Relates.value = Relates.join(",\r\n");
                        } else {
                            CTT_Relates.value = CTT_Relates.value + ',' + Relates.join(",\r\n");
                        }
                        return;
                    }
                }
            }
        },
        BROWSER_TRIGGERS: {
            removeItem(preset, id, sort, page, status, cls) {
                if (parseInt(id)) {
                    YangRAM.API.MSG.popup({
                        title: __('WORDS')('Remove Item?'),
                        content: __('WORDS')('Are you sure to remove this item?'),
                        confirm: "Sure",
                        cancel: "Cancel",
                        done: () => {
                            if (preset.toUpperCase() === __thisapp__.tabviews.currTabName) {
                                return self.submit(preset, id, 'del', sort, page, status, cls);
                            }
                            alert(__('WORDS')('Reference Error'));
                        }
                    });
                } else {
                    YangRAM.API.MSG.notice({
                        appid: __thisapp__.appid,
                        title: __('WORDS')('I\'m Sorry!'),
                        content: __('WORDS')('Operation not supported!'),
                    });
                }
            },
            ToList(preset, id, sort, page, status, cls) {
                if (preset.toUpperCase() === __thisapp__.tabviews.currTabName) {
                    //console.log(__('WORDS')('Are you sure to go to the list page before save your editing?'));
                    YangRAM.API.MSG.popup({
                        title: __('WORDS')('Abandon Editing?'),
                        content: __('WORDS')('Are you sure to go to the list page before save your editing?'),
                        confirm: "Sure",
                        cancel: "Cancel",
                        done: () => {
                            if (preset == 'general') {
                                if (status == undefined) {
                                    __thisapp__.open('gec/' + __thisapp__.tabviews.currTabName + '?sort=' + sort + '&page=' + page, true);
                                } else {
                                    __thisapp__.open('gec/' + __thisapp__.tabviews.currTabName + '?sort=' + sort + '&group=' + status + '&page=' + page, true);
                                }
                            } else {
                                __thisapp__.open('spc/' + __thisapp__.tabviews.currTabName + '?sort=' + sort + '&stts=' + status + '&cat=' + cls + '&page=' + page, true);
                            }
                        }
                    });
                }
            },
            ToTop() {
                __thisapp__.toTop();
            },
            PubItem(preset, id, sort, page, status, cls) {
                if (preset.toUpperCase() === __thisapp__.tabviews.currTabName) {
                    self.submit(preset, id, 'pub', sort, page, status, cls);
                }
            },
            SaveItem(preset, id, sort, page, status, cls) {
                if (preset.toUpperCase() === __thisapp__.tabviews.currTabName) {
                    self.submit(preset, id, 'sav', sort, page, status, cls);
                }
            },
            CopyContentAlias(alias) {
                YangRAM.API.TXT.copy(alias);
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