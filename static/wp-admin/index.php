<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
<title>Dashboard &lsaquo; nelson.cloud &#8212; WordPress</title>
<meta name='robots' content='noindex, nofollow' />
<link rel='stylesheet' id='dashicons-css' href='/wp-includes/css/dashicons.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='admin-bar-css' href='/wp-includes/css/admin-bar.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='common-css' href='/wp-admin/css/common.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='forms-css' href='/wp-admin/css/forms.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='dashboard-css' href='/wp-admin/css/dashboard.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='wp-admin-css' href='/wp-admin/css/wp-admin.min.css?ver=6.2.2' media='all' />
<script src='/wp-includes/js/jquery/jquery.min.js?ver=3.6.4' id='jquery-core-js'></script>
<script src='/wp-includes/js/jquery/jquery-migrate.min.js?ver=3.4.0' id='jquery-migrate-js'></script>
<script id="common-js-extra">
var pagenow = "dashboard", typenow = "", adminpage = "index-php", thousandsSeparator = ",", decimalPoint = ".", isRtl = 0;
var ajaxurl = "/wp-admin/admin-ajax.php";
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
#adminmenu li.current a,#adminmenu li.wp-has-current-submenu a{background:#2271b1;color:#fff;font-weight:600}
#adminmenu .wp-menu-separator{height:5px;padding:0;margin:5px 0;background:#2c3338}
#adminmenu .wp-menu-image{display:inline-block;width:20px;opacity:.6}
#wpcontent{margin-left:160px;padding:32px 20px 0 20px}
#wpbody-content{padding-bottom:65px}
.wrap{margin:10px 20px 0 2px}
.wrap h1.wp-heading-inline{font-size:23px;font-weight:400;padding:9px 0 4px;line-height:1.3;display:inline-block}
#welcome-panel{background:#fff;border:1px solid #c3c4c7;box-shadow:0 1px 1px rgba(0,0,0,.04);margin:16px 0;padding:23px 10px 0;position:relative}
#welcome-panel h2{font-size:21px;font-weight:400;margin:0;line-height:1.2}
#welcome-panel p.about-description{font-size:16px;margin:1em 0}
.welcome-panel-column-container{display:flex;gap:2%;padding-bottom:14px}
.welcome-panel-column{flex:1}
.welcome-panel-column h3{font-size:16px;margin:0 0 .6em}
.welcome-panel-column ul{list-style:none;margin:0;padding:0}
.welcome-panel-column li{margin:7px 0}
.button-primary{background:#2271b1;border:1px solid #2271b1;color:#fff;display:inline-block;padding:6px 14px;border-radius:3px;font-size:14px;cursor:pointer}
.button{background:#f6f7f7;border:1px solid #2271b1;color:#2271b1;display:inline-block;padding:4px 10px;border-radius:3px;font-size:13px;cursor:pointer}
#dashboard-widgets{display:flex;flex-wrap:wrap;gap:0;margin:0 -8px}
.postbox-container{padding:0 8px}
#postbox-container-1,#postbox-container-2{width:50%}
.postbox{background:#fff;border:1px solid #c3c4c7;box-shadow:0 1px 1px rgba(0,0,0,.04);margin-bottom:20px;min-width:255px}
.postbox .postbox-header{border-bottom:1px solid #c3c4c7;display:flex;justify-content:space-between;align-items:center}
.postbox h2{font-size:14px;padding:8px 12px;margin:0;font-weight:600;line-height:1.4}
.inside{padding:0 12px 12px;margin:11px 0}
.main ul{margin:0;padding:0;list-style:none}
#dashboard_right_now li{display:inline-block;width:50%;margin:0;padding:4px 0}
#dashboard_right_now li a,#dashboard_right_now li span{padding:3px 0 3px 26px;display:block}
#dashboard_activity .subsubsub{margin:0 0 8px}
.activity-block{border-bottom:1px solid #f0f0f1;padding:8px 0;margin:0}
.activity-block h3{font-size:14px;margin:0 0 8px;color:#646970;font-weight:600}
#activity-widget ul{margin:0;padding:0;list-style:none}
#activity-widget li{padding:3px 0;color:#646970}
#activity-widget li span{color:#646970;margin-right:8px}
.comment-item{border-bottom:1px solid #f0f0f1;padding:8px 0;display:flex;gap:10px}
.comment-avatar{width:32px;height:32px;background:#c3c4c7;border-radius:50%;flex-shrink:0}
.rss-widget ul{list-style:none;margin:0;padding:0}
.rss-widget li{margin-bottom:12px;line-height:1.5}
.rss-date{color:#646970;font-size:12px}
#wpfooter{position:absolute;bottom:0;left:160px;right:0;padding:10px 20px;color:#646970;border-top:1px solid #dcdcde;font-size:13px;display:flex;justify-content:space-between}
#wpfooter p{margin:0}
.screen-meta-toggle{float:right;margin:0 0 0 6px}
.screen-meta-toggle button{background:#fff;border:1px solid #c3c4c7;border-top:none;border-radius:0 0 4px 4px;color:#646970;padding:3px 16px 3px 6px;font-size:13px;cursor:pointer}
.hidden{display:none}
input[type=text],textarea{border:1px solid #8c8f94;border-radius:4px;padding:6px 8px;width:100%;font-size:14px;background:#fff;color:#2c3338}
.subsubsub{list-style:none;margin:8px 0;padding:0;font-size:13px;color:#646970}
.subsubsub li{display:inline-block;margin:0}
.subsubsub li:after{content:" |";color:#c3c4c7;padding:0 4px}
.subsubsub li:last-child:after{content:""}
.count{color:#646970}
@media screen and (max-width:782px){#dashboard-widgets #postbox-container-1,#dashboard-widgets #postbox-container-2{width:100%}}
</style>
</head>
<body class="wp-admin wp-core-ui js index-php auto-fold admin-bar branch-6-2 version-6-2-2 admin-color-fresh locale-en-us">
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
  <li class="current"><a href="/wp-admin/index.php"><span class="wp-menu-image">&#9636;</span> Dashboard</a></li>
  <li class="wp-menu-separator"></li>
  <li><a href="/wp-admin/edit.php"><span class="wp-menu-image">&#128196;</span> Posts</a></li>
  <li><a href="/wp-admin/upload.php"><span class="wp-menu-image">&#128247;</span> Media</a></li>
  <li><a href="/wp-admin/edit.php?post_type=page"><span class="wp-menu-image">&#128203;</span> Pages</a></li>
  <li><a href="/wp-admin/edit-comments.php"><span class="wp-menu-image">&#128172;</span> Comments</a></li>
  <li class="wp-menu-separator"></li>
  <li><a href="/wp-admin/themes.php"><span class="wp-menu-image">&#127912;</span> Appearance</a></li>
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
<h1 class="wp-heading-inline">Dashboard</h1>

<div id="welcome-panel" class="welcome-panel">
  <h2>Welcome to WordPress!</h2>
  <p class="about-description">We&#8217;ve assembled some links to get you started:</p>
  <div class="welcome-panel-column-container">
    <div class="welcome-panel-column">
      <h3>Get Started</h3>
      <p><a class="button button-primary" href="/wp-admin/customize.php">Customize Your Site</a></p>
      <p>or, <a href="/wp-admin/themes.php">change your theme completely</a></p>
    </div>
    <div class="welcome-panel-column">
      <h3>Next Steps</h3>
      <ul>
        <li><a href="/wp-admin/post.php?post=1&#038;action=edit">Edit your front page</a></li>
        <li><a href="/wp-admin/post-new.php?post_type=page">Add additional pages</a></li>
        <li><a href="/wp-admin/post-new.php">Write your first blog post</a></li>
        <li><a href="/">View your site</a></li>
      </ul>
    </div>
    <div class="welcome-panel-column">
      <h3>More Actions</h3>
      <ul>
        <li><a href="/wp-admin/widgets.php">Manage widgets</a></li>
        <li><a href="/wp-admin/options-discussion.php">Turn comments on or off</a></li>
        <li><a href="https://wordpress.org/documentation/">Learn more about getting started</a></li>
      </ul>
    </div>
  </div>
</div>

<div id="dashboard-widgets-wrap">
<div id="dashboard-widgets" class="metabox-holder">

  <div id="postbox-container-1" class="postbox-container">

    <div id="dashboard_right_now" class="postbox">
      <div class="postbox-header"><h2>At a Glance</h2></div>
      <div class="inside">
        <div class="main">
          <ul>
            <li><a href="/wp-admin/edit.php">&#128196; 96 Posts</a></li>
            <li><a href="/wp-admin/edit.php?post_type=page">&#128203; 6 Pages</a></li>
            <li><a href="/wp-admin/edit-comments.php">&#128172; 47 Comments</a></li>
            <li><a href="/wp-admin/edit-comments.php?comment_status=spam">&#128465; 12,908 Spam</a></li>
          </ul>
          <p>WordPress 6.2.2 running <a href="/wp-admin/themes.php">Twenty Twenty-Three</a> theme.</p>
          <p><a href="/wp-admin/options-privacy.php">Search engines discouraged</a></p>
        </div>
      </div>
    </div>

    <div id="dashboard_quick_press" class="postbox">
      <div class="postbox-header"><h2>Quick Draft</h2></div>
      <div class="inside">
        <form name="post" action="/wp-admin/post.php" method="post">
          <p><label for="title">Title</label><input type="text" name="post_title" id="title" autocomplete="off" /></p>
          <p><label for="content">Content</label><textarea name="content" id="content" rows="4" placeholder="What&#8217;s on your mind?"></textarea></p>
          <p><input type="submit" value="Save Draft" class="button-primary" /></p>
        </form>
        <div class="drafts">
          <h3>Your Recent Drafts</h3>
          <ul>
            <li><a href="/wp-admin/post.php?post=412&#038;action=edit">Migrating off Cloudflare (again)</a><time>July 14, 2026</time></li>
            <li><a href="/wp-admin/post.php?post=408&#038;action=edit">Notes on IMDSv2 enforcement</a><time>June 29, 2026</time></li>
          </ul>
        </div>
      </div>
    </div>

  </div>

  <div id="postbox-container-2" class="postbox-container">

    <div id="dashboard_activity" class="postbox">
      <div class="postbox-header"><h2>Activity</h2></div>
      <div class="inside" id="activity-widget">
        <div class="activity-block">
          <h3>Recently Published</h3>
          <ul>
            <li><span>Jul 22, 2026, 9:14 am</span><a href="/wp-admin/post.php?post=411&#038;action=edit">Proxying GoatCounter requests through CloudFront</a></li>
            <li><span>Jul 8, 2026, 7:02 pm</span><a href="/wp-admin/post.php?post=407&#038;action=edit">Finding AWS resources by IP address</a></li>
            <li><span>Jun 18, 2026, 6:41 am</span><a href="/wp-admin/post.php?post=399&#038;action=edit">.gitignore isn&#8217;t the only way to ignore files in git</a></li>
          </ul>
        </div>
        <div class="activity-block">
          <h3>Recent Comments</h3>
          <div class="comment-item">
            <div class="comment-avatar"></div>
            <div><strong>Кристина</strong> on <a href="/wp-admin/post.php?post=399&#038;action=edit">.gitignore isn&#8217;t the only way&#8230;</a><br />
            <span style="color:#646970">Very informative article! Check out my site for cheap [&#8230;]</span></div>
          </div>
          <div class="comment-item">
            <div class="comment-avatar"></div>
            <div><strong>seo_expert_2026</strong> on <a href="/wp-admin/post.php?post=407&#038;action=edit">Finding AWS resources by IP&#8230;</a><br />
            <span style="color:#646970">I can improve your Google ranking guaranteed first page [&#8230;]</span></div>
          </div>
          <div class="comment-item">
            <div class="comment-avatar"></div>
            <div><strong>dave</strong> on <a href="/wp-admin/post.php?post=399&#038;action=edit">.gitignore isn&#8217;t the only way&#8230;</a><br />
            <span style="color:#646970">TIL about .git/info/exclude, thanks for this.</span></div>
          </div>
          <p><a href="/wp-admin/edit-comments.php" class="button">View all comments</a></p>
        </div>
      </div>
    </div>

    <div id="dashboard_primary" class="postbox">
      <div class="postbox-header"><h2>WordPress Events and News</h2></div>
      <div class="inside rss-widget">
        <ul>
          <li><a href="https://wordpress.org/news/">WordPress 6.2.3 Maintenance Release</a><br />
            <span class="rss-date">July 19, 2026</span>
            <div>This release fixes 8 bugs and includes 3 security fixes. Because this is a security release, it is recommended that you update your sites immediately.</div>
          </li>
          <li><a href="https://wordpress.org/news/">The Month in WordPress &#8211; June 2026</a><br />
            <span class="rss-date">July 3, 2026</span>
          </li>
        </ul>
        <p><a href="https://wordpress.org/news/" class="button">Read more</a></p>
      </div>
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
</div>
</body>
</html>
