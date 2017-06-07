System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        launchedApps = Runtime.storage.launchedApplications,
        ProcessBus = System.ProcessBus,
        _ = System.Pandora;

    var timeFormat = (s) => {
            if (s >= 86400) {
                return (s / 86400).toFixed(2) + Runtime.locales.HIGHBAR.TMR.UNIT.d;
            }
            if (s >= 3600) {
                return (s / 3600).toFixed(2) + Runtime.locales.HIGHBAR.TMR.UNIT.h;
            }
            if (s >= 60) {
                return (s / 60).toFixed(2) + Runtime.locales.HIGHBAR.TMR.UNIT.m;
            }
            return s.toFixed(2) + Runtime.locales.HIGHBAR.TMR.UNIT.s;
        },
        getAppStatus = (app) => {
            var status;
            //console.log(app);
            if (app.state) {
                if (app.activeStatus) {
                    var status = Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["Actived"] + app.Active;
                } else {
                    var status = Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["Running"];
                }
            } else {
                if (YangRAM.API.APP.checkAppid(app.appid)) {
                    var status = Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["UnKnow"];
                } else {
                    var status = Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["Sleeping"];
                }
            }
            if (app.appid === Runtime.currentRunningAppID) {
                status = Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["CURRENT"];
            }
            return status;
        },
        renderHTML = (i, app) => {
            status = getAppStatus(app);
            var html = '<th>' + (i + 1) + ')&nbsp;' + app.name + '(' + app.title + ')</th>';
            html += '<td>' + status + '</td>';
            html += '<td>' + timeFormat(app.runTime) + '</td>';
            if (parseInt(app.appid)) {
                html += '<td><click data-onclick="CutTo">' + Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["CutTo"] + '</click></td>';
                html += '<td><click data-onclick="Close">' + Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["Close"] + '</click></td>';
            } else {
                switch (app.appid) {
                    case 'YANGRAM':
                        html += '<td colspan="2"><click data-onclick="Logoff">' + Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["Logoff"] + '</click></td>';
                        break;
                    case 'PROCESSBUS':
                        html += '<td colspan="2"><click data-onclick="Close">' + Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["Sleeping"] + '</click></td>';
                        break;
                    case 'CUBES':
                        html += '<td><click data-onclick="display2D">' + Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["Show2D"] + '</click></td>';
                        html += '<td><click data-onclick="display3D">' + Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["Show3D"] + '</click></td>';
                        break;
                    case 'I4PLAZA':
                        html += '<td colspan="2"><click data-onclick="CutTo">' + Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["ReturnI4Plaza"] + '</click></td>';
                        break;
                    case 'SETTINGS':
                    case 'EXPLORER':
                    case 'TRASHCAN':
                        html += '<td><click data-onclick="CutTo">' + Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["CutTo"] + '</click></td>';
                        html += '<td><click data-onclick="Close">' + Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["Restart"] + '</click></td>';
                        break;
                    default:
                        html += '<td colspan="2">' + Runtime.locales.PROCESSBUS.STATUS_AND_OPERATE["NoOperation"] + '</td>';
                }
            }
            return html;
        };

    _.extend(ProcessBus, true, {
        name: Runtime.locales.PROCESSBUS.APPNAME,
        build() {
            if (this.document) {
                this.List = YangRAM.create('table', YangRAM.create('content', this.document));
                return this.listenEvents().resize();
            }
        },
        launch() {
            this.scan().List.innerHTML = '';
            YangRAM.API.APP.sleep('SYSTEM-MODULES').create('tr', this.List, {
                menu: '',
                html: '<th width="45%">' + Runtime.locales.PROCESSBUS.TABLE_HEAD["App name"] +
                    '</th><th width="18%">' + Runtime.locales.PROCESSBUS.TABLE_HEAD["App Status"] +
                    '</th><th width="15%">' + Runtime.locales.PROCESSBUS.TABLE_HEAD["Run Time"] +
                    '</th><th colspan="2" width="22%">' + Runtime.locales.PROCESSBUS.TABLE_HEAD["Operation"] +
                    '</th>'
            });
            for (var g in this.Processes) {
                if (this.Processes[g].length > 0) {
                    YangRAM.create('tr', this.List, {
                        group: '',
                        html: '<th>' + g + '</th><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td><td>&nbsp;</td>'
                    });
                    for (var i = 0; i < this.Processes[g].length; i++) {
                        var app = this.Processes[g][i],
                            html = renderHTML(i, app);
                        YangRAM.create('tr', this.List, {
                            appid: app.appid,
                            row: i % 2 ? 'odd' : 'even',
                            html: html
                        });
                    }
                }
            }
            return this.on();
        },
        scan() {
            var result = {
                DevApps: [],
                SysApps: [],
                SysComs: []
            };
            for (var i in launchedApps) {
                if (launchedApps[i]) {
                    var data = {
                        appid: launchedApps[i].appid,
                        name: launchedApps[i].name,
                        title: launchedApps[i].title,
                        state: ((launchedApps[i].appid == 'YANGRAM') || (launchedApps[i].appid == 'PROCESSBUS')) ? true : launchedApps[i].state,
                        activeStatus: Runtime.storage.activedApplications[i] ? Runtime.storage.activedApplications[i] : 0,
                        runTime: (new Date().getTime() - launchedApps[i].timeStamp) / 1000,
                    }
                    if (_.util.type(i, true) === 'StringInteger') {
                        if (parseInt(i) > 1000) {
                            result.DevApps.push(data);
                        } else {
                            result.SysApps.push(data);
                        }
                    } else {
                        result.SysComs.push(data);
                    }
                }
            }
            this.Processes = result;
            return this;
        },
        rescan() {
            if (this.state) {
                this.launch();
            }
        },
        sleep: ProcessBus.off,
        resize() {
            var that = this;
            YangRAM.setStyle(this.document, {
                height: System.Height - 40
            });
            return this;
        },
        listenEvents() {
            var Handler = {
                click(event) {
                    var row = this.parentNode.parentNode;
                    if (YangRAM.attr(row, 'state') != 'off') {
                        var appid = YangRAM.attr(row, 'appid');
                        var href = YangRAM.attr(this, 'data-onclick');
                        var handlers = {
                            CutTo() {
                                ProcessBus.sleep();
                                YangRAM.API.APP.launch(appid);
                            },
                            Close() {
                                YangRAM.attr(row, 'state', 'off').API.APP.close(appid, true);
                            },
                            display2D() {
                                System.MagicCube.show(7000, () => {
                                    YangRAM.API.MSG.notice({
                                        title: 'Complete Demo Displaying',
                                        content: 'Loading Animation Demo displays Completely!'
                                    });
                                }, '2d');
                            },
                            display3D() {
                                System.MagicCube.show(7000, () => {
                                    YangRAM.API.MSG.notice({
                                        title: 'Complete Demo Displaying',
                                        content: 'Loading Animation Demo displays Completely!'
                                    });
                                }, '3d');
                            },
                            Logoff() {
                                System.Logger.logoff();
                            }
                        }
                        handlers[href] && handlers[href]();
                    }
                },
            }
            YangRAM.bindListener('processbus click', 'click', Handler.click);
            return this;
        }
    });
});