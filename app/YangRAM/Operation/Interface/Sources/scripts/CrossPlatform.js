System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Logger = System.Logger,
        _ = System.Pandora;

    global.FormData = _.form.Data;

    /* Public Variables of System */
    var ID = 'UOI',
        Name = 'Uniform Operator Interface',
        Theme = 'default',
        Author = 'TANGRAM I4s DEVELOP CENTER',
        Version = '2.0.0.0';

    /* Request Methods of System */
    var Response = declare({
            _init(response) {
                this.statusCode = response.statusCode;
                this.statusText = response.statusMessage;
                this.responseHeaders = {};
                for (var i = 0; i < response.rawHeaders.length; i++) {
                    this.responseHeaders[response.rawHeaders[i]] = response.rawHeaders[++i];
                }
            },
            getAllResponseHeaders() {
                var result = this.responseHeaders ? '' : null;
                for (var key in this.responseHeaders) {
                    result += key + ' : ' + this.responseHeaders[key] + ' \n';
                }
                return result;
            },
            getResponseHeader(key) {
                return this.responseHeaders ? this.responseHeaders[key] : null;
            },
        }),
        RequestFailed = function(code, data, callback) {
            //console.log(code);
            switch (code) {
                case '700.5':
                    if (System.State) {
                        System.Locker.launch();
                    } else {
                        location.reload();
                    }
                    break;
                case '700.6':
                case '700.7':
                    location.reload();
                    break;
                case '704.0':
                case '705.0':
                case '708.0':
                case '709.0':
                    alert(this.getResponseHeader('NI-Response-Text') + ' [' + code + ']');
                    break;
                case '708.4':
                    alert(Runtime.locales.COMMON.OF_NOT_FOUND);
                    break;
                default:
                    callback.call(this, data);
            }
        },
        Uploader = {
            appid: 'UPLOADER',
            Status: true,
            name: 'Uploader',
            title: 'Uploader',
            Author: System.author,
            Version: System.version,
            timeStamp: new Date().getTime(),
            MaxSize: System.UploadMaxSize,
            extends: _.data.Component.prototype._x
        };

    /* extends Variables Methods For YangRAM */
    _.extend(System, true, {
        ID: ID,
        Name: Name,
        Theme: Theme,
        Author: Author,
        Version: Version,
        Uploader: Uploader,
        JSON: _.data.json,
        GET(options) {
            options = options || {};
            var url = options.url || System.YangRAM.URI;
            var fail = _.util.bool.isFn(options.fail) ? options.fail : function(data) {
                global.console.log(url, data, this);
            };
            var done = function(data) {
                //global.console.log(this);
                var code;
                if (code = this.getResponseHeader('NI-Response-Code')) {
                    if ((code >= 200 && code < 300) || code == 304) {
                        _.util.bool.isFn(options.done) && options.done.call(this, System.TrimHTML(data));
                    } else {
                        RequestFailed.call(this, code, data, fail);
                    }
                } else {
                    _.util.bool.isFn(options.done) && options.done.call(this, System.TrimHTML(data));
                }
            };

            if (_.util.bool.isStr(options.data)) {
                if (url.indexof('?')) {
                    url = url + "&" + options.data;
                } else {
                    url = url + "?" + options.data;
                }
            } else if (_.util.bool.isObj(options.data)) {
                if (options.data instanceof FormData) {
                    if (url.indexof('?')) {
                        url = url + "&" + options.data.encodeQueryString();
                    } else {
                        url = url + "?" + options.data.encodeQueryString();
                    }
                } else {
                    if (url.indexof('?')) {
                        url = url + "&" + encodeQueryString(options.data);
                    } else {
                        url = url + "?" + encodeQueryString(options.data);
                    }
                }
            }
            new _.data.XHR({
                url: url,
                method: 'get'
            }).done(done).fail(fail).always(options.always).send();
            return this;
        },
        POST(options) {
            options = options || {};
            var url = options.url || System.YangRAM.URI;
            var fail = _.util.bool.isFn(options.fail) ? options.fail : function(data) {
                global.console.log(url, data, this);
            };
            var done = function(data) {
                var code;
                if (code = this.getResponseHeader('NI-Response-Code')) {
                    if ((code >= 200 && code < 300) || code == 304) {
                        _.util.bool.isFn(options.done) && options.done.call(this, System.TrimHTML(data));
                    } else {
                        RequestFailed.call(this, code, data, fail);
                    }
                } else {
                    _.util.bool.isFn(options.done) && options.done.call(this, System.TrimHTML(data));
                }
            };

            var data;
            if (_.util.bool.isStr(options.data)) {
                data = options.data;
            } else if (_.util.bool.isObj(options.data)) {
                if (options.data instanceof FormData) {
                    if (options.data.useMultipartFormData) {
                        new _.data.XHR({
                            url: url,
                            method: 'POST'
                        }).done(done).fail(fail).always(options.always).send(options.data.getNativeObject());
                        return this;
                    } else {
                        data = options.data.encodeQueryString();
                    }
                } else {
                    data = _.data.encodeQueryString(options.data);
                }
            } else {
                data = '';
            }
            new _.data.XHR({
                url: url,
                method: 'POST'
            }).done(done).fail(fail).always(options.always).setRequestHeader('Content-type', 'application/x-www-form-urlencoded').send(data);
            return this;
        },
        OnLogon(message) {
            location.reload();
        },
        OnLogoff() {
            location.reload();
        }
    });

    _.extend(Runtime, true, {
        runInDesktop: false,
        checkAppOS(mainURL, href) {
            //console.log('foo');
            var that = this;
            System.GET({
                url: mainURL + '?check=web',
                done(data) {
                    Runtime.AppLoadOS.call(that, mainURL, href);
                },
                fail(data) {
                    if (Runtime.currentRunningAppID) {
                        Runtime.AppLoadError.call(that, 'AF_NOT_FOUND');
                    } else {
                        global.console.log(that);
                    }
                }
            });
        }
    });

    _.extend(YangRAM, true, {
        get: System.GET,
        set: System.POST,
        json: System.JSON,
        oninitialize(BGMPlayer, main) {
            System.JSON(YangRAM.PhysicalURI + 'Sources/bgmlist.json', (data) => {
                var srcs = {};
                _.each(data, (i, music) => {
                    srcs[i] = {
                        'audio/ogg': YangRAM.PhysicalURI + music.ogg,
                        'audio/mpeg': YangRAM.PhysicalURI + music.mp3
                    };
                });
                BGMPlayer.register(srcs);
                main();
            }, () => {
                location.reload();
            });
        }
    });

    _.extend(Logger, true, {
        activeForm() {
            YangRAM.tools.playBgMusic('Open');
            this.avatar.attr('status', 'actived').css('background-image', 'url(' + System.UserAvatar + ')');
            this.form.attr('status', 'actived');
            this.username.attr('placeholder', Runtime.locales.LOGGER.USERNAME);
            this.password.attr('placeholder', Runtime.locales.LOGGER.PASSWORD);
            this.pincode.attr('placeholder', ' P I N ');
            YangRAM.removeListener('logger avatar', 'click');
            return this;
        },
        onlogon(message) {
            Logger.pincode.val(' 3 2 1 ');
            setTimeout(() => {
                Logger.pincode.val(' * 2 1 ');
                Logger.avatar.css('background-image', 'url(' + message.avatar + ')');
            }, 1000);
            setTimeout(() => {
                Logger.pincode.val(' * * 1 ');
                Logger.avatar.attr('status', 'checked');
                Logger.form.attr('status', 'checked');
            }, 2000);
            setTimeout(() => {
                System.OnLogon(message);
            }, 3000);
        }
    });
});