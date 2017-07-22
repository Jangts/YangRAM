/*!
 * Block.JS Framework Source Code
 *
 * class view/Scrollbar
 *
 * Date: 2017-04-06
 */

block([
    '$_/util/bool.xtd',
    '$_/see/BasicScrollBAR.cls'
], function(pandora, global, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        document = global.document,
        console = global.console,
        location = global.location;

    var scrollbar = '';
    scrollbar += '<a href="javascript:void(0);" class="scroll-button-prev" oncontextmenu="return false;"></a>';
    scrollbar += '<div class="scroll-bar">';
    scrollbar += '<div class="scroll-dragger" oncontextmenu="return false;"></div>';
    scrollbar += '<div class="scroll-rail"></div></div>';
    scrollbar += '<a href="javascript:void(0);" class="scroll-button-next" oncontextmenu="return false;"></a>';

    declare('see.Scrollbar', _.see.BasicScrollBAR, {
        _init: function(elem, settings) {
            settings = _.extend(settings || {}, false, {
                theme: 'default-dark',
                uesVertical: true,
                uesHorizontal: false
            });
            this.Element = _.util.bool.isStr(elem) ? _.dom.query.byId(elem) : elem;
            _.dom.addClass(this.Element, 'bc')
                .addClass(this.Element, 'scrollbar')
                .addClass(this.Element, settings.theme || 'light');
            this.build(settings.uesVertical, settings.uesHorizontal);
            this.resize();
            this.bind();
            // console.log(settings);
        },
        Element: document,
        scrollbar: scrollbar,
        build: function(uesVertical, uesHorizontal) {
            this.content = this.Element.innerHTML;
            _.dom.setStyle(this.Element, 'overflow', 'hidden');
            if (_.byCn('scroll-doc-container', this.Element)[0]) {
                this.document = _.byCn('scroll-doc-container', this.Element)[0];
            } else {
                this.Element.innerHTML = '';
                this.document = _.dom.create('div', this.Element, {
                    className: 'scroll-doc-container',
                    innerHTML: this.content
                });
                _.dom.setStyle(this.document, {
                    width: this.Element.scrollWidth,
                    height: this.Element.scrollHeight
                });
            }

            if (uesVertical) {
                if (_.byCn('scroll-bar-vertical', this.Scrollbar)[0]) {
                    this.vertical = _.byCn('scroll-bar-vertical', this.Scrollbar)[0];
                } else {
                    this.vertical = _.dom.create('div', this.Element, {
                        className: 'scroll-bar-vertical',
                        innerHTML: this.scrollbar
                    });
                }
            }

            if (uesHorizontal) {
                if (_.byCn('scroll-bar-horizontal', this.Scrollbar)[0]) {
                    this.horizontal = _.byCn('scroll-bar-horizontal', this.Scrollbar)[0];
                } else {
                    this.horizontal = _.dom.create('div', this.Element, {
                        className: 'scroll-bar-horizontal',
                        innerHTML: this.scrollbar
                    });
                }
            }
            this.buttonUp = _.byCn('scroll-button-prev', this.Scrollbar)[0];
            this.buttonRight = _.byCn('scroll-button-next', this.Scrollbar)[0];
            this.buttonDown = _.byCn('scroll-button-next', this.Scrollbar)[0];
            this.buttonLeft = _.byCn('scroll-button-prev', this.Scrollbar)[0];
            this.horizontalRail = _.byCn('scroll-rail', this.horizontal)[0];
            this.horizontalDragger = _.byCn('scroll-dragger', this.horizontal)[0];
            this.verticalRail = _.byCn('scroll-rail', this.vertical)[0];
            this.verticalDragger = _.byCn('scroll-dragger', this.vertical)[0];
        }
    });
});