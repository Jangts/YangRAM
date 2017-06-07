System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        installedApps = Runtime.storage.installedApplications,
        Launcher = System.Workspace.Launcher,
        ARL = Launcher.ARL,
        Memowall = Launcher.Memowall,
        Bookmark = Launcher.Bookmark,
        _ = System.Pandora;

    var events = {
        'Launcher bookmark': {
            'click' (event) {
                var appid = YangRAM.attr(this, 'appid');
                var href = YangRAM.attr(this, 'href');
                YangRAM.API.APP.launch(appid, href);
            }
        },
        'rankinglist application': {
            'click' (event) {
                var appid = YangRAM.attr(this, 'appid');
                if (Runtime.currentRunningAppID == appid) {
                    YangRAM.API.APP.sleep(appid);
                } else {
                    var href = YangRAM.attr(this, 'href') || 'default';
                    YangRAM.API.APP.launch(appid, href);
                }
            },
            'rclick' (event) {}
        }
    };

    _.extend(Launcher, true, {
        name: Runtime.locales.LAUNCHER.APPNAME,
        viewstatus: 'dockmode',
        build(callback) {
            ARL.build(callback);
            Memowall.build();
            return this.listenEvents(events).off();
        },
        launch() {
            YangRAM.attr(System.HiBar.Start, 'started', 'true');
            return Launcher.setViewStatus('dockmode').on();
        },
        sleep() {
            YangRAM.attr(System.HiBar.Start, 'started', 'false');
            Memowall.sleep();
            return this.off().resize();
        },
        setViewStatus(viewstatus) {
            this.viewstatus = viewstatus;
            this.attr('viewstatus', viewstatus);
            if (viewstatus == 'dockmode') {
                ARL.showHot();
                ARL.scrollBAR.toTop().disabled = true;
            } else {
                ARL.showAll();
                setTimeout(() => {
                    ARL.scrollBAR.disabled = false;
                    ARL.scrollBAR.resize();
                }, 510);
            }
            return this.resize();
        },
        resize() {
            if (typeof installedApps == 'object') {
                Launcher.TopGap = 40;
                Launcher.Height = System.Height - Launcher.TopGap;
                var CountAllow = parseInt(Launcher.Height / 50);
                var CountAll = _.util.obj.length(installedApps) > 8 ? _.util.obj.length(installedApps) : 8;
                var Count = Math.min(CountAllow - 5, CountAll);
                var HeightExcept = Launcher.Height - Count * 50 - 204;
                switch (this.viewstatus) {
                    case 'dockmode':
                        var leftGap = 200;
                        Launcher.Width = 50;
                        ARL.Width = 50;
                        ARL.Height = Count * 50 + 204;
                        ARL.TopGap = HeightExcept / 2;
                        ARL.middleAppsHeight = Count * 50;
                        break;
                    case 'wallmode':
                        var leftGap = 200;
                        Launcher.Width = System.Width;
                        ARL.Width = 200;
                        ARL.Height = Launcher.Height;
                        ARL.TopGap = 0;
                        ARL.middleAppsHeight = Launcher.Height - 214;
                        break;
                }
                YangRAM.setStyle(this.document, {
                    width: Launcher.Width,
                    height: Launcher.Height,
                    top: Launcher.TopGap,
                });

                YangRAM.setStyle(Memowall.document, {
                    width: System.Width - leftGap,
                    height: Launcher.Height,
                    paddingLeft: leftGap
                });
                var MemowallWidthMax = System.Width - leftGap,
                    PreRowBookmarkCountMax = parseInt((MemowallWidthMax - 20) / 240) * 2,
                    MemowallWidth = PreRowBookmarkCountMax * 120;
                YangRAM.$('Launcher memowall content').css({
                    width: MemowallWidth,
                });

                YangRAM.setStyle(ARL.document, {
                    width: ARL.Width,
                    height: ARL.Height,
                    top: ARL.TopGap
                });
                YangRAM.$('Launcher rankinglist noramlapps application').removeAttr('visible').each((i, el) => {
                    if (i < Count) {
                        YangRAM.attr(el, 'visible', 'visible');
                    }
                });


                YangRAM.setStyle(ARL.middleApps, {
                    height: ARL.middleAppsHeight,
                });
                Memowall.scrollBAR && Memowall.scrollBAR.resize();
                System.Workspace.resize();
            }
            return this;
        }
    });
});