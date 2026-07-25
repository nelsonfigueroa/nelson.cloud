<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
<title>Profile &lsaquo; nelson.cloud &#8212; WordPress</title>
<meta name='robots' content='noindex, nofollow' />
<link rel='stylesheet' id='dashicons-css' href='/wp-includes/css/dashicons.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='admin-bar-css' href='/wp-includes/css/admin-bar.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='common-css' href='/wp-admin/css/common.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='forms-css' href='/wp-admin/css/forms.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='l10n-css' href='/wp-admin/css/l10n.min.css?ver=6.2.2' media='all' />
<script src='/wp-includes/js/jquery/jquery.min.js?ver=3.6.4' id='jquery-core-js'></script>
<script src='/wp-admin/js/user-profile.min.js?ver=6.2.2' id='user-profile-js'></script>
<script src='/wp-admin/js/password-strength-meter.min.js?ver=6.2.2' id='password-strength-meter-js'></script>
<script id="common-js-extra">
var pagenow = "profile", typenow = "", adminpage = "profile-php", thousandsSeparator = ",", decimalPoint = ".", isRtl = 0;
var ajaxurl = "/wp-admin/admin-ajax.php";
var userProfileL10n = {"user_id":"1","nonce":"a3f9c21e07"};
</script>
<style>
*{box-sizing:border-box}
html{background:#f0f0f1}
body{background:#f0f0f1;color:#3c434a;font-family:-apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,Oxygen-Sans,Ubuntu,Cantarell,"Helvetica Neue",sans-serif;font-size:13px;line-height:1.4em;margin:0;min-width:600px}
a{color:#2271b1;text-decoration:none}
a:hover{color:#135e96}
h1,h2,h3{color:#1d2327;font-weight:400;margin:0}
#wpadminbar{background:#1d2327;color:#c3c4c7;height:32px;position:fixed;top:0;left:0;right:0;z-index:99999;font-size:13px;line-height:32px;min-width:600px}
#wpadminbar .ab-item{color:#c3c4c7;padding:0 10px;display:inline-block;height:32px}
#wpadminbar .ab-item:hover{color:#72aee6;background:#2c3338}
#wpadminbar .quicklinks{display:flex;justify-content:space-between}
#wpadminbar .ab-top-secondary{margin-left:auto}
#adminmenuback,#adminmenuwrap{background:#1d2327;width:160px;position:fixed;top:32px;bottom:0;left:0;overflow:hidden}
#adminmenu{width:160px;margin:0;padding:0;list-style:none}
#adminmenu li a{color:#f0f0f1;display:block;padding:9px 10px;font-size:14px;line-height:18px}
#adminmenu li a:hover{background:#2c3338;color:#72aee6}
#adminmenu li.current a{background:#2271b1;color:#fff;font-weight:600}
#adminmenu .wp-menu-separator{height:5px;padding:0;margin:5px 0;background:#2c3338}
#adminmenu .wp-menu-image{display:inline-block;width:20px;opacity:.6}
#wpcontent{margin-left:160px;padding:32px 20px 0 20px}
#wpbody-content{padding-bottom:65px}
.wrap{margin:10px 20px 0 2px;max-width:1100px}
.wrap h1{font-size:23px;font-weight:400;padding:9px 0 4px;line-height:1.3;margin:0 0 15px}
h2{font-size:1.3em;margin:1.5em 0 1em;font-weight:600;color:#1d2327}
.form-table{border-collapse:collapse;margin-top:.5em;width:100%;clear:both}
.form-table th{vertical-align:top;text-align:left;padding:20px 10px 20px 0;width:200px;line-height:1.3;font-weight:600;color:#1d2327;font-size:14px}
.form-table td{margin-bottom:9px;padding:15px 10px;line-height:1.3;vertical-align:middle;font-size:14px}
.form-table tr{border-bottom:1px solid #f0f0f1}
.regular-text{width:25em;border:1px solid #8c8f94;border-radius:4px;padding:6px 8px;font-size:14px;background:#fff;color:#2c3338;line-height:1.4}
input[type=email].regular-text,input[type=url].regular-text,input[type=password].regular-text{width:25em}
textarea{width:500px;height:120px;border:1px solid #8c8f94;border-radius:4px;padding:6px 8px;font-size:14px;background:#fff;color:#2c3338;font-family:inherit;line-height:1.6}
select{border:1px solid #8c8f94;border-radius:3px;padding:4px 24px 4px 8px;font-size:14px;background:#fff;color:#2c3338;min-height:30px}
.description{color:#646970;font-size:13px;font-style:italic;margin:5px 0 0;display:block}
.button{background:#f6f7f7;border:1px solid #c3c4c7;color:#2c3338;display:inline-block;padding:5px 12px;border-radius:3px;font-size:13px;cursor:pointer}
.button-primary{background:#2271b1;border:1px solid #2271b1;color:#fff;display:inline-block;padding:6px 14px;border-radius:3px;font-size:14px;cursor:pointer}
.button-secondary{background:#f6f7f7;border:1px solid #2271b1;color:#2271b1;display:inline-block;padding:5px 12px;border-radius:3px;font-size:13px;cursor:pointer}
p.submit{margin:2em 0 1em;padding:0}
.color-option{display:inline-block;width:130px;padding:10px 8px;margin:0 8px 8px 0;border:2px solid transparent;background:#f6f7f7;cursor:pointer;vertical-align:top;border-radius:3px}
.color-option.selected{border-color:#2271b1;background:#fff}
.color-option .color-palette{display:table;width:100%;border-spacing:0;border-collapse:collapse;margin-top:6px}
.color-palette td{height:20px;padding:0;border:none;margin:0}
.color-option-label{font-size:13px;color:#3c434a}
#profile-page .form-table #email{margin-right:6px}
.user-profile-picture td{display:flex;gap:14px;align-items:flex-start}
.avatar{width:96px;height:96px;border-radius:50%;background:linear-gradient(135deg,#8c8f94,#c3c4c7);flex-shrink:0}
fieldset{border:0;padding:0;margin:0}
fieldset label{display:block;margin-bottom:6px;font-size:14px}
.wp-pwd{display:flex;gap:6px;align-items:center}
#pass-strength-result{background:#f0f0f1;border:1px solid #c3c4c7;color:#3c434a;margin:8px 0 0;padding:6px 8px;width:24em;text-align:center;font-weight:600;border-radius:3px}
#pass-strength-result.strong{background:#d5e5d5;border-color:#68de7c}
.sessions-row td{color:#3c434a}
#wpfooter{position:absolute;bottom:0;left:160px;right:0;padding:10px 20px;color:#646970;border-top:1px solid #dcdcde;font-size:13px;display:flex;justify-content:space-between}
#wpfooter p{margin:0}
.screen-meta-toggle{float:right;margin:0 0 0 6px}
.screen-meta-toggle button{background:#fff;border:1px solid #c3c4c7;border-top:none;border-radius:0 0 4px 4px;color:#646970;padding:3px 16px 3px 6px;font-size:13px;cursor:pointer}
</style>
</head>
<body class="wp-admin wp-core-ui js profile-php auto-fold admin-bar branch-6-2 version-6-2-2 admin-color-fresh locale-en-us">
<div id="wpwrap">

<div id="wpadminbar" class="nojq nojs">
  <div class="quicklinks">
    <div class="ab-top-menu">
      <a class="ab-item" href="/wp-admin/about.php">&#127968; nelson.cloud</a>
      <a class="ab-item" href="/">Visit Site</a>
      <a class="ab-item" href="/wp-admin/update-core.php">&#8635; 3 Updates</a>
      <a class="ab-item" href="/wp-admin/edit-comments.php">&#128172; 47</a>
      <a class="ab-item" href="/wp-admin/post-new.php">+ New</a>
    </div>
    <div class="ab-top-secondary">
      <a class="ab-item" href="/wp-admin/profile.php">Howdy, <span class="display-name">admin</span></a>
    </div>
  </div>
</div>

<div id="adminmenuback"></div>
<div id="adminmenuwrap">
<ul id="adminmenu">
  <li><a href="/wp-admin/index.php"><span class="wp-menu-image">&#9636;</span> Dashboard</a></li>
  <li class="wp-menu-separator"></li>
  <li><a href="/wp-admin/edit.php"><span class="wp-menu-image">&#128196;</span> Posts</a></li>
  <li><a href="/wp-admin/upload.php"><span class="wp-menu-image">&#128247;</span> Media</a></li>
  <li><a href="/wp-admin/edit.php?post_type=page"><span class="wp-menu-image">&#128203;</span> Pages</a></li>
  <li><a href="/wp-admin/edit-comments.php"><span class="wp-menu-image">&#128172;</span> Comments</a></li>
  <li class="wp-menu-separator"></li>
  <li><a href="/wp-admin/themes.php"><span class="wp-menu-image">&#127912;</span> Appearance</a></li>
  <li><a href="/wp-admin/plugins.php"><span class="wp-menu-image">&#128268;</span> Plugins <span class="count">3</span></a></li>
  <li class="current"><a href="/wp-admin/users.php"><span class="wp-menu-image">&#128100;</span> Users</a></li>
  <li><a href="/wp-admin/tools.php"><span class="wp-menu-image">&#128295;</span> Tools</a></li>
  <li><a href="/wp-admin/options-general.php"><span class="wp-menu-image">&#9881;</span> Settings</a></li>
  <li class="wp-menu-separator"></li>
  <li><a href="#" onclick="return false"><span class="wp-menu-image">&#9664;</span> Collapse menu</a></li>
</ul>
</div>

<div id="wpcontent">
<div id="wpbody">
<div id="wpbody-content">

<div class="screen-meta-toggle">
  <button type="button" id="contextual-help-link">Help</button>
</div>

<div class="wrap" id="profile-page">
<h1>Profile</h1>

<form id="your-profile" action="/wp-admin/profile.php" method="post" novalidate="novalidate">
<input type="hidden" name="_wpnonce" value="a3f9c21e07" />
<input type="hidden" name="_wp_http_referer" value="/wp-admin/profile.php" />
<input type="hidden" name="from" value="profile" />
<input type="hidden" name="checkuser_id" value="1" />

<h2>Personal Options</h2>
<table class="form-table" role="presentation">
  <tr class="user-rich-editing-wrap">
    <th scope="row">Visual Editor</th>
    <td><label for="rich_editing"><input name="rich_editing" type="checkbox" id="rich_editing" value="false" /> Disable the visual editor when writing</label></td>
  </tr>
  <tr class="user-syntax-highlighting-wrap">
    <th scope="row">Syntax Highlighting</th>
    <td><label for="syntax_highlighting"><input name="syntax_highlighting" type="checkbox" id="syntax_highlighting" value="false" /> Disable syntax highlighting when editing code</label></td>
  </tr>
  <tr class="user-admin-color-wrap">
    <th scope="row">Admin Color Scheme</th>
    <td>
      <div class="color-option selected">
        <input name="admin_color" id="admin_color_fresh" type="radio" value="fresh" checked="checked" />
        <label for="admin_color_fresh" class="color-option-label">Default</label>
        <table class="color-palette"><tr><td style="background:#1d2327"></td><td style="background:#2c3338"></td><td style="background:#2271b1"></td><td style="background:#72aee6"></td></tr></table>
      </div>
      <div class="color-option">
        <input name="admin_color" id="admin_color_light" type="radio" value="light" />
        <label for="admin_color_light" class="color-option-label">Light</label>
        <table class="color-palette"><tr><td style="background:#e5e5e5"></td><td style="background:#999"></td><td style="background:#d64e07"></td><td style="background:#04a4cc"></td></tr></table>
      </div>
      <div class="color-option">
        <input name="admin_color" id="admin_color_midnight" type="radio" value="midnight" />
        <label for="admin_color_midnight" class="color-option-label">Midnight</label>
        <table class="color-palette"><tr><td style="background:#25282b"></td><td style="background:#363b3f"></td><td style="background:#69a8bb"></td><td style="background:#e14d43"></td></tr></table>
      </div>
      <div class="color-option">
        <input name="admin_color" id="admin_color_coffee" type="radio" value="coffee" />
        <label for="admin_color_coffee" class="color-option-label">Coffee</label>
        <table class="color-palette"><tr><td style="background:#46403c"></td><td style="background:#59524c"></td><td style="background:#c7a589"></td><td style="background:#9ea476"></td></tr></table>
      </div>
    </td>
  </tr>
  <tr class="user-comment-shortcuts-wrap">
    <th scope="row">Keyboard Shortcuts</th>
    <td><label for="comment_shortcuts"><input type="checkbox" name="comment_shortcuts" id="comment_shortcuts" value="true" /> Enable keyboard shortcuts for comment moderation.</label></td>
  </tr>
  <tr class="show-admin-bar user-admin-bar-front-wrap">
    <th scope="row">Toolbar</th>
    <td><label for="admin_bar_front"><input name="admin_bar_front" type="checkbox" id="admin_bar_front" value="1" checked="checked" /> Show Toolbar when viewing site</label></td>
  </tr>
  <tr class="user-language-wrap">
    <th scope="row"><label for="locale">Language</label></th>
    <td><select name="locale" id="locale"><option value="" selected="selected">English (United States)</option><option value="es_ES">Espa&ntilde;ol</option><option value="de_DE">Deutsch</option></select></td>
  </tr>
</table>

<h2>Name</h2>
<table class="form-table" role="presentation">
  <tr class="user-user-login-wrap">
    <th><label for="user_login">Username</label></th>
    <td><input type="text" name="user_login" id="user_login" value="admin" disabled="disabled" class="regular-text" /> <span class="description">Usernames cannot be changed.</span></td>
  </tr>
  <tr class="user-role-wrap">
    <th>Role</th>
    <td><input type="text" value="Administrator" disabled="disabled" class="regular-text" /></td>
  </tr>
  <tr class="user-first-name-wrap">
    <th><label for="first_name">First Name</label></th>
    <td><input type="text" name="first_name" id="first_name" value="Nelson" class="regular-text" /></td>
  </tr>
  <tr class="user-last-name-wrap">
    <th><label for="last_name">Last Name</label></th>
    <td><input type="text" name="last_name" id="last_name" value="Figueroa" class="regular-text" /></td>
  </tr>
  <tr class="user-nickname-wrap">
    <th><label for="nickname">Nickname <span class="description">(required)</span></label></th>
    <td><input type="text" name="nickname" id="nickname" value="admin" class="regular-text" /></td>
  </tr>
  <tr class="user-display-name-wrap">
    <th><label for="display_name">Display name publicly as</label></th>
    <td><select name="display_name" id="display_name"><option>Nelson Figueroa</option><option selected="selected">admin</option><option>Nelson</option></select></td>
  </tr>
</table>

<h2>Contact Info</h2>
<table class="form-table" role="presentation">
  <tr class="user-email-wrap">
    <th><label for="email">Email <span class="description">(required)</span></label></th>
    <td><input type="email" name="email" id="email" value="admin@nelson.cloud" class="regular-text ltr" /></td>
  </tr>
  <tr class="user-url-wrap">
    <th><label for="url">Website</label></th>
    <td><input type="url" name="url" id="url" value="https://nelson.cloud" class="regular-text code" /></td>
  </tr>
</table>

<h2>About Yourself</h2>
<table class="form-table" role="presentation">
  <tr class="user-description-wrap">
    <th><label for="description">Biographical Info</label></th>
    <td><textarea name="description" id="description" rows="5" cols="30">Cloud engineer. I write about AWS, Linux, privacy, and wasting scammers' time.</textarea>
    <p class="description">Share a little biographical information to fill out your profile. This may be shown publicly.</p></td>
  </tr>
  <tr class="user-profile-picture">
    <th>Profile Picture</th>
    <td>
      <div class="avatar"></div>
      <p class="description">You can change your profile picture on <a href="https://gravatar.com/">Gravatar</a>.</p>
    </td>
  </tr>
</table>

<h2>Account Management</h2>
<table class="form-table" role="presentation">
  <tr id="password" class="user-pass1-wrap">
    <th><label for="pass1">New Password</label></th>
    <td>
      <button type="button" class="button button-secondary wp-generate-pw">Set New Password</button>
      <div class="wp-pwd hide-if-js" style="display:none">
        <input type="password" name="pass1" id="pass1" class="regular-text" autocomplete="new-password" />
        <button type="button" class="button wp-hide-pw">Hide</button>
      </div>
    </td>
  </tr>
  <tr class="user-sessions-wrap sessions-row">
    <th>Sessions</th>
    <td>
      <button type="button" class="button" id="destroy-sessions">Log Out Everywhere Else</button>
      <p class="description">You are only logged in at this location.</p>
    </td>
  </tr>
  <tr class="user-application-passwords-wrap">
    <th>Application Passwords</th>
    <td>
      <p class="description">Application passwords allow authentication via non-interactive systems, such as XML-RPC or the REST API, without providing your actual password.</p>
      <p><label for="new_application_password_name">New Application Password Name</label><br />
      <input type="text" name="new_application_password_name" id="new_application_password_name" class="regular-text" /></p>
      <p><button type="button" name="do_new_application_password" id="do_new_application_password" class="button button-secondary">Add New Application Password</button></p>
    </td>
  </tr>
</table>

<p class="submit"><input type="submit" name="submit" id="submit" class="button button-primary" value="Update Profile" /></p>

</form>
</div>

</div>
</div>
</div>

<div id="wpfooter">
  <p>Thank you for creating with <a href="https://wordpress.org/">WordPress</a>.</p>
  <p>Version 6.2.2 <a href="/wp-admin/update-core.php">Please update now</a></p>
</div>

</div>
</body>
</html>
