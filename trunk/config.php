<?php
session_start();

define("CONNECTION_STRING", "mysql://user:pass@localhost/kissa?persist", true);
define("MAX_URL_ID_GENERATE_SIZE", 50);


define(PROJECT_DIR, realpath('./'));

define(LOCALE_DIR, PROJECT_DIR .'/locale');

require_once("php-gettext/gettext.inc");
define(DEFAULT_LOCALE, 'tr_TR');
$encoding = 'UTF-8';

// LANGUAGE SETTINGS...
$supported_locales = array('en_US', 'tr_TR');

global $locale;
$days = 1;
$cookie_time = time()+($days*24*60*60);

if(isset($_REQUEST['lang']))
{
	$locale = $_REQUEST["lang"];

	setcookie("CURRENT_LANGUAGE", $locale, $cookie_time);
}
else if (!isset($_COOKIE["CURRENT_LANGUAGE"]))
{
	$languages = Array("tr-TR", "en-US", "tr", "en");
	$client_language = $_SERVER["HTTP_ACCEPT_LANGUAGE"];

	$locale = "en_US";
	foreach($languages as $l)
	{
		if (stristr($client_language, $l))
		{
			$locale = str_replace('-', '_', $l);
			// fix turkish char;
			if ($locale == "tr") { $locale = "tr_TR"; }
			break;
		}
	}

	setcookie("CURRENT_LANGUAGE", $locale, $cookie_time);
}
else 
{
	$locale = $_COOKIE["CURRENT_LANGUAGE"];
}

$_SESSION["CURRENT_LANGUAGE"] = $locale;


// gettext setup
T_setlocale(LC_MESSAGES, $locale);
// Set the text domain as 'messages'
$domain = 'messages';
T_bindtextdomain($domain, LOCALE_DIR);
// bind_textdomain_codeset is supported only in PHP 4.2.0+
T_bind_textdomain_codeset($domain, $encoding);
T_textdomain($domain);

header("Content-type: text/html; charset=$encoding");
?>
