<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
<title>Themes &lsaquo; nelson.cloud &#8212; WordPress</title>
<meta name='robots' content='noindex, nofollow' />
<link rel='stylesheet' id='dashicons-css' href='/wp-includes/css/dashicons.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='admin-bar-css' href='/wp-includes/css/admin-bar.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='common-css' href='/wp-admin/css/common.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='themes-css' href='/wp-admin/css/themes.min.css?ver=6.2.2' media='all' />
<script src='/wp-includes/js/jquery/jquery.min.js?ver=3.6.4' id='jquery-core-js'></script>
<script src='/wp-admin/js/theme.min.js?ver=6.2.2' id='theme-js'></script>
<script id="common-js-extra">
var pagenow = "themes", typenow = "", adminpage = "themes-php", thousandsSeparator = ",", decimalPoint = ".", isRtl = 0;
var ajaxurl = "/wp-admin/admin-ajax.php";
var _wpThemeSettings = {"themes":[],"settings":{"canInstall":true,"installURI":"/wp-admin/theme-install.php","adminUrl":"/wp-admin/"}};
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
.wrap{margin:10px 20px 0 2px}
.wrap h1.wp-heading-inline{font-size:23px;font-weight:400;padding:9px 0 4px;line-height:1.3;display:inline-block;margin-right:5px}
.title-count{background:#c3c4c7;border-radius:16px;color:#fff;display:inline-block;font-size:14px;font-weight:600;padding:2px 12px;margin-left:5px;vertical-align:middle}
.page-title-action{background:#f6f7f7;border:1px solid #2271b1;color:#2271b1;display:inline-block;padding:4px 10px;border-radius:3px;font-size:13px;vertical-align:middle}
.wp-filter{background:#fff;border:1px solid #c3c4c7;box-shadow:0 1px 1px rgba(0,0,0,.04);margin:12px 0 20px;padding:11px 12px;display:flex;justify-content:space-between;align-items:center;border-radius:3px}
.wp-filter-search{border:1px solid #8c8f94;border-radius:4px;padding:5px 8px;font-size:14px;width:280px}
.filter-links{list-style:none;margin:0;padding:0;display:flex;gap:14px;font-size:14px}
.filter-links a{color:#646970;padding:4px 0}
.filter-links a.current{color:#1d2327;font-weight:600;box-shadow:inset 0 -3px #3582c4}
.theme-browser .themes{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:24px}
.theme{background:#fff;border:1px solid #dcdcde;box-shadow:0 1px 1px rgba(0,0,0,.04);position:relative;border-radius:3px;overflow:hidden}
.theme:hover{box-shadow:0 2px 6px rgba(0,0,0,.12)}
.theme.active{border-color:#2271b1;box-shadow:0 0 0 1px #2271b1}
.theme-screenshot{height:200px;overflow:hidden;border-bottom:1px solid #dcdcde;position:relative}
.theme-screenshot .mock{position:absolute;inset:0;padding:14px}
.theme-screenshot .mock .bar{height:8px;border-radius:2px;margin-bottom:8px}
.theme-id-container{padding:12px 14px;position:relative}
.theme-name{font-size:15px;font-weight:600;margin:0;padding:0;line-height:1.3;color:#1d2327}
.theme-name span{font-weight:400;color:#646970}
.theme-actions{margin-top:10px;display:flex;gap:6px}
.button{background:#f6f7f7;border:1px solid #c3c4c7;color:#2c3338;display:inline-block;padding:4px 10px;border-radius:3px;font-size:13px;cursor:pointer}
.button-primary{background:#2271b1;border:1px solid #2271b1;color:#fff;display:inline-block;padding:4px 10px;border-radius:3px;font-size:13px;cursor:pointer}
.theme.add-new-theme a{display:block;height:100%;text-decoration:none}
.theme.add-new-theme .theme-screenshot{background:#f6f7f7;display:flex;align-items:center;justify-content:center;border-bottom:1px solid #dcdcde}
.theme.add-new-theme .theme-screenshot span{font-size:48px;color:#c3c4c7;line-height:1}
.theme.add-new-theme:hover .theme-screenshot span{color:#2271b1}
.theme-author{color:#646970;font-size:13px;margin-top:3px}
.notice{background:#fff;border:1px solid #c3c4c7;border-left-width:4px;box-shadow:0 1px 1px rgba(0,0,0,.04);margin:15px 0;padding:1px 12px;border-radius:3px}
.notice-info{border-left-color:#72aee6}
.notice p{margin:.5em 0;font-size:13px}
#wpfooter{position:absolute;bottom:0;left:160px;right:0;padding:10px 20px;color:#646970;border-top:1px solid #dcdcde;font-size:13px;display:flex;justify-content:space-between}
#wpfooter p{margin:0}
.screen-meta-toggle{float:right;margin:0 0 0 6px}
.screen-meta-toggle button{background:#fff;border:1px solid #c3c4c7;border-top:none;border-radius:0 0 4px 4px;color:#646970;padding:3px 16px 3px 6px;font-size:13px;cursor:pointer}
</style>
</head>
<body class="wp-admin wp-core-ui js themes-php auto-fold admin-bar branch-6-2 version-6-2-2 admin-color-fresh locale-en-us">
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
      <a class="ab-item" href="/wp-admin/profile.php">Howdy, admin</a>
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
  <li class="current"><a href="/wp-admin/themes.php"><span class="wp-menu-image">&#127912;</span> Appearance</a></li>
  <li><a href="/wp-admin/plugins.php"><span class="wp-menu-image">&#128268;</span> Plugins <span class="count">3</span></a></li>
  <li><a href="/wp-admin/users.php"><span class="wp-menu-image">&#128100;</span> Users</a></li>
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
  <button type="button" id="show-settings-link">Screen Options</button>
  <button type="button" id="contextual-help-link">Help</button>
</div>

<div class="wrap">
<h1 class="wp-heading-inline">Themes <span class="title-count theme-count">5</span></h1>
<a href="/wp-admin/theme-install.php" class="page-title-action hide-if-no-js">Add New</a>
<hr class="wp-header-end" style="border:0;border-top:1px solid #c3c4c7;margin:12px 0 0" />

<div class="notice notice-info">
  <p>The block editor requires JavaScript. Some features of the Site Editor may be unavailable.</p>
</div>

<div class="wp-filter">
  <ul class="filter-links">
    <li><a href="/wp-admin/themes.php" class="current">All <span class="count">(5)</span></a></li>
    <li><a href="/wp-admin/themes.php?theme_status=block">Block Themes <span class="count">(2)</span></a></li>
  </ul>
  <div class="search-form">
    <label class="screen-reader-text" for="wp-filter-search-input">Search Installed Themes</label>
    <input type="search" id="wp-filter-search-input" class="wp-filter-search" placeholder="Search installed themes..." />
  </div>
</div>

<div class="theme-browser rendered">
<div class="themes wp-clearfix">

  <div class="theme active" tabindex="0" aria-describedby="twentytwentythree-action twentytwentythree-name">
    <div class="theme-screenshot">
      <div class="mock" style="background:#fff">
        <div class="bar" style="background:#1e1e1e;width:38%"></div>
        <div class="bar" style="background:#e0e0e0;width:88%"></div>
        <div class="bar" style="background:#e0e0e0;width:76%"></div>
        <div class="bar" style="background:#e0e0e0;width:82%;margin-bottom:16px"></div>
        <div class="bar" style="background:#1e1e1e;width:30%"></div>
        <div class="bar" style="background:#e0e0e0;width:70%"></div>
        <div class="bar" style="background:#e0e0e0;width:84%"></div>
      </div>
    </div>
    <div class="theme-id-container">
      <h2 class="theme-name" id="twentytwentythree-name"><span>Active:</span> Twenty Twenty-Three</h2>
      <div class="theme-author">By the WordPress team</div>
      <div class="theme-actions" id="twentytwentythree-action">
        <a class="button button-primary customize load-customize hide-if-no-customize" href="/wp-admin/customize.php">Customize</a>
        <a class="button" href="/wp-admin/site-editor.php">Edit Site</a>
      </div>
    </div>
  </div>

  <div class="theme" tabindex="0">
    <div class="theme-screenshot">
      <div class="mock" style="background:#f5f2ed">
        <div class="bar" style="background:#c8b89a;width:100%;height:44px;margin-bottom:12px"></div>
        <div class="bar" style="background:#3d3d3d;width:52%"></div>
        <div class="bar" style="background:#d6d0c6;width:90%"></div>
        <div class="bar" style="background:#d6d0c6;width:78%"></div>
        <div class="bar" style="background:#d6d0c6;width:86%"></div>
      </div>
    </div>
    <div class="theme-id-container">
      <h2 class="theme-name">Twenty Twenty-Two</h2>
      <div class="theme-author">By the WordPress team</div>
      <div class="theme-actions">
        <a class="button activate" href="/wp-admin/themes.php?action=activate&#038;stylesheet=twentytwentytwo">Activate</a>
        <a class="button button-primary load-customize" href="/wp-admin/customize.php?theme=twentytwentytwo">Live Preview</a>
      </div>
    </div>
  </div>

  <div class="theme" tabindex="0">
    <div class="theme-screenshot">
      <div class="mock" style="background:#d1e4dd">
        <div class="bar" style="background:#28303d;width:44%;height:14px;margin-bottom:14px"></div>
        <div class="bar" style="background:#fff;width:92%"></div>
        <div class="bar" style="background:#fff;width:80%"></div>
        <div class="bar" style="background:#fff;width:88%"></div>
        <div class="bar" style="background:#28303d;width:26%;margin-top:16px"></div>
      </div>
    </div>
    <div class="theme-id-container">
      <h2 class="theme-name">Twenty Twenty-One</h2>
      <div class="theme-author">By the WordPress team</div>
      <div class="theme-actions">
        <a class="button activate" href="/wp-admin/themes.php?action=activate&#038;stylesheet=twentytwentyone">Activate</a>
        <a class="button button-primary load-customize" href="/wp-admin/customize.php?theme=twentytwentyone">Live Preview</a>
      </div>
    </div>
  </div>

  <div class="theme" tabindex="0">
    <div class="theme-screenshot">
      <div class="mock" style="background:#fff">
        <div class="bar" style="background:#0073aa;width:100%;height:26px;margin-bottom:14px"></div>
        <div class="bar" style="background:#333;width:60%"></div>
        <div class="bar" style="background:#eee;width:94%"></div>
        <div class="bar" style="background:#eee;width:72%"></div>
        <div class="bar" style="background:#0073aa;width:34%;height:16px;margin-top:14px"></div>
      </div>
    </div>
    <div class="theme-id-container">
      <h2 class="theme-name">Hello Elementor</h2>
      <div class="theme-author">By Elementor Team</div>
      <div class="theme-actions">
        <a class="button activate" href="/wp-admin/themes.php?action=activate&#038;stylesheet=hello-elementor">Activate</a>
        <a class="button button-primary load-customize" href="/wp-admin/customize.php?theme=hello-elementor">Live Preview</a>
      </div>
    </div>
  </div>

  <div class="theme" tabindex="0">
    <div class="theme-screenshot">
      <div class="mock" style="background:#fafafa">
        <div class="bar" style="background:#5b34ea;width:100%;height:20px;margin-bottom:12px"></div>
        <div class="bar" style="background:#222;width:48%"></div>
        <div class="bar" style="background:#e6e6e6;width:90%"></div>
        <div class="bar" style="background:#e6e6e6;width:84%"></div>
        <div class="bar" style="background:#e6e6e6;width:66%"></div>
      </div>
    </div>
    <div class="theme-id-container">
      <h2 class="theme-name">Astra</h2>
      <div class="theme-author">By Brainstorm Force</div>
      <div class="theme-actions">
        <a class="button activate" href="/wp-admin/themes.php?action=activate&#038;stylesheet=astra">Activate</a>
        <a class="button button-primary load-customize" href="/wp-admin/customize.php?theme=astra">Live Preview</a>
      </div>
    </div>
  </div>

  <div class="theme add-new-theme">
    <a href="/wp-admin/theme-install.php">
      <div class="theme-screenshot"><span>+</span></div>
      <div class="theme-id-container"><h2 class="theme-name">Add New Theme</h2></div>
    </a>
  </div>

</div>
</div>

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
