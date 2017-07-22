"use strict";

var System = {
    ModuleSeeds: {
        models: {},
        apis: {},
        main: null,
        methods: []
    },
    DeclareModel(classname, builder) {
        this.ModuleSeeds.models[classname] = builder;
    },
    ExtendsMethods(extender, name) {
        if (name) {
            this.ModuleSeeds.apis[name] = extender;
        } else {
            this.ModuleSeeds.methods.push(extender);
        }

    },
    Runtime: {
        locales: { CODE: 'en-us' },
        operationscripts: {},
        currentRunningAppID: ''
    }
}

const RegApplication = (() => {
    var operationscripts = System.Runtime.operationscripts;

    return (appid, callback) => {
        operationscripts[appid] = callback;
    }
})();

block((pandora, global, undefined) => {
    var _ = pandora,
        declare = pandora.declareClass,
        document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime;

    /* Public Variables of System */

    var winSize = _.dom.getSize(global),
        Width = winSize['width'] > 1050 ? winSize['width'] : 1050,
        Height = winSize['height'] > 544 ? winSize['height'] : 544,
        UploadMaxSize = 1050 * 1050 * 200;

    /* extends Variables Methods For YangRAM */
    _.extend(System, true, {
        State: false,
        Width: Width,
        Height: Height,
        UploadMaxSize: UploadMaxSize,
        Pandora: _,
        Empty() {
            return this;
        },
        TrimHTML(html) {
            var rega = /<(html|\?xml|meta)[^>]*>/gi;
            var regb = /<\/html>/i;
            var regc = /<(head|style|script)[^>]*>[\s\S]*<\/\1>/gi;
            html = html.replace(rega, '').replace(regb, '').replace(regc, '');
            return html;
        },
        Resize() {
            var winSize = _.dom.getSize(window);
            this.Width = winSize['width'] > 1050 ? winSize['width'] : 1050;
            this.Height = winSize['height'] > 544 ? winSize['height'] : 544;
            System.YangRAM.setStyle(System.YangRAM.document, {
                width: this.Width,
                height: this.Height
            });
            // console.log(this.HiBar);
            this.HiBar.resize();
            this.Workspace.Launcher.resize();
            this.Notifier.resize();
            this.ProcessBus.resize();
            this.Smartian.resize();
            return this;
        },
        Load() {
            _.data.loadScript(System.YangRAM.PhysicalURI + 'Sources/loadingitems.js');
            return this;
        },
        LoadedRateChange() {
            System.LoadingItemsCount
            if (System.LoadedRate < 100) {
                var float,
                    timer = (inincrement) => {
                        System.LoadedRate += inincrement;
                        //console.log(System.Loaded, System.LoadingStatus, inincrement, System.LoadedRate);
                        System.ListenLoaded();
                    }
                if (System.Loaded === System.LoadingItemsCount) {
                    float = 100 - System.LoadedRate;
                    for (var i = 0; i < float; i++) {
                        setTimeout(() => timer(1), i * 48);
                    }
                } else {
                    var prestep = 100 / System.LoadingItemsCount,
                        goal = parseInt(prestep * (System.Loaded + 1 - Math.random() * Math.PI / 2));
                    if (System.LoadedRate < goal) {
                        timer(goal - System.LoadedRate);
                    } else {
                        timer(1);
                    }
                    System.Loaded++;
                }
            }
            return this;
        },
        Loaded: 0,
        LoadingStatus: 'Start Loading...',
        UpdateLoadingStatus() {
            System.LoadingStatus = Runtime.locales.SYSTEM.LOADSTATUS[System.Loaded];
            _.util.bool.isFn(System.OnLoadingStatusChange) && System.OnLoadingStatusChange();
            return this;
        },
        LoadedRate: 0,
        Completed: false,
        ListenLoaded() {
            if (this.LoadedRate === 0) {
                this.OnLoadStart && this.OnLoadStart();
            } else {
                if (this.LoadedRate === 100) {
                    this.Completed = true;
                    this.UpdateLoadingStatus();
                    this.State = true;
                    this.OnLoadCompletely && this.OnLoadCompletely();
                    var time = new Date().getTime() - Runtime.timeStamp;
                    setTimeout(() => {
                            System.Notifier.notice({
                                title: Runtime.locales.UOI.WELCOME_T,
                                content: Runtime.locales.UOI.WELCOME_C(time)
                            });
                        },
                        5000);
                }
                _.util.bool.isFn(this.OnLoaded) && this.OnLoaded();
            }
            return this;
        },
        OnLoadStart: null,
        OnLoadingStatusChange: null,
        OnLoaded: null,
        OnLoadCompletely: null,
        OnClose() {
            //return true;
            var result = Runtime.checkActivities();
            if (result) {
                new System.Notifier.Message({
                    title: 'Applications Still In Editing',
                    content: result,
                    confirm: "Still Log Out",
                    cancel: "Cancel",
                    done() {
                        return true;
                    }
                }).alert();
            } else {
                return true;
            }
        }
    });
});