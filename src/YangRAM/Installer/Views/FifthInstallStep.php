<!doctype html>
<html>
<head>
<meta charset="utf-8">
<title>TANGRAM Software License Terms|YangRAM安装向导</title>
<link href="/YangRAM/Installer/Sources/install.css" type="text/css" rel="stylesheet" />
</head>

<body>
<div class="main">
  <header class="header db-write-bgcolor">Installation of YangRAM Moses
    <div class="logo"></div>
    <div class="subtitle">5/5 Installer Is Writing Data Into The Database</div>
  </header>
  <article class="fifth">
    <div id="msg-box" class="msg-box db-write-bgcolor">
      <p>&nbsp;</p>
      <p>安裝工具正在後臺爲您寫入系統預裝應用所需要的數據，請耐心等待。</p>
      <p>&nbsp;</p>
    </div>
  </article>
</div>
<script>
var timer;
function checkWriting(){
	var url = '/?segment_type=check_db_write';
	var xmlhttp = XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	xmlhttp.open('GET', url, true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200 && this.responseText != '') {
			if(this.responseText=='7'){
				var box = document.getElementById('msg-box');
				box.innerHTML = '<p>Installer已完成全部安装工作</p><p>&nbsp;</p><p>您现在可以尽情享用您的I4s</p><form id="step-button" action="/" method="get"><button type="submit">前往首页</button></form>';
				var button = document.getElementById('step-button');
				button.style.opacity = 1;
				clearInterval(timer);
			}
		}
	}
}
timer = setInterval(checkWriting, 1000);
</script>
</body>
</html>
