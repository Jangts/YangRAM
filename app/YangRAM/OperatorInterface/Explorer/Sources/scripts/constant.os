const {
    $ : YangRAM.$,
CTX_MENU_HANDLERS : {
    OpenFolder() {},
        MoveTo() {
            __thisapp__.API.BROWSER_TRIGGERS.MoveSelected.call(this);
        },
        CopyURL() {
            var type = YangRAM.attr(this, 'x-type');
            var id = YangRAM.attr(this, 'x-id');
            var suffix = YangRAM.attr(this, 'x-suffix');
            YangRAM.API.TXT.copy('//' + window.location.host + YangRAM.RequestDIR + 'files/' + type + '/' + id + '.' + suffix);
        },
        CopyCode() {
            var type = YangRAM.attr(this, 'x-type');
            var id = YangRAM.attr(this, 'x-id');
            var suffix = YangRAM.attr(this, 'x-suffix');
            var src = '//' + window.location.host + YangRAM.RequestDIR + 'files/' + type + '/' + id + '.' + suffix;
            if (type == 'img') {
                YangRAM.API.TXT.copy('<img src="' + src + '"/>');
            };
            if (type == 'wav') {
                YangRAM.API.TXT.copy('<audio src="' + src + '">' + __('WORDS')("Not Support") + '</audio>');
            };
        },
        Replace() {
            var that = this;
            var preg = new RegExp('\.' + YangRAM.attr(this, 'x-suffix') + '$');
            YangRAM.tools.pickFiles({
                multiple: false,
                sfixs: true,
                maxsize: 1024 * 1024 * 20,
                done(files) {
                    var name = files[0].name;
                    if (preg.test(files[0].name)) {
                        var p = YangRAM.tools.showMagicCube(30000, function() {
                            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Timeout"));
                        });
                        __thisapp__.upload(files[0], {
                            fldid: __thisapp__.currentFolder,
                            id: YangRAM.attr(that, 'x-id'),
                            done(data) {
                                YangRAM.tools.hideMagicCube(p);
                                __thisapp__.refresh();
                            },
                            fail(data) {
                                console.log(data);
                                YangRAM.tools.hideMagicCube(p);
                                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                            }
                        });
                    } else {
                        alert(__('WORDS')("Different Formats!"));
                    }
                },
                fail(file, errtype) {
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
        Rename() {
            var elem = YangRAM.$('.name', this)[0];
            elem && self.renameItem.call(elem);
        },
        Delete() {
            __thisapp__.Handlers.API.BROWSER_TRIGGERS.DeleteSelected(this);
        }
},

UPLOADER(files) {
        if (privates.uploading) {
            alert(__('WORDS')("Uploader Already In Working, Please Wait For The End Of This Operation!"));
        } else {
            if (privates.uploadingState) {
                privates.toBeupload  = YangRAM.API.util.arr.merge(privates.toBeupload , files);
            } else {
                privates.uploadingState = true;
                privates.toBeupload  = files;
            }
            UPLOADS_LISTER(privates.toBeupload);
        }
},

UPLOADS_LISTER(files) {
        var lister = __thisapp__.$('.uploader-lister')[0] || YangRAM.create('panel', __thisapp__.view, { className: 'uploader-lister' });
        var html = '<v class="uploader-header">' + __('WORDS')('File_Count')(files.length) + '</v>';
        html += '<list class="uploader-titles"><item>';
        html += '<v class="data-info">' + YangRAM.API.TXT.local('COMMON')('ATTRS')("Name") + '</v>';
        html += '<v class="data-status" style="">' + YangRAM.API.TXT.local('COMMON')('ATTRS')("Status") + '</v>';
        html += '<v class="data-size">' + YangRAM.API.TXT.local('COMMON')('ATTRS')("FileSize") + '</v>';
        html += '<v class="x-action">' + YangRAM.API.TXT.local('COMMON')('ATTRS')("Operate") + '</v></item></list>';
        html += '<v class="uploader-content">';
        html += '<scrollbar type="vert"><rail></rail><scrolldragger></scrolldragger></scrollbar>';
        html += '<content><list>';
        for (var i = 0; i < files.length; i++) {
            html += '<item><v class="data-info">' + files[i].name + '</v>';
            html += '<v class="data-status" style="">' + __('WORDS')("Waiting") + '</v>';
            html += '<v class="data-size">' + GET_FILE_SIZE(files[i].size) + '</v>';
            html += '<v x-index="' + i + '" class="x-action">Remove</v>';
            html += '</item>';
        }
        html += '</list></content></v>';
        html += '<v class="uploader-control"><click class="uploader-startbtn">' + __('WORDS')("Upload") + '</click><click class="uploader-cancelbtn">' + YangRAM.API.TXT.local('COMMON')('WORDS')("Cancel") + '</click></v>';
        lister.innerHTML = html;
        privates.uploaderScrollBAR = __thisapp__.OIMLElement.bind('scrollbar', __thisapp__.$('.uploader-content')[0]).resize();
},
GET_FILE_SIZE(size) {
        if (size > 1024 * 1024 * 1204 * 1204) {
            return (size / (1024 * 1024 * 1204 * 1204)).toFixed(2) + 'T';
        }
        if (size > 1024 * 1024 * 1204) {
            return (size / (1024 * 1024 * 1204)).toFixed(2) + 'G';
        }
        if (size > 1024 * 1024) {
            return (size / (1024 * 1024)).toFixed(2) + 'M';
        }
        if (size > 1024) {
            return (size / (1024)).toFixed(2) + 'K';
        }
        return size.toFixed(2) + 'B';
},

UPLOADER_START() {
        privates.uploading = true;
        privates.toBeuploadNum = privates.toBeupload.length;
        privates.uploadedNum = 0;
        privates.uploadedFailNum = 0;
        for (var i = 0; i < privates.toBeupload.length; i++) {
            UPLOADER_TRANSFER(privates.toBeupload [i], __thisapp__.$('.uploader-content .data-status')[i]);
        }
},
UPLOADER_TRANSFER(file, status) {
        __thisapp__.upload(file, {
            fldid: __thisapp__.currentFolder,
            before(data) {
                status.innerHTML = data.responseText;
            },
            progress(data) {
                if (data.lengthComputable) {
                    status.innerHTML = Math.round(data.loaded * 100 / data.total) + '%';
                };
            },
            after(data) {
                status.innerHTML = data.responseText;
            },
            done(data) {
                try {
                    var json = JSON.parse(data.responseText);
                    switch (json.code) {
                        case '703.61':
                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_INI_SIZE');
                            break;
                        case '703.62':
                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_FORM_SIZE');
                            break;
                        case '703.63':
                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_PARTIAL');
                            break;
                        case '703.64':
                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_NO_FILE');
                            break;
                        case '703.66':
                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_NO_TMP_DIR');
                            break;
                        case '703.67':
                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_CANT_WRITE');
                            break;
                        case '703.68':
                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_EXTENSION');
                            break;
                        default:
                            var text = YangRAM.API.TXT.local('UPLOADER')('STATUS')('Unknown Result');
                            console.log(json);
                    }
                } catch (e) {
                    var text = data.responseText;
                }
                status.innerHTML = text;
                privates.uploadedNum++;
                UPLOADER_LISTEN();
            },
            fail(data) {
                status.innerHTML = data.responseText;
                privates.uploadedFailNum++;
                UPLOADER_LISTEN();
            }
        });
},
UPLOADER_LISTEN() {
        if (privates.uploading && privates.uploadedNum + privates.uploadedFailNum == privates.toBeuploadNum) {
            privates.toBeupload  = [];
            privates.toBeuploadNum = 0;
            privates.uploading = false;
            __thisapp__.$('.uploader-control').html('<click class="uploader_completebtn">' + YangRAM.API.TXT.local('COMMON')('WORDS')("Complete") + '</click>');
            var content = __('WORDS')("YangRAM Explorer Has Complete The Upload Operating!");
            if (privates.uploadedFailNum > 0) {
                content += __('WORDS')("But There Are Some Files Uploaded Failed.");
            }
            YangRAM.API.MSG.notice({
                appId: __thisapp__.appid,
                title: __('WORDS')("Upload Complete"),
                content: content
            });
        }
},
UPLOADER_HIDE() {
        if (!privates.uploading) {
            privates.toBeupload  = [];
            privates.toBeuploadNum = 0;
            privates.uploadedNum = 0;
            privates.uploadedFailNum = 0;
            privates.uploadingState = false;
            __thisapp__.$('.uploader-lister').remove();
        }
},
SMARTIAN_OPEN(){
		var itemtype = arguments[1];
		var itemid = arguments[2];
		if(itemtype&&itemid){
			switch(itemtype){
				case 'folder':
				__thisapp__.open('src/all/'+itemid+'/');
				break;
			}
		}
	}
};