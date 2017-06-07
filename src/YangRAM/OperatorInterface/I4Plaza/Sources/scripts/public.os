public {
    name: __LANG__.APPNAME,
    off: YangRAM.donothing,
    render: YangRAM.donothing,
    open: YangRAM.donothing,
    require: YangRAM.donothing,
    refresh: YangRAM.donothing,
    mark: YangRAM.donothing,
    release: YangRAM.donothing,
    loadStyle: YangRAM.donothing,
    resize: YangRAM.donothing,
    API:{
        HIGHBAR_HANDLERS: {
            ProcessBus() {
                System.ProcessBus.Status ? System.ProcessBus.sleep() : System.ProcessBus.launch();
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
    launch() {
        if(System.Runtime.currentRunningAppID!==this.appid){
            System.Workspace.resortApplications(true).Browser.check();
            System.Runtime.resortApps(this.appid, true);
        }
        return this;
    },
    rebuild() {
        this.document.innerHTML = '';
        this.WallPaper = YangRAM.create('wallpaper', this.document.parentNode, {
            display: 'none'
        });
        this.widgets = YangRAM.create('widgets', this.document, {
            'data-posi': 0
        });

        this.NonWork = YangRAM.create('widgets', this.document, {
            'data-posi': 1,
            'html': '<section class="top-title"><wgt>放松一下</wgt><sbtn class="player">Player</sbtn><sbtn class="reader">Reader</sbtn><sbtn class="stock">Stock</sbtn><sbtn class="weather">Weather</sbtn></section>'
        });
        this.Player = YangRAM.create('section', this.NonWork, {
            class: 'board commom-player'
        });
        this.Reader = YangRAM.create('section', this.NonWork, {
            class: 'board commom-reader'
        });
        this.Chart = YangRAM.create('section', this.NonWork, {
            class: 'board stock-chart'
        });
        this.Board = YangRAM.create('section', this.NonWork, {
            class: 'board weather-board'
        });

        this.Circle = YangRAM.create('widgets', this.document, {
            'data-posi': 2,
            'html': '<section class="top-title"><wgt class="banner">我的友站</wgt></section>'
        });
        this.Sites = YangRAM.create('section', this.Circle, {
            class: 'site-infos'
        });

        var buttons = YangRAM.create('wsbtns', this.document.parentNode);
        YangRAM.create('vision', buttons, {
            className: 'widget-switch-button',
            current: 'current',
            dataSwitchTo: '0'
        });
        YangRAM.create('vision', buttons, {
            className: 'widget-switch-button',
            dataSwitchTo: '-1'
        });
        YangRAM.create('vision', buttons, {
            className: 'widget-switch-button',
            dataSwitchTo: '-2'
        });
        return this;
    },
    loadWallPaper() {
        var that = this;
        setTimeout(function() {
            YangRAM.$(that.WallPaper).hide().fadeIn(2000);
        }, 500);
        return this;
    },
    main(appid, href) {
        __thisapp__.regHeadBar(self.HiBar);
        this.rebuild()
            .listenEvents(self.events)
            .loadWallPaper()
            .loadWidgets();
        System.LoadedRateChange();
    }
};