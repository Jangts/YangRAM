iBlock('$_/dom/Elements/', function(_, global, undefined) {
    var document = global.document;
    var location = global.location;
    var $ = _.dom.select;

    $('dd.share-item').click(function() {
        switch ($(this).data('target')) {
            case 'sina':
                return global.open('http://weibo.com/u/5549296537?is_hot=1', '_blank');

            case 'yiban':
                return global.open('http://www.yiban.cn/Newgroup/indexOrg/group_id/210816/puid/5365968', '_blank');

            case 'weixin':
                return $('#weixinQR').addClass('actived');
        }
    });

    $('#weixinQR .qr-cls').click(function() {
        $('#weixinQR').removeClass('actived');
    });
}, true);