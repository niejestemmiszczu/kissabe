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

	$url_info = get_content_info_by_code($code);

	if (empty($url_info))
	{
		header("Location: index.php");
		exit;
	}

	$status = $url_info["status"];

	if ($is_preview == false)
	{
		add_visitor_log($code);
		if (in_array($status, Array(1, 2)))
		{
			header("Location: ".$url_info["data"]);
			exit;
		}
	}
	else
	{
		$short_url = "http://kissa.be/".encode_url_id($url_info["id"]);
		$long_url = $url_info["data"];
		$visitor_size = get_visitor_size($url_info["id"]);
		$uniq_visitor_size = get_uniq_visitor_size($url_info["id"]);
	}

	require_once("functions.php");

	include("header.php");

	switch($status)
	{
		case 1:
		case 2:
?>
			<p><b><?=T_("Your original URL")?></b><p/>
			<i><?= $long_url; ?></i><p/>
			<a href="<?= $short_url; ?>"><?=T_("Go this page")?></a><br/>&nbsp;<br/>
<?
			break;
		case 3:
			$data = $url_info["data"];
			if (!has_html($data)) {
              	$data = str_replace("\n", "<br/>", $data);
			}

			echo "<p style='padding:30px;'>$data</p>";
			break;
		case 4:
			session_start();

			$_SESSION["image_key"] = "sdf";
			echo "<p style='padding:20px;'><img src='imagehandler.php?id=$code' /></p>";
			break;
	}

	if ($is_preview == true)
	{
?>
		<p/><b><?=T_("Statistic")?></b><p/>
		<i><?=T_("Page View")?> : <b><?= $visitor_size; ?></b></i><br/>
		<i><?=T_("Uniq Page View")?> : <b><?= $uniq_visitor_size; ?></b></i><br/>
		</p>
<? 	
	}

	include("footer.php"); 
?>

