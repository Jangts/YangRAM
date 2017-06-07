<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>TANGRAM Software License Terms|YangRAM安装向导</title>
<link rel="stylesheet" type="text/css" href="/YangRAM/Installer/Sources/install.css" />
</head>

<body>
<div class="main">
  <header class="header">Installation of YangRAM Moses
    <div class="logo"></div>
    <div class="subtitle">1/5 Please Read And Accept The Software License Terms</div>
  </header>
  <article class="first">
    <div class="clause-content">
      <div class="clause-view-box">
        <?=$clause?>
      </div>
    </div>
    <div class="clause-buttons">
      <form class="clause-form" method="post">
        <input type="text" name="step" value="2" hidden="" />
        <button type="submit">接受</button>
      </form>
      <form class="clause-form" method="post">
        <input type="text" name="step" value="-1" hidden="" />
        <button type="submit">拒絕</button>
      </form>
    </div>
  </article>
</div>
</body>
</html>
