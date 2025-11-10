var _YCT = (function () {

    //private properties:
    var _debug = false;
    var _properties = {
        apiEP: (_debug ? 'http://localhost:53606/api/v1/tracking' : 'https://analytics.yatco.com/api/v1/tracking')
    };
    var _endPoints = {
        sid: '/sid', //site id
        pv: '/pv', //page view
        pve: '/pve', //page view end
        fs: '/fs', //form submit
        vi: '/vi', //vessel impressions
        vv: '/vv', //vessel view
        ci: '/ci', //charter impressions
        cv: '/cv', //charter view
        fgv: '/fgv', //forsale gallery view
        fvp: '/fvp', //forsale vessel phone
    };

    //private methods:
    var _helpers = {
        url: function (ep, qsObj) {
            qsObj = qsObj || {};
            var qs = [];
            for (var key in qsObj) { qs.push(key + '=' + encodeURIComponent(qsObj[key])); }
            return _properties.apiEP + ep + '?' + qs.join('&');
        },
        get: function (uri, callbackFunction) {
            callbackFunction = callbackFunction || function () { };
            var img = new Image();
            img.addEventListener('load', callbackFunction);
            img.src = uri;
        },
        pvObj: function () {
            return {
                referrer: document.referrer,
                host: window.location.protocol + '//' + window.location.host,
                path: window.location.pathname,
                title: document.title,
                res: window.screen.width + 'x' + window.screen.height,
                lang: window.navigator ? window.navigator.language : "unavailable",
                ua: window.navigator ? window.navigator.userAgent : "unavailable"
            };
        },
        argtolist: function (l) {
            var argList = l;
            var idList = [];
            if (argList.length > 1) {
                for (var i = 0; i < argList.length; i++) idList.push(argList[i]);
            }
            else if (argList.length == 1) {
                idList = argList[0].split(',');
            }
            //console.log(idList);
            return idList;
        }
    };

    //Request Site ID from _Init:
    function _getCookie() {
        var host = window.location.host;
        if (host.indexOf('www.') == 0) host = host.replace('www.', '');
        _helpers.get(_helpers.url(_endPoints.sid, { h: host }), function () {
            _log.pv();
        });
    }

    //log functions:
    var _log = {
        pv: function () { _helpers.get(_helpers.url(_endPoints.pv, _helpers.pvObj())); },
        pve: function () { _helpers.get(_helpers.url(_endPoints.pve)); } //keep this, but log the time on page on the pageview table instead
    };

    function _Init() {
        _getCookie();

        window.addEventListener('beforeunload', _log.pve);
    }

    _Init();
    
    function fs(eml, extraData) {
        _helpers.get(_helpers.url(_endPoints.fs, { email: eml, extraData: extraData }));
    }
    
    function vi() {
        var idList = _helpers.argtolist(arguments);
        _helpers.get(_helpers.url(_endPoints.vi, { v: idList }));
    }

    function vv(vid) {
        _helpers.get(_helpers.url(_endPoints.vv, { v: vid }));
    }

    function ci() {
        var idList = _helpers.argtolist(arguments);
        _helpers.get(_helpers.url(_endPoints.ci, { v: idList }));
    }

    function cv(vid) {
        _helpers.get(_helpers.url(_endPoints.cv, { v: vid }));
    }

    function fgv(vid) { _helpers.get(_helpers.url(_endPoints.fgv, { v: vid })); }

    function fvp(vid) { _helpers.get(_helpers.url(_endPoints.fvp, { v: vid })); }

    //expose any functions publicly:
    return {
        formSubmit: fs,
        forsale: {
            impressions: vi,
            view: vv,
            gallery: fgv,
            phone: fvp
        },
        charter: {
            impressions: ci,
            view: cv
        }
    };

})();