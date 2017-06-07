RegApplication('I4PLAZA', (__thisapp__, System, YangRAM, Using, Global, undefined) => {
'use strict';
const __LANG__ = System.Runtime.locales.I4PLAZA;
const __ = (word) => {
	return YangRAM.API.TXT.dictReader(__LANG__, word);
};
const __APPDIR__ = '/YangRAM/OperatorInterface/I4Plaza/';
const Runtime = System.Runtime;
const self = {
	widgets : {},
	widgetsLeft : [{
            type:'full-column-chart',
            alias:'app4-yesterday-hours',
            title:'',
            app_id:'4',
            api_method:'widgets/hours',
            api_token:''
        }, {
            type:'full-column-stripe',
            alias:'app9-yesterday-new-contents',
            title:'New Contents',
            app_id:'contents',
            api_method:'widgets/new-contents',
            api_token:''
        },
        {
            type:'one-third-column-chart',
            alias:'app8-app-updates',
            title:'New Contents',
            app_id:'1',
            api_method:'widgets/updates',
            api_token:''
        },
        {
            type:'full-column-chart',
            alias:'app10-aweek-pageviews',
            title:'Pageview Aweek',
            app_id:'3',
            api_method:'widgets/aweek-pageviews',
            api_token:''
        }
    ],
	widgetsRight : [{
        alias:'weather',
        api:''
    }, {
        alias:'club',
        api:''
    }, {
        alias:'following',
        api:''
    }, {
        alias:'post',
        api:''
    }, ],
	loading : '<vision class="data-loading-spinner"><vision class="dls-container1"><el class="dls-circle1"></el><el class="dls-circle2"></el><el class="dls-circle3"></el><el class="dls-circle4"></el></vision><vision class="dls-container2"><el class="dls-circle1"></el><el class="dls-circle2"></el><el class="dls-circle3"></el><el class="dls-circle4"></el></vision><vision class="dls-container3"><el class="dls-circle1"></el><el class="dls-circle2"></el><el class="dls-circle3"></el><el class="dls-circle4"></el></vision></vision>',
	events : {
        'wsbtns > vision.widget-switch-button':{
            'click' () {
                __thisapp__.$('wsbtns > vision.widget-switch-button').removeAttr('current');;
                var index=YangRAM.attr(this, 'current', 'current').attr(this, 'data-switch-to');
                YangRAM.attr(__thisapp__.widgets, 'data-posi', index);
                YangRAM.attr(__thisapp__.NonWork, 'data-posi', ++index);
                YangRAM.attr(__thisapp__.Circle, 'data-posi', ++index);
            }
        }
    },
	HiBar : {
        appname:__LANG__.APPNAME,
        menus:{
            'Common':[{
                title:__('MENUS')("YangRAM Settings"),
                state:'on',
                handler:'SysSetting'
            }, {
                title:__('MENUS')("App Store"),
                state:'on',
                handler:'AppStore'
            }, {
                title:__('MENUS')("Process Bus"),
                state:'on',
                handler:'ProcessBus'
            }, {
                title:__('MENUS')("Use Manual"),
                state:'on',
                handler:'Help'
            }, {
                title:__('MENUS')("YangRAM Update"),
                state:'on',
                handler:'SysUpdate'
            }],
            'Help':[{
                title:__('MENUS')("YangRAM Official Website"),
                state:'on',
                handler:'GotoYangRAM'
            }, {
                title:__('MENUS')("Forum"),
                state:'on',
                handler:'GotoForum'
            }, {
                title:__('MENUS')("Developer Center"),
                state:'on',
                handler:'GotoDevCnt'
            }, {
                title:__('MENUS')("About YangRAM"),
                state:'on',
                handler:'GotoAbout'
            }],
        }
    },
	widgetRenderers : {
        charts(alias, className, list, height) {
            var widget=self.widgets[alias].widget;
            require([
                'echarts',
                'echarts/../theme/dark',
                'echarts/chart/pie',
                'echarts/chart/bar',
                'echarts/chart/line',
                'echarts/component/title',
                'echarts/component/legend',
                'echarts/component/grid',
                'echarts/component/tooltip'
            ], function(echarts, theme) {
                widget.innerHTML='';
                YangRAM.API.util.arr.each(list, function(i, data) {
                    var el=YangRAM.create('vision', widget, {
                        className:className,
                        height:height
                    });
                    var myChart=echarts.init(el, 'dark');
                    //console.log(myChart);
                    myChart.setOption(data);
                });
                YangRAM.create('click', widget, {
                    className:'widget-link',
                    href:'launch://' + self.widgets[alias].appid+'::launch',
                    html:'查看详情'
                });
            });
        },
        stripe(alias, className, list, height) {
            var widget=self.widgets[alias].widget,
                title=self.widgets[alias].title,
                nums=[' odd', ' even'];
            widget.innerHTML='<vision class="widget-title">' + title + '<vision>';
            YangRAM.API.util.arr.each(list, function(i, data) {
                var el=YangRAM.create('vision', widget, {
                    className:className + nums[i % 2],
                    height:height,
                    html:'<el class="for-type">[' + data.MARK + ']</el>' + data.TITLE + '<el class="for-time">[' + data.TIME + ']</el>'
                });
            });
            YangRAM.create('click', widget, {
                className:'widget-link',
                href:'launch://' + self.widgets[alias].appid+'::launch',
                html:'See Details'
            });
        }
}};
const pm_5912b53940996 = {
	};
const pm_5912b5394099a = {
	name : __LANG__.APPNAME,
	off : YangRAM.donothing,
	render : YangRAM.donothing,
	open : YangRAM.donothing,
	require : YangRAM.donothing,
	refresh : YangRAM.donothing,
	mark : YangRAM.donothing,
	release : YangRAM.donothing,
	loadStyle : YangRAM.donothing,
	resize : YangRAM.donothing,
	API : {
        HIGHBAR_HANDLERS:{
            ProcessBus() {
                System.ProcessBus.Status ? System.ProcessBus.sleep():System.ProcessBus.launch();
            },
            GotoYangRAM() {
                this.browseWebPage('http://chb.yangram.ni/');
            },
            GotoForum() {
                this.browseWebPage('http://chb.yangram.ni/cn/');
            },
            GotoDevCnt() {
                this.browseWebPage('http://cn.chb.yangram.ni');
            },
        }
    },
	launch(){
        if(System.Runtime.currentRunningAppID!==this.appid){
            System.Workspace.resortApplications(true).Browser.check();
            System.Runtime.resortApps(this.appid, true);
        }
        return this;
    },
	rebuild(){
        this.document.innerHTML='';
        this.WallPaper=YangRAM.create('wallpaper', this.document.parentNode, {
            display:'none'
        });
        this.widgets=YangRAM.create('widgets', this.document, {
            'data-posi':0
        });

        this.NonWork=YangRAM.create('widgets', this.document, {
            'data-posi':1,
            'html':'<section class="top-title"><wgt>放松一下</wgt><sbtn class="player">Player</sbtn><sbtn class="reader">Reader</sbtn><sbtn class="stock">Stock</sbtn><sbtn class="weather">Weather</sbtn></section>'
        });
        this.Player=YangRAM.create('section', this.NonWork, {
            class:'board commom-player'
        });
        this.Reader=YangRAM.create('section', this.NonWork, {
            class:'board commom-reader'
        });
        this.Chart=YangRAM.create('section', this.NonWork, {
            class:'board stock-chart'
        });
        this.Board=YangRAM.create('section', this.NonWork, {
            class:'board weather-board'
        });

        this.Circle=YangRAM.create('widgets', this.document, {
            'data-posi':2,
            'html':'<section class="top-title"><wgt class="banner">我的友站</wgt></section>'
        });
        this.Sites=YangRAM.create('section', this.Circle, {
            class:'site-infos'
        });

        var buttons=YangRAM.create('wsbtns', this.document.parentNode);
        YangRAM.create('vision', buttons, {
            className:'widget-switch-button',
            current:'current',
            dataSwitchTo:'0'
        });
        YangRAM.create('vision', buttons, {
            className:'widget-switch-button',
            dataSwitchTo:'-1'
        });
        YangRAM.create('vision', buttons, {
            className:'widget-switch-button',
            dataSwitchTo:'-2'
        });
        return this;
    },
	loadWallPaper(){
        var that=this;
        setTimeout(function() {
            YangRAM.$(that.WallPaper).hide().fadeIn(2000);
        }, 500);
        return this;
    },
	main(appid, href){
        __thisapp__.regHeadBar(self.HiBar);
        this.rebuild()
            .listenEvents(self.events)
            .loadWallPaper()
            .loadWidgets();
        System.LoadedRateChange();
    },
	loadWidgets(){
        this.LEFT=YangRAM.create('section', __thisapp__.widgets, {
            className:'left-widgets',
        });
        this.RIGHT=YangRAM.create('section', __thisapp__.widgets, {
            className:'right-widgets',
        });
        YangRAM.get({
            url:__thisapp__.__dirs.getter + 'module/widgets',
            done(txt) {
                if (txt=='<ERROR>' || txt.match('PHP Notice:') || txt.match('{"status":"error"')) {
                    console.log(txt);
                    //
                } else {
                    var customs=JSON.parse(txt);
                    self.widgetsLeft=YangRAM.API.util.arr.merge(self.widgetsLeft, customs);
                    YangRAM.API.util.arr.each(self.widgetsLeft, function(i, widget) {
                        self.widgets[widget.alias]={
                            title:widget.title,
                            appid:widget.app_id,
                            widget:YangRAM.create('widget', __thisapp__.LEFT, {
                                html:'<atip>' + self.loading + '</atip>'
                            })
                        }
                        var url=YangRAM.URI + widget.app_id + '/' + widget.api_method + (widget.api_token ? ('?token=' + widget.api_token):'');
                        YangRAM.get({
                            url:url,
                            done(txt) {
                                if (txt=='' || txt=='<ERROR>' || txt.match('PHP Notice:') || txt.match('{"status":"error"')) {
                                    self.widgets[widget.alias].innerHTML='<atip>加载失败！<atip>';
                                } else {
                                    try {
                                            var data=JSON.parse(txt);
                                            if (YangRAM.API.isFn(self.widgetRenderers[data.type])) {
                                                self.widgetRenderers[data.type](widget.alias, widget.type, data.data, data.height);
                                            }
                                        } catch (e) {
                                            console.log(url);
                                        }
                                }
                            },
                            fail(txt) {
                                console.log(url);
                                self.widgets[widget.alias].innerHTML='<atip>加载失败！<atip>';
                            }
                        });
                    });
                }
            },
            fail(txt) {
                console.log(txt);
            },
        });
        return this;
    }};
const privates = {
	};
YangRAM.extends(__thisapp__, true, pm_5912b5394099a);

});