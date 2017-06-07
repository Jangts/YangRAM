RegApplication('EXPLORER', (__thisapp__, System, YangRAM, Using, Global, undefined) => {
'use strict';
const __LANG__ = System.Runtime.locales.EXPLORER;
const __ = (word) => {
	return YangRAM.API.TXT.dictReader(__LANG__, word);
};
const __APPDIR__ = '/YangRAM/OperatorInterface/Explorer/';
const PICS = function(){
        var id=YangRAM.attr(this, 'x-id');
        self.getItemInfo('img', id, function(info) {
            var width=YangRAM.API.APP.width() - 380;
            var height=YangRAM.API.APP.fsHeight() - 80;
            var _height;
            var src=YangRAM.RequestDIR + 'files/img/' + info["ID"] + '.' + info["SUFFIX"];
            var html='<el class="browse-close">×</el>';
            html +='<vision class="browse-pic" style="width:' + width + 'px; height:' + height + 'px; "><img class="browse-img-file" src="' + src + '?mt=' + Date.parse(info["KEY_MTIME"]) + '" /></vision>';
            html +='<scroll-vision scroll-y="true" class="browse-img-info" style="height:' + height + 'px;">';
            html +='<vision class="browse-img-infolist browse-img-title">' + info["FILE_NAME"] + '</vision>';
            html +='<vision class="browse-img-infolist browse-img-width"><el>' + __('ATTRS')("MIME") + ':</el>' + info["MIME"] + '(.' + info["SUFFIX"] + ')</vision>';
            html +='<vision class="browse-img-infolist browse-img-width"><el>' + __('ATTRS')("Width") + ':</el>' + info["WIDTH"] + 'px</vision>';
            html +='<vision class="browse-img-infolist browse-img-height"><el>' + __('ATTRS')("Height") + ':</el>' + info["HEIGHT"] + 'px</vision>';
            html +='<vision class="browse-img-infolist browse-img-ctime"><el>' + YangRAM.API.TXT.local('COMMON')('ATTRS')("Create Time") + ':</el>' + info["KEY_CTIME"] + '</vision>';
            html +='<vision class="browse-img-infolist browse-img-mtime"><el>' + YangRAM.API.TXT.local('COMMON')('ATTRS')("Modify Time") + ':</el>' + info["KEY_MTIME"] + '</vision>';
            html +='<vision class="browse-img-dashed"></vision>';
            html +='<vision class="browse-img-control browse-img-src" data-val="' + src + '">' + __('WORDS')("Copy URL") + ' (' + __('WORDS')("natural size") + ')</vision>';
            var OrginWidth=parseInt(info["WIDTH"]);
            var OrginHeight=parseInt(info["HEIGHT"]);
            if (OrginWidth > 1920) {
                _height=parseInt(OrginHeight * 1920 / OrginWidth);
                html +='<vision class="browse-img-control browse-img-src" data-val="' + src + '">' + __('WORDS')("Copy URL") + ' (1920 x ' + _height + ')</vision>';
            }
            if (OrginWidth > 1200) {
                _height=parseInt(OrginHeight * 1200 / OrginWidth);
                html +='<vision class="browse-img-control browse-img-src" data-val="' + src + '_1200.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (1200 x ' + _height + ')</vision>';
            }
            if (OrginWidth > 1000) {
                _height=parseInt(OrginHeight * 1000 / OrginWidth);
                html +='<vision class="browse-img-control browse-img-src" data-val="' + src + '_1000.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (1000 x ' + _height + ')</vision>';
            }
            if (OrginWidth > 800) {
                _height=parseInt(OrginHeight * 800 / OrginWidth);
                html +='<vision class="browse-img-control browse-img-src" data-val="' + src + '_800.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (800 x ' + _height + ')</vision>';
            }
            if (OrginWidth > 640) {
                _height=parseInt(OrginHeight * 640 / OrginWidth);
                html +='<vision class="browse-img-control browse-img-src" data-val="' + src + '_640.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (640 x ' + _height + ')</vision>';
            }
            if (OrginWidth > 480) {
                _height=parseInt(OrginHeight * 480 / OrginWidth);
                html +='<vision class="browse-img-control browse-img-src" data-val="' + src + '_480.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (480 x ' + _height + ')</vision>';
            }
            if (OrginWidth > 360) {
                _height=parseInt(OrginHeight * 360 / OrginWidth);
                html +='<vision class="browse-img-control browse-img-src" data-val="' + src + '_360.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (360 x ' + _height + ')</vision>';
            }
            if (OrginWidth > 240) {
                _height=parseInt(OrginHeight * 240 / OrginWidth);
                html +='<vision class="browse-img-control browse-img-src" data-val="' + src + '_240.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (240 x ' + _height + ')</vision>';
            }
            if (OrginWidth > 120) {
                _height=parseInt(OrginHeight * 120 / OrginWidth);
                html +='<vision class="browse-img-control browse-img-src" data-val="' + src + '_120.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (120 x ' + _height + ')</vision>';
            }
            if (OrginWidth > 30) {
                _height=parseInt(OrginHeight * 30 / OrginWidth);
                html +='<vision class="browse-img-control browse-img-src" data-val="' + src + '_30.' + info["SUFFIX"] + '">' + __('WORDS')("Copy URL") + ' (30 x ' + _height + ')</vision>';
            }
            html +='<vision class="browse-img-control browse-img-img" data-val="' + src + '">' + __('WORDS')("Copy HTML Code") + '</vision>';
            html +='</vision>';
            var popup=YangRAM.$('.browse-popup', __thisapp__.view)[0] || YangRAM.create('vision', __thisapp__.view, {
                className:'browse-popup',
            });
            popup.innerHTML=html;
            popup.style.display='block';
        });
    };
const TXTS = function(){
        var id=YangRAM.attr(this, 'x-id');
        self.getItemInfo('txt', id, function(info) {
            var left=(YangRAM.API.APP.width() - 1000) / 2;
            var right=left - 15;
            var height=YangRAM.API.APP.fsHeight() - 160;
            var src=YangRAM.RequestDIR + 'files/txt/' + info["ID"] + '.' + info["SUFFIX"];
            var html='<el class="browse-close"  style="right:' + right + 'px; ">×</el>';
            html +='<vision class="browse-txt" style="left:' + left + 'px;">';
            html +='<header class="browse-txt-title">' + info["FILE_NAME"] + '</header>';
            html +='<p class="browse-txt-info"><el title="Create Time">[' + YangRAM.API.TXT.local('COMMON')('ATTRS')("Create Time") + ']' + info["KEY_CTIME"] + '</el>';
            html +='<el title="Modify Time">[' + YangRAM.API.TXT.local('COMMON')('ATTRS')("Modify Time") + ']' + info["KEY_MTIME"] + '</el>';
            html +='<el><click data-val="' + src + '">[' + __('WORDS')("Copy File URL") + ']</click></el></p>';
            html +='<textarea class="browse-txt-content" style="height:' + height + 'px;" readonly>' + info["FILE_CONTENT"] + '</textarea>';
            html +='</vision>';
            var popup=YangRAM.$('.browse-popup', __thisapp__.view)[0] || YangRAM.create('vision', __thisapp__.view, {
                className:'browse-popup',
            });
            popup.innerHTML=html;
            popup.style.display='block';
        });
    };
const WAVS = function(){
        var id=YangRAM.attr(this, 'x-id');
        self.getItemInfo('wav', id, function(info) {
            var src=YangRAM.RequestDIR + 'files/wav/' + info["ID"] + '.' + info["SUFFIX"];
            var html='<el class="browse-close">×</el>';
            html +='<audio class="browse-wav" src="' + src + '" controls autoplay></audio>';
            self.palyer=YangRAM.$('.browse-popup', __thisapp__.view)[0] || YangRAM.create('vision', __thisapp__.view, {
                className:'browse-popup',
            });
            self.palyer.innerHTML=html;
            self.palyer.style.display='block';
        });
    };
const $ = YangRAM.$;
const CTX_MENU_HANDLERS = {
    OpenFolder() {},
        MoveTo() {
            __thisapp__.API.BROWSER_TRIGGERS.MoveSelected.call(this);
        },
        CopyURL() {
            var type=YangRAM.attr(this, 'x-type');
            var id=YangRAM.attr(this, 'x-id');
            var suffix=YangRAM.attr(this, 'x-suffix');
            YangRAM.API.TXT.copy('//' + window.location.host + YangRAM.RequestDIR + 'files/' + type + '/' + id + '.' + suffix);
        },
        CopyCode() {
            var type=YangRAM.attr(this, 'x-type');
            var id=YangRAM.attr(this, 'x-id');
            var suffix=YangRAM.attr(this, 'x-suffix');
            var src='//' + window.location.host + YangRAM.RequestDIR + 'files/' + type + '/' + id + '.' + suffix;
            if (type=='img') {
                YangRAM.API.TXT.copy('<img src="' + src + '"/>');
            };
            if (type=='wav') {
                YangRAM.API.TXT.copy('<audio src="' + src + '">' + __('WORDS')("Not Support") + '</audio>');
            };
        },
        Replace() {
            var that=this;
            var preg=new RegExp('\.' + YangRAM.attr(this, 'x-suffix') + '$');
            YangRAM.tools.pickFiles({
                multiple:false,
                sfixs:true,
                maxsize:1024 * 1024 * 20,
                done(files) {
                    var name=files[0].name;
                    if (preg.test(files[0].name)) {
                        var p=YangRAM.tools.showMagicCube(30000, function() {
                            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Timeout"));
                        });
                        __thisapp__.upload(files[0], {
                            fldid:__thisapp__.currentFolder,
                            id:YangRAM.attr(that, 'x-id'),
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
                        case 0:alert(__('WORDS')("Type Not Support!"));
                            break;
                        case 1:alert(__('WORDS')("Filesize OVER!"));
                            break;
                        case 2:alert(__('WORDS')("No Legal File Selected!"));
                            break;
                    };
                }
            });
        },
        Rename() {
            var elem=YangRAM.$('.name', this)[0];
            elem && self.renameItem.call(elem);
        },
        Delete() {
            __thisapp__.Handlers.API.BROWSER_TRIGGERS.DeleteSelected(this);
        }
};
const UPLOADER = function(files){
        if (privates.uploading) {
            alert(__('WORDS')("Uploader Already In Working, Please Wait For The End Of This Operation!"));
        } else {
            if (privates.uploadingState) {
                privates.toBeupload=YangRAM.API.util.arr.merge(privates.toBeupload , files);
            } else {
                privates.uploadingState=true;
                privates.toBeupload=files;
            }
            UPLOADS_LISTER(privates.toBeupload);
        }
};
const UPLOADS_LISTER = function(files){
        var lister=__thisapp__.$('.uploader-lister')[0] || YangRAM.create('panel', __thisapp__.view, { className:'uploader-lister' });
        var html='<vision class="uploader-header">' + __('WORDS')('File_Count')(files.length) + '</vision>';
        html +='<list class="uploader-titles"><item>';
        html +='<vision class="data-info">' + YangRAM.API.TXT.local('COMMON')('ATTRS')("Name") + '</vision>';
        html +='<vision class="data-status" style="">' + YangRAM.API.TXT.local('COMMON')('ATTRS')("Status") + '</vision>';
        html +='<vision class="data-size">' + YangRAM.API.TXT.local('COMMON')('ATTRS')("FileSize") + '</vision>';
        html +='<vision class="x-action">' + YangRAM.API.TXT.local('COMMON')('ATTRS')("Operate") + '</vision></item></list>';
        html +='<vision class="uploader-content">';
        html +='<scrollbar type="vert"><rail></rail><scrolldragger></scrolldragger></scrollbar>';
        html +='<content><list>';
        for (var i=0; i < files.length; i++) {
            html +='<item><vision class="data-info">' + files[i].name + '</vision>';
            html +='<vision class="data-status" style="">' + __('WORDS')("Waiting") + '</vision>';
            html +='<vision class="data-size">' + GET_FILE_SIZE(files[i].size) + '</vision>';
            html +='<vision x-index="' + i + '" class="x-action">Remove</vision>';
            html +='</item>';
        }
        html +='</list></content></vision>';
        html +='<vision class="uploader-control"><click class="uploader-startbtn">' + __('WORDS')("Upload") + '</click><click class="uploader-cancelbtn">' + YangRAM.API.TXT.local('COMMON')('WORDS')("Cancel") + '</click></vision>';
        lister.innerHTML=html;
        privates.uploaderScrollBAR=__thisapp__.OIMLElement.bind('scrollbar', __thisapp__.$('.uploader-content')[0]).resize();
};
const GET_FILE_SIZE = function(size){
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
};
const UPLOADER_START = function(){
        privates.uploading=true;
        privates.toBeuploadNum=privates.toBeupload.length;
        privates.uploadedNum=0;
        privates.uploadedFailNum=0;
        for (var i=0; i < privates.toBeupload.length; i++) {
            UPLOADER_TRANSFER(privates.toBeupload [i], __thisapp__.$('.uploader-content .data-status')[i]);
        }
};
const UPLOADER_TRANSFER = function(file, status){
        __thisapp__.upload(file, {
            fldid:__thisapp__.currentFolder,
            before(data) {
                status.innerHTML=data.responseText;
            },
            progress(data) {
                if (data.lengthComputable) {
                    status.innerHTML=Math.round(data.loaded * 100 / data.total) + '%';
                };
            },
            after(data) {
                status.innerHTML=data.responseText;
            },
            done(data) {
                try {
                    var json=JSON.parse(data.responseText);
                    switch (json.code) {
                        case '703.61':var text=YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_INI_SIZE');
                            break;
                        case '703.62':var text=YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_FORM_SIZE');
                            break;
                        case '703.63':var text=YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_PARTIAL');
                            break;
                        case '703.64':var text=YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_NO_FILE');
                            break;
                        case '703.66':var text=YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_NO_TMP_DIR');
                            break;
                        case '703.67':var text=YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_CANT_WRITE');
                            break;
                        case '703.68':var text=YangRAM.API.TXT.local('UPLOADER')('STATUS')('ERR_EXTENSION');
                            break;
                        default:var text=YangRAM.API.TXT.local('UPLOADER')('STATUS')('Unknown Result');
                            console.log(json);
                    }
                } catch (e) {
                    var text=data.responseText;
                }
                status.innerHTML=text;
                privates.uploadedNum++;
                UPLOADER_LISTEN();
            },
            fail(data) {
                status.innerHTML=data.responseText;
                privates.uploadedFailNum++;
                UPLOADER_LISTEN();
            }
        });
};
const UPLOADER_LISTEN = function(){
        if (privates.uploading && privates.uploadedNum + privates.uploadedFailNum==privates.toBeuploadNum) {
            privates.toBeupload=[];
            privates.toBeuploadNum=0;
            privates.uploading=false;
            __thisapp__.$('.uploader-control').html('<click class="uploader_completebtn">' + YangRAM.API.TXT.local('COMMON')('WORDS')("Complete") + '</click>');
            var content=__('WORDS')("YangRAM Explorer Has Complete The Upload Operating!");
            if (privates.uploadedFailNum > 0) {
                content +=__('WORDS')("But There Are Some Files Uploaded Failed.");
            }
            YangRAM.API.MSG.notice({
                appId:__thisapp__.appid,
                title:__('WORDS')("Upload Complete"),
                content:content
            });
        }
};
const UPLOADER_HIDE = function(){
        if (!privates.uploading) {
            privates.toBeupload=[];
            privates.toBeuploadNum=0;
            privates.uploadedNum=0;
            privates.uploadedFailNum=0;
            privates.uploadingState=false;
            __thisapp__.$('.uploader-lister').remove();
        }
};
const SMARTIAN_OPEN = function(){
		var itemtype=arguments[1];
		var itemid=arguments[2];
		if(itemtype&&itemid){
			switch(itemtype){
				case 'folder':__thisapp__.open('src/all/'+itemid+'/');
				break;
			}
		}
	};
const self = {
	getItemInfo(type, id, callback){
        YangRAM.get({
            url:__thisapp__.__dirs.getter + 'fileinfo/' + type + '/' + id + '/',
            done(txt) {
                if (txt.match(/^\{/)) {
                    var info=JSON.parse(txt);
                    callback(info);
                } else if (txt=='<ERROR404>') {
                    alert('File Not Exists');
                    __thisapp__.refresh();
                } else {
                    console.log(txt);
                    alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                }
            },
            fail(txt) {
                console.log(txt);
                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
            }
        });
},
	menuOnHigabar : {
		appname:__LANG__.APPNAME,
        menus:{
            'Common':[{
                    title:__('MENUS')("Default Page"),
                    state:'on',
                    handler:'OpenDefault'
                },
                {
                    title:__('MENUS')("Refresh"),
                    state:'on',
                    handler:'ReLoad'
                }
            ],
            'View':[{
                    title:'Sleep',
                    state:'on',
                    handler:'Sleep'
                },
                {
                    title:'Center',
                    state:'off',
                    handler:'Center'
                },
                {
                    title:'Cover',
                    state:'off',
                    handler:'Cover'
                },
                {
                    title:'Close',
                    state:'on',
                    handler:'Close'
                }
            ],
            'Help':[{
                    title:YangRAM.API.TXT.local('I4PLAZA')('MENUS')("YangRAM Official Website"),
                    state:'on',
                    handler:'GotoYangRAM'
                },
                {
                    title:YangRAM.API.TXT.local('I4PLAZA')('MENUS')("Forum"),
                    state:'on',
                    handler:'GotoForum'
                },
                {
                    title:YangRAM.API.TXT.local('I4PLAZA')('MENUS')("Developer Center"),
                    state:'on',
                    handler:'GotoDevCnt'
                },
                {
                    title:YangRAM.API.TXT.local('I4PLAZA')('MENUS')("About YangRAM"),
                    state:'on',
                    handler:'GotoAbout'
                }
            ],
        }
	},
	 menuOnFolder : [
		[
			{
                title:__('MENUS')("Open Folder"),
                state:'on',
                handler:CTX_MENU_HANDLERS.OpenFolder
            },
            {
                title:__('MENUS')("Move To"),
                state:'on',
                handler:CTX_MENU_HANDLERS.MoveTo
            },
            {
                title:__('MENUS')("Rename"),
                state:'on',
                handler:CTX_MENU_HANDLERS.Rename
            },
            {
                title:__('MENUS')("Delete Folder"),
                state:'on',
                handler:CTX_MENU_HANDLERS.Delete
            }
        ]
	],
	 menuOnSPC : [
		[{
            title:__('MENUS')("Delete Content"),
            state:'on',
            handler:CTX_MENU_HANDLERS.Delete
        }]
	],
	 menuOnDocument : [
		[{
                title:__('MENUS')("Move To"),
                state:'on',
                handler:CTX_MENU_HANDLERS.MoveTo
            },
            {
                title:__('MENUS')("Copy URL"),
                state:'on',
                handler:CTX_MENU_HANDLERS.CopyURL
            },
            {
                title:__('MENUS')("Replace"),
                state:'on',
                handler:CTX_MENU_HANDLERS.Replace
            },
            {
                title:__('MENUS')("Rename"),
                state:'on',
                handler:CTX_MENU_HANDLERS.Rename
            },
            {
                title:__('MENUS')("Delete Documents"),
                state:'on',
                handler:CTX_MENU_HANDLERS.Delete
            }
        ]
	],
	 menuOnPicture : [
		[{
                title:__('MENUS')("Preview"),
                state:'on',
                handler:PICS
            },
            {
                title:__('MENUS')("Move To"),
                state:'on',
                handler:CTX_MENU_HANDLERS.MoveTo
            },
            {
                title:__('MENUS')("Copy URL"),
                state:'on',
                handler:CTX_MENU_HANDLERS.CopyURL
            },
            {
                title:__('MENUS')("Copy Code"),
                state:'on',
                handler:CTX_MENU_HANDLERS.CopyCode
            },
            {
                title:__('MENUS')("Replace"),
                state:'on',
                handler:CTX_MENU_HANDLERS.Replace
            },
            {
                title:__('MENUS')("Rename"),
                state:'on',
                handler:CTX_MENU_HANDLERS.Rename
            },
            {
                title:__('MENUS')("Delete Documents"),
                state:'on',
                handler:CTX_MENU_HANDLERS.Delete
            }
        ]
	],
	 menuOnText : [
		[{
                title:__('MENUS')("Preview"),
                state:'on',
                handler:TXTS
            },
            {
                title:__('MENUS')("Move To"),
                state:'on',
                handler:CTX_MENU_HANDLERS.MoveTo
            },
            {
                title:__('MENUS')("Copy URL"),
                state:'on',
                handler:CTX_MENU_HANDLERS.CopyURL
            },
            {
                title:__('MENUS')("Replace"),
                state:'on',
                handler:CTX_MENU_HANDLERS.Replace
            },
            {
                title:__('MENUS')("Rename"),
                state:'on',
                handler:CTX_MENU_HANDLERS.Rename
            },
            {
                title:__('MENUS')("Delete Documents"),
                state:'on',
                handler:CTX_MENU_HANDLERS.Delete
            }
        ]
	],
	 menuOnAudio : [
		[{
                title:__('MENUS')("Play"),
                state:'on',
                handler:WAVS
            },
            {
                title:__('MENUS')("Move To"),
                state:'on',
                handler:CTX_MENU_HANDLERS.MoveTo
            },
            {
                title:__('MENUS')("Copy URL"),
                state:'on',
                handler:CTX_MENU_HANDLERS.CopyURL
            },
            {
                title:__('MENUS')("Copy HTML Code"),
                state:'on',
                handler:CTX_MENU_HANDLERS.CopyCode
            },
            {
                title:__('MENUS')("Replace"),
                state:'on',
                handler:CTX_MENU_HANDLERS.Replace
            },
            {
                title:__('MENUS')("Rename"),
                state:'on',
                handler() {}
            },
            {
                title:__('MENUS')("Delete Documents"),
                state:'on',
                handler:CTX_MENU_HANDLERS.Delete
            }
        ]
	],
	renameItem(){
    if (YangRAM.attr(this.parentNode, 'x-type') !='spc') {
            if (YangRAM.attr(this.parentNode, 'readonly') !='' && YangRAM.attr(this, 'contenteditable') !='') {
                var orgName=this.innerHTML;
                YangRAM.attr(this, 'contenteditable', 'true').attr(this, 'tabindex', '2');
                var that=this;
                this.onblur=function() {
                    var newName=this.innerHTML.replace(/<[^>]*>/g, '');
                    if (newName !=orgName) {
                        if (newName.match(/(<|>|\/|\\|\||:|\"|\*|\?)/)) {
                            alert(__('WORDS')('Can Not Contain <>/\|:"*?'),
                                function() {
                                    that.innerHTML=orgName;
                                });
                        } else if (newName.length > 50) {
                            alert(__('WORDS')("Can Not More Than 50 Words!"),
                                function() {
                                    that.innerHTML=orgName;
                                });
                        } else if (newName.length < 1) {
                            alert(__('WORDS')("Can Not Empty!"),
                                function() {
                                    that.innerHTML=orgName;
                                });
                        } else {
                            var type=YangRAM.attr(this.parentNode, 'x-type');
                            var id=YangRAM.attr(this.parentNode, 'x-id');
                            var preset=YangRAM.attr(this.parentNode, 'preset');
                            var itemNames={
                                'img':__('NAMES')('img'),
                                'txt':__('NAMES')('txt'),
                                'vod':__('NAMES')('vod'),
                                'wav':__('NAMES')('wav'),
                                'folder':__('NAMES')('folder'),
                                'doc':__('NAMES')('doc'),
                                'spc':YangRAM.attr(this.parentNode, 'itemname')
                            }
                            var data=new FormData();
                            data.append('type', type);
                            data.append('preset', preset);
                            data.append('id', id);
                            data.append('name', newName);
                            YangRAM.set({
                                url:__thisapp__.__dirs.setter + 'submit/mod_name/',
                                data:data,
                                done(txt) {
                                    //console.log(txt);
                                    YangRAM.tools.hideMagicCube(timeout);
                                    if (txt=='<ERROR>' || txt.match('PHP Notice:')) {
                                        alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"),
                                            function() {
                                                that.innerHTML=orgName;
                                            });
                                    } else if (txt=='<ERROR404>') {
                                        alert(__('WORDS')("File Not Exists!"));
                                        __thisapp__.refresh();
                                    } else {
                                        that.innerHTML=txt;
                                        YangRAM.attr(that.parentNode, 'name', txt)
                                            .API.MSG.notice({
                                                appId:__thisapp__.appid,
                                                title:__('WORDS')("Rename Success"),
                                                content:__('WORDS')('Rename_Success')(itemNames[type])
                                            });
                                    }
                                    that.onblur=null;
                                },
                                fail(txt) {
                                    console.log(txt);
                                    YangRAM.tools.hideMagicCube(timeout);
                                    alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"),
                                        function() {
                                            that.innerHTML=orgName;
                                        });
                                    that.onblur=null;
                                }
                            });
                            var timeout=YangRAM.tools.showMagicCube(30000, function() {
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
                appId:__thisapp__.appid,
                title:__('WORDS')("Rename Prohibited"),
                content:__('WORDS')("Cannot Rename for a Preset Content!")
            });
        }
},
	itemTags : {
        a(type, id, name, sfix) {
            var src=YangRAM.RequestDIR + 'files/' + type + '/' + id + '.' + sfix;
            return '<a href="' + src + '">' + name + '</a>';
        },
        folder() {
            return '';
        },
        pic(type, id, name, sfix) {
            var src=YangRAM.RequestDIR + 'files/img/' + id + '.' + sfix;
            return '<img src="' + src + '" alt="' + name + '" />';
        },
        wav(type, id, name, sfix) {
            var src=YangRAM.RequestDIR + 'files/wav/' + id + '.' + sfix;
            return '<audio src="' + src + '"><a href="' + src + '?download">' + name + '</a></audio>';
        },
        vod(type, id, name, sfix) {
            var src=YangRAM.RequestDIR + 'files/vod/' + id + '.' + sfix;
            return '<video src="' + src + '"><a href="' + src + '?download">' + name + '</a></video>';
        }
},
	handleSmartianOrder(){
        var itemtype=arguments[1];
        var itemid=arguments[2];
        if (itemtype && itemid) {
            switch (itemtype) {
                case 'folder':this.open('src/all/' + itemid + '/');
                    break;
            }
        }
},
	getchosenFolder(event){
        var elem=event.relatedTarget  ||  event.srcElement  ||  event.target  || event.currentTarget;
        if (elem.tagName=='item') {
            return elem;
        } else {
            return YangRAM.API.dom.closest(elem, 'item');
        }
},
	showDialogOfFolderList(txt){
    YangRAM.tools.showDialog({
                title:__('WORDS')("Move folders and files to"),
                appid:__thisapp__.appid,
                css:'dialog',
                height:400,
                control:[{
                        name:YangRAM.API.TXT.local('COMMON')('WORDS')("Cancel"),
                        href:'CloseDialog',
                        args:''
                    },
                    {
                        name:YangRAM.API.TXT.local('COMMON')('WORDS')("Confirm"),
                        href:'MoveSelectedTochosenFolder',
                        args:''
                    }
                ]
            }, function() {
                var dialogMain=this.render(txt).contentarea;
                dialogMain.bindListener('item[fldid]', 'mouseup', function() {
                        var folder=self.getchosenFolder(event)
                        if (folder && folder==this) {
                            YangRAM.$('item[selected]', dialogMain).removeAttr('selected');
                            YangRAM.attr(folder, 'selected', '');
                            if (YangRAM.API.dom.hasClass(folder, 'has-child')) {
                                var childList=YangRAM.$('list.folder-tree', folder);
                                if (childList.html()=='') {
                                    var data=new FormData();
                                    data.append('fldid', YangRAM.attr(folder, 'fldid'));
                                    data.append('level', YangRAM.attr(folder, 'level'));
                                    YangRAM.set({
                                        url:__thisapp__.__dirs.getter + 'folders/children/',
                                        data:data,
                                        done(txt) {
                                            if (txt=='<ERROR>' || txt.match('PHP Notice:')) {
                                                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                                            } else if (txt=='<ERROR404>') {
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
},
	eventHandlers : {
        'left item[x-href]':{
            'click' (event) {
                var href=YangRAM.attr(this, 'x-href');
                __thisapp__.onlaunch(href);
            }
        },
        'main content item[x-href]':{
            'click' (event) {
                var href=YangRAM.attr(this, 'x-href');
                __thisapp__.onlaunch(href);
            }
        },
        'main content .item.folder':{
            'click' (event) {
                var elem=event.relatedTarget  ||  event.srcElement  ||  event.target  || event.currentTarget;
                if (elem.className !="sele" && elem.className !="rplc" && (elem.className !="name" || YangRAM.attr(elem.parentNode.parentNode.parentNode, 'x-type')=='list') && elem.tagName !="INPUT") {
                    var href=YangRAM.attr(this, 'x-href');
                    var folder=parseInt(YangRAM.attr(this, 'x-id'));
                    __thisapp__.onlaunch(href, folder);
                }
            }
        },
        'main content .item[x-type=img]':{
            'click' (event) {
                var elem=event.relatedTarget  ||  event.srcElement  ||  event.target  || event.currentTarget;
                if (elem.className !="sele" && elem.className !="rplc" && (elem.className !="name" || YangRAM.attr(elem.parentNode.parentNode.parentNode, 'x-type')=='list') && elem.tagName !="INPUT") {
                    PICS.call(this);
                }
            }
        },
        'main content .item[x-type=txt]':{
            'click' (event) {
                var elem=event.relatedTarget  ||  event.srcElement  ||  event.target  || event.currentTarget;
                if (elem.className !="sele" && elem.className !="rplc" && (elem.className !="name" || YangRAM.attr(elem.parentNode.parentNode.parentNode, 'x-type')=='list') && elem.tagName !="INPUT") {
                    TXTS.call(this);
                }
            }
        },
        'main content .item[x-type=wav]':{
            'click' (event) {
                var elem=event.relatedTarget  ||  event.srcElement  ||  event.target  || event.currentTarget;
                if (elem.className !="sele" && elem.className !="rplc" && (elem.className !="name" || YangRAM.attr(elem.parentNode.parentNode.parentNode, 'x-type')=='list') && elem.tagName !="INPUT") {
                    WAVS.call(this);
                }
            }
        },
        'main content .item':{
            'rclick' (event) {
                __thisapp__.$('main content .item').toggleClass('selected', false);
                YangRAM.toggleClass(this, 'selected');
            }
        },
        'main content .item .sele':{
            'click' (event) {
                YangRAM.toggleClass(this.parentNode, 'selected');
            }
        },
        'main content .item .rplc':{
            'click' (event) {
                CTX_MENU_HANDLERS.Replace.call(this.parentNode);
                //YangRAM.toggleClass(this.parentNode, 'selected');
            }
        },
        'main .main-content[x-type=tile] .name':{
            'click'(event){
                self.renameItem.call(this);
            } 
        },
        '.uploader-content .x-action':{
            'click' (event) {
                var num=parseInt(YangRAM.attr(this, 'x-index'));
                privates.toBeupload=YangRAM.API.util.arr.removeByIndex(privates.toBeupload , num);
                var file=YangRAM.API.dom.closest(this, 'item');
                YangRAM.API.dom.remove(file);
            }
        },
        '.uploader-startbtn':{
            'mousedown' (event) {
                if (privates.toBeupload.length > 0) {
                    UPLOADER_START();
                } else {
                    alert(__('WORDS')("Please Select Files To Be Upload."));
                }
            }
        },
        '.uploader-cancelbtn':{
            'mousedown' (event) {
                if (privates.uploading) {
                    alert(__('WORDS')("Uploader Already In Working, Can Not Be Canceled!"));
                } else {
                    UPLOADER_HIDE();
                }
            }
        },
        '.uploader_completebtn':{
            'mousedown' () {
                __thisapp__.refresh();
            }
        },
        'top-vision .searcher input':{
            'enter' (event) {
                var kw=this.value;
                if (kw && kw !='') {
                    this.value='';
                    __thisapp__.open('sch/?kw=' + encodeURIComponent(kw));
                }
            }
        },
        '.browse-popup .browse-img-src':{
            'click' () {
                var src=YangRAM.attr(this, 'data-val');
                YangRAM.API.TXT.copy(src);
            }
        },
        '.browse-popup .browse-img-img':{
            'click' () {
                var src=YangRAM.attr(this, 'data-val');
                var alt=YangRAM.$('.browse-img-title', this.parentNode).html();
                YangRAM.API.TXT.copy('<img src="' + src + '" alt="' + alt + '" />');
            }
        },
        '.browse-popup .browse-txt click':{
            'click' () {
                var src=YangRAM.attr(this, 'data-val');
                YangRAM.API.TXT.copy(src);
            }
        },
        '.browse-popup .browse-close':{
            'click' () {
                __thisapp__.$('.browse-popup').hide();
                self.palyer && (self.palyer.innerHTML='');
            }
        },
        '.browse-popup .browse-txt-content':{
            'mousewheel' () {
                document.onmousewheel==null;
            }
        }
}};
const pm_5919526128523 = {
	palyer : null,
	toBeupload : [],
	toBeuploadNum : 0,
	uploadedNum : 0,
	uploadedFailNum : 0,
	uploadingState : false,
	uploading : false,
	uploaderScrollBAR : null
};
const pm_5919526128527 = {
	name : __LANG__.APPNAME,
	listType : 'tile',
	listOrder : 'na',
	currentFolder : 6,
	chosenFolder : 6,
	foldersList : '',
	API : {
        HIGHBAR_HANDLERS:{
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
                    case 'open':SMARTIAN_OPEN.apply(this, arguments);
                        break;
                }
            }
        },
        BROWSER_TRIGGERS:{
            CreateFolder() {
                var data=new FormData();
                data.append('parent', __thisapp__.currentFolder);
                YangRAM.set({
                    url:__thisapp__.__dirs.setter + 'submit/new_folder/',
                    data:data,
                    done(txt) {
                        YangRAM.tools.hideMagicCube(timeout);
                        if (txt=='<SUCCESS>') {
                            __thisapp__.refresh();
                            YangRAM.API.MSG.notice({
                                title:__('WORDS')("Create Success"),
                                content:__('WORDS')("A New Folder Has Been Create Successfully!")
                            });
                        } else if (txt=='<NO_PERMISSIONS>') {
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
                var timeout=YangRAM.tools.showMagicCube(30000, function() {
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
                var selects=__thisapp__.$('main content .item.selected');
                if (selects.length > 1) {
                    var pass=0;
                    var success=0;
                    var failed=0;
                    var listen=function() {
                        if (success + failed + pass==selects.length) {
                            if (success > 0) {
                                YangRAM.API.MSG.notice({
                                    appId:__thisapp__.appid,
                                    title:__('WORDS')("Delete Success"),
                                    content:__('WORDS')('Delete_Success_Multiple')(success)
                                });
                                __thisapp__.refresh();
                                //YangRAM.API.CHK.recycle();
                            } else {
                                alert(__('WORDS')("Delete Failed!"));
                            }
                        }
                    }
                    selects.each(function(index) {
                        if (YangRAM.attr(this, 'readonly') !='') {
                            __thisapp__.removeItem(this, function(txt) {
                                if (txt=='<SUCCESS>') {
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
                } else if (selects.length==1) {
                    if (YangRAM.attr(selects[0], 'readonly') !='') {
                        __thisapp__.removeItem(selects[0]);
                    } else {
                        alert(__('WORDS')("Items Readonly"));
                    }
                } else {
                    alert(__('WORDS')("Please select items to be operate."));
                }
            },
            MoveSelected() {
                var selects=__thisapp__.$('main content .item.selected');
                if (selects.length > 0) {
                    if (__thisapp__.foldersList=='') {
                        YangRAM.set({
                            url:__thisapp__.__dirs.getter + 'folders/roots/',
                            done(txt) {
                                if (txt=='<ERROR>' || txt.match('PHP Notice:')) {
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
                var selects=__thisapp__.$('main content .item.selected');
                if (selects.length > 0) {
                    var codes='';
                    selects.each(function() {
                        var type=YangRAM.attr(this, 'x-type');
                        var id=YangRAM.attr(this, 'x-id');
                        var name=YangRAM.attr(this, 'name');
                        var sfix=YangRAM.attr(this, 'x-suffix');
                        codes +=self.itemTags[type] ? self.itemTags[type](type, id, name, sfix):self.itemTags.a(type, id, name, sfix);
                    });
                    YangRAM.API.TXT.copy(codes);
                } else {
                    alert(__('WORDS')("Please select items to be operate."));
                }
            },
            CopyJSONCodes() {
                var selects=__thisapp__.$('main content .item.selected');
                if (selects.length > 0) {
                    var codes='[';
                    selects.each(function(i) {
                        var type=YangRAM.attr(this, 'x-type');
                        var id=YangRAM.attr(this, 'x-id');
                        var name=YangRAM.attr(this, 'name');
                        var sfix=YangRAM.attr(this, 'x-suffix');
                        var src=YangRAM.RequestDIR + 'files/' + type + '/' + id + '.' + sfix;
                        if (i) {
                            codes +=',';
                        }
                        codes +='{"name":"' + name + '", "src":"' + src + '"}';
                    });
                    codes +=']'
                    YangRAM.API.TXT.copy(codes);
                } else {
                    alert(__('WORDS')("Please select items to be operate."));
                }
            },
            Uploader() {
                YangRAM.tools.pickFiles({
                    multiple:true,
                    sfixs:true,
                    maxsize:1024 * 1024 * 20,
                    done(files) {
                        //console.log(files)
                        UPLOADER(files);
                    },
                    fail(file, errtype) {
                        console.log(file)
                        switch (errtype) {
                            case 0:alert(__('WORDS')("Type Not Support!"));
                                break;
                            case 1:alert(__('WORDS')("Filesize OVER!"));
                                break;
                            case 2:alert(__('WORDS')("No Legal File Selected!"));
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
                if (YangRAM.attr(this, 'readonly') !='') {
                    __thisapp__.setSource(__thisapp__.source.replace(/lo=(na|nd|ta|td|sa|sd)/i, 'lo=sa'));
                    __thisapp__.refresh();
                }
            },
            OrderByFileSizeDESC() {
                if (YangRAM.attr(this, 'readonly') !='') {
                    __thisapp__.setSource(__thisapp__.source.replace(/lo=(na|nd|ta|td|sa|sd)/i, 'lo=sd'));
                    __thisapp__.refresh();
                }
            },
            SwitchMainContentViewType() {
                var mc=__thisapp__.$('.main-content');
                if (mc.attr('x-type')==='tile') {
                    mc.attr('x-type', 'list');
                    __thisapp__.listType='list';
                    __thisapp__.setSource(__thisapp__.source.replace('lt=tile', 'lt=list'));
                } else {
                    mc.attr('x-type', 'tile');
                    __thisapp__.listType='tile';
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
                    var data=new FormData();
                    data.append('parent', __thisapp__.chosenFolder);
                    data.append('id', YangRAM.attr(this, 'x-id'));
                    data.append('type', YangRAM.attr(this, 'x-type'));
                    YangRAM.set({
                        url:__thisapp__.__dirs.setter + 'submit/move_to_folder/',
                        data:data,
                        done(txt) {
                            if (txt=='<SUCCESS>') {
                                __thisapp__.refresh();
                            } else if (txt=='<SELF>') {
                                alert(__('WORDS')("Can Not Move To Self"));
                            } else if (txt=='<CHILD>') {
                                alert(__('WORDS')("Can Not Move To Child Folder"));
                            } else if (txt=='<!EXIST>') {
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
    },
	setCenteredView : YangRAM.donothing,
	onload(){
        this.leftSide=YangRAM.$('left', this.document)[0];
        this.mainTop=YangRAM.$('vision.main-topbar', this.document)[0];
        this.mainContent=YangRAM.$('vision.main-content', this.document)[0];
        this.leftScrollBAR=__thisapp__.OIMLElement.bind('scrollbar', this.leftSide);
        this.mainScrollBAR=__thisapp__.OIMLElement.bind('scrollbar', this.mainContent);
        this.foldersList='';
        return this;
    },
	onlaunch(href, folder){
        if (typeof href=='string') {
            href=href.split('?')[0];
            if (folder) {
                this.currentFolder=folder;
            } else {
                var REQUEST=href.replace(/\/+/, '/').split('/');
                if (REQUEST[0]=='src' && REQUEST[2] !='') {
                    this.currentFolder=parseInt(REQUEST[2]);
                } else {
                    this.currentFolder=6;
                }
            }
            href +='?lt=' + this.listType + '&lo=' + this.listOrder;
            this.open(href, ()=> {
                if(privates.toBeupload.length){
                    UPLOADS_LISTER(privates.toBeupload );
                }
            });
        }
        //console.log(href, REQUEST, folder, this.currentFolder);
        return this.resize();
    },
	onclosedialog(dialogMain){
        this.chosenFolder=YangRAM.$('item[fldid][selected]', dialogMain).attr('fldid');
        this.foldersList=dialogMain.innerHTML;
    },
	removeItem(elem, doneCallback, failCallback){
        var type=YangRAM.attr(elem, 'x-type');
        var id=YangRAM.attr(elem, 'x-id');
        var data=new FormData();
        var itemName, url;
        switch (type) {
            case 'img':itemName=__('NAMES')(type);
                url=__thisapp__.__dirs.setter + 'submit/rmv_img/';
                data.append('id', id);
                break;
            case 'txt':itemName=__('NAMES')(type);
                url=__thisapp__.__dirs.setter + 'submit/rmv_txt/';
                data.append('id', id);
                break;
            case 'vod':itemName=__('NAMES')(type);
                url=__thisapp__.__dirs.setter + 'submit/rmv_vod/';
                data.append('id', id);
                break;
            case 'wav':itemName=__('NAMES')(type);
                url=__thisapp__.__dirs.setter + 'submit/rmv_wav/';
                data.append('id', id);
                break;
            case 'doc':itemName=__('NAMES')(type);
                url=__thisapp__.__dirs.setter + 'submit/rmv_doc/';
                data.append('id', id);
                break;
            case 'folder':itemName=__('NAMES')(type);
                url=__thisapp__.__dirs.setter + 'submit/rmv_folder/';
                data.append('fldid', id);
                break;
            case 'spc':itemName=YangRAM.attr(elem, 'itemname');
                url=__thisapp__.__dirs.setter + 'submit/rmv_content/';
                data.append('preset', YangRAM.attr(elem, 'preset'));
                data.append('id', id);
                break;
        }
        var doneCallback=doneCallback || function(txt) {
            if (txt.match('PHP Notice:')) {
                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
            } else if (txt=='<Deleted>') {
                alert(__('WORDS')("<Deleted>")(itemName));
            } else if (txt=='<ReadOnly>') {
                alert(__('WORDS')("<ReadOnly>")(itemName));
            } else if (txt=='<CAN_NOT_FIND>') {
                alert(__('WORDS')("<CAN_NOT_FIND>")(itemName));
            } else if (txt=='<SUCCESS>') {
                YangRAM.API.MSG.notice({
                    appId:__thisapp__.appid,
                    title:__('WORDS')("Delete Success"),
                    content:__('WORDS')("Delete_Success")(itemName)
                });
                __thisapp__.refresh();
                //YangRAM.API.CHK.recycle();
            } else {
                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Unkonw Error!"));
            }
        }
        var failCallback=failCallback || function(txt) {
            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
        }
        YangRAM.set({
            url:url,
            data:data,
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
        var timeout=YangRAM.tools.showMagicCube(30000, function() {
            alert('Timeout');
        });
    },
	onafterresize(){
        var appContentHeight=System.Height - 91;
        this.$('left').css('height', appContentHeight).css('max-height', appContentHeight).css('min-height', appContentHeight);
        this.$('vision.main-content').css('height', appContentHeight - 90).css('max-height', appContentHeight - 90).css('min-height', appContentHeight - 90);
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
                    __thisapp__.regHeadBar(self.menuOnHigabar).open(__thisapp__.__temp.href, ()=> {
                        __thisapp__.setFullScreenView();
                    });
                }, 1500)
            })
    }};
const privates = {
	palyer : null,
	toBeupload : [],
	toBeuploadNum : 0,
	uploadedNum : 0,
	uploadedFailNum : 0,
	uploadingState : false,
	uploading : false,
	uploaderScrollBAR : null
};
YangRAM.extends(__thisapp__, true, pm_5919526128527);

});