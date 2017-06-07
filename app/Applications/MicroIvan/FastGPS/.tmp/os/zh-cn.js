RegApplication(1234, (__thisapp__, YangRAM, Using, Global, undefined) => {
'use strict';
const __LANG__ = {
    "CODE": "zh-cn",
    "APPNAME": "我的页面",
    "MENUS": {
        "Default Page": "首页",
        "List Of Preset": "预设列表页",
        "Preview": "预览",
        "Dataroom Official Website": "Dataroom官网",
        "Publisher Forum": "Publisher论坛",
        "About YangRAM Dataroom": "关于YangRAM Dataroom"
    },
    "TABS": {
        "default": "首页",
        "singlepage": "GPS独立页面",
        "index": "GEC分组索引页",
        "generalpage": "GEC分组详情页",
        "commonlist": "SPC通用列表页",
        "ataglist": "SPC标签列表页",
        "acatlist": "SPC分类列表页",
        "commondetail": "SPC预设详情页",
        "acatdetail": "SPC分类详情页"
    },
    "WORDS": {
        "Confirm To Close?": "确定关闭？",
        "You are trying to close a tab, are you sure to do this?": "你试图关闭一个选项卡，确定要这么做吗？",
        "Close": "关闭",
        "Cut To": "转到",
        "Cancel": "取消",
        "Exceed Maximum Size Allowed Upload": "超过最大允许上传大小",
        "Unsupported File Format": "不被支持的文件格式",
        "Remove Page?": "移除页面？",
        "Are you sure to remove this page?": "确定要移除该页面？",
        "Disuse Page?": "停用页面",
        "Are you sure to disuse this page?": "确定要停用该页面？",
        "Sure": "确定",
        "I'm Sorry!": "抱歉！",
        "Operation not supported!": "不支持此操作！",
        "A Page Has Been Saved!": "保存了一个页面！",
        "A Page Has Been Deleted!": "删除了一个页面！",
        "A Page Has Been Used!": "启用了一个页面！",
        "A Page Has Been Disused!": "停用了一个页面！",
        "Disuse Using Success": "停用成功",
        "Start Using Success": "启用成功",
        "Save Success": "保存成功",
        "Delete Success": "删除成功",
        "No Insert Area": "丢失插入点",
        "This App Not Support To Insert Preset Contents": "这个应用不支持插入预设内容"
    }
};
const __ = (word) => {
	return YangRAM.API.TXT.dictReader(__LANG__, word);
};
const __APPDIR__ = '/Applications/MicroIvan/FastGPS/';
const $ = YangRAM.$;
const SHOW_TEMPLATES = function(themesList, themeInput, templateInput){
        YangRAM.tools.showDialog({
                title:'Choose a template',
                appid:__thisapp__.appid,
                css:'dialog',
                height:400
            }, function() {
                var dialogMain=this.render(themesList).contentarea;
                var dialogMain=this.render(themesList).contentarea;
        dialogMain.bindListener('.theme-list item.theme', 'mouseup', function() {
                var theme=YangRAM.attr(this, 'data-theme-alias');
                YangRAM.get({
                    url:__thisapp__.__dirs.getter + 'dialog/theme/' + theme,
                    done:function(txt) {
                        YangRAM.tools.hideMagicCube();
                        if (txt=='<ERROR>' || txt.match('PHP Notice:')) {
                            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                        } else {
                            dialogMain.innerHTML=txt;
                            setTimeout(function() {
                                dialogMain.scrollBAR.resize();
                            }, 0);
                        }
                    },
                    fail(txt) {
                        console.log(txt);
                        YangRAM.tools.hideMagicCube();
                        alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
                    }
                }).tools.showMagicCube();
            })
            .bindListener('.template-list item.themes', 'mouseup', function() {
                YangRAM.get({
                    url:__thisapp__.__dirs.getter + 'dialog/themes/',
                    done:function(txt) {
                        YangRAM.tools.hideMagicCube();
                        if (txt=='<ERROR>' || txt.match('PHP Notice:')) {
                            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                        } else {
                            dialogMain.innerHTML=txt;
                            setTimeout(function() {
                                dialogMain.scrollBAR.resize();
                            }, 0);
                        }
                    },
                    fail(txt) {
                        console.log(txt);
                        YangRAM.tools.hideMagicCube();
                        alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
                    }
                }).tools.showMagicCube();
            })
            .bindListener('.template-list item.template', 'dblclick', function() {
                themeInput.value=YangRAM.attr(this, 'data-theme-alias');
                templateInput.value=YangRAM.attr(this, 'data-template-path').replace(/^\/+/, '');
                YangRAM.tools.hideDialog();
            });
    });
};
const CHK_FORMDATA = function(data){
    var elem=__thisapp__.$('section[data-tab-name].curr popup-form form-vision')[0];
    var form=new YangRAM.API.form.Data(elem);
    var fields=form.checkValue().data;
    for (var i in fields) {
        data.append(i, fields[i][0]);
    }
    return data;
};
const self = {
	menuOnHigabar : {
        appname:__LANG__.APPNAME,
        menus:{
            'Open':[{
                    title:__('MENUS')("Default Page"),
                    state:'on',
                    handler:'OpenDefault'
                },
                {
                    title:__('MENUS')("List Of Preset"),
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
                    title:'Delete',
                    state:'on',
                    handler:'DeleteConten'
                },
                {
                    title:__('MENUS')("Preview"),
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
                    title:__('MENUS')("Dataroom Official Website"),
                    state:'on',
                    handler:'OfficialWebsite'
                },
                {
                    title:__('MENUS')("Publisher Forum"),
                    state:'on',
                    handler:'GotoForum'
                },
                {
                    title:__('MENUS')("About YangRAM Dataroom"),
                    state:'on',
                    handler:'GotoAbout'
                }
            ]
        }
	},
	events : {
            'main item[href]':{
                'click':function() {
                    var href=YangRAM.attr(this, 'href');
                    __thisapp__.open(href);
                }
            },
            'tools.page-ctrl item':{
                'click':function() {
                    if (!YangRAM.API.DOM.hasClass(this, 'unavailable')) {
                        var type=YangRAM.attr(this, 'x-usefor');
                        typeof self.pageControl[type]=='function' && self.pageControl[type]();
                    };
                }
            },
            'section.curr .form-input .page-upload-button':{
                'click':function() {
                    var input=YangRAM.$('input[name=cover]', this.parentNode)[0];
                    YangRAM.tools.pickFiles({
                        multiple:false,
                        filter:false,
                        type:'image',
                        sfixs:false,
                        maxsize:1024 * 1024 * 20,
                        done:function(files) {
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
                                    eval(data.responseText)
                                    input.value=json.url;
                                },
                                fail(data) {
                                    input.value='';
                                    alert('Upload Failed');
                                },
                                returnType:'json'
                            });
                        },
                        fail(error, err_type) {
                            if (err_type) {
                                alert('Exceed Maximum Size Allowed Upload');
                            } else {
                                alert('Unsupported File Format');
                            }
                        }
                    });
                }
            },
            'section.curr inputs .pick-button':{
                'click':function() {
                    var theme=YangRAM.$('input[name=theme]', this.parentNode)[0];
                    var template=YangRAM.$('input[name=template]', this.parentNode)[0];
                    YangRAM.get({
                        url:__thisapp__.__dirs.getter + 'dialog/themes/',
                        done:function(txt) {
                            YangRAM.tools.hideMagicCube();
                            if (txt=='<ERROR>' || txt.match('PHP Notice:')) {
                                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                            } else {
                                SHOW_TEMPLATES(txt, theme, template);
                            }
                        },
                        fail(txt) {
                            console.log(txt);
                            YangRAM.tools.hideMagicCube();
                            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
                        }
                    }).tools.showMagicCube();
                }
            }
    },
	pageControl : {
        'page-list' () {
            __thisapp__.$('section[data-tab-name].curr popup-form').remove();
            __thisapp__.refreshTopVision();
        },
        'page-new' () {
            self.submit(0, 'edi');
        },
        'page-save' () {
            var id=__thisapp__.$('section.curr .content-edit-form').attr('data-content-id');
            self.submit(preset, id, 'sav');
        },
        'page-del' () {
            var id=__thisapp__.$('section.curr .content-edit-form').attr('data-content-id');
            if (parseInt(id)) {
                YangRAM.API.MSG.popup({
                    title:__('WORDS')("Remove Page?"),
                    content:__('WORDS')("Are you sure to remove this page?"),
                    confirm:__('WORDS')("Sure"),
                    cancel:__('WORDS')("Cancel"),
                    done() {
                        return self.submit(id, 'del');
                    }
                });
            } else {
                YangRAM.API.MSG.notice({
                    appid:__thisapp__.appid,
                    title:__('WORDS')("Im Sorry!"),
                    content:__('WORDS')("Operation not supported!")
                });
            }
        },
        'page-fix' () {
            //YangRAM.tools.ExplorerSRC();
        },
        'page-setting' () {
            YangRAM.API.APP.launch(3, 'application/10/');
        }
    },
	submit(id, type){
        var url, title, content;
        var data=new FormData();
        data.append('id', id);
        switch (type) {
            case 'edi':url=__thisapp__.__dirs.getter + 'open/form/';
                title=__('WORDS')("Save Success");
                content=__('WORDS')("A Page Has Been Saved!");
                break;
            case 'del':url=__thisapp__.__dirs.setter + 'submit/del/';
                title=__('WORDS')("Delete Success");
                content=__('WORDS')("A Page Has Been Deleted!");
                break;
            case 'dis':url=__thisapp__.__dirs.setter + 'submit/dis/';
                title=__('WORDS')("Disuse Using Success");
                content=__('WORDS')("A Page Has Been Disused!");
                break;
            case 'use':url=__thisapp__.__dirs.setter + 'submit/use/';
                title=__('WORDS')("Start Using Success");
                content=__('WORDS')("A Page Has Been Used!");
                break;
            case 'sav':url=__thisapp__.__dirs.setter + 'submit/sav/';
                title=__('WORDS')("Save Success");
                content=__('WORDS')("A Page Has Been Saved!");
                data=CHK_FORMDATA(data);
                break;
        }
        YangRAM.set({
            url:url,
            data:data,
            done (txt) {
                if (parseInt(txt)) {
                    YangRAM.API.MSG.notice({
                        appId:__thisapp__.appid,
                        title:title,
                        content:content
                    });
                    var qs=__thisapp__.$('section.curr main').attr('x-query-string');
                    switch (type) {
                        case 'del':__thisapp__.open(__thisapp__.tabviews.currTabName+'/?'+qs, true);
                            YangRAM.API.CHK.recycle();
                            break;
                        case 'dis':__thisapp__.open(__thisapp__.tabviews.currTabName+'/?'+qs, true);
                            break;
                        case 'use':__thisapp__.open(__thisapp__.tabviews.currTabName+'/?'+qs, true);
                            break;
                        case 'sav':__thisapp__.open(__thisapp__.tabviews.currTabName+'/?'+qs, true);
                            break;
                    }
                } else if (txt=='<CAN_NOT_FIND>') {
                    alert('Specifies No Existing Item');
                } else if (txt=='<ERROR>') {
                    console.log(txt);
                    alert('Unkonw Error');
                } else if (type=='edi') {
                    if(id==0){
                        __thisapp__.tabviews.cutTo('DEFAULT');
                    }
                    __thisapp__.$('section[data-tab-name].curr popup-form').remove();
                    __thisapp__.$('section[data-tab-name].curr').append(txt);
                    __thisapp__.refreshTopVision();
                }
            },
            fail(txt) {
                console.log(url, txt);
                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
            }
        });
    }};
const pm_591959a3c2c32 = {
	};
const pm_591959a3c2c37 = {
	name : __LANG__.APPNAME,
	API : {
        HIGHBAR_HANDLERS:{
		    OpenDefault() {
                this.open('default');
            },
            OpenList() {
                if (this.tabviews.currTabName !='default') {
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
        EXPLORER_CALLBACKS:{
            SRC(files) {
                return true;
            },
            PCL(contents) {
                alert(__('WORDS')("This App Not Support To Insert Preset Contents"));
            }
        },
        BROWSER_TRIGGERS:{
            CopyMark(id) {
                YangRAM.API.TXT.copy(id);
            },
            EditItem(id) {
                if (parseInt(id)) {
                    return self.submit(id, 'edi');
                }
                YangRAM.API.MSG.Notice({
                    appid:__thisapp__.appid,
                    title:__('WORDS')("I\'m Sorry!"),
                    content:__('WORDS')("Operation not supported!"),
                });
            },
            SaveItem(id) {
                if (YangRAM.API.isNum(parseInt(id))) {
                    return self.submit(id, 'sav');
                }
                YangRAM.API.MSG.Notice({
                    appid:__thisapp__.appid,
                    title:__('WORDS')("I\'m Sorry!"),
                    content:__('WORDS')("Operation not supported!"),
                });
            },
            RemoveItem(id) {
                if (parseInt(id)) {
                    YangRAM.API.MSG.popup({
                        title:__('WORDS')("Remove Page?"),
                        content:__('WORDS')("Are you sure to remove this page?"),
                        confirm:__('WORDS')("Sure"),
                        cancel:__('WORDS')("Cancel"),
                        done() {
                            return self.submit(id, 'del');
                        }
                    });
                } else {
                    YangRAM.API.MSG.notice({
                        appid:__thisapp__.appid,
                        title:__('WORDS')("I\'m Sorry!"),
                        content:__('WORDS')("Operation not supported!"),
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
                        title:__('WORDS')("Disuse Page?"),
                        content:__('WORDS')("Are you sure to disuse this page?"),
                        confirm:__('WORDS')("Sure"),
                        cancel:__('WORDS')("Cancel"),
                        done() {
                            return self.submit(id, 'dis');
                        }
                    });
                } else {
                    YangRAM.API.MSG.notice({
                        appid:__thisapp__.appid,
                        title:__('WORDS')("I\'m Sorry!"),
                        content:__('WORDS')("Operation not supported!"),
                    });
                }
            },
            StartUse(id) {
                self.submit(id, 'use');
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
	onload(){
        if (this.tabviews==undefined) {
            var elem=this.$('tab-vision')[0];
            this.tabviews=this.OIMLElement.renderTabs(elem, {
                starttab:'DEFAULT',
                start(){
                    this.open(this.startTabName, 'default/startpage/');
                    return this;
                },
                onbeforeunload(tabName, oldTag, newTag) {
                    return this.onbeforewrite(tabName, newTag);
                },
                onbeforewrite(tabName, tag) {
                    //console.log(tabName, tag);
                    YangRAM.get({
                        url:__thisapp__.__dirs.getter + 'open/' + tag.origin,
                        done:(txt)=> {
                            if (txt=='<ERROR>' || txt.match('PHP Notice:')) {
                                console.log(txt);
                                alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Something Wrong!"));
                            } else {
                                this.write(tabName, tag, txt)
                                __thisapp__.$('section[data-tab-name=STARTPAGE] click[data-tab-name=' + tabName.toLowerCase() + ']').attr('href', tag.origin);
                                //self.checkEditor(tag.path);
                                __thisapp__.resize().toTop();
                            }
                        },
                        fail:(txt)=> {
                            console.log(txt);
                            alert(YangRAM.API.TXT.local('COMMON')('WORDS')("Network Error!"));
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
                },
            });
        }
        return this.open(__thisapp__.__temp.href);
    },
	open(href, must){
        //console.log(href, must);
        if (typeof href=='string') {
            var tabPath=(href.split('?')[0] + '/').split(/\/+/);
            var tabName=tabPath[0];
            tabName=(tabName==='') ? 'DEFAULT':tabName;
            //console.log(tabPath);
            this.tabviews && this.tabviews.open(tabName, href, must, __('TABS')(tabName));
        }
        return this;
    },
	refreshTopVision(){
        YangRAM.$('.page-ctrl-item', this.Topbar).addClass('unavailable');
        YangRAM.$('.sys-tool', this.Topbar).removeClass('unavailable');
        setTimeout(()=> {
        
        if (__thisapp__.$('section[data-tab-name].curr popup-form').length > 0) {
            YangRAM.$('.page-edit', this.Topbar).removeClass('unavailable');
            YangRAM.$('.page-list', this.Topbar).removeClass('unavailable');
        } else if (__thisapp__.tabviews.currTabName===__thisapp__.tabviews.startTabName) {
            YangRAM.$('.page-new', this.Topbar).removeClass('unavailable');
        }
    }, 0);
        return this.resize();
    },
	onlaunch(href, folder){
        return this.open(href);
    },
	main(){
		__thisapp__.listenEvents(self.events).regHeadBar(self.menuOnHigabar).loadStyle(function() {
            __thisapp__.loadURI('default/startpage/');
        });
	}};
const privates = {
	};
YangRAM.extends(__thisapp__, true, pm_591959a3c2c37);

});