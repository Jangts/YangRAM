<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Yes, Tangram!</title>
<meta name="viewport" content="width=device-width, maximum-scale=0.75, minimum-scale=0.25, user-scalable=no" />
<script src="<?=SSRC_PID?>Block.JS/block.js" type="text/javascript" data-debug-mode></script>
<!--link href="<?=SSRC_PID?>Block.JS/view/highlight/themes/default.css" type="text/css" rel="stylesheet" /-->
<link href="<?=SSRC_PID?>Fonts/Fonts.css" type="text/css" rel="stylesheet" />
<link href="<?=SSRC_PID?>Block.JS/see/see.css" type="text/css" rel="stylesheet" />
<link href="<?=UOI_PID?>Sources/styles/IconsForUoi.css" type="text/css" rel="stylesheet" />
<link href="<?=UOI_PID?>Sources/styles/CommonBasics.css" type="text/css" rel="stylesheet" />
<link href="<?=UOI_PID?>Sources/styles/Widgets.css" type="text/css" rel="stylesheet" />
<link href="<?=UOI_PID?>Sources/styles/LogInterface.css" type="text/css" rel="stylesheet" />
<link href="<?=UOI_PID?>Sources/styles/LockInterface.css" type="text/css" rel="stylesheet" />
</head>

<body>
<yangram>
  <workspace viewstatus="workmode">
    <windows></windows>
    <browser></browser>
    <launcher>
      <rankinglist></rankinglist>
      <memowall></memowall>
    </launcher>
  </workspace>
  <hibar>
    <mask></mask>
    <start>
      <logo>&nbsp;</logo>
    </start>
    <searcher>
      <icon data="magnifier"></icon>
    </searcher>
    <timer>01/01  23:59:59</timer>
    <wsmswitcher>
      <icon data="layers"></icon>
    </wsmswitcher>
    <appstore>
      <icon data="puzzle"></icon>
    </appstore>
    <msger>
      <icon data="bell"></icon>
    </msger>
    <account></account>
    <subbars></subbars>
    <menus></menus>
  </hibar>
  <widgets name="yangram-tools">
    <smartian></smartian>
    <kalendar></kalendar>
    <msgcenter></msgcenter>
    <processbus></processbus>
    <dialogs></dialogs>
    <explorer></explorer>
    
  </widgets>
  <widgets high-order="true">
    <cubes></cubes>
    <timepicker></timepicker>
    <locker>
      <masker></masker>
      <avatar style="background-image: url(<?=$avatar?>)">
        <status-vision>Locked</status-vision>
        <circle-vision></circle-vision>
      </avatar>
      <form name="oui-unlock-form">
        <v name="username">
          <?=$username?>
        </v>
        <v name="pincode">
          <input name="pin" type="text" autocomplete="off" placeholder="" max-length="7" value="" />
          <pinshow></pinshow>
        </v>
      </form>
    </locker>
    <logger>
      <avatar status="loaded" style="background-image: url(<?=$avatar?>)">
        <percent-vision status="loading">
          <el>0</el>
          %</percent-vision>
        <circle-vision status="loading"></circle-vision>
        <status-vision status="loading"></status-vision>
      </avatar>
    </logger>
  </widgets>
  <hiddens></hiddens>
</yangram>
<script>
block([
    '$_/util/imports.xtd',
    '$_/util/arr.xtd',
    '$_/util/obj.xtd',
    '$_/util/str.xtd',
    '$_/Time/locales/en',
    '$_/Time/',
    '$_/util/Promise.cls',
    '$_/data/hash.xtd',
    '$_/data/MD5.cls',
    '$_/data/Uploader.cls',
    '$_/data/Clipboard.cls',
    '$_/Time/Month.cls',
    '$_/dom/Elements/animation.clsx',
    '$_/dom/Elements/form.clsx',
    '$_/dom/Template.cls',
    '$_/form/Editor/toolbarTypes/complete.bar',
    '$_/form/Editor/toolbarTypes/normal.bar',
    '$_/form/Editor/toolbarTypes/simple.bar',
    '$_/form/Editor/emoticons/default.emt',
    '$_/form/Data.cls',
    '$_/medias/Player.cls',
    '$_/medias/Image.cls',
    '$_/see/fa.css',
    '$_/see/BasicScrollBAR.cls',
    '$_/see/Slider/',
    '$_/see/Tabs/TabViews.cls',
    '$_/data/Component.cls',
    '$_/see/widgets/Alerter.cls',
    '<?=__GET_DIR?>uoi/scripts/system',
    '<?=__GET_DIR?>uoi/scripts/evn/<?=$__Lang?>/runtime'
], function(pandora, global, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        document = global.document,
        location = global.location;

    var modules = '<?=SSRC_PID?>';

    require.config({
        mainUrl: '<?=__URL?>',
        packages: [{
                main: 'echarts',
                location: modules + 'ECharts/src',
                name: 'echarts'
            },
            {
                main: 'zrender',
                location: modules + 'ZRender',
                name: 'zrender'
            }
        ]
    });

    var System = global.System;    

    System.DebugMode = '<?=_USE_DEBUG_MODE_?>';
    System.Theme = 'common';
    System.User = '<?=$username?>';
    System.UserAvatar = '<?=$avatar?>';
    System.LoadingItemsCount = 39;
    System.OnLoadStart = () => {
        System.Logger.loadingstatus.attr('status', 'loading');
        System.Logger.loadedpercent.html(System.LoadedRate);
    };
    System.OnLoadingStatusChange = () => {
        var str = System.LoadingStatus + '(' + System.Loaded + '/'+System.LoadingItemsCount+')';
        System.Logger.loadingstatus.html(str);
        // console.log(System.Loaded, str);
    };
    System.OnLoaded = () => {
        System.Logger.loadedpercent.html(System.LoadedRate);
    }
    System.OnLoadCompletely = () => {
        global.System = undefined;
        System.Logger.sleep();
        setTimeout(function() {
            System.HiBar.launch().listenEvents();
        }, 500);
        setTimeout(function() {
            System.Workspace.Launcher.launch();
        }, 1500);
    };

    global.onresize = () => {
        System.Resize();
    };
    window.onbeforeunload = () => {
        return System.OnClose();
    };

    System.KeyEvents = (e) => {
        var  elem  =  e.relatedTarget  ||  e.srcElement  ||  e.target  || e.currentTarget;
        if (e.keyCode == 8) {
            if (elem.tagName == 'INPUT') {
                var type = elem.type.toUpperCase();
                if (type == 'TEXT' || type == 'PASSWORD') {
                    if (elem.readOnly == true || elem.disabled == true) {
                        return false;
                    }
                    return;
                }
                return false;
            } else if (elem.tagName == 'TEXTcontainer') {
                if (elem.readOnly == true || elem.disabled == true) {
                    return false;
                }
                return;
            } else if (elem.getAttribute('contenteditable') == 'true') {
                return;
            }
            return false;
        }
    }
    document.onkeydown = System.KeyEvents;
    
    new _.data.XHR({
        url:'<?=SSRC_PID?>Block.JS/Time/locales/<?=substr(strtolower($__Lang), 0, 2)?>.js',
    }).done((script)=>{
        try {
            eval(script);
        } catch (error) {
            console.log(error);
        } 
    }).always(()=>{
        YangRAM.initialize('<?=UOI_PID?>', '<?=__UOI_DIR?>', '<?=__GET_DIR?>', '<?=__SET_DIR?>', function() {
            System.Load();
        });
    }).send();
}, true);
</script>
</body>
</html>