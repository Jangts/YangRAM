System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        TitleAndMenu = System.HiBar.TitleAndMenu,
        _ = System.Pandora;

    var create = (appid) => {
            return YangRAM.create('application', TitleAndMenu.document, {
                appid: appid,
                state: 'off'
            })
        },
        render = (data) => {
            var html = '<appname>' + data.appname + '</appname>';
            for (var i in data.menus) {
                html += '<menu name="' + getMenuGroupname(i) + '" state="on"><items>';
                for (var n = 0; n < data.menus[i].length; n++) {
                    var state = data.menus[i][n].state;
                    if ((state && state === 'off') || state === false || state === 0) {
                        state = 'off';
                    } else {
                        state = 'on';
                    }
                    html += '<item href="' + data.menus[i][n].handler +
                        '" state="' + data.menus[i][n].state + '">' +
                        getMenuGroupname(data.menus[i][n].title) + '</item>';
                }
                html += '</items></menu>';
            }
            return html;
        },
        getMenuGroupname = (name, appid) => {
            if (Runtime.locales.COMMON.TOPMENUS[name]) {
                return Runtime.locales.COMMON.TOPMENUS[name];
            }
            return name;
        };

    _.extend(TitleAndMenu, true, {
        name: Runtime.locales.HIGHBAR.TAM.APPNAME,
        bars: {},
        launch: TitleAndMenu.on,
        sleep: TitleAndMenu.off,
        setViewStatus(viewstatus) {
            return this.attr('viewstatus', (viewstatus === 'browsing') ? 'browsing' : 'normal');
        },
        push(appid, data) {
            if (!_.util.bool.isObj(data)) {
                return false;
            }
            if (!data instanceof Array) {
                return false;
            }
            this.bars[appid] = this.bars[appid] || create(appid);
            this.bars[appid].innerHTML = render(data);
            return this;
        },
        unload(appid) {
            if (this.bars[appid]) {
                this.document.removeChild(this.bars[appid]);
                delete this.bars[appid];
            }
            return this;
        },
        onafterresize() {
            //
        }
    });
});