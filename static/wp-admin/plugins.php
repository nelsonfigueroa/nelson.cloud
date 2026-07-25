<!DOCTYPE html>
<html lang="en-US">
<head>
<meta charset="UTF-8" />
<meta name="viewport" content="width=device-width,initial-scale=1.0" />
<title>Plugins &lsaquo; nelson.cloud &#8212; WordPress</title>
<meta name='robots' content='noindex, nofollow' />
<link rel='stylesheet' id='dashicons-css' href='/wp-includes/css/dashicons.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='admin-bar-css' href='/wp-includes/css/admin-bar.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='common-css' href='/wp-admin/css/common.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='forms-css' href='/wp-admin/css/forms.min.css?ver=6.2.2' media='all' />
<link rel='stylesheet' id='list-tables-css' href='/wp-admin/css/list-tables.min.css?ver=6.2.2' media='all' />
<script src='/wp-includes/js/jquery/jquery.min.js?ver=3.6.4' id='jquery-core-js'></script>
<script src='/wp-admin/js/plugin-install.min.js?ver=6.2.2' id='plugin-install-js'></script>
<script id="common-js-extra">
var pagenow = "plugins", typenow = "", adminpage = "plugins-php", thousandsSeparator = ",", decimalPoint = ".", isRtl = 0;
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
#adminmenu li.current a{background:#2271b1;color:#fff;font-weight:600}
#adminmenu .wp-menu-separator{height:5px;padding:0;margin:5px 0;background:#2c3338}
#adminmenu .wp-menu-image{display:inline-block;width:20px;opacity:.6}
#wpcontent{margin-left:160px;padding:32px 20px 0 20px}
#wpbody-content{padding-bottom:65px}
.wrap{margin:10px 20px 0 2px}
.wrap h1.wp-heading-inline{font-size:23px;font-weight:400;padding:9px 0 4px;line-height:1.3;display:inline-block;margin-right:5px}
.page-title-action{background:#f6f7f7;border:1px solid #2271b1;color:#2271b1;display:inline-block;padding:4px 10px;border-radius:3px;font-size:13px;vertical-align:middle}
.button{background:#f6f7f7;border:1px solid #c3c4c7;color:#2c3338;display:inline-block;padding:4px 10px;border-radius:3px;font-size:13px;cursor:pointer}
.notice{background:#fff;border:1px solid #c3c4c7;border-left-width:4px;box-shadow:0 1px 1px rgba(0,0,0,.04);margin:15px 0;padding:1px 12px}
.notice-warning{border-left-color:#dba617}
.notice p{margin:.5em 0;font-size:13px}
.subsubsub{list-style:none;margin:8px 0;padding:0;font-size:13px;color:#646970;float:left;clear:both}
.subsubsub li{display:inline-block;margin:0}
.subsubsub li:after{content:" |";color:#c3c4c7;padding:0 4px}
.subsubsub li:last-child:after{content:""}
.subsubsub a.current{font-weight:600;color:#000}
.count{color:#646970}
.search-box{float:right;margin-bottom:8px}
.search-box input[type=search]{border:1px solid #8c8f94;border-radius:4px;padding:4px 8px;font-size:14px;width:280px}
.tablenav{clear:both;height:30px;margin:6px 0 4px;padding-top:5px;display:flex;justify-content:space-between;align-items:center}
.tablenav .actions{display:flex;gap:6px;align-items:center}
.tablenav select{border:1px solid #8c8f94;border-radius:3px;padding:3px 24px 3px 8px;font-size:14px;background:#fff;color:#2c3338;height:30px}
.wp-list-table{border-spacing:0;width:100%;clear:both;background:#fff;border:1px solid #c3c4c7;box-shadow:0 1px 1px rgba(0,0,0,.04);border-radius:3px}
.wp-list-table thead th,.wp-list-table tfoot th{padding:8px 10px;text-align:left;font-weight:400;color:#2c3338;font-size:14px;border-bottom:1px solid #c3c4c7}
.wp-list-table tfoot th{border-bottom:none;border-top:1px solid #c3c4c7}
.wp-list-table td{padding:10px 10px;vertical-align:top;font-size:13px;line-height:1.5;color:#3c434a}
.check-column{width:2.2em;padding:10px 0 0 3px!important}
.plugin-title{width:25%}
tr.active td,tr.active th{background:#f0f6fc;border-left:4px solid #72aee6;box-shadow:none}
tr.inactive td,tr.inactive th{background:#fff;border-left:4px solid #fff}
tr.active.update td,tr.inactive.update td{border-bottom:0}
.plugin-title strong{font-size:14px;line-height:1.3;color:#1d2327;font-weight:600}
.row-actions{color:#646970;font-size:13px;padding:2px 0 0;margin:0}
.row-actions span:after{content:" | ";color:#c3c4c7}
.row-actions span.last:after{content:""}
.row-actions .delete a{color:#b32d2e}
.plugin-version-author-uri{font-size:13px;color:#646970;margin-top:6px}
.plugin-update-tr .update-message{background:#fcf9e8;border-left:4px solid #dba617;padding:9px 12px;margin:0;font-size:13px}
.plugin-update-tr td{padding:0!important;border-top:none}
.inactive-plugin-desc{color:#646970}
#wpfooter{position:absolute;bottom:0;left:160px;right:0;padding:10px 20px;color:#646970;border-top:1px solid #dcdcde;font-size:13px;display:flex;justify-content:space-between}
#wpfooter p{margin:0}
.screen-meta-toggle{float:right;margin:0 0 0 6px}
.screen-meta-toggle button{background:#fff;border:1px solid #c3c4c7;border-top:none;border-radius:0 0 4px 4px;color:#646970;padding:3px 16px 3px 6px;font-size:13px;cursor:pointer}
</style>
</head>
<body class="wp-admin wp-core-ui js plugins-php auto-fold admin-bar branch-6-2 version-6-2-2 admin-color-fresh locale-en-us">
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
  <li><a href="/wp-admin/themes.php"><span class="wp-menu-image">&#127912;</span> Appearance</a></li>
  <li class="current"><a href="/wp-admin/plugins.php"><span class="wp-menu-image">&#128268;</span> Plugins <span class="count">3</span></a></li>
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
<h1 class="wp-heading-inline">Plugins</h1>
<a href="/wp-admin/plugin-install.php" class="page-title-action">Add New</a>
<hr class="wp-header-end" style="border:0;border-top:1px solid #c3c4c7;margin:12px 0 0" />

<div class="notice notice-warning">
  <p>There are updates available for the following plugins: <strong>WP File Manager</strong>, <strong>Contact Form 7</strong>, and <strong>Duplicator</strong>.</p>
</div>

<ul class="subsubsub">
  <li class="all"><a href="/wp-admin/plugins.php?plugin_status=all" class="current">All <span class="count">(9)</span></a></li>
  <li class="active"><a href="/wp-admin/plugins.php?plugin_status=active">Active <span class="count">(6)</span></a></li>
  <li class="inactive"><a href="/wp-admin/plugins.php?plugin_status=inactive">Inactive <span class="count">(3)</span></a></li>
  <li class="upgrade"><a href="/wp-admin/plugins.php?plugin_status=upgrade">Update Available <span class="count">(3)</span></a></li>
  <li class="auto-update-disabled"><a href="/wp-admin/plugins.php?plugin_status=auto-update-disabled">Auto-updates Disabled <span class="count">(9)</span></a></li>
</ul>

<form class="search-form search-plugins" method="get">
  <p class="search-box">
    <label class="screen-reader-text" for="plugin-search-input">Search Installed Plugins:</label>
    <input type="search" id="plugin-search-input" name="s" value="" />
    <input type="submit" id="search-submit" class="button" value="Search Installed Plugins" />
  </p>
</form>

<div class="tablenav top">
  <div class="actions bulkactions">
    <label for="bulk-action-selector-top" class="screen-reader-text">Select bulk action</label>
    <select name="action" id="bulk-action-selector-top">
      <option value="-1">Bulk actions</option>
      <option value="activate-selected">Activate</option>
      <option value="deactivate-selected">Deactivate</option>
      <option value="update-selected">Update</option>
      <option value="delete-selected">Delete</option>
    </select>
    <input type="submit" id="doaction" class="button action" value="Apply" />
  </div>
  <div class="tablenav-pages one-page"><span class="displaying-num">9 items</span></div>
</div>

<table class="wp-list-table widefat plugins">
<thead>
<tr>
  <td class="manage-column column-cb check-column"><input id="cb-select-all-1" type="checkbox" /></td>
  <th scope="col" class="manage-column column-name column-primary">Plugin</th>
  <th scope="col" class="manage-column column-description">Description</th>
  <th scope="col" class="manage-column column-auto-updates">Automatic Updates</th>
</tr>
</thead>
<tbody id="the-list">

<tr class="active update" data-plugin="wp-file-manager/file_folder_manager.php" data-slug="wp-file-manager">
  <th scope="row" class="check-column"><input type="checkbox" name="checked[]" value="wp-file-manager/file_folder_manager.php" /></th>
  <td class="plugin-title column-primary">
    <strong>WP File Manager</strong>
    <div class="row-actions visible">
      <span class="deactivate"><a href="/wp-admin/plugins.php?action=deactivate&#038;plugin=wp-file-manager%2Ffile_folder_manager.php">Deactivate</a></span>
      <span class="settings"><a href="/wp-admin/admin.php?page=wp_file_manager">Settings</a></span>
      <span class="last"><a href="/wp-admin/plugin-editor.php?plugin=wp-file-manager%2Ffile_folder_manager.php">Edit</a></span>
    </div>
  </td>
  <td class="column-description desc">
    <div class="plugin-description"><p>Manage your WP files from the WordPress backend. Upload, edit, delete, copy, move, archive and extract files directly from the admin panel.</p></div>
    <div class="plugin-version-author-uri">Version 6.0 | By <a href="#">mndpsingh287</a> | <a href="#">View details</a></div>
  </td>
  <td class="column-auto-updates">Auto-updates disabled</td>
</tr>
<tr class="plugin-update-tr active">
  <td colspan="4" class="plugin-update colspanchange">
    <div class="update-message notice inline notice-warning notice-alt">
      <p>There is a new version of WP File Manager available. <a href="#">View version 7.2.1 details</a> or <a href="/wp-admin/update.php?action=upgrade-plugin&#038;plugin=wp-file-manager%2Ffile_folder_manager.php">update now</a>.</p>
    </div>
  </td>
</tr>

<tr class="active update" data-plugin="contact-form-7/wp-contact-form-7.php" data-slug="contact-form-7">
  <th scope="row" class="check-column"><input type="checkbox" name="checked[]" value="contact-form-7/wp-contact-form-7.php" /></th>
  <td class="plugin-title column-primary">
    <strong>Contact Form 7</strong>
    <div class="row-actions visible">
      <span class="deactivate"><a href="/wp-admin/plugins.php?action=deactivate&#038;plugin=contact-form-7%2Fwp-contact-form-7.php">Deactivate</a></span>
      <span class="last"><a href="/wp-admin/plugin-editor.php?plugin=contact-form-7%2Fwp-contact-form-7.php">Edit</a></span>
    </div>
  </td>
  <td class="column-description desc">
    <div class="plugin-description"><p>Just another contact form plugin. Simple but flexible.</p></div>
    <div class="plugin-version-author-uri">Version 5.3.1 | By <a href="#">Takayuki Miyoshi</a> | <a href="#">View details</a></div>
  </td>
  <td class="column-auto-updates">Auto-updates disabled</td>
</tr>
<tr class="plugin-update-tr active">
  <td colspan="4" class="plugin-update colspanchange">
    <div class="update-message notice inline notice-warning notice-alt">
      <p>There is a new version of Contact Form 7 available. <a href="#">View version 5.9.3 details</a> or <a href="/wp-admin/update.php?action=upgrade-plugin&#038;plugin=contact-form-7%2Fwp-contact-form-7.php">update now</a>.</p>
    </div>
  </td>
</tr>

<tr class="inactive update" data-plugin="duplicator/duplicator.php" data-slug="duplicator">
  <th scope="row" class="check-column"><input type="checkbox" name="checked[]" value="duplicator/duplicator.php" /></th>
  <td class="plugin-title column-primary">
    <strong>Duplicator</strong>
    <div class="row-actions visible">
      <span class="activate"><a href="/wp-admin/plugins.php?action=activate&#038;plugin=duplicator%2Fduplicator.php">Activate</a></span>
      <span class="delete"><a href="/wp-admin/plugins.php?action=delete-selected&#038;checked%5B0%5D=duplicator%2Fduplicator.php">Delete</a></span>
      <span class="last"><a href="/wp-admin/plugin-editor.php?plugin=duplicator%2Fduplicator.php">Edit</a></span>
    </div>
  </td>
  <td class="column-description desc inactive-plugin-desc">
    <div class="plugin-description"><p>Duplicate, clone, backup, move and transfer an entire site from one location to another.</p></div>
    <div class="plugin-version-author-uri">Version 1.3.26 | By <a href="#">Snap Creek</a> | <a href="#">View details</a></div>
  </td>
  <td class="column-auto-updates">Auto-updates disabled</td>
</tr>
<tr class="plugin-update-tr inactive">
  <td colspan="4" class="plugin-update colspanchange">
    <div class="update-message notice inline notice-warning notice-alt">
      <p>There is a new version of Duplicator available. <a href="#">View version 1.5.7 details</a> or <a href="/wp-admin/update.php?action=upgrade-plugin&#038;plugin=duplicator%2Fduplicator.php">update now</a>.</p>
    </div>
  </td>
</tr>

<tr class="active" data-plugin="akismet/akismet.php" data-slug="akismet">
  <th scope="row" class="check-column"><input type="checkbox" name="checked[]" value="akismet/akismet.php" /></th>
  <td class="plugin-title column-primary">
    <strong>Akismet Anti-spam</strong>
    <div class="row-actions visible">
      <span class="deactivate"><a href="/wp-admin/plugins.php?action=deactivate&#038;plugin=akismet%2Fakismet.php">Deactivate</a></span>
      <span class="settings"><a href="/wp-admin/admin.php?page=akismet-key-config">Settings</a></span>
      <span class="last"><a href="/wp-admin/plugin-editor.php?plugin=akismet%2Fakismet.php">Edit</a></span>
    </div>
  </td>
  <td class="column-description desc">
    <div class="plugin-description"><p>Used by millions, Akismet is quite possibly the best way in the world to protect your blog from spam. Your site is fully configured and being protected.</p></div>
    <div class="plugin-version-author-uri">Version 5.0.2 | By <a href="#">Automattic</a> | <a href="#">View details</a></div>
  </td>
  <td class="column-auto-updates">Auto-updates disabled</td>
</tr>

<tr class="active" data-plugin="wordpress-seo/wp-seo.php" data-slug="wordpress-seo">
  <th scope="row" class="check-column"><input type="checkbox" name="checked[]" value="wordpress-seo/wp-seo.php" /></th>
  <td class="plugin-title column-primary">
    <strong>Yoast SEO</strong>
    <div class="row-actions visible">
      <span class="deactivate"><a href="/wp-admin/plugins.php?action=deactivate&#038;plugin=wordpress-seo%2Fwp-seo.php">Deactivate</a></span>
      <span class="settings"><a href="/wp-admin/admin.php?page=wpseo_dashboard">Settings</a></span>
      <span class="last"><a href="/wp-admin/plugin-editor.php?plugin=wordpress-seo%2Fwp-seo.php">Edit</a></span>
    </div>
  </td>
  <td class="column-description desc">
    <div class="plugin-description"><p>The first true all-in-one SEO solution for WordPress, including on-page content analysis, XML sitemaps and much more.</p></div>
    <div class="plugin-version-author-uri">Version 20.8 | By <a href="#">Team Yoast</a> | <a href="#">View details</a></div>
  </td>
  <td class="column-auto-updates">Auto-updates disabled</td>
</tr>

<tr class="active" data-plugin="wp-super-cache/wp-cache.php" data-slug="wp-super-cache">
  <th scope="row" class="check-column"><input type="checkbox" name="checked[]" value="wp-super-cache/wp-cache.php" /></th>
  <td class="plugin-title column-primary">
    <strong>WP Super Cache</strong>
    <div class="row-actions visible">
      <span class="deactivate"><a href="/wp-admin/plugins.php?action=deactivate&#038;plugin=wp-super-cache%2Fwp-cache.php">Deactivate</a></span>
      <span class="settings"><a href="/wp-admin/options-general.php?page=wpsupercache">Settings</a></span>
      <span class="last"><a href="/wp-admin/plugin-editor.php?plugin=wp-super-cache%2Fwp-cache.php">Edit</a></span>
    </div>
  </td>
  <td class="column-description desc">
    <div class="plugin-description"><p>A very fast caching engine for WordPress that produces static html files.</p></div>
    <div class="plugin-version-author-uri">Version 1.9.4 | By <a href="#">Automattic</a> | <a href="#">View details</a></div>
  </td>
  <td class="column-auto-updates">Auto-updates disabled</td>
</tr>

<tr class="active" data-plugin="wps-hide-login/wps-hide-login.php" data-slug="wps-hide-login">
  <th scope="row" class="check-column"><input type="checkbox" name="checked[]" value="wps-hide-login/wps-hide-login.php" /></th>
  <td class="plugin-title column-primary">
    <strong>WPS Hide Login</strong>
    <div class="row-actions visible">
      <span class="deactivate"><a href="/wp-admin/plugins.php?action=deactivate&#038;plugin=wps-hide-login%2Fwps-hide-login.php">Deactivate</a></span>
      <span class="settings"><a href="/wp-admin/options-general.php?page=wps-hide-login">Settings</a></span>
      <span class="last"><a href="/wp-admin/plugin-editor.php?plugin=wps-hide-login%2Fwps-hide-login.php">Edit</a></span>
    </div>
  </td>
  <td class="column-description desc">
    <div class="plugin-description"><p>Change wp-login.php to anything you want. Lightweight, does not literally rename or change files in core, nor does it add rewrite rules.</p></div>
    <div class="plugin-version-author-uri">Version 1.9.1 | By <a href="#">WPServeur</a> | <a href="#">View details</a></div>
  </td>
  <td class="column-auto-updates">Auto-updates disabled</td>
</tr>

<tr class="inactive" data-plugin="hello.php" data-slug="hello-dolly">
  <th scope="row" class="check-column"><input type="checkbox" name="checked[]" value="hello.php" /></th>
  <td class="plugin-title column-primary">
    <strong>Hello Dolly</strong>
    <div class="row-actions visible">
      <span class="activate"><a href="/wp-admin/plugins.php?action=activate&#038;plugin=hello.php">Activate</a></span>
      <span class="delete"><a href="/wp-admin/plugins.php?action=delete-selected&#038;checked%5B0%5D=hello.php">Delete</a></span>
      <span class="last"><a href="/wp-admin/plugin-editor.php?plugin=hello.php">Edit</a></span>
    </div>
  </td>
  <td class="column-description desc inactive-plugin-desc">
    <div class="plugin-description"><p>This is not just a plugin, it symbolizes the hope and enthusiasm of an entire generation summed up in two words sung most famously by Louis Armstrong.</p></div>
    <div class="plugin-version-author-uri">Version 1.7.2 | By <a href="#">Matt Mullenweg</a> | <a href="#">View details</a></div>
  </td>
  <td class="column-auto-updates">Auto-updates disabled</td>
</tr>

<tr class="inactive" data-plugin="wp-maintenance-mode/wp-maintenance-mode.php" data-slug="wp-maintenance-mode">
  <th scope="row" class="check-column"><input type="checkbox" name="checked[]" value="wp-maintenance-mode/wp-maintenance-mode.php" /></th>
  <td class="plugin-title column-primary">
    <strong>WP Maintenance Mode</strong>
    <div class="row-actions visible">
      <span class="activate"><a href="/wp-admin/plugins.php?action=activate&#038;plugin=wp-maintenance-mode%2Fwp-maintenance-mode.php">Activate</a></span>
      <span class="delete"><a href="/wp-admin/plugins.php?action=delete-selected&#038;checked%5B0%5D=wp-maintenance-mode%2Fwp-maintenance-mode.php">Delete</a></span>
      <span class="last"><a href="/wp-admin/plugin-editor.php?plugin=wp-maintenance-mode%2Fwp-maintenance-mode.php">Edit</a></span>
    </div>
  </td>
  <td class="column-description desc inactive-plugin-desc">
    <div class="plugin-description"><p>Adds a splash page to your site that lets visitors know your site is down for maintenance.</p></div>
    <div class="plugin-version-author-uri">Version 2.6.2 | By <a href="#">Designmodo</a> | <a href="#">View details</a></div>
  </td>
  <td class="column-auto-updates">Auto-updates disabled</td>
</tr>

</tbody>
<tfoot>
<tr>
  <td class="manage-column column-cb check-column"><input id="cb-select-all-2" type="checkbox" /></td>
  <th scope="col" class="manage-column column-name column-primary">Plugin</th>
  <th scope="col" class="manage-column column-description">Description</th>
  <th scope="col" class="manage-column column-auto-updates">Automatic Updates</th>
</tr>
</tfoot>
</table>

<div class="tablenav bottom">
  <div class="actions bulkactions">
    <select name="action2" id="bulk-action-selector-bottom">
      <option value="-1">Bulk actions</option>
      <option value="activate-selected">Activate</option>
      <option value="deactivate-selected">Deactivate</option>
      <option value="update-selected">Update</option>
      <option value="delete-selected">Delete</option>
    </select>
    <input type="submit" id="doaction2" class="button action" value="Apply" />
  </div>
  <div class="tablenav-pages one-page"><span class="displaying-num">9 items</span></div>
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
