System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        Contexts = System.HiBar.Contexts,
        _ = System.Pandora;

    var getMenuname = (elem) => {
        var node = elem;
        while (node && node != document) {
            var name = YangRAM.attr(node, 'menu');
            if (name) {
                var app = _.dom.closest(node, 'application');
                if (app) {
                    appid = YangRAM.attr(app, 'appid');
                    if (_.util.bool.isNumeric(appid)) {
                        name = 'A' + appid + '-' + name;
                    }
                };
                if (Contexts.menus[name]) {
                    Contexts.target = node;
                    return name;
                }
            }
            node = node.parentNode;
        }
        return null;
    };

    var events = {
        'contextmenu' (event) {
            var elem = event.relatedTarget  ||  event.srcElement  ||  event.target  || event.currentTarget;
            Contexts.show(getMenuname(elem), event.pageX, event.pageY);
            return false;
        },
        'mousedown' (event) {
            if (Contexts.state) {
                var elem = event.relatedTarget  ||  event.srcElement  ||  event.target  || event.currentTarget;
                if (_.dom.hasChildNode(Contexts.document, elem)) {
                    if (elem.tagName == 'ITEM' && elem.getAttribute('state') == 'on') {
                        Contexts.call(parseInt(elem.getAttribute('num')));
                        Contexts.hide();
                    }
                    if (elem.parentNode.getAttribute('state') == 'off' || elem.getAttribute('state') == 'off') {
                        Contexts.hide();
                    }
                } else {
                    Contexts.hide();
                }
            }
        }
    };

    var handlers = {};

    _.extend(Contexts, true, {
        name: Runtime.locales.HIGHBAR.CTX.APPNAME,
        target: null,
        menus: {},
        current: undefined,
        show(name, x, y) {
            if (name) {
                x = x || 0;
                y = y || 0;
                this.on().current = name;
                YangRAM.attr(this.menus[this.current], 'state', 'on');
                var Size = _.dom.getSize(this.menus[this.current], 'box');
                var width = Size.width;
                var height = Size.height;
                var top = y >= System.Height - height ? y - height : y;
                var left = x >= System.Width - width ? System.Width - width : x;
                YangRAM.setStyle(this.menus[this.current], {
                    top: top,
                    left: left
                });
            }
            return this;
        },
        hide() {
            if (this.menus[this.current]) {
                YangRAM.attr(this.menus[this.current], 'state', 'off');
            }
            this.off();
            this.current = undefined;
        },
        call(num) {
            if (this.menus[this.current]) {
                var callback = handlers[this.current][num];
                var target = this.target || global;
                _.util.bool.isFn(callback) && callback.call(target);
            }
        },
        push(name, data) {
            if (!_.util.bool.isObj(data)) {
                return false;
            }
            if (!_.util.bool.isArr(data)) {
                return false;
            }
            var handler = [];
            var num = 0;
            var html = '';
            for (var gid = 0; gid < data.length; gid++) {
                html += '<group>';
                for (var n = 0; n < data[gid].length; n++, num++) {
                    html += '<item num="' + num + '" state="' + data[gid][n].state + '">' + data[gid][n].title + '</item>';
                    handler.push(data[gid][n].handler);
                }
                html += '</group>';
            }
            this.menus[name] = this.$('menu[name=' + name + ']')[0] || YangRAM.create('menu', this.document, { name: name, state: 'off' });
            this.menus[name].innerHTML = html;
            handlers[name] = handler;
            return this;
        },
        unload(name) {
            this.menus[name] && this.menus[name].parentNode && this.menus[name].parentNode.removeChild(this.menus[name]);
            handlers[name] = null;
            delete handlers[name];
            return this;
        },
        listenEvents() {
            var handlers = handlers.bind || {};
            document.oncontextmenu = events['contextmenu'];
            document.addEventListener('mousedown', events['mousedown']);
            return this;
        }
    });
});