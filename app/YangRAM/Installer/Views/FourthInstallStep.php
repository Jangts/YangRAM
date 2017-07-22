<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>TANGRAM Software License Terms|YangRAM安装向导</title>
<link href="/YangRAM/Installer/Sources/install.css" type="text/css" rel="stylesheet" />
</head>

<body>
<div class="main">
  <header class="header">Installation of YangRAM Moses
    <div class="logo"></div>
    <div class="subtitle">4/5 Set The Default Connection For Database</div>
  </header>
  <article class="fourth">
    <form class="info-form" method="post">
      <input type="text" name="step" value="5" hidden="" />
      <div class="info-inputs">
        <div>
          <label>YangRAM賬號</label>
          <input type="text" name="_OWNER_" placeholder="siteuser@yangram.com" value="" />
          <span class="remark">用於登錄服務支持中心</span> </div>
        <div>
          <label>主域名</label>
          <input type="text" name="_DOMAIN_" placeholder="www.yourdomain.com" value="" />
          <span class="remark">請勿添加http://</span> </div>
        <div>
          <label>首選語言</label>
          <input type="text" name="_LANG_" value="<?=$NEWIDEA->LANGUAGE?>" />
        </div>
        <div>
          <label>空間容量</label>
          <input type="number" name="_SPACE_" value="1024" />
          <span class="remark">Mb，分配給該站點的空間大小</span> </div>
        <div>
          <label>偽靜態首頁</label>
          <input type="text" name="_HOME_" value="index.htm/index.html/index.php/index.asp/index.jsp/boot.php" />
          <span class="remark">多個文件名請用“/”隔開</span> </div>
        <div>
          <label>NtvOI認證碼</label>
          <input type="text" name="_DOI_TOKEN_" value="<?=$ntvOIF\controllersode?>" />
          <span class="remark">配置NtvOI時要用到</span> </div>
        <div>
          <label>開啓WebOI</label>
          <input type="radio" name="_WEBUOI_ENABLE_" value="1" checked />
          <span class="radio-label">是</span>
          <input type="radio" name="_WEBUOI_ENABLE_" value="0" />
          <span class="radio-label">否</span> </div>
      </div>
      <div class="info-button">
        <button type="submit">確認</button>
      </div>
    </form>
  </article>
</div>
</body>
</html>
