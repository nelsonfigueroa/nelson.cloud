<!DOCTYPE html>
<html lang="en-US">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<title>Log In &lsaquo; nelson.cloud &#8212; WordPress</title>
<meta name='robots' content='noindex, noarchive' />
<meta name='viewport' content='width=device-width' />
<link rel='stylesheet' id='dashicons-css' href='/wp-includes/css/dashicons.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='buttons-css' href='/wp-includes/css/buttons.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='forms-css' href='/wp-admin/css/forms.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='l10n-css' href='/wp-admin/css/l10n.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='login-css' href='/wp-admin/css/login.min.css?ver=6.2.2' media='all' />
<style>
*{box-sizing:border-box}
html{background:#f0f0f1}
body.login{background:#f0f0f1;color:#3c434a;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;font-size:13px;line-height:1.4;margin:0;padding:0;min-width:0}
#login{width:320px;padding:8% 0 0;margin:auto}
#login h1{text-align:center;margin:0}
#login h1 a{display:block;width:84px;height:84px;margin:0 auto 25px;text-indent:-9999px;overflow:hidden;outline:0}
#login h1 svg{display:block}
#loginform,#lostpasswordform{background:#fff;margin-top:20px;margin-left:0;padding:26px 24px;font-weight:400;overflow:hidden;border:1px solid #c3c4c7;box-shadow:0 1px 3px rgba(0,0,0,.04)}
.login label{color:#3c434a;font-size:14px;display:block;line-height:1.5;margin-bottom:3px}
.login form .input,.login input[type=text],.login input[type=password]{font-size:24px;line-height:1.33;width:100%;border:1px solid #8c8f94;background:#fff;box-shadow:0 0 0 transparent;border-radius:4px;padding:3px 8px;margin:0 0 16px;min-height:40px;color:#2c3338;outline:0}
.login form .input:focus{border-color:#2271b1;box-shadow:0 0 0 1px #2271b1}
.user-pass-wrap{position:relative}
.login .wp-pwd{position:relative;display:flex}
.login .wp-pwd .button{position:absolute;right:0;top:0;height:40px;width:44px;min-width:40px;border:none;background:transparent;color:#787c82;cursor:pointer;font-size:16px}
.login .password-input{padding-right:48px}
.forgetmenot{font-weight:400;float:left;margin-bottom:0;line-height:1.5;font-size:13px}
.forgetmenot label{display:inline;font-size:13px;line-height:1.5}
.login .button-primary{float:right;background:#2271b1;border:1px solid #2271b1;border-radius:3px;color:#fff;padding:0 16px;line-height:2.30769231;min-height:32px;font-size:13px;cursor:pointer}
.login .button-primary:hover{background:#135e96;border-color:#135e96}
.login form .submit{margin:0;padding:0}
#nav,#backtoblog{font-size:13px;padding:0 24px;margin:24px 0 0;text-align:left}
#backtoblog{margin:16px 0 0}
#nav a,#backtoblog a{color:#50575e;text-decoration:none}
#nav a:hover,#backtoblog a:hover{color:#135e96}
.login .privacy-policy-page-link{margin:3em 0;text-align:center;width:100%}
.clear{clear:both}
.screen-reader-text{border:0;clip:rect(1px,1px,1px,1px);clip-path:inset(50%);height:1px;margin:-1px;overflow:hidden;padding:0;position:absolute;width:1px;word-wrap:normal}
</style>
</head>
<body class="login no-js login-action-login wp-core-ui locale-en-us">
<script type="text/javascript">document.body.className = document.body.className.replace('no-js','js');</script>

<div id="login">
  <h1><a href="https://wordpress.org/">
    <svg viewBox="0 0 122.5 122.5" width="84" height="84" aria-hidden="true" focusable="false">
      <circle cx="61.25" cy="61.25" r="58.5" fill="none" stroke="#3c434a" stroke-width="5"/>
      <text x="61.25" y="89" font-family="Georgia, 'Times New Roman', serif" font-size="78" font-weight="700" fill="#3c434a" text-anchor="middle">W</text>
    </svg>
    Powered by WordPress</a></h1>

  <form name="loginform" id="loginform" action="/wp-login.php" method="post">
    <p>
      <label for="user_login">Username or Email Address</label>
      <input type="text" name="log" id="user_login" class="input" value="" size="20" autocapitalize="off" autocomplete="username" required="required" />
    </p>

    <div class="user-pass-wrap">
      <label for="user_pass">Password</label>
      <div class="wp-pwd">
        <input type="password" name="pwd" id="user_pass" class="input password-input" value="" size="20" autocomplete="current-password" spellcheck="false" required="required" />
        <button type="button" class="button button-secondary wp-hide-pw hide-if-no-js" data-toggle="0" aria-label="Show password">
          <span class="dashicons dashicons-visibility" aria-hidden="true">&#128065;</span>
        </button>
      </div>
    </div>

    <p class="forgetmenot"><input name="rememberme" type="checkbox" id="rememberme" value="forever" /> <label for="rememberme">Remember Me</label></p>

    <p class="submit">
      <input type="submit" name="wp-submit" id="wp-submit" class="button button-primary button-large" value="Log In" />
      <input type="hidden" name="redirect_to" value="/wp-admin/" />
      <input type="hidden" name="testcookie" value="1" />
    </p>
    <div class="clear"></div>
  </form>

  <p id="nav">
    <a href="/wp-login.php?action=lostpassword">Lost your password?</a>
  </p>

  <p id="backtoblog"><a href="/">&larr; Go to nelson.cloud</a></p>
</div>

<script type="text/javascript">
try{document.getElementById('user_login').focus();}catch(e){}
if(typeof wpOnload==='function'){wpOnload();}
</script>
</body>
</html>
