/*!
 * Interblocks Framework Source Code
 *
 * http://www.yangram.net/interblocks/
 *
 * Date: 2017-04-06
 */
;
iBlock([
    '$_/util/bool.xtd',
    '$_/dom/Elements/animation.clsx'
], function(pandora, global, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        document = global.document,
        location = global.location,
        $ = _.dom.select;



    declare('see.NavMenu', {
        _init: function(elem) {
            this.Element = _.util.bool.isStr(elem) ? _.dom.query.byId(elem) : elem;
            if (_.util.bool.isEl(this.Element)) {
                this.render();
            }
        },
        render: function() {
            $('.submenu[data-width]', this.Element).each(function() {
                $(this).css('width', parseFloat($(this).data('width')));
            });
            $('.submenu[data-height]', this.Element).each(function() {
                $(this).css('height', parseFloat($(this).data('height')));
            });
            $('.submenu.under.al-center, .submenu.upon.al-center', this.Element).each(function() {
                $(document.body).addClass('__while-menu-item-get-size');
                width = $(this).width();
                $(document.body).removeClass('__while-menu-item-get-size');
                $(this).css('margin-left', '-' + width / 2 + 'px');
            });
            $('.submenu.rside.al-middle, .submenu.lside.al-middle', this.Element).each(function() {
                $(document.body).addClass('__while-menu-item-get-size');
                height = $(this).height();
                $(document.body).removeClass('__while-menu-item-get-size');
                $(this).css('margin-top', '-' + height / 2 + 'px');
            });
        }
    });

    _.extend(_.see.Slider, {
        auto: function() {
            $('.ic.navmenu[data-ic-auto]').each(function() {
                if ($(this).data('icAuto') != 'false') {
                    new _.see.NavMenu(this);
                }
            });
        }
    });

    _.see.Slide.auto();
});