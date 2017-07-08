RegApplication(1002, (__thisapp__, YangRAM, Using, Global, undefined) => {
'use strict';
const __LANG__ = {
    "CODE": "zh-cn",
    "APPNAME": "唐云内容发表者",
    "TOPMENUS": {
        "Default Page": "首页",
        "List Of Preset": "用途列表",
        "Preview": "预览"
    },
    "WORDS": {
        "Remove Item?": "移除项目？",
        "Are you sure to remove this item?": "你确定要移除这个项目吗？",
        "Reference Error": "引用错误",
        "I\"m Sorry!": "抱歉！",
        "Operation not supported!": "操作不支持！",
        "Save Success": "保存成功",
        "A Content Has Been Saved!": "成功保存了一个项目",
        "Published": "发表成功",
        "A Content Has Been Published!": "成功发表了一个项目！",
        "Remove Success": "移除成功",
        "A Content Has Been Removed!": "成功移除了一个项目！",
        "Specifies No Existing Item": "指定了一个不存在的项目",
        "Confirm Close?": "确定关闭？",
        "You are trying to close a tab, are you sure to do this?": "你正在试图关闭一个选项卡，确定要这样做吗？",
        "Cut To": "切到",
        "Exceed Maximum Size Allowed Upload": "操作最大上传尺寸",
        "Unsupported File Format": "不支持的文件格式",
        "Abandon Editing?": "放弃编辑?",
        "Are you sure to go to the list page before save your editing?": "你确定要在保存编辑中的内容前转到列表页吗？",
        "Are you sure to open a new item before save the editing item?": "你确定要在保存编辑中的内容前新建一个项目吗？"
    }
};
const __ = (word) => {
	return YangRAM.API.TXT.dictReader(__LANG__, word);
};
const __APPDIR__ = '/Applications/Tangram/Studio/Publisher/';
const $ = YangRAM.$;
const self = {
	menuOnHigabar : {
	    appname:__LANG__.APPNAME,
        menus:{
            'Open':[{
                    title:__LANG__.TOPMENUS['Default Page'],
                    state:'on',
                    handler:'OpenDefault'
                },
                {
                    title:__LANG__.TOPMENUS['List Of Preset'],
                    state:'on',
                    handler:'OpenList'
                },
            ],
            'Operate':[{
                    title:'New',
                    state:'on',
                    handler:'NewContent'
                },
                {
                    title:'Save',
                    state:'on',
                    handler:'SaveContent'
                },
                {
                    title:'Remove',
                    state:'on',
                    handler:'RemoveConten'
                },
                {
                    title:__LANG__.TOPMENUS['Preview'],
                    state:'on',
                    handler:'PreviewContent'
                }
            ],
            'Pageview':[{
                    title:'Sleep',
                    state:'on',
                    handler:'Sleep'
                },
                {
                    title:'Center',
                    state:'on',
                    handler:'Center'
                },
                {
                    title:'Cover',
                    state:'on',
                    handler:'Cover'
                },
                {
                    title:'Close',
                    state:'on',
                    handler:'Close'
                }
            ],
            'Help':[{
                    title:'Dataroom Official Website',
                    state:'on',
                    handler:'OfficialWebsite'
                },
                {
                    title:'Publisher Forum',
                    state:'on',
                    handler:'GotoForum'
                },
                {
                    title:'About YangRAM Dataroom',
                    state:'on',
                    handler:'GotoAbout'
                }
            ]
        }
	},
	events : {
	'ico.pub-icon':{
            'click':function() {
                __thisapp__.open('default/startpage/');
            }
        },
        'main item[href]':{
            'click':function() {
                var href=YangRAM.attr(this, 'href');
                __thisapp__.open(href);
            }
        },
        'tools.pub-ctrl item':{
            'click':function() {
                if (!YangRAM.API.DOM.hasClass(this, 'unavailable')) {
                    var type=YangRAM.attr(this, 'x-usefor');
                    typeof self.controls[type]=='function' && self.controls[type]();
                };
            }
        },
        'section.curr inputs[type=text] click[type=pick][picker=timepicker]':{
            'click':function() {
                var fieldname=YangRAM.attr(this, 'name');
                var input=YangRAM.$('input[name=' + fieldname + ']', this.parentNode);
                var type=YangRAM.attr(this, 'pick-data-type') || 'datetime';
                    YangRAM.tools.pickTime(input.val(), type, (val)=> {
                        input.val(val);
                    });
            }
        },
        'section.curr inputs[type=text] click[type=pick][picker=uploader]':{
            'click':function() {
                var fieldname=YangRAM.attr(this, 'name');
                var type=YangRAM.attr(this, 'filetype');
                var sfixs=false;
                var input=YangRAM.$('input[name=' + fieldname + ']', this.parentNode)[0];
                if (!type || type=='all') {
                    type=null;
                    sfixs=true;
                }
                YangRAM.tools.pickFiles({
                    multiple:false,
                    filter:false,
                    type:type,
                    sfixs:sfixs,
                    maxsize:1024 * 1024 * 20,
                    done:function(files) {
                        console.log(this);
                        __thisapp__.upload(files[0], {
                            before:function(data) {
                                input.value=data.responseText;
                            },
                            progress:function(data) {
                                if (data.lengthComputable) {
                                    input.value=Math.round(data.loaded * 100 / data.total) + '%';
                                };
                            },
                            after:function(data) {
                                input.value=data.responseText;
                            },
                            done:function(data) {
                                try {
                                    var json=JSON.parse(data.responseText);
                                    switch (json.code) {
                                        case 200:var text=json.file.url;
                                            break;
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
                                input.value=text;
                            },
                            fail(data) {
                                input.value='';
                                alert('Upload Failed');
                                console.log(data);
                            },
                            returnType:'json'
                        });
                    },
                    fail(error, err_type) {
                        if (err_type) {
                            alert(__('WORDS')('Exceed Maximum Size Allowed Upload'));
                        } else {
                            alert(__('WORDS')('Unsupported File Format'));
                        }
                    }
                });
            }
        },
        'input, textarea, .ib-editor[data-editor-id]':{
            'mouseup':function() {
                __thisapp__.Input=this;
            }
        },
        'inputs[inpreview] input':{
            'mouseover':function() {
                var parent=this.parentNode;
                var status=YangRAM.attr(parent, 'inpreview');
                if (status !='inpreview') {
                    var src=this.value;
                    var url=YangRAM.attr(parent, 'preview-src');
                    if (src) {
                        if (src !=url) {
                            var ctx=YangRAM.$('image-vision', this.parentNode).html('').get(0);
                            var img=new YangRAM.API.medias.Image({
                                src:src,
                                context:ctx,
                                onload:function() {
                                    if (this.width > this.height) {
                                        var w=this.width < 250 ? this.width:250;
                                        var h=this.height / this.width * w;
                                    } else {
                                        var h=this.height < 250 ? this.height:250;
                                        var w=this.width / this.height * h;
                                    }
                                    YangRAM.setStyle(ctx, {
                                        width:w,
                                        height:h
                                    });
                                    YangRAM.attr(parent, 'inpreview', 'inpreview');
                                    //console.log(src, this.width, this.height);
                                },
                                onerror:function() {
                                    YangRAM.attr(parent, 'preview-src', '');
                                }
                            });
                            YangRAM.attr(parent, 'preview-src', src);
                        } else if (url) {
                            YangRAM.attr(parent, 'inpreview', 'inpreview');
                        }
                    }
                }
            },
            'mouseout':function() {
                var parent=this.parentNode;
                var status=YangRAM.attr(parent, 'inpreview');
                if (status=='inpreview') {
                    YangRAM.attr(parent, 'inpreview', 'outpreview');
                }
            }

        }
    },
	editors : {},
	checkEditor(tabPath){
        //console.log(tabPath);
		if (tabPath[0] !='' && tabPath[0] !='default' && tabPath.length > 2) {
            var preset=tabPath[1].toUpperCase();
            var selector='#' + __thisapp__.attr('id');
            selector +=' section[data-tab-name=' + preset.toUpperCase() + ']';
            selector +=' inputs[type=editor] textarea';
            //console.log(selector);
            self.editors[preset]=YangRAM.API.form.careatEditors(selector, {
                themeType:'default',
                border:{
                    color:'#DDD'
                },
                toolbarType:'complete',
                fragments:[{
                        name:'分页符',
                        code:'{{@page_break}}'
                    }],
                onSwitch:function() {},
                uploader:{
                    maxsize:1024 * 1024 * 20,
                    sfixs:false,
                    transfer:function(files, inserter) {
                        var total=files.length;
                        var loaded=0;
                        var failed=0;
                        var images=[];

                        function listen() {
                            if (loaded + failed==total) {
                                inserter(images, failed);
                            }
                        };
                        for (var i=0; i < files.length; i++) {
                            __thisapp__.upload(files[i], {
                                done(data) {
                                    try {
                                        var json=JSON.parse(data.responseText);
                                        if (json.code==200) {
                                            loaded++;
                                            images.push(json.file.url);
                                        } else {
                                            failed++;
                                            console.log('File Upload Failed');
                                        }
                                    } catch (e) {
                                        failed++;
                                        console.log('File Upload Failed');
                                    }
                                    listen();
                                },
                                fail(data) {
                                    failed++;
                                    listen();
                                },
                                returnType:'json'
                            });
                        }
                    }
                }
            });
        }
	},
	controls : {
		'preset-list':function() {
            if (__thisapp__.tabviews.currTabName=='GENERAL') {
                __thisapp__.open('gec/' + __thisapp__.tabviews.currTabName);
            } else {
                __thisapp__.open('spc/' + __thisapp__.tabviews.currTabName);
            }
        },
        'content-new':function() {
            if (__thisapp__.tabviews.currTabName=='GENERAL') {
                __thisapp__.open('gec/' + __thisapp__.tabviews.currTabName + '/new/');
            } else {
                __thisapp__.open('spc/' + __thisapp__.tabviews.currTabName + '/new/');
            }
        },
        'content-save':function() {
            var preset=__thisapp__.tabviews.currTabName;
            var id=__thisapp__.$('section.curr form-vision').attr('x-cid'),
                sort=__thisapp__.$('section.curr form-vision').attr('x-sort'),
                page=__thisapp__.$('section.curr form-vision').attr('x-page'),
                status=__thisapp__.$('section.curr form-vision').attr('x-status'),
                cls=__thisapp__.$('section.curr form-vision').attr('x-cls');
            self.submit(preset, id, 'sav', sort, page, status, cls);
        },
        'content-del':function() {
            var preset=__thisapp__.tabviews.currTabName;
            var id=__thisapp__.$('section.curr form-vision').attr('x-cid'),
                sort=__thisapp__.$('section.curr form-vision').attr('x-sort'),
                page=__thisapp__.$('section.curr form-vision').attr('x-page'),
                status=__thisapp__.$('section.curr form-vision').attr('x-status'),
                cls=__thisapp__.$('section.curr form-vision').attr('x-cls');
            if (parseInt(id)) {
                YangRAM.API.MSG.popup({
                    title:__('Remove Item?'),
                    content:__('Are you sure to remove this item?'),
                    confirm:YangRAM.API.TXT.local('NOTIFIER')('DEFAULT')('confirm'),
					cancel:YangRAM.API.TXT.local('NOTIFIER')('DEFAULT')('cancel'),
                    done:function() {
                        if (preset==__thisapp__.tabviews.currTabName) {
                            return self.submit(preset, id, 'del', sort, page, status, cls);
                        }
                        alert(__('Reference Error'));
                    }
                });
            } else {
                YangRAM.API.MSG.notice({
                    appid:__thisapp__.appid,
                    title:__('WORDS')('I\'m Sorry!'),
                    content:__('WORDS')('Operation not supported!'),
                });
            }
        },
        'content-view':function() {
            var preset=__thisapp__.tabviews.currTabName;
            var elem=__thisapp__.$('section.curr form-vision');
            var id=elem.attr('x-cid');
            var theme=elem.attr('x-theme');
            var template=elem.attr('x-template');
            if (theme && theme !='' && template && template !='') {
                var form=document.createElement("form");
                form.action=YangRAM.VirtualURI + 'preview/' + preset + '/' + theme + '/?temp=' + template;
                form.method='post';
                form.target='_blank';
                YangRAM.create('input', form, {
                    type:'hidden',
                    name:'ID',
                    value:id,
                });
                if (self.editors[preset]) {
                    for (var e=0; e < self.editors[preset].length; e++) {
                        self.editors[preset][e].getValue();
                    }
                };
                var fields=new YangRAM.API.form.Data(elem[0]).checkValue().data;
                for (var i in fields) {
                    YangRAM.create('input', form, {
                        type:'hidden',
                        name:i,
                        value:fields[i][0],
                    });
                }
                form.submit();
            } else {
                alert(__('WORDS')('No Default template!'));
            }
        },
        'content-base':function() {
            self.switchContentInfoSection('content-base')
        },
        'content-prop':function() {
            self.switchContentInfoSection('content-prop')
        },
        'content-rele':function() {
            self.switchContentInfoSection('content-rele')
        },
        'mini-explorer':function() {
            YangRAM.tools.ExplorerSRC();
        },
        'pub-setting':function() {
            YangRAM.API.APP.launch(3, 'set-application-permissions/1002/')
        }
	},
	switchContentInfoSection(section){
		__thisapp__.$('section[data-tab-name='+__thisapp__.tabviews.currTabName+'] .content-info-section').hide();
		__thisapp__.$('section[data-tab-name='+__thisapp__.tabviews.currTabName+'] .'+section).show();
        console.log('section[data-tab-name='+__thisapp__.tabviews.currTabName+'] .'+section);
	},
	submit(preset, id, type, sort, page, status, cls){
		var url, title, content;
        var data=new FormData();
        preset=preset.toLowerCase();
        data.append('SET_ALIAS', preset);
        data.append('ID', id);
        switch (type) {
            case 'sav':url=__thisapp__.__dirs.setter + 'submit/sav/';
                title=__('WORDS')('Save Success');
                content=__('WORDS')('A Content Has Been Saved!');
                data=self.checkForm(data, preset);
                break;
            case 'pub':url=__thisapp__.__dirs.setter + 'submit/pub/';
                title=__('WORDS')('Published');
                content=__('WORDS')('A Content Has Been Published!');
                data=self.checkForm(data, preset);
                break;
            case 'del':url=__thisapp__.__dirs.setter + 'submit/rmv/';
                title=__('WORDS')('Remove Success');
                content=__('WORDS')('A Content Has Been Removed!');
                break;
        }
        YangRAM.set({
            url:url,
            data:data,
            done:function(txt) {
                //console.log(txt);
                if (parseInt(txt)) {
                    YangRAM.API.MSG.notice({
                        appId:__thisapp__.appid,
                        title:title,
                        content:content
                    });
                    if (preset=='general') {
                        if (status==undefined) {
                            __thisapp__.open('gec/' + __thisapp__.tabviews.currTabName + '?sort=' + sort + '&page=' + page, true);
                        } else {
                            __thisapp__.open('gec/' + __thisapp__.tabviews.currTabName + '?sort=' + sort + '&group=' + status + '&page=' + page, true);
                        }
                    } else {
                        __thisapp__.open('spc/' + __thisapp__.tabviews.currTabName + '?sort=' + sort + '&stts=' + status + '&cat=' + cls + '&page=' + page, true);
                    }
                    //YangRAM.API.CHK.recycle();
                } else if (txt=='<CAN_NOT_FIND>') {
                    alert(__('WORDS')('Specifies No Existing Item'));
                } else {
                    console.log(url, preset, id, txt);
                    alert('Unkonw Error');
                }
            },
            fail(txt) {
                console.log(txt);
                alert('Network Error');
            }
        });
	},
	checkForm(data, preset){
		var elem=__thisapp__.$('section.curr'),
        preset=preset.toUpperCase();
        if (elem.attr('data-tab-name')===preset) {
            if (self.editors[preset]) {
                for (var e=0; e < self.editors[preset].length; e++) {
                    self.editors[preset][e].getValue();
                }
            }else{
            }
            var form=new YangRAM.API.form.Data(elem[0]);
            var fields=form.checkValue().data;
            for (var i in fields) {
                data.append(i, fields[i][0]);
            }
        }
        return data;
	}};
const pm_59605d1182b4a = {
	};
const pm_59605d1182b50 = {
	onload(){
        if (this.tabviews==undefined) {
            var elem=this.$('tab-vision')[0];
            this.tabviews=this.OIMLElement.renderTabs(elem, {
                starttab:'startpage',
                start(){
                    this.open(this.startTabName, 'default/startpage/');
                    return this;
                },
                onbeforeunload(tabName, oldTag, newTag) {
                    if (oldTag.trimed=='SPC' + tabName || oldTag.trimed=='GEC' + tabName) {
                        this.onbeforewrite(tabName, newTag);
                    } else {
						console.log(oldTag.trimed, tabName);
                        if (newTag.trimed=='GEC' + tabName || newTag.trimed=='SPC' + tabName) {
                            var content=__('WORDS')('Are you sure to go to the list page before save your editing?');
                        } else {
							console.log(newTag.trimed, tabName);
                            var content=__('WORDS')('Are you sure to open a new item before save the editing item?');
                        }
                        YangRAM.API.MSG.popup({
                            title:__('WORDS')('Abandon Editing?'),
                            content:content,
                            confirm:"Sure",
                            cancel:"Cancel",
                            done:()=> {
								//console.log(tabName, newTag);
                                this.onbeforewrite(tabName, newTag);
                            }
                        });
                    }
                    return this;
                },
                onbeforewrite(tabName, tag) {
                    YangRAM.get({
                        url:__thisapp__.__dirs.getter + 'open/' + tag.origin,
                        done:(txt)=> {
                            if (txt=='<ERROR>' || txt.match('PHP Notice:')) {
                                alert('Something Wrong');
                                if (this.currTabName !='STSTARTPAGE') {
                                    __thisapp__.open(this.currTabName);
                                }
                            } else {
                                this.write(tabName, tag, txt)
                                __thisapp__.$('section[data-tab-name=STARTPAGE] click[data-tab-name=' + tabName.toLowerCase() + ']').attr('href', tag.origin);
                                self.checkEditor(tag.path);
                                __thisapp__.resize().toTop();
                            }
                        },
                        fail:(txt)=> {
                            if (txt.match('PHP Notice:')) {
                                alert('Something Wrong');
                                if (this.currTabName !='STSTARTPAGE') {
                                    __thisapp__.open(this.currTabName);
                                }
                                //console.log(txt);
                            } else {
                                console.log(__thisapp__.__dirs.getter + 'open/' + tag.origin);
                                alert('Network Error');
                            }
                        }
                    });
                    return this;
                },
                onbeforecut(tag) {
                    __thisapp__.refreshTopVision(tag.path);
                    return this;
                },
                onaftercut(tag) {
                    __thisapp__.setSource(tag.origin).resize();
                    return this;
                }
            });
        }
		return this.open(__thisapp__.__temp.href);
	},
	name : __LANG__.APPNAME,
	API : {
        HIGHBAR_HANDLERS:{
		    OpenDefault() {
                this.open('default/startpage/', true);
            },
            OpenList() {
                if (__thisapp__.tabviews.currTabName==__thisapp__.tabviews.startTabName) {
                    // do nothing
                } else if (__thisapp__.tabviews.currTabName=='GENERAL') {
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
            var ctrllr=arguments[0];
            var preset=arguments[1];
            var itemid=arguments[2];
            if (ctrllr && preset && itemid) {
                this.open(ctrllr + '/' + preset + '/' + itemid + '/');
            }
        },
        EXPLORER_CALLBACKS:{
            SRC(files) {
                if (this.Input && YangRAM.API.hasChildNode(this.$('section.curr')[0], this.Input)) {
                    if (this.Input.tagName=='INPUT' || this.Input.tagName=='TEXTAREA') {
                        this.Input.value=this.Input.value + files[0].src;
                    } else {
                        var editor=YangRAM.API.form.getEditorById(YangRAM.attr(this.Input, 'data-editor-id'));
                        if (editor) {
                            var html='';
                            for (var i=0; i < files.length; i++) {
                                switch (files[i].type) {
                                    case 'img':html +='<img src="' + files[i].src + '" class="ib-editor-emoticon" alt="' + files[i].name + '"/>';
                                        break;
                                    case 'wav':html +='<audio src="' + files[i].src + '" class="ib-editor-emoticon" ><a href="' + files[i].src + '" target="_blank" >' + files[i].name + '</a></audio>';
                                        break;
                                    default:html +='Attachment:<a href="' + files[i].src + '" target="_blank" title="click to download" class="ib-editor-attachment">' + files[i].name + '</a><br />';
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
                var Relates=[];
                YangRAM.ForEach(contents, function(index, elem) {
                    var Item='{';
                    Item +="preset:" + this.itemPreset + ",";
                    Item +="id:" + this.id + ",";
                    Item +="title:" + this.title + "}";
                    Relates.push(Item);
                });
                var src=this.$('tab-options list item.curr').attr('src');
                var tabPath=(src.split('?')[0] + '/').split(/\/+/);
                if (tabPath[0] !='' && tabPath[0] !='default' && tabPath.length > 2) {
                    var CTT_Relates=this.$('section.curr').find('textarea[name=CTT_Relates]')[0];
                    if (CTT_Relates && CTT_Relates.parentNode.parentNode.style.display !='none') {
                        if (CTT_Relates.value=='') {
                            CTT_Relates.value=Relates.join(",\r\n");
                        } else {
                            CTT_Relates.value=CTT_Relates.value + ',' + Relates.join(",\r\n");
                        }
                        return;
                    }
                }
            }
        },
        BROWSER_TRIGGERS:{
            removeItem(preset, id, sort, page, status, cls) {
                if (parseInt(id)) {
                    YangRAM.API.MSG.popup({
                        title:__('WORDS')('Remove Item?'),
                        content:__('WORDS')('Are you sure to remove this item?'),
                        confirm:"Sure",
                        cancel:"Cancel",
                        done:()=> {
                            if (preset.toUpperCase()===__thisapp__.tabviews.currTabName) {
                                return self.submit(preset, id, 'del', sort, page, status, cls);
                            }
                            alert(__('WORDS')('Reference Error'));
                        }
                    });
                } else {
                    YangRAM.API.MSG.notice({
                        appid:__thisapp__.appid,
                        title:__('WORDS')('I\'m Sorry!'),
                        content:__('WORDS')('Operation not supported!'),
                    });
                }
            },
            ToList(preset, id, sort, page, status, cls) {
                if (preset.toUpperCase()===__thisapp__.tabviews.currTabName) {
                    //console.log(__('WORDS')('Are you sure to go to the list page before save your editing?'));
                    YangRAM.API.MSG.popup({
                        title:__('WORDS')('Abandon Editing?'),
                        content:__('WORDS')('Are you sure to go to the list page before save your editing?'),
                        confirm:"Sure",
                        cancel:"Cancel",
                        done:()=> {
                            if (preset=='general') {
                                if (status==undefined) {
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
                if (preset.toUpperCase()===__thisapp__.tabviews.currTabName) {
                    self.submit(preset, id, 'pub', sort, page, status, cls);
                }
            },
            SaveItem(preset, id, sort, page, status, cls) {
                if (preset.toUpperCase()===__thisapp__.tabviews.currTabName) {
                    self.submit(preset, id, 'sav', sort, page, status, cls);
                }
            },
            CopyContentAlias(alias) {
                YangRAM.API.TXT.copy(alias);
            }
        }
    },
	onafterresize(){
        var sideHeight=(this.$('section.curr left dl').outerHeight() || 0) + 20;
        var mainHeight=this.$('section.curr main').outerHeight();
        var contentHeightMin=mainHeight > sideHeight ? mainHeight:sideHeight;
        contentHeightMin=contentHeightMin < 350 ? 350:contentHeightMin;
        if (this.viewstatus==1) {
            contentHeightMin=YangRAM.API.APP.fsHeight() - 75 > contentHeightMin ? YangRAM.API.APP.fsHeight() - 75:contentHeightMin;
            this.$('section.curr').css('min-height', contentHeightMin);
        } else {
            this.$('section.curr').css('min-height', contentHeightMin);
        }
        return this;
    },
	open(href, must){
		if (typeof href=='string') {
            var tabPath=(href.split('?')[0] + '/').split(/\/+/);
            var tabName=tabPath[1];
            tabName=tabName==='' ? 'startpage':tabName;
            //console.log(tabName, href, must);
            this.tabviews && this.tabviews.open(tabName, href, must);
        }
        return this;
	},
	refreshTopVision(tabPath){
		YangRAM.$('.pub-ctrl-item', this.Topbar).addClass('unavailable');
        YangRAM.$('.pub-tool', this.Topbar).removeClass('unavailable');
        //console.log(tabPath);
        if (tabPath[0] !='' && tabPath[0] !='default') {
            if (tabPath.length > 3) {
                if (tabPath[0]=='gec') {
                    YangRAM.$('.content-view, .content-prop, .content-rele', this.Topbar).addClass('unavailable');
                } else {
                    YangRAM.$('.content-tool', this.Topbar).removeClass('unavailable');
                }
            } else if (tabPath.length > 2) {
                YangRAM.$('.preset-tool', this.Topbar).removeClass('unavailable');
            } else {
                YangRAM.$('.preset-tool', this.Topbar).removeClass('unavailable');
            }
        }
        return this;
	},
	onlaunch(href){
		return this.open(href);
	},
	main(){
		__thisapp__.listenEvents(self.events).regHeadBar(self.menuOnHigabar).loadStyle(function() {
            __thisapp__.loadURI('default/startpage/').regBackgroundLayer('rgba(255,255,255,0.9)', true);
        });
	}};
const privates = {
	};
YangRAM.extends(__thisapp__, true, pm_59605d1182b50);

});