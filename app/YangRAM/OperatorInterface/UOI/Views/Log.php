<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<title>Yes, Tangram!</title>
<meta name="viewport" content="width=device-width, maximum-scale=0.75, minimum-scale=0.25, user-scalable=no" />
<script src="<?=NIAF_PID?>Sources/Interblocks/iblock.js" type="text/javascript" data-debug-mode></script>
<link href="<?=UOI_PID?>Sources/styles/CommonBasics.css" type="text/css" rel="stylesheet" />
<link href="<?=UOI_PID?>Sources/styles/LogInterface.css" type="text/css" rel="stylesheet" />
</head>
<body>
<yangram>
  <widgets high-order="true">
    <cubes> </cubes>
    <logger state="on">
      <avatar>
        <percent-vision></percent-vision>
        <circle-vision></circle-vision>
        <status-vision></status-vision>
      </avatar>
      <form name="oui-login-form" autocomplete="off">
        <v name="username">
          <input name="opn" type="text" value="<?php echo $username ?>" placeholder="" />
        </v>
        <v name="password">
          <input name="opp" type="password" placeholder="" value="" />
        </v>
        <v name="pincode">
          <input name="pin" type="text" autocomplete="off" placeholder="" max-length="7" value="" />
          <pinshow></pinshow>
        </v>
      </form>
    </logger>
  </widgets>
</yangram>
<script>
iBlock([
    '$_/util/Time.Cls',
    '$_/data/',
    '$_/data/Clipboard.Cls',
    '$_/dom/Elements/form.clsx',
    '$_/medias/Player.Cls',
    '$_/form/Data.Cls',
    '$_/see/BasicScrollBAR.Cls',
    '$_/data/Component.Cls',
    '$_/see/widgets/Alerter.Cls',
    '<?=__GET_DIR?>uoi/scripts/system',
    '<?=__GET_DIR?>uoi/scripts/log/<?=$__Lang?>/runtime'
], function(pandora, global, undefined) {
    System.UserAvatar = '<?=$avatar?>';
    YangRAM.initialize('<?=UOI_PID?>', '<?=__UOI_DIR?>', '<?=__GET_DIR?>', '<?=__SET_DIR?>', function() {
        System.Logger.listenEvents();
    });
}, true);
</script>
</body>
</html>