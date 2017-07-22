System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        XMLHttpRequest = global.XMLHttpRequest,
        ActiveXObject = global.ActiveXObject,
        FormData = global.FormData,
        System = global.System,
        Runtime = System.Runtime,
        Uploader = System.Uploader,
        _ = System.Pandora;

    global.XMLHttpRequest = function() { this.send = function() { alert('Error') } };
    global.ActiveXObject = function() { this.send = function() { alert('Error') } };

    var FileTypes = {
        'image': ['image/jpeg', 'image/pjpeg', 'image/gif', 'image/png'],
        'audio': ['audio/midi', 'audio/x-midi', 'audio/x-pn-realaudio', 'audio/mp3', 'audio/mpeg', 'audio/ogg', 'audio/3gpp', 'audio/ac3', 'audio/wav'],
        'video': ['video/x-ms-wmv', 'video/mp4', 'video/mpeg', 'video/3gpp'],
        'text': ['text/html', 'text/css', 'text/xml', 'text/csv', 'text/plain'],
        'pdf': ['application/pdf'],
        'document': ['application/msword', 'application/vnd.ms-excel', 'application/vnd.ms-powerpoint', 'application/vnd.openxmlformats-officedocument.wordprocessingml.document', 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet', 'application/vnd.openxmlformats-officedocument.presentationml.presentation', 'application/pdf'],
        'compressed': ['application/x-zip-compressed', 'application/x-rar-compressed', 'application/zip'],
        'archive': ['application/postscript'],
    };

    var GetTypeArray = (type) => {
        if (type && FileTypes[type]) {
            return FileTypes[type];
        } else {
            array = _.util.arr.merge(
                FileTypes['image'],
                FileTypes['audio'],
                FileTypes['video'],
                FileTypes['text'],
                FileTypes['document'],
                FileTypes['compressed'],
                FileTypes['archive'], ['application/octet-stream']
            );
            return array;
        }
    }

    var SecondTransfer = (data, handlers, returnType) => {
        var response,
            url = YangRAM.SubmitDIR + 'files/sec/' + returnType,
            uploader = XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");

        uploader.onreadystatechange = function() {
            //console.log(this.readyState);
            if (this.readyState == 1) {
                response = {
                    lengthComputable: false,
                    loaded: 0,
                    total: 0,
                    readyState: this.readyState,
                    status: this.status,
                    responseText: 'Waiting'
                }
                _.util.bool.isFn(handlers.before) && handlers.before(response);
            } else if (this.readyState == 2 || this.readyState == 3) {
                response = {
                    readyState: this.readyState,
                    status: this.status,
                    responseText: 'Processing'
                }
                _.util.bool.isFn(handlers.after) && handlers.after(response);
            } else if (this.readyState == 4) {
                //console.log(url, data, handlers, this);
                if (this.status == 200) {
                    response = {
                        readyState: this.readyState,
                        status: this.status,
                        responseText: this.responseText
                    }
                    _.util.bool.isFn(handlers.done) && handlers.done(response);
                } else {
                    response = {
                        readyState: this.readyState,
                        status: this.status,
                        responseText: this.responseText
                    }
                    _.util.bool.isFn(handlers.fail) && handlers.fail(response);
                }
            }
        }
        uploader.open('POST', url, true);
        var form = new FormData();
        for (var i in data) {
            form.append(i, data[i]);
        }
        uploader.send(form.getNativeObject());
    }

    var Selector = {
            MultiFilesInput: System.HiddenArea.create('file', {
                id: 'System-Files-Selector',
                multiple: 'multiple'
            }),
            SingleFileInput: System.HiddenArea.create('file', {
                id: 'System-File-Selector',
            }),
            FilterMode: true,
            FileMime: null,
            FileMaxSize: null,
            DoneCallback: null,
            FailCallback: null,
            listenEvents() {
                YangRAM
                    .bindListener('#System-File-Selector', 'change', FileInputChange)
                    .bindListener('#System-Files-Selector', 'change', FileInputChange);
            }
        },
        FileInputChange = function(event) {
            var uploader = new _.data.Uploader(this.files, Selector.FileMime, Selector.SufFixs, Selector.FileMaxSize);
            uploader.isOnlyFilter = Selector.FilterMode;
            //console.log(uploader);
            uploader.checkType(Selector.doneCallback, Selector.failCallback);
            Selector.FilterMode = true;
            Selector.FileMime = null;
            Selector.FileMaxSize = null;
            Selector.doneCallback = null;
            Selector.failCallback = null;
        };

    Selector.listenEvents();

    _.extend(Uploader, true, {
        name: Runtime.locales.UPLOADER.APPNAME,
        pick(settings) {
            settings = settings || {};
            if (_.util.bool.isBool(settings.filter)) {
                Selector.FilterMode = settings.filter;
            } else {
                Selector.FilterMode = true;
            }
            if (_.util.bool.isArr(settings.mimes)) {
                Selector.FileMime = settings.mimes;
            } else if (_.util.bool.isStr(settings.mimes)) {
                Selector.FileMime = [settings.mimes];
            } else {
                Selector.FileMime = GetTypeArray(settings.type);
            }
            if (_.util.bool.isArr(settings.sfixs)) {
                Selector.SufFixs = settings.sfixs;
            } else if (_.util.bool.isStr(settings.sfixs)) {
                Selector.SufFixs = [settings.sfixs];
            } else if (settings.sfixs) {
                Selector.SufFixs = ['torrent', 'rar', 'sql', 'psd', 'cdr'];
            } else {
                Selector.SufFixs = [];
            }
            if (_.util.bool.isNum(settings.maxsize)) {
                Selector.FileMaxSize = settings.maxsize < Uploader.MaxSize ? settings.maxsize : Uploader.MaxSize;
            } else {
                Selector.FileMaxSize = Uploader.MaxSize;
            }
            Selector.doneCallback = _.util.bool.isFn(settings.done) ? settings.done : YangRAM.donothing;
            Selector.failCallback = _.util.bool.isFn(settings.fail) ? settings.fail : YangRAM.donothing;
            Selector.status = true;
            if (settings.multiple) {
                Selector.MultiFilesInput.value = '';
                Selector.MultiFilesInput.click()
            } else {
                Selector.SingleFileInput.value = '';
                Selector.SingleFileInput.click();
            }
        },
        transfer(file, appid, settings) {
            if (file) {
                _.data.MD5.file(file, (hash) => {
                    YangRAM.set({
                        url: YangRAM.SubmitDIR + 'files/has/',
                        data: {
                            hash: hash,
                            type: file.type
                        },
                        done(data) {
                            var result = JSON.parse(data);
                            var returnType = settings.returnType ? '?returntype=' + settings.returnType : '';
                            if (result.code == 'Y') {
                                var data = settings.data || {};
                                data.appid = appid;
                                data.srcid = result.srcid;
                                data.srctype = result.srctype;
                                data.filename = file.name;
                                data.hash = hash;
                                SecondTransfer(data, settings.handlers, returnType);
                            } else {
                                _settings = settings || {};
                                _settings.data = settings.data || {};
                                _settings.data.appid = appid;
                                _settings.data.hash = hash;
                                _settings.url = YangRAM.SubmitDIR + 'files/' + returnType;
                                _settings.filefield = 'myfile';
                                var type;

                                if (type = file.type.match(/^(audio|video)\//)) {
                                    var media = document.createElement(type[1]);
                                    media.src = global.URL.createObjectURL(file);
                                    media.onloadedmetadata = () => {
                                        _settings.data.duration = media.duration;
                                        _.data.Uploader.transfer(file, _settings);
                                    }
                                } else {
                                    _.data.Uploader.transfer(file, _settings);
                                }
                            }
                        },
                        fail(data) {
                            console.log(data);
                        }
                    });
                });
            }
        }
    });
});