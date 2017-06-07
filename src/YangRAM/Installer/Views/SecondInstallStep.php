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
    <div class="subtitle">2/5 Set The Default Connection For Database</div>
  </header>
  <article class="second">
    <form class="conn-form" method="post">
      <input type="text" name="step" value="3" hidden="" />
      <div class="conn-driver">
        <label>數據庫驅動</label>
        <select name="driver" onChange="selectDriver(this);">
          <option value="MySQL" selected>MySQL</option>
          <option value="Access">Access</option>
          <option value="Cubrid">Cubrid</option>
          <option value="DB2">DB2</option>
          <option value="Firebird">Firebird</option>
          <option value="Informix">Informix</option>
          <option value="Oracle">Oracle</option>
          <option value="PostgreSQL">PostgreSQL</option>
          <option value="SQLite">SQLite</option>
          <option value="SQLite2">SQLite2</option>
          <option value="SQLServer">SQLServer use PDO_SQLSRV</option>
          <option value="MSSQLServer">SQLServer use PDO_DBLIB</option>
          <option value="Sybase">Sybase</option>
        </select>
      </div>
      <div id="conn-inputs" class="conn-inputs">
        <div class="full-input">
          <label>服務器</label>
          <input type="text" name="host" value="localhost" />
        </div>
        <div class="full-input">
          <label>數據庫名</label>
          <input type="text" name="dbname" value="" />
        </div>
        <div class="full-input">
          <label>用戶名</label>
          <input type="text" name="username" value="" />
        </div>
        <div class="full-input">
          <label>登錄口令</label>
          <input type="text" name="password" value="" />
        </div>
        <div class="full-input">
          <label>主機端口</label>
          <input type="text" name="hostport" value="3306" />
        </div>
        <div class="full-input">
          <label>代理</label>
          <input type="text" name="socket" value="" />
        </div>
        <div class="full-input">
          <label>字符集</label>
          <input type="text" name="charset" value="utf8" />
        </div>
      </div>
      <div class="conn-dbpre">
        <label>表前綴</label>
        <input type="text" name="_DBPRE_" value="ni" />
        <button type="submit">確認</button>
      </div>
    </form>
  </article>
</div>
<script>
function selectDriver(obj){
	var driver = obj.value.toLowerCase();
	var url = '/?segment_type=inpus_of_' + driver;
	var xmlhttp = XMLHttpRequest ? new XMLHttpRequest() : new ActiveXObject("Microsoft.XMLHTTP");
	xmlhttp.open('GET', url, true);
	xmlhttp.send();
	xmlhttp.onreadystatechange = function () {
		if (this.readyState == 4 && this.status == 200 && this.responseText != '') {
			var inputs = document.getElementById('conn-inputs');
			inputs.innerHTML = this.responseText;
		}
	}
}
</script>
</body>
</html>
