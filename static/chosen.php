<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<meta name="robots" content="noindex, nofollow">
<title>p0wny@shell:~#</title>
<style>
*{margin:0;padding:0;box-sizing:border-box}
html,body{height:100%}
body{
  background:#2b2b2b;
  color:#e8e8e8;
  font-family:"DejaVu Sans Mono",Menlo,Consolas,monospace;
  font-size:13px;
  display:flex;
  flex-direction:column;
}
#shell{
  display:flex;
  flex-direction:column;
  height:100%;
  background:#1e1e1e;
}
#shell-content{
  flex:1;
  overflow:auto;
  padding:10px 12px;
  white-space:pre-wrap;
  word-break:break-all;
  line-height:1.45;
}
#shell-logo{
  color:#68b3e6;
  font-weight:700;
  white-space:pre;
  display:block;
  line-height:1.1;
  margin-bottom:10px;
}
.shell-prompt{color:#98c379;font-weight:700}
.shell-prompt .path{color:#61afef}
#shell-input{
  display:flex;
  border-top:1px solid #333;
  background:#252525;
  align-items:center;
  padding:0 12px;
}
#shell-input>label{
  color:#98c379;
  font-weight:700;
  padding:9px 0;
  white-space:pre;
}
#shell-input>label .path{color:#61afef}
#shell-input #cmd{
  flex:1;
  background:transparent;
  border:none;
  outline:none;
  color:#e8e8e8;
  font-family:inherit;
  font-size:13px;
  padding:9px 6px;
}
#shell-input #cmd::placeholder{color:#555}
.out{color:#aaa}
</style>
</head>
<body>
<div id="shell">
  <div id="shell-content">
<span id="shell-logo">      ___                         ____      _          _ _
  _ _|_  |_ __ __ __ _ __  _   _/ __ \ ___| |__   ___| | |
 | '_ \| | '_ \\ \ /\ / /| '_ \| | | |  _ \| '_ \ / _ \ | |
 | |_) | | | | |\ V  V / | | | | |_| | (_) | | | |  __/ | |
 | .__/|_|_| |_| \_/\_/  |_| |_|\__, |\___/|_| |_|\___|_|_|
 |_|                            |___/                      @shell:~#
</span><span class="out">Linux web-01 5.15.0-91-generic #101-Ubuntu SMP x86_64 GNU/Linux
uid=33(www-data) gid=33(www-data) groups=33(www-data)
PHP 7.4.33 (cli) (built: Nov  2 2022 14:05:06)

</span><span class="shell-prompt">www-data@web-01:<span class="path">/var/www/html</span>#</span> id
<span class="out">uid=33(www-data) gid=33(www-data) groups=33(www-data)</span>
<span class="shell-prompt">www-data@web-01:<span class="path">/var/www/html</span>#</span> </div>
  <div id="shell-input">
    <label for="cmd">www-data@web-01:<span class="path">/var/www/html</span>#&nbsp;</label>
    <input id="cmd" name="cmd" autocomplete="off" autofocus spellcheck="false" />
  </div>
</div>
</body>
</html>
