var includes = [
    '$_/data/',
    '$_/data/Base64.cls',
    '$_/dom/Elements/',
    '$_/dom/Template.cls',
    '$_/see/Slider/',
    '$_/see/Slider/colx3.opts',
    '$_/see/Slider/slide-vert.opts'
];
block(includes, function(_, global, undefined) {
    var document = global.document;
    var location = global.location;
    var $ = _.dom.select;

    $('#myslide').each(function(index, element) {
        new _.see.Slider(this, 'colx3', {
            kbCtrlAble: true
        });
    });

    $('.banners').each(function(index, element) {
        new _.see.Slider(this, 'slide-vert', {
            kbCtrlAble: false
        });
    });

    $('.artilisttabs .see-more').click(function() {
        //alert('You triggered a see-more button');
        var url = $('.tab-anchor.actived', this.parentNode).data('src');
        if (url) {
            global.open(url, '_blank');
        }
    });



    var newscats = {
            xysx: {
                set_alias: 'news',
                url: '/o/contents/spc/get_list_by_cat/16/2/0/6/'
            },
            jzmt: {
                set_alias: 'news',
                url: '/o/contents/spc/get_list_by_cat/20/2/0/6/'
            },
            cxcy: {
                set_alias: 'chuangs',
                url: '/o/contents/spc/get_list_by_preset/chuangs/2/0/6/'
            },
            zxfz: {
                set_alias: 'fazhans',
                url: '/o/contents/spc/get_list_by_preset/fazhans/2/0/6/'
            },
            jyxx: {
                set_alias: 'jiuyes',
                url: '/o/contents/spc/get_list_by_cat/4/2/0/6/'
            },
            xyzj: {
                set_alias: 'stunews',
                url: '/o/contents/spc/get_list_by_preset/stunews/2/0/6/'
            }
        },
        coder = new _.data.Base64(),
        template = new _.dom.Template($('#newstp').html());

    _.each(newscats, function(cat, meta) {
        _.data.json(meta.url, function(data) {
            template.clear();
            _.each(data, function(i, news) {
                template.complie({
                    url: '/s/' + news.SET_ALIAS + '/article/' + coder.encode(news.ID),
                    title: news.TITLE
                });
            });
            template.echo(), $('.artilisttabs .tab-section.' + cat).html(template.echo());
        }, function() {
            console.log(cat + '加载失败');
        })
    });
}, true);