<?php

global $page_title;

if ($page_title != "")
	$page_title = " | $page_title";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">

<title>kissa.be! - web adresi kissaltma hedesi <?= $page_title; ?></title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen">
<link rel="shortcut icon" href="favicon.png">
<meta name="keywords" content="URL shortener,shorten,URL,link,smaller,shorten web address">
<meta name="description" content="kissa.be! - web adresi kissaltma hedesi">
<script type="text/javascript" src="kissa.js"></script>
</head><body>

<div id="menutop">
<a href="index.php"><img src="kissa-logo-mini.png" alt="kissa.be! logo"></a>
<div id="menutext">
<a href="index.php">anasayfa</a><a href="description.php">talimatnane</a><a href="privacy.php">gizlilik</a><a href="contact.php">iletişim</a>
</div>
</div>
<!-- Piwik -->
<div style="display:none">
<a href="http://piwik.org" title="Website analytics" onclick="window.open(this.href);return(false);">
<script type="text/javascript">
var pkBaseURL = (("https:" == document.location.protocol) ? "https://analytics.netology.org/" : "http://analytics.netology.org/");
document.write(unescape("%3Cscript src='" + pkBaseURL + "piwik.js' type='text/javascript'%3E%3C/script%3E"));
</script>
<script type="text/javascript">
<!--
piwik_action_name = '';
piwik_idsite = 1;
piwik_url = pkBaseURL + "piwik.php";
piwik_log(piwik_action_name, piwik_idsite, piwik_url);
//-->
</script><object>
<noscript><p>Website analytics <img src="http://analytics.netology.org/piwik.php" style="border:0" alt="piwik"/></p>
</noscript></object></a>
</div>
<!-- /Piwik --> 

<div id="main">

