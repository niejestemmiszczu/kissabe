<?php

global $page_title;

header('Cache-Control: no-cache');
header('Pragma: no-cache');

if ($page_title != "")
	$page_title = " | $page_title";

?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">

<title><?= T_("kissa.be! - url shortener")?><?= $page_title; ?></title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen">
<link rel="shortcut icon" href="favicon.png">
<meta name="keywords" content="URL shortener,shorten,URL,link,smaller,shorten web address">
<meta name="description" content="<?= T_("kissa.be! - url shortener")?>">
<script type="text/javascript" src="kissa.js"></script>
</head><body>

<div id="menutop">
<a href="index.php"><img src="kissa-logo-mini.png" alt="kissa.be! logo"></a>
<div id="menutext">
	<a href="index.php"><?=T_("homepage")?></a>
	<a href="termofuse.php"><?=T_("term of use");?></a>
	<a href="privacy.php"><?=T_("privacy")?></a>
	<a href="contact.php"><?=T_("contact")?></a>
</div>
</div>
<div id="main">

