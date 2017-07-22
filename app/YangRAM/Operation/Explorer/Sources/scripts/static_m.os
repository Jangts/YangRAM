static renameItem() {
    if (YangRAM.attr(this.parentNode, 'x-type') != 'spc') {
            if (YangRAM.attr(this.parentNode, 'readonly') != '' && YangRAM.attr(this, 'contenteditable') != '') {
                var orgName = this.innerHTML;
                YangRAM.attr(this, 'contenteditable', 'true').attr(this, 'tabindex', '2');
                var that = this;
                this.onblur = function() {
                    var newName = this.innerHTML.replace(/<[^>]*>/g, '');
                    if (newName != orgName) {
                        if (newName.match(/(<|>|\/|\\|\||:|\"|\*|\?)/)) {
                            alert(__('WORDS')('Can Not Contain <>/\|:"*?'),
                                function() {
                                    that.innerHTML = orgName;
                                });
                        } else if (newName.length > 50) {
                            alert(__('WORDS')("Can Not More Than 50 Words!"),
                                function() {
                                    that.innerHTML = orgName;
                                });
                        } else if (newName.length < 1) {
                            alert(__('WORDS')("Can Not Empty!"),
                                function() {
                                    that.innerHTML = orgName;
                                });
                        } else {
                            var type = YangRAM.attr(this.parentNode, 'x-type');
                            var id = YangRAM.attr(this.parentNode, 'x-id');
                            var preset = YangRAM.attr(this.parentNode, 'preset');
                            var itemNames = {
                                'img': __('NAMES')('img'),
                                'txt': __('NAMES')('txt'),
                                'vod': __('NAMES')('vod'),
                                'wav': __('NAMES')('wav'),
                                'folder': __('NAMES')('folder'),
                                'doc': __('NAMES')('doc'),
                                'spc': YangRAM.attr(this.parentNode, 'itemname')
                            }
                            var data = new FormData();
                            data.append('type', type);
                            data.append('preset', preset);
                            data.append('id', id);
                            data.append('name', newName);
                            YangRAM.set({
                                url: __thisapp__.__dirs.setter + 'submit/mod_name/',
                                data: data,
                                done(txt) {
                                    //console.log(txt);
                                    YangRAM.tools.hideMagicCube(timeout);
                                    if (txt == '<ERROR>' || txt.match('PHP Notice:')) {
                                        alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"),
                                            function() {
                                                that.innerHTML = orgName;
                                            });
                                    } else if (txt == '<ERROR404>') {
                                        alert(__('WORDS')("File Not Exists!"));
                                        __thisapp__.refresh();
                                    } else {
                                        that.innerHTML = txt;
                                        YangRAM.attr(that.parentNode, 'name', txt)
                                            .API.MSG.notice({
                                                appId: __thisapp__.appid,
                                                title: __('WORDS')("Rename Success"),
                                                content: __('WORDS')('Rename_Success')(itemNames[type])
                                            });
                                    }
                                    that.onblur = null;
                                },
                                fail(txt) {
                                    console.log(txt);
                                    YangRAM.tools.hideMagicCube(timeout);
                                    alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"),
                                        function() {
                                            that.innerHTML = orgName;
                                        });
                                    that.onblur = null;
                                }
                            });
                            var timeout = YangRAM.tools.showMagicCube(30000, function() {
                                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Operate Failed"));
                            });
                        }
                    }
                    YangRAM.attr(this, 'contenteditable', false).attr(this, 'tabindex', false);
                };
                setTimeout(function() {
                    that.focus();
                }, 0);
            }
        } else {
            YangRAM.API.MSG.notice({
                appId: __thisapp__.appid,
                title: __('WORDS')("Rename Prohibited"),
                content: __('WORDS')("Cannot Rename for a Preset Content!")
            });
        }
};

static itemTags = {
        a(type, id, name, sfix) {
            var src = YangRAM.RequestDIR + 'files/' + type + '/' + id + '.' + sfix;
            return '<a href="' + src + '">' + name + '</a>';
        },
        folder() {
            return '';
        },
        pic(type, id, name, sfix) {
            var src = YangRAM.RequestDIR + 'files/img/' + id + '.' + sfix;
            return '<img src="' + src + '" alt="' + name + '" />';
        },
        wav(type, id, name, sfix) {
            var src = YangRAM.RequestDIR + 'files/wav/' + id + '.' + sfix;
            return '<audio src="' + src + '"><a href="' + src + '?download">' + name + '</a></audio>';
        },
        vod(type, id, name, sfix) {
            var src = YangRAM.RequestDIR + 'files/vod/' + id + '.' + sfix;
            return '<video src="' + src + '"><a href="' + src + '?download">' + name + '</a></video>';
        }
};
static handleSmartianOrder() {
        var itemtype = arguments[1];
        var itemid = arguments[2];
        if (itemtype && itemid) {
            switch (itemtype) {
                case 'folder':
                    this.open('src/all/' + itemid + '/');
                    break;
            }
        }
}

static getchosenFolder(event) {
        var elem = event.relatedTarget  ||  event.srcElement  ||  event.target  || event.currentTarget;
        if (elem.tagName == 'item') {
            return elem;
        } else {
            return YangRAM.API.dom.closest(elem, 'item');
        }
}

static showDialogOfFolderList(txt) {
    YangRAM.tools.showDialog({
                title: __('WORDS')("Move folders and files to"),
                appid: __thisapp__.appid,
                css: 'dialog',
                height: 400,
                control: [{
                        name: YangRAM.API.TXT.local('COMMON')('WORDS')("Cancel"),
                        href: 'CloseDialog',
                        args: ''
                    },
                    {
                        name: YangRAM.API.TXT.local('COMMON')('WORDS')("Confirm"),
                        href: 'MoveSelectedTochosenFolder',
                        args: ''
                    }
                ]
            }, function() {
                var dialogMain = this.render(txt).contentarea;
                dialogMain.bindListener('item[fldid]', 'mouseup', function() {
                        var folder = self.getchosenFolder(event)
                        if (folder && folder == this) {
                            YangRAM.$('item[selected]', dialogMain).removeAttr('selected');
                            YangRAM.attr(folder, 'selected', '');
                            if (YangRAM.API.dom.hasClass(folder, 'has-child')) {
                                var childList = YangRAM.$('list.folder-tree', folder);
                                if (childList.html() == '') {
                                    var data = new FormData();
                                    data.append('fldid', YangRAM.attr(folder, 'fldid'));
                                    data.append('level', YangRAM.attr(folder, 'level'));
                                    YangRAM.set({
                                        url: __thisapp__.__dirs.getter + 'folders/children/',
                                        data: data,
                                        done(txt) {
                                            if (txt == '<ERROR>' || txt.match('PHP Notice:')) {
                                                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                                            } else if (txt == '<ERROR404>') {
                                                alert(__('WORDS')("Folder Not Exists"));
                                                //
                                            } else {
                                                childList.html(txt);
                                                setTimeout(function() {
                                                    dialogMain.scrollBAR.resize();
                                                }, 0);
                                            }
                                        },
                                        fail(txt) {
                                            console.log(txt);
                                            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
                                        },
                                        always(txt) {
                                            //console.log(txt);
                                            YangRAM.tools.hideMagicCube();
                                        }
                                    });YangRAM.tools.showMagicCube();;
                                }
                                childList.toggleClass('hidden');
                            }
                            YangRAM.toggleClass(folder, 'expand');
                        }
                    });
            });
}