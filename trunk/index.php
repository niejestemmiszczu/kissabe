<?php
require_once("functions.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml"><head>
<meta http-equiv="content-type" content="text/html; charset=utf-8">

<title>kissa.be! - web adresi kissaltma hedesi</title>
<link rel="stylesheet" type="text/css" href="style.css" media="screen">
<link rel="shortcut icon" href="favicon.png">
<meta name="keywords" content="URL shortener,shorten,URL,link,smaller,shorten web address">
<meta name="description" content="kissa.be! - web adresi kissaltma hedesi">
<script language="JavaScript" type="text/javascript">
<!--
function checkform (form)
{
	if (form.url.length == 0)
	{
		alert ("Lütfen kıssaltmak istediğiniz web adresini metin kutusuna yazın yada yapıştırın.");
		form.url.focus();
		return false;
	}

	var myMatch = form.url.value.search(/\./);
	if(myMatch == -1)
	{
		alert ("Lütfen geçerli bir web adresi giriniz!.");
        form.url.focus();
        return false;
	}
}

function focusbox() {
	el = document.getElementById('url');
	el.focus();
}

window.onload = focusbox;

//-->
</script>
</head><body>
<div id="frontpagecontainer">
</div>
<div id="frontpage">
<img src="kissa-logo.png" alt="kissa.be! logo">
<div id="menutext"><a href="description.php">talimatnane</a><a href="privacy.php">gizlilik</a><a href="contact.php">iletişim</a></div>
<form action="create.php" method="post" onsubmit="return checkform(this);">
<input class="textbox" maxlength="2000" size="55" id="url" name="url" value="" type="text">
<input class="button" value="bu adresi kıssalt bakim! " type="submit">
</form>

<div id="urlcount"><b><?= get_url_count(true) ?></b> URL başarıyla kıssaltıldı<br/></div>
<br/>
</div>
<br/>

<div id="footer">
<p><a href="http://www.dahius.com/">© 2008 - Dahius</a></p>
</div>


