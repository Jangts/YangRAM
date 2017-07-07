System.ExtendsMethods((YangRAM, declare, global, undefined) => {
    var document = global.document,
        location = global.location,
        System = global.System,
        Runtime = System.Runtime,
        MagicCube = System.MagicCube,
        _ = System.Pandora;

    _.extend(MagicCube, true, {
        name: Runtime.locales.MAGICCUBE.APPNAME,
        build() {
            this.Element.innerHTML = '<mask></mask>';
            this.vision = YangRAM.create('v', this.Element, {
                className: 'data-submitting-spinner',
            });
            for (var i = 1; i < 10; i++) {
                YangRAM.create('el', this.vision, {
                    className: 'dss-cube'
                });
            }
            return this;
        },
        show(timeout, callback, type) {
            if (type === '3d') {
                YangRAM.attr(MagicCube.vision, 'type', 'three-dime');
            } else {
                YangRAM.attr(MagicCube.vision, 'type', 'two-dime');
            }
            MagicCube.on();
            if (_.util.bool.isFn(callback)) {
                timeout = timeout || 10000;
                return setTimeout(() => {
                    MagicCube.hide();
                    callback();
                }, timeout);
            }
            return null;
        },
        hide(timer) {
            timer && clearTimeout(timer);
            setTimeout(() => {
                MagicCube.off();
            }, 200);
            return null;
        }
    }).build();
});