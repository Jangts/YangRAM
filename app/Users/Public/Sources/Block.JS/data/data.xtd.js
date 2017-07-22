/*!
 * Block.JS Framework Source Code
 *
 * static data
 *
 * Date 2017-04-06
 */
;
block([
    '$_/util/bool.xtd',
    '$_/dom/',
    '$_/data/XHR.cls'
], function(pandora, global, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        document = global.document,
        console = global.console;


    _('data', {
        load: _.load,
        loadCSS: function(href, callback) {
            var link = _.dom.query('link[href="' + href + '"]')[0] || _.dom.create('link', document.getElementsByTagName('head')[0], {
                type: 'text/css',
                rel: 'stylesheet',
                async: 'async'
            });
            if (!_.dom.getAttr(link, 'href')) {
                link.href = href;
            }

            if (_.dom.getAttr(link, 'loaded') === 'loaded') {
                setTimeout(function() {
                    _.util.bool.isFn(callback) && callback();
                }, 0);
            } else {
                if (typeof(link.onreadystatechange) === 'object') {
                    link.attachEvent('onreadystatechange', function() {
                        if (link.readyState === 'loaded' || link.readyState === 'complete') {
                            _.dom.setAttr(link, 'loaded', 'loaded');
                            _.util.bool.isFn(callback) && callback();
                        } else {
                            console.log(link.readyState);
                        }
                    });
                } else if (typeof(link.onload) !== 'undefined') {
                    link.addEventListener('load', function() {
                        _.dom.setAttr(link, 'loaded', 'loaded');
                        _.util.bool.isFn(callback) && callback();
                    });
                };
            };

        },
        loadScript: function(src, callback) {
            var script = _.dom.query('script[src="' + src + '"]')[0] || _.dom.create('script', document.getElementsByTagName('head')[0], {
                type: 'application/javascript',
                async: 'async'
            });
            if (!_.dom.getAttr(script, 'src')) {
                script.src = src;
            }
            if (_.dom.getAttr(script, 'loaded')) {
                _.util.bool.isFn(callback) && callback();
            } else {
                if (typeof(script.onreadystatechange) === 'object') {
                    script.attachEvent('onreadystatechange', function() {
                        if (script.readyState === 'loaded' || script.readyState === 'complete') {
                            _.dom.setAttr(script, 'loaded', 'loaded');
                            _.util.bool.isFn(callback) && callback();
                        };
                    });
                } else if (typeof(script.onload) === 'object') {
                    script.addEventListener('load', function() {
                        _.dom.setAttr(script, 'loaded', '');
                        _.util.bool.isFn(callback) && callback();
                    });
                };
            };
        },
        AJAX: function(url, settings) {
            switch (arguments.length) {
                case 2:
                    settings = _.util.bool.isObj(settings) ? settings : {};
                    if (_.util.bool.isStr(url)) {
                        settings.url = url;
                    }
                    break;
                case 1:
                    if (_.util.bool.isObj(url)) {
                        settings = url;
                    } else if (_.util.bool.isStr(url)) {
                        settings = {
                            url: url
                        }
                    }
                    break;
                case 0:
                    settings = {};
                    break;
                default:
                    return undefined;
            }
            var Promise = new _.data.XHR(settings);
            Promise.success = Promise.done;
            Promise.error = Promise.fail;
            Promise.complete = Promise.always;
            if (settings.beforeSend && typeof settings.beforeSend == 'function') {
                settings.beforeSend(Promise.xmlhttp);
            };
            Promise.progress(settings.progress).success(settings.success).error(settings.fail).complete(settings.complete)
            if (settings.method === 'POST' || typeof settings.data == 'string') {
                return Promise.setRequestHeader('Content-type', 'application/x-www-form-urlencoded').send(settings.data);
            } else {
                Promise.send()
            }
        },
        json: function(url, doneCallback, failCallback) {
            _.data.AJAX({
                url: url,
                success: function(txt) {
                    doneCallback(JSON.parse(txt));
                },
                fail: failCallback
            });
        },
        encodeQueryString: function(data) {
            var fields = []
            for (var i in data) {
                fields.push(i + "=" + data[i]);
            }
            return fields.join("&");
        },
        decodeQueryString: function(str) {
            var data = {};
            var fields = str.split('&');
            var i = 0,
                filed;
            for (i; i < fields.length; i++) {
                filed = fields[i].split('=');
                data[filed[0]] = filed[1];
            }
            return data;
        },
        cookie: function(name, value, prop) {
            var c = document.cookie,
                ret = null;
            if (arguments.length == 1) {
                if (c && c !== '') {
                    var cookies = c.split(';');
                    for (var i = 0, l = cookies.length; i < l; i++) {
                        var cookie = JY.trim(cookies[i]);
                        if (cookie.substring(0, name.length + 1) == (name + '=')) {
                            ret = decodeURIComponent(cookie.substring(name.length + 1));
                            break;
                        }
                    }
                }
            } else {
                prop = prop || {};
                var expires = '';
                if (prop.expires) {
                    var date;
                    switch (prop.expires.constructor) {
                        case Number:
                            {
                                date = new Date();
                                date.setTime(date.getTime() + (prop.expires * 1000 * 60 * 60 * 24));
                                date = date.toUTCString();
                            }
                            break;
                        case String:
                            {
                                date = prop.expires;
                            }
                            break;
                        default:
                            {
                                date = prop.expires.toUTCString();
                            }
                            break;
                    }
                    expires = '; expires=' + date;
                }
                var path = prop.path ? '; path=' + (prop.path) : '';
                var domain = prop.domain ? '; domain=' + (prop.domain) : '';
                var secure = prop.secure ? '; secure' : '';
                document.cookie = [name, '=', encodeURIComponent(value), expires, path, domain, secure].join('');
            }
            return ret;
        }
    });
});