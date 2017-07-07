System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Smartian = System.Smartian,
        _ = System.Pandora;

    var Instructions = {};

    var handlers = {
        'launch' (h, appid) {
            this.launch(appid);
        }
    }

    var events = {
        submit() {
            Smartian.analysis();
        },
        'smartian item[appid][args]': {
            'click' (event) {
                var appid = YangRAM.attr(this, 'appid');
                if (YangRAM.API.APP.checkAppid(appid) == Runtime.currentRunningAppID) {
                    var app = Runtime.application();
                    var args = YangRAM.attr(this, 'args').split(/,\s*/);
                    app.API && _.util.bool.isFn(app.API.SMARTIAN_HELPER) && app.API.SMARTIAN_HELPER.apply(app, args);
                }
                if (appid == 'YANGRAM') {
                    var app = Smartian;
                    var args = YangRAM.attr(this, 'args').split(/,\s*/);
                    app.handlers && _.util.bool.isFn(app.handlers.YangRAM) && app.handlers.YangRAM.apply(YangRAM, args);
                }
            }
        }
    };

    _.extend(Smartian, true, {
        name: Runtime.locales.SMARTIAN.APPNAME,
        viewstatus: 'nrmlmode',
        Keyword: '',
        placeholder: '',
        setViewStatus(type) {
            this.attr('viewstatus', type).viewstatus = type;
            this.resize();
            return this;
        },
        handlers: {
            YangRAM(Handler) {
                _.util.bool.isFn(handlers[Handler]) && handlers[Handler].apply(YangRAM, arguments);
            }
        },
        build(callback) {
            var resulter = YangRAM.create('resulter', this.document, {
                html: '<content></content><scrollbar type="vert"><rail></rail><scrolldragger></scrolldragger></scrollbar>'
            });
            var form = YangRAM.create('form', this.document, {
                name: 'smartian-inputer',
                onsubmit: 'return false;'
            });
            var txt = YangRAM.create('input', form, {
                type: 'text',
                name: 'you-say',
                value: this.Keyword,
                placeholder: this.placeholder
            });
            var btn = YangRAM.create('input', form, {
                type: 'button',
                name: 'call-me',
            });
            var ani = YangRAM.create('v', form, {
                type: 'connecting-animation',
                innerHTML: '<el class="data-connecting-spinner"><el class="dcs-rect1"></el><el class="dcs-rect2"></el><el class="dcs-rect3"></el><el class="dcs-rect4"></el><el class="dcs-rect5"></el></el>',
            });
            this.input = txt;
            this.button = btn;
            this.listenEvents();
            this.scrollBAR = System.Workspace.OIMLElement.renderScrollBAR(resulter);
            this.results = YangRAM.$('content', this.document)[0];
            this.onconnect = () => {
                _.dom.addClass(ani, 'on');
            };
            this.onafterconnect = () => {
                _.dom.removeClass(ani, 'on');
            };
            return this.initialize(callback);
        },
        onafterresize() {
            if (this.viewstatus == 'nrmlmode') {
                _.dom.setStyle(this.document, 'height', System.Height - 120);
            }
            if (this.viewstatus == 'widemode') {
                _.dom.setStyle(this.document, 'height', System.Height - 40);
            }
            setTimeout(() => {
                this.scrollBAR && this.scrollBAR.resize().toBottom();
            }, 510);
        },
        listenEvents() {
            YangRAM.bindListener(this.button, 'click', events.submit)
                .bindListener(this.input, 'enter', events.submit)
                .bindListener('smartian item[appid][args]', 'click', events['smartian item[appid][args]']['click']);
            return this;
        },
        analysis() {
            this.Keyword = _.util.str.trim(this.input.value);
            if (this.Keyword != '') {
                var receipt = this.match();
                if (receipt !== null && receipt[1]) {
                    YangRAM.$('[type=welcome]').attr('type', 'welcomed');
                    YangRAM.create('ask', this.results, {
                        innerHTML: this.Keyword
                    });
                    switch (receipt[0]) {
                        case 'Calc':
                        case 'Time':
                        case 'Dail':
                            YangRAM.create('answer', this.results, {
                                innerHTML: receipt[1]
                            });
                            break;
                        case 'Self':
                        case 'Play':
                        case 'Open':
                            YangRAM.create('tips', this.results, {
                                innerHTML: receipt[1]
                            });
                            break;
                    }
                    this.clearInput().resize();
                }
            }
        },
        match() {
            for (var exec in Instructions) {
                for (var type in Instructions[exec].RegExps) {
                    var result = this.Keyword.match(Instructions[exec].RegExps[type]);
                    if (result) {
                        //console.log(exec, type, result);
                        if (_.util.bool.isFn(Instructions[exec].Handlers[type])) {
                            return [exec, Instructions[exec].Handlers[type].call(this, result)];
                        } else {
                            console.log(exec, type, result);
                        }
                    }
                }
            }
            this.search();
            return null;
        },
        clear() {
            this.results.innerHTML = '';
            this.input.value = '';
            this.resize();
            return this;
        },
        clearInput() {
            YangRAM.attr(this.input, 'placeholder', this.Keyword);
            this.input.value = '';
            this.Keyword = '';
            return this;
        },
        initialize(callback) {
            YangRAM.get({
                url: this.__dirs.main + 'launch/init/?lang=' + Runtime.locales.CODE,
                done: (txt) => {
                    eval(txt);
                    this.resize();
                    _.util.bool.isFn(callback) && callback();
                }
            });
            return this;
        },
        launch() {
            YangRAM.attr(System.HiBar.Searcher, 'type', 'start').get({
                url: this.__dirs.main + 'launch/welcome/?lang=' + Runtime.locales.CODE,
                done: (txt) => {
                    this.results.innerHTML = txt;
                    YangRAM.API.APP.sleep('SYSTEM-MODULES').attr(System.HiBar.Searcher, 'type', 'searcher');
                    this.on().setViewStatus('nrmlmode').resize();
                }
            });
            return this;
        },
        sleep() {
            YangRAM.attr(System.HiBar.Searcher, 'type', 'start');
            this.off().setViewStatus('nrmlmode').clearInput();
            for (var i in Instructions.Dail.TimesCount) {
                Instructions.Dail.TimesCount[i] = 0;
            }
            return this;
        },
        search() {
            YangRAM.get({
                url: this.__dirs.getter + 'assistant/search/' + Runtime.currentRunningAppID + '/?kw=' + encodeURIComponent(this.Keyword) + '&lang=' + Runtime.locales.CODE,
                done: (txt) => {
                    YangRAM.$('[type=welcome]').attr('type', 'welcomed');
                    this.results.innerHTML += System.TrimHTML(txt);
                    this.clearInput().resize();
                    this.onafterconnect();
                },
                fail: (txt) => {
                    console.log(txt);
                    this.onafterconnect();
                }
            });
            this.onconnect();
            return this;
        },
        query() {
            YangRAM.get({
                url: this.__dirs.setter + 'query/?q=' + encodeURIComponent(this.Sentence) + '&lang=' + Runtime.locales.CODE,
                done: (txt) => {
                    YangRAM.$('[type=welcome]').attr('type', 'welcomed');
                    this.results.innerHTML += System.TrimHTML(txt);
                    this.clearInput().resize();
                    this.onafterconnect();
                },
                fail: (txt) => {
                    console.log(txt);
                    this.onafterconnect();
                }
            });
            this.onconnect();
            return this;
        },
        exec() {
            var data = new FormData();
            data.append('params', this.Parameters);
            YangRAM.set({
                url: this.__dirs.setter + 'settings/?cmd=' + encodeURIComponent(this.Command) + '&lang=' + Runtime.locales.CODE,
                data: data,
                done: (txt) => {
                    //console.log(txt);
                    YangRAM.$('[type=welcome]').attr('type', 'welcomed');
                    this.results.innerHTML += System.TrimHTML(txt);
                    this.clearInput().resize();
                    this.onafterconnect();
                },
                fail: (txt) => {
                    console.log(txt);
                    this.onafterconnect();
                }
            });
            this.onconnect();
            return this;
        }
    });
});