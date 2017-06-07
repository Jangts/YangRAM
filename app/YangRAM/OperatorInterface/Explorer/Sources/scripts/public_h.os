public {
    API:{
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
            },
            Close() {
                YangRAM.API.APP.close(this.appid);
            }
        },
        SMARTIAN_HELPER(type) {
            if (type) {
                switch (type) {
                    case 'open':
                        SMARTIAN_OPEN.apply(this, arguments);
                        break;
                }
            }
        },
        BROWSER_TRIGGERS: {
            CreateFolder() {
                var data = new FormData();
                data.append('parent', __thisapp__.currentFolder);
                YangRAM.set({
                    url: __thisapp__.__dirs.setter + 'submit/new_folder/',
                    data: data,
                    done(txt) {
                        YangRAM.tools.hideMagicCube(timeout);
                        if (txt == '<SUCCESS>') {
                            __thisapp__.refresh();
                            YangRAM.API.MSG.notice({
                                title: __('WORDS')("Create Success"),
                                content: __('WORDS')("A New Folder Has Been Create Successfully!")
                            });
                        } else if (txt == '<NO_PERMISSIONS>') {
                            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("No Permissions!"));
                        } else {
                            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                        }
                    },
                    fail(txt) {
                        console.log(txt);
                        alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
                    }
                });
                var timeout = YangRAM.tools.showMagicCube(30000, function() {
                    alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Operate Failed"));
                });
            },
            SelectAll() {
                __thisapp__.$('main content .item').toggleClass('selected', true);
            },
            InvertSelection() {
                __thisapp__.$('main content .item').toggleClass('selected');
            },
            DeselectAll() {
                __thisapp__.$('main content .item').toggleClass('selected', false);
            },
            DeleteSelected() {
                var selects = __thisapp__.$('main content .item.selected');
                if (selects.length > 1) {
                    var pass = 0;
                    var success = 0;
                    var failed = 0;
                    var listen = function() {
                        if (success + failed + pass == selects.length) {
                            if (success > 0) {
                                YangRAM.API.MSG.notice({
                                    appId: __thisapp__.appid,
                                    title: __('WORDS')("Delete Success"),
                                    content: __('WORDS')('Delete_Success_Multiple')(success)
                                });
                                __thisapp__.refresh();
                                //YangRAM.API.CHK.recycle();
                            } else {
                                alert(__('WORDS')("Delete Failed!"));
                            }
                        }
                    }
                    selects.each(function(index) {
                        if (YangRAM.attr(this, 'readonly') != '') {
                            __thisapp__.removeItem(this, function(txt) {
                                if (txt == '<SUCCESS>') {
                                    success++;
                                    listen();
                                } else {
                                    failed++;
                                    listen();
                                }
                            }, function(txt) {
                                failed++;
                                listen();
                            });
                        } else {
                            pass++;
                            listen();
                        }
                    });
                } else if (selects.length == 1) {
                    if (YangRAM.attr(selects[0], 'readonly') != '') {
                        __thisapp__.removeItem(selects[0]);
                    } else {
                        alert(__('WORDS')("Items Readonly"));
                    }
                } else {
                    alert(__('WORDS')("Please select items to be operate."));
                }
            },
            MoveSelected() {
                var selects = __thisapp__.$('main content .item.selected');
                if (selects.length > 0) {
                    if (__thisapp__.foldersList == '') {
                        YangRAM.set({
                            url: __thisapp__.__dirs.getter + 'folders/roots/',
                            done(txt) {
                                if (txt == '<ERROR>' || txt.match('PHP Notice:')) {
                                    alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                                    console.log(txt);
                                } else {
                                    //console.log(txt);
                                    self.showDialogOfFolderList(txt);
                                }
                            },
                            fail(txt) {
                                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
                                console.log(txt);
                            },
                            always(txt) {
                                //console.log(txt);
                                YangRAM.tools.hideMagicCube();
                            }
                        });YangRAM.tools.showMagicCube();
                    } else {
                        self.showDialogOfFolderList(__thisapp__.foldersList);
                    }
                } else {
                    alert(__('WORDS')("Please select items to be operate."));
                }
            },
            CopyHTMLCodes() {
                var selects = __thisapp__.$('main content .item.selected');
                if (selects.length > 0) {
                    var codes = '';
                    selects.each(function() {
                        var type = YangRAM.attr(this, 'x-type');
                        var id = YangRAM.attr(this, 'x-id');
                        var name = YangRAM.attr(this, 'name');
                        var sfix = YangRAM.attr(this, 'x-suffix');
                        codes += self.itemTags[type] ? self.itemTags[type](type, id, name, sfix) : self.itemTags.a(type, id, name, sfix);
                    });
                    YangRAM.API.TXT.copy(codes);
                } else {
                    alert(__('WORDS')("Please select items to be operate."));
                }
            },
            CopyJSONCodes() {
                var selects = __thisapp__.$('main content .item.selected');
                if (selects.length > 0) {
                    var codes = '[';
                    selects.each(function(i) {
                        var type = YangRAM.attr(this, 'x-type');
                        var id = YangRAM.attr(this, 'x-id');
                        var name = YangRAM.attr(this, 'name');
                        var sfix = YangRAM.attr(this, 'x-suffix');
                        var src = YangRAM.RequestDIR + 'files/' + type + '/' + id + '.' + sfix;
                        if (i) {
                            codes += ',';
                        }
                        codes += '{"name":"' + name + '", "src":"' + src + '"}';
                    });
                    codes += ']'
                    YangRAM.API.TXT.copy(codes);
                } else {
                    alert(__('WORDS')("Please select items to be operate."));
                }
            },
            Uploader() {
                YangRAM.tools.pickFiles({
                    multiple: true,
                    sfixs: true,
                    maxsize: 1024 * 1024 * 20,
                    done(files) {
                        //console.log(files)
                        UPLOADER(files);
                    },
                    fail(file, errtype) {
                        console.log(file)
                        switch (errtype) {
                            case 0:
                                alert(__('WORDS')("Type Not Support!"));
                                break;
                            case 1:
                                alert(__('WORDS')("Filesize OVER!"));
                                break;
                            case 2:
                                alert(__('WORDS')("No Legal File Selected!"));
                                break;
                        };
                    }
                });
            },
            OrderByNameASC() {
                __thisapp__.setSource(__thisapp__.source.replace(/lo=(na|nd|ta|td|sa|sd)/i, 'lo=na'));
                __thisapp__.refresh();
            },
            OrderByNameDESC() {
                __thisapp__.setSource(__thisapp__.source.replace(/lo=(na|nd|ta|td|sa|sd)/i, 'lo=nd'));
                __thisapp__.refresh();
            },
            OrderByModTimeASC() {
                __thisapp__.setSource(__thisapp__.source.replace(/lo=(na|nd|ta|td|sa|sd)/i, 'lo=ta'));
                __thisapp__.refresh();
            },
            OrderByModTimeDESC() {
                __thisapp__.setSource(__thisapp__.source.replace(/lo=(na|nd|ta|td|sa|sd)/i, 'lo=td'));
                __thisapp__.refresh();
            },
            OrderByFileSizeASC() {
                if (YangRAM.attr(this, 'readonly') != '') {
                    __thisapp__.setSource(__thisapp__.source.replace(/lo=(na|nd|ta|td|sa|sd)/i, 'lo=sa'));
                    __thisapp__.refresh();
                }
            },
            OrderByFileSizeDESC() {
                if (YangRAM.attr(this, 'readonly') != '') {
                    __thisapp__.setSource(__thisapp__.source.replace(/lo=(na|nd|ta|td|sa|sd)/i, 'lo=sd'));
                    __thisapp__.refresh();
                }
            },
            SwitchMainContentViewType() {
                var mc = __thisapp__.$('.main-content');
                if (mc.attr('x-type') === 'tile') {
                    mc.attr('x-type', 'list');
                    __thisapp__.listType = 'list';
                    __thisapp__.setSource(__thisapp__.source.replace('lt=tile', 'lt=list'));
                } else {
                    mc.attr('x-type', 'tile');
                    __thisapp__.listType = 'tile';
                    __thisapp__.setSource(__thisapp__.source.replace('lt=list', 'lt=tile'));
                }
                mc.find('content').css('top', 0);
                __thisapp__.mainScrollBAR && __thisapp__.mainScrollBAR.resize();
            },
            CloseDialog() {
                YangRAM.tools.hideDialog();
            },
            MoveSelectedTochosenFolder() {
                YangRAM.tools.hideDialog();
                __thisapp__.$('main content .item.selected').each(function() {
                    var data = new FormData();
                    data.append('parent', __thisapp__.chosenFolder);
                    data.append('id', YangRAM.attr(this, 'x-id'));
                    data.append('type', YangRAM.attr(this, 'x-type'));
                    YangRAM.set({
                        url: __thisapp__.__dirs.setter + 'submit/move_to_folder/',
                        data: data,
                        done(txt) {
                            if (txt == '<SUCCESS>') {
                                __thisapp__.refresh();
                            } else if (txt == '<SELF>') {
                                alert(__('WORDS')("Can Not Move To Self"));
                            } else if (txt == '<CHILD>') {
                                alert(__('WORDS')("Can Not Move To Child Folder"));
                            } else if (txt == '<!EXIST>') {
                                alert(__('WORDS')("Target Folder Not Exist"));
                            } else {
                                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                            }
                        },
                        fail(txt) {
                            console.log(txt);
                            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
                        },
                        always() {
                            YangRAM.tools.hideMagicCube();
                        }
                    });YangRAM.tools.showMagicCube();;
                });
            }
        }
    }
};
