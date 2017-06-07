<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Yes, Tangram!</title>
<meta name="viewport" content="width=device-width, maximum-scale=0.75, minimum-scale=0.25, user-scalable=no" />
<script src="<?=NIAF_PID?>Sources/Interblocks/iBlock.js" type="text/javascript" data-debug-mode></script>
<!--link href="<?=NIAF_PID?>Sources/Interblocks/view/highlight/themes/default.css" type="text/css" rel="stylesheet" /-->
<link href="<?=NIAF_PID?>Sources/Fonts/Fonts.css" type="text/css" rel="stylesheet" />
<link href="<?=NIAF_PID?>Sources/Interblocks/see/see.css" type="text/css" rel="stylesheet" />
<link href="<?=UOI_PID?>Sources/themes/<?=$__Style?>/IconsForUoi.css" type="text/css" rel="stylesheet" />
<link href="<?=UOI_PID?>Sources/themes/<?=$__Style?>/CommonBasics.css" type="text/css" rel="stylesheet" />
<link href="<?=UOI_PID?>Sources/themes/<?=$__Style?>/Widgets.css" type="text/css" rel="stylesheet" />
<link href="<?=UOI_PID?>Sources/themes/<?=$__Style?>/LogInterface.css" type="text/css" rel="stylesheet" />
<link href="<?=UOI_PID?>Sources/themes/<?=$__Style?>/LockInterface.css" type="text/css" rel="stylesheet" />
</head>

<body>
<yangram>
  <workspace>
    <windows></windows>
    <browser></browser>
    <launcher>
      <rankinglist></rankinglist>
      <memowall></memowall>
      <momodifier></momodifier>
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
        <vision name="username">
          <?=$username?>
        </vision>
        <vision name="pincode">
          <input name="pin" type="text" autocomplete="off" placeholder="" max-length="7" value="" />
          <pinshow></pinshow>
        </vision>
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
iBlock([
    '$_/util/imports.xtd',
    '$_/util/arr.xtd',
    '$_/util/obj.xtd',
    '$_/util/str.xtd',
    '$_/util/locales/en',
    '$_/util/Time.Cls',
    '$_/util/Promise.Cls',
    '$_/data/hash.xtd',
    '$_/data/MD5.Cls',
    '$_/data/Uploader.Cls',
    '$_/data/Clipboard.Cls',
    '$_/data/Month.Cls',
    '$_/dom/Elements/animation.clsx',
    '$_/dom/Elements/form.clsx',
    '$_/dom/Template.Cls',
    '$_/form/Editor/toolbarTypes/complete.bar',
    '$_/form/Editor/toolbarTypes/normal.bar',
    '$_/form/Editor/toolbarTypes/simple.bar',
    '$_/form/Editor/emoticons/default.emt',
    '$_/form/Data.Cls',
    '$_/medias/Player.Cls',
    '$_/medias/Image.Cls',
    '$_/see/fa.css',
    '$_/see/BasicScrollBAR.Cls',
    '$_/see/Slider/',
    '$_/see/Tabs/TabViews.Cls',
    '$_/data/Component.Cls',
    '$_/see/widgets/Alerter.Cls',
    '<?=__GET_DIR?>uoi/scripts/system',
    '<?=__GET_DIR?>uoi/scripts/evn/<?=$__Lang?>/runtime'
], function(pandora, global, undefined) {
    var _ = pandora,
        declare = pandora.declareClass,
        cache = pandora.locker,
        document = global.document,
        location = global.location;

    var modules = '<?=NIAF_PID?>Sources/';

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
    System.Theme = '<?=$__Style?>';
    System.User = '<?=$username?>';
    System.UserAvatar = '<?=$avatar?>';
    System.LoadingItemsCount = 38;
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
            } else if (elem.tagName == 'TEXTAREA') {
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
        url:'<?=NIAF_PID?>Sources/Interblocks/util/locales/<?=substr(strtolower($__Lang), 0, 2)?>.js',
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