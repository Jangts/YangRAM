System.ModuleSeeds.main = (Application, pandora, declare, global, undefined) => {
    var _ = pandora,
        cache = _.locker,
        document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        launchedApps = Runtime.storage.launchedApplications;


    /* --------------------------------------------------------------------------------
    /* Base elemects of YangRAM (Uniform Opreate User Interface) */

    var Defaultattrs = {
            'css': {
                tag: 'link',
                attrs: {
                    type: 'text/css',
                    rel: 'stylesheet'
                }
            },
            'js': {
                tag: 'script',
                attrs: {
                    type: 'application/javascript',
                    async: 'async'
                }
            },
            'file': {
                tag: 'input',
                attrs: {
                    type: 'file',
                    value: '',
                    hidden: 'hidden'
                }
            }
        },
        HiddenArea = {
            document: document.getElementsByTagName('hiddens').item(0),
            $(selector) {
                return _.dom.select(selector, this.document);
            },
            create(tag, attrs) {
                if (Defaultattrs[tag]) {
                    var defaults = Defaultattrs[tag];
                    tag = defaults.tag;
                    _.extend(attrs, true, defaults.attrs);
                }
                return _.dom.create(tag, this.document, attrs);
            }
        },
        YangRAM = System.YangRAM = global.YangRAM = new Application('yangram'),
        BGMPlayer = new _.medias.Player(),
        MusicPlayer = new _.medias.Player();

    _.extend(YangRAM, true, {
        document: document.getElementsByTagName('yangram').item(0),
        API: _,
        tools: {
            //document: document.getElementsByTagName('widgets[name=yangram-tools]').item(0),
            playBgMusic(code) {
                BGMPlayer.play(code);
            },
            loadMusic(srcs) {
                MusicPlayer.register(srcs);
            },
            playMusic(code) {
                MusicPlayer.play(code);
            }
        },
        donothing: System.Empty,
        extends: _.extend,
        each: _.forEach,
        $: _.dom.select,
        create: _.dom.create,
        loadStyle: _.data.loadCSS,
        loadScript: _.data.loadScript,
        encodeQueryString: _.data.encodeQueryString,
        decodeQueryString: _.data.decodeQueryString,
        //---------------------------------------------------------|
        toggleClass(elem, classname, isSwitch) {
            _.dom.toggleClass(elem, classname, isSwitch);
            return this;
        },
        attr(elem, attr, val) {
            if (val === undefined) {
                return _.dom.getAttr(elem, attr);
            }
            if (val === false) {
                _.dom.removeAttr(elem, attr);
            } else {
                _.dom.setAttr(elem, attr, val);
            }
            return this;
        },
        setStyle(elem, styles) {
            _.dom.setStyle(elem, styles);
            return this;
        }
    });

    YangRAM.initialize = (path, url, get, set, main) => {
        _.extend(YangRAM, true, {
            URI: (url + '/').replace(/\/+/g, '/').replace(/:\/+/, '://'),
            PhysicalURI: (path + '/').replace(/\/+/g, '/').replace(/:\/+/, '://'),
            RequestDIR: (get + '/').replace(/\/+/g, '/').replace(/:\/+/, '://'),
            SubmitDIR: (set + '/').replace(/\/+/g, '/').replace(/:\/+/, '://')
        });

        _.extend(System, true, {
            Path: YangRAM.URI + 'yangram/',
            Logger: new Application('logger'),
            Locker: new Application('locker'),
            HiBar: new Application('hibar'),
            Workspace: new Application('workspace'),
            Notifier: new Application('msgcenter'),
            Smartian: new Application('smartian'),
            Kalendar: new Application('kalendar'),
            ProcessBus: new Application('processbus'),
            Explorer: new Application('explorer'),
            TimePicker: new Application('timepicker'),
            MagicCube: new Application('cubes'),
            Dialog: new Application('dialogs'),
            HiddenArea: HiddenArea,
            Loader(loadingitems) {
                loadingitems.call(System, YangRAM, global, undefined);
            }
        });

        _.extend(System.HiBar, true, {
            Start: document.getElementsByTagName('start').item(0),
            Subbars: document.getElementsByTagName('subbars').item(0),
            Clock: document.getElementsByTagName('timer').item(0),
            Searcher: document.getElementsByTagName('searcher').item(0),
            WSMSwitcher: document.getElementsByTagName('wsmswitcher').item(0),
            Messager: document.getElementsByTagName('msger').item(0),
            Account: document.getElementsByTagName('account').item(0),
            TitleAndMenu: new Application('subbars'),
            Contexts: new Application('menus'),
            Timer: new _.Time()._x({
                appid: 'TIMER',
                Status: true,
                name: 'Timer',
                title: 'Timer',
                Author: YangRAM.author,
                Version: YangRAM.version,
                timeStamp: new Date().getTime(),
                LocalWeek() {
                    var weeks = [Runtime.locales.HIGHBAR.TMR.Weeks.SUNDAY, Runtime.locales.HIGHBAR.TMR.Weeks.MONDAY, Runtime.locales.HIGHBAR.TMR.Weeks.TUESDAY, Runtime.locales.HIGHBAR.TMR.Weeks.WEDNESDAY, Runtime.locales.HIGHBAR.TMR.Weeks.THURSDAY, Runtime.locales.HIGHBAR.TMR.Weeks.FRIDAY, Runtime.locales.HIGHBAR.TMR.Weeks.SATURDAY];
                    return weeks[this.week()];
                }
            })
        });

        var AlerterElement = YangRAM.create('alert', System.Notifier.document, { state: 'off' });
        _.extend(System.Notifier, true, {
            Alerter: new _.see.widgets.Alerter(AlerterElement)._x({
                vision: YangRAM.create('msgbox', AlerterElement)
            }),
        });

        _.extend(System.Notifier.Alerter, true, {
            title: YangRAM.create('msgtit', System.Notifier.Alerter.vision),
            content: YangRAM.create('msgcon', System.Notifier.Alerter.vision),
            buttons: YangRAM.create('msgbtn', System.Notifier.Alerter.vision),
            on() {
                YangRAM.attr(this.Element, 'state', 'on');
                BGMPlayer.play('Alert');
                return this;
            },
            off() {
                YangRAM.attr(this.Element, 'state', 'off');
                return this;
            },
        });

        _.extend(System.Workspace, true, {
            Launcher: new Application('launcher'),
            Windows: document.getElementsByTagName('windows').item(0),
            Browser: new Application('browser')
        });

        _.extend(System.Workspace.Launcher, true, {
            ARL: new Application('rankinglist'),
            Memowall: new Application('memowall'),
            Modifier: new Application('momodifier')
        });

        _.each(System.ModuleSeeds.methods, (i, extender) => extender(YangRAM, declare, global, undefined));

        _.extend(YangRAM.API, true, System.ModuleSeeds.apis.YangRAM(YangRAM, declare, global, undefined), _.util.bool);

        _.extend(YangRAM.tools, true, {
            pickFiles: System.Uploader.pick,
            pickTime: System.TimePicker.launch,
            ExplorerSRC: System.Explorer.launch,
            showMagicCube: System.MagicCube.show,
            hideMagicCube: System.MagicCube.hide,
            showDialog() {
                switch (arguments.length) {
                    case 0:
                        System.Dialog.build().show('');
                        break;
                    case 1:
                        switch (typeof arguments[0]) {
                            case 'object':
                                System.Dialog.build(arguments[0]).show();
                                break;
                            default:
                                System.Dialog.build().show(arguments[0]);
                                break;
                        }
                        break;
                    default:
                        System.Dialog.build(arguments[0]).show(arguments[1]);
                }
            },
            hideDialog: System.Dialog.hide
        });

        launchedApps['HiddenArea'] = HiddenArea;
        launchedApps['UPLOADER'] = System.Uploader;
        launchedApps['TIMER'] = System.HiBar.Timer;

        YangRAM.oninitialize(BGMPlayer, main);
        document.YangRAMObject = YangRAM;
        return YangRAM;
    };
    return YangRAM;
};