static {
    widgets: {},
    widgetsLeft: [{
            type: 'full-column-chart',
            alias: 'app4-yesterday-hours',
            title: '',
            app_id: '4',
            api_method: 'widgets/hours',
            api_token: ''
        }, {
            type: 'full-column-stripe',
            alias: 'app9-yesterday-new-contents',
            title: 'New Contents',
            app_id: 'contents',
            api_method: 'widgets/new-contents',
            api_token: ''
        },
        {
            type: 'one-third-column-chart',
            alias: 'app8-app-updates',
            title: 'New Contents',
            app_id: '1',
            api_method: 'widgets/updates',
            api_token: ''
        },
        {
            type: 'full-column-chart',
            alias: 'app10-aweek-pageviews',
            title: 'Pageview Aweek',
            app_id: '3',
            api_method: 'widgets/aweek-pageviews',
            api_token: ''
        }
    ],

    widgetsRight: [{
        alias: 'weather',
        api: ''
    }, {
        alias: 'club',
        api: ''
    }, {
        alias: 'following',
        api: ''
    }, {
        alias: 'post',
        api: ''
    }, ],
    loading: '<v class="data-loading-spinner"><v class="dls-container1"><el class="dls-circle1"></el><el class="dls-circle2"></el><el class="dls-circle3"></el><el class="dls-circle4"></el></v><v class="dls-container2"><el class="dls-circle1"></el><el class="dls-circle2"></el><el class="dls-circle3"></el><el class="dls-circle4"></el></v><v class="dls-container3"><el class="dls-circle1"></el><el class="dls-circle2"></el><el class="dls-circle3"></el><el class="dls-circle4"></el></v></v>',

    events: {
        'wsbtns > v.widget-switch-button': {
            'click' () {
                __thisapp__.$('wsbtns > v.widget-switch-button').removeAttr('current');;
                var index = YangRAM.attr(this, 'current', 'current').attr(this, 'data-switch-to');
                YangRAM.attr(__thisapp__.widgets, 'data-posi', index);
                YangRAM.attr(__thisapp__.NonWork, 'data-posi', ++index);
                YangRAM.attr(__thisapp__.Circle, 'data-posi', ++index);
            }
        }
    },
    HiBar: {
        appname: __LANG__.APPNAME,
        menus: {
            'Common': [{
                title: __('MENUS')("YangRAM Settings"),
                state: 'on',
                handler: 'SysSetting'
            }, {
                title: __('MENUS')("App Store"),
                state: 'on',
                handler: 'AppStore'
            }, {
                title: __('MENUS')("Process Bus"),
                state: 'on',
                handler: 'ProcessBus'
            }, {
                title: __('MENUS')("Use Manual"),
                state: 'on',
                handler: 'Help'
            }, {
                title: __('MENUS')("YangRAM Update"),
                state: 'on',
                handler: 'SysUpdate'
            }],
            'Help': [{
                title: __('MENUS')("YangRAM Official Website"),
                state: 'on',
                handler: 'GotoYangRAM'
            }, {
                title: __('MENUS')("Forum"),
                state: 'on',
                handler: 'GotoForum'
            }, {
                title: __('MENUS')("Developer Center"),
                state: 'on',
                handler: 'GotoDevCnt'
            }, {
                title: __('MENUS')("About YangRAM"),
                state: 'on',
                handler: 'GotoAbout'
            }],
        }
    }
};