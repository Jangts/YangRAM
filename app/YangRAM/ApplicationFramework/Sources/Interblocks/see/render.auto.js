/*!
 * Interblocks Framework Source Code
 *
 * class see.Sticker,
 *
 * Date: 2017-04-06
 */
;
iBlock([
    '$_/see/NavMenu/',
    '$_/see/Scrollbar/',
    '$_/see/Tabs/SlideTabs.Cls',
    '$_/see/ListView/'
], function(pandora, global, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        document = global.document,
        location = global.location,
        $ = _.dom.select;

    _.extend(_.see.Scrollbar, {
        auto: function() {
            $('.ic.scrollbar[data-ic-auto]').each(function() {
                if (($(this).data('icAuto') != 'false') && ($(this).data('icRendered') != 'scrollbar')) {
                    $(this).data('icRendered', 'scrollbar');
                    new _.see.Scrollbar(this, {
                        theme: $(this).data('scbarTheme') || 'default-light',
                    });
                }
            });
        }
    });

    _.see.NavMenu.auto();
    _.see.Scrollbar.auto();
    _.see.Tabs.auto();
    _.see.Tabs.SlideTabs.auto();
    _.see.ListView.auto();
}, true);