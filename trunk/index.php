<?php
	header('Cache-Control: no-cache');
	header('Pragma: no-cache');

	require_once("functions.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
	<meta http-equiv="content-type" content="text/html; charset=utf-8">

	<title><?= T_("kissa.be! - url shortener")?></title>
	<link rel="stylesheet" type="text/css" href="style.css" media="screen">

	<link rel="shortcut icon" href="favicon.png">
	<meta name="keywords" content="URL shortener,shorten,URL,link,smaller,shorten web address">
	<meta name="description" content="<?= T_("kissa.be! - url shortener")?>">

	<script type="text/javascript" src="jquery.js"></script>
	<script type="text/javascript" src="kissa.js"></script>

	<script language="JavaScript" type="text/javascript"> window.onload = start_page;</script>
</head>

<body>
	<div id="frontpagecontainer"></div>
	<div id="frontpage">
	<img src="kissa-logo.png" alt="kissa.be! logo">
	<div id="menutext">
		<a id="kissa_url" href="kissa_url.php" class="menuitem_selected"><?= T_("url link") ?></a>
		<a id="kissa_mail" href="kissa_mail.php" class="menuitem"><?= T_("email link") ?></a>
		<a id="kissa_text" href="kissa_text.php" class="menuitem"><?= T_("text link") ?></a>
		<a id="kissa_image" href="kissa_image.php" class="menuitem"><?= T_("image link") ?></a>

	</div>
	<div id="content"></div>

	<br/>
<center>
	<div id="footer">
		<div id="language-menu">
		<form id="current_lang" method="post">
			<?= T_("Languages");?>
			<select id="language" name="lang">
				<option value="en_US"<?=(($locale == "en_US") ? " selected" : "")?>>English</option>
				<option value="tr_TR"<?=(($locale == "tr_TR") ? " selected" : "")?>>Türkçe</option>
			</select>
			<br/>
			<a href="kissa.pot"><?= T_("Add New Language");?></a>

		</form>
		</div>

		<div id="footer-menu">
			<a href="termofuse.php"><?= T_("term of use")?></a> | 
			<a href="privacy.php"><?= T_("privacy")?></a> | 
			<a href="contact.php"><?= T_("contact")?></a> | 
			<a href="http://code.google.com/p/kissabe/" target="new"><?= T_("download")?></a>
		</div>

		<p><a href="http://www.dahius.com/">© 2008 - Dahius</a></p>
	</div>
</center>
	</div>
</body>
</html>
