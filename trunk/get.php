<?php

	require_once("functions.php");

	if (isset($_GET["code"]) == false) return;

	$code = $_GET["code"];

	$parts = split("/", $code);
	$code = $parts[0];

	$is_preview = false;
	if (strchr($code, "-") != "")
	{
		$is_preview = true;
		$code = str_replace("-", "", $code);
	}

	$url_info = get_url_info_by_code($code);

	if (empty($url_info))
	{
		header("Location: index.php");
		exit;
	}

	if ($is_preview == false)
	{
		add_visitor_log($code);
		header("Location: ".$url_info["url"]);
		exit;
	}
	else
	{
		$short_url = "http://kissa.be/".encode_url_id($url_info["id"]);
		$long_url = $url_info["url"];
		$visitor_size = get_visitor_size($url_info["id"]);
		$uniq_visitor_size = get_uniq_visitor_size($url_info["id"]);
	}

	require_once("functions.php");

	global $page_title;
	$page_title = "Gizlilik Bildirgesi";

	include("header.php");
?>
<p>
	<b>Sizin orjinal adresiniz:</b><p/>
	<i><?= $long_url; ?></i><p/>
	<a href="<?= $short_url; ?>">Bu web adresine git bakim!</a><br/>&nbsp;<br/>

	<b>İstatistik Dökümü</b><p/>
	<i>Toplam Ziyaret : <b><?= $visitor_size; ?></b></i><br/>
	<i>Toplam Tekil Ziyaret : <b><?= $uniq_visitor_size; ?></b></i><br/>
</p>

<? 	include("footer.php"); ?>

