public {
    setCenteredView: YangRAM.donothing,
    onload() {
        this.leftSide = YangRAM.$('left', this.document)[0];
        this.mainTop = YangRAM.$('v.main-topbar', this.document)[0];
        this.mainContent = YangRAM.$('v.main-content', this.document)[0];
        this.leftScrollBAR = __thisapp__.OIMLElement.bind('scrollbar', this.leftSide);
        this.mainScrollBAR = __thisapp__.OIMLElement.bind('scrollbar', this.mainContent);
        this.foldersList = '';
        return this;
    },
    onlaunch(href, folder) {
        if (typeof href == 'string') {
            href = href.split('?')[0];
            if (folder) {
                this.currentFolder = folder;
            } else {
                var REQUEST = href.replace(/\/+/, '/').split('/');
                if (REQUEST[0] == 'src' && REQUEST[2] != '') {
                    this.currentFolder = parseInt(REQUEST[2]);
                } else {
                    this.currentFolder = 6;
                }
            }
            href += '?lt=' + this.listType + '&lo=' + this.listOrder;
            this.open(href, () => {
                if(privates.toBeupload.length){
                    UPLOADS_LISTER(privates.toBeupload );
                }
            });
        }
        //console.log(href, REQUEST, folder, this.currentFolder);
        return this.resize();
    },
    onclosedialog(dialogMain) {
        this.chosenFolder = YangRAM.$('item[fldid][selected]', dialogMain).attr('fldid');
        this.foldersList = dialogMain.innerHTML;
    },
    removeItem(elem, doneCallback, failCallback) {
        var type = YangRAM.attr(elem, 'x-type');
        var id = YangRAM.attr(elem, 'x-id');
        var data = new FormData();
        var itemName, url;
        switch (type) {
            case 'img':
                itemName = __('NAMES')(type);
                url = __thisapp__.__dirs.setter + 'submit/rmv_img/';
                data.append('id', id);
                break;
            case 'txt':
                itemName = __('NAMES')(type);
                url = __thisapp__.__dirs.setter + 'submit/rmv_txt/';
                data.append('id', id);
                break;
            case 'vod':
                itemName = __('NAMES')(type);
                url = __thisapp__.__dirs.setter + 'submit/rmv_vod/';
                data.append('id', id);
                break;
            case 'wav':
                itemName = __('NAMES')(type);
                url = __thisapp__.__dirs.setter + 'submit/rmv_wav/';
                data.append('id', id);
                break;
            case 'doc':
                itemName = __('NAMES')(type);
                url = __thisapp__.__dirs.setter + 'submit/rmv_doc/';
                data.append('id', id);
                break;
            case 'folder':
                itemName = __('NAMES')(type);
                url = __thisapp__.__dirs.setter + 'submit/rmv_folder/';
                data.append('fldid', id);
                break;
            case 'spc':
                itemName = YangRAM.attr(elem, 'itemname');
                url = __thisapp__.__dirs.setter + 'submit/rmv_content/';
                data.append('preset', YangRAM.attr(elem, 'preset'));
                data.append('id', id);
                break;
        }
        var doneCallback = doneCallback || function(txt) {
            if (txt.match('PHP Notice:')) {
                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
            } else if (txt == '<Deleted>') {
                alert(__('WORDS')("<Deleted>")(itemName));
            } else if (txt == '<ReadOnly>') {
                alert(__('WORDS')("<ReadOnly>")(itemName));
            } else if (txt == '<CAN_NOT_FIND>') {
                alert(__('WORDS')("<CAN_NOT_FIND>")(itemName));
            } else if (txt == '<SUCCESS>') {
                YangRAM.API.MSG.notice({
                    appId: __thisapp__.appid,
                    title: __('WORDS')("Delete Success"),
                    content: __('WORDS')("Delete_Success")(itemName)
                });
                __thisapp__.refresh();
                //YangRAM.API.CHK.recycle();
            } else {
                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Unkonw Error!"));
            }
        }
        var failCallback = failCallback || function(txt) {
            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
        }
        YangRAM.set({
            url: url,
            data: data,
            done(txt) {
                console.log(txt);
                YangRAM.tools.hideMagicCube(timeout);
                doneCallback(txt);
            },
            fail(txt) {
                console.log(txt);
                YangRAM.tools.hideMagicCube(timeout);
                failCallback(txt);
            }
        });
        var timeout = YangRAM.tools.showMagicCube(30000, function() {
            alert('Timeout');
        });
    },
    onafterresize() {
        var appContentHeight = System.Height - 91;
        this.$('left').css('height', appContentHeight).css('max-height', appContentHeight).css('min-height', appContentHeight);
        this.$('v.main-content').css('height', appContentHeight - 90).css('max-height', appContentHeight - 90).css('min-height', appContentHeight - 90);
        setTimeout(function() {
                __thisapp__.leftScrollBAR && __thisapp__.leftScrollBAR.resize();
                __thisapp__.mainScrollBAR && __thisapp__.mainScrollBAR.resize();
            },
            100);
        return this;
    },
    main(){
        this.regContextMenus('ctx-fld', self.menuOnFolder)
            .regContextMenus('ctx-set', self.menuOnSPC)
            .regContextMenus('ctx-doc', self.menuOnDocument)
            .regContextMenus('ctx-vod', self.menuOnDocument)
            .regContextMenus('ctx-txt', self.menuOnText)
            .regContextMenus('ctx-img', self.menuOnPicture)
            .regContextMenus('ctx-wav', self.menuOnAudio)
            .listenEvents(self.eventHandlers)
            .loadStyle(function() {
                setTimeout(function() {
                    //console.log(__thisapp__);
                    __thisapp__.regHeadBar(self.menuOnHigabar).open(__thisapp__.__temp.href, () => {
                        __thisapp__.setFullScreenView();
                    });
                }, 1500)
            })
    }
};