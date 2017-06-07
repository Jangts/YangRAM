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
    <div class="subtitle">3/5 Installer Is Writing Data Into The Database</div>
  </header>
  <article class="third">
    <div class="msg-box db-write-bgcolor">
      <p>安裝工具正在後臺爲您寫入系統所需要的數據，請耐心等待。</p>
      <p>頁面數據會自動更新，請勿手動刷新，亦切勿關閉此頁。</p>
      <p>當前完成<span id="db-write-percent">0</span>%</p>
      <form id="step-button" method="post">
        <input type="text" name="step" value="4" hidden="" />
        <button type="submit">下一步</button>
      </form>
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
			var inputs = document.getElementById('db-write-percent');
			inputs.innerHTML = this.responseText;
			if(this.responseText=='100'){
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
