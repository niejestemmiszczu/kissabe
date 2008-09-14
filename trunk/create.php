<?php

	$url = $_REQUEST["content_data"];

	require_once("functions.php");

	global $page_title;
	$page_title = T_("creator");

	include("header.php");

	if (isset($_GET["url"]))
	{
		$data = $_REQUEST["url"];
		$status = 1;
	}
	else {
		$data = $_REQUEST["content_data"];
		$status = $_REQUEST["status"];
	}
	
	$url_info = save_content($data, $status);

	switch ($url_info[0])
	{
		case "SUCCESS":
			$code = encode_url_id($url_info[1]["id"]);
			$long_url = $url_info[1]["data"];

			$short_url = "http://kissa.be/$code";
			$short_url_size = strlen($short_url);
			$long_url_size = strlen($long_url);

			echo "<h2>$short_url</h2>";
			echo '<a href="javascript:///" id="copied" onclick="cSn(\''.$short_url.'\', \'copied\');">'.T_("Copy to Clipboard").'</a><br/>';			

			echo '<div id="blip" style="left: 339px; top: 198px; display:none; padding:7px 0 7px 0; font-weight:bold;">'.
				T_("it's copied to clipboard ;-)").'</div>';

			switch($status)
			{
				case 1:
				case 2:
					echo "<hr/>";
					echo sprintf(T_("Your shortened URL is %s [%s] (%d) characters."), 
							"<a href='$short_url'>$short_url</a>",
							"<a href='$short_url' style='font-size:8px;' target='$code'>(".T_("open in new window").")</a>",
							$short_url_size
						 );
					echo "<p/>";
					echo sprintf(T_("The original URL was %s [%s] (%d) characters."), 
							"<a href='$long_url'>$long_url</a>",
							"<a href='$long_url' style='font-size:8px;' target='$code-orj'>(".T_("open in new window").")</a>",
							$long_url_size
						 );
					echo "<p/>";

					$per_size = 100 - ceil($short_url_size * 100 / $long_url_size);
					$diff_size = $long_url_size - $short_url_size;
	
					if ($diff_size > 0)
						echo sprintf(T_("We made your URL <b>%d%%</b> (%d characters) shorter!"), $per_size, $diff_size);
					else
						echo T_("Sorry! we didn't make your URL!");

					$user_friendly_url = T_("user-friendly-url-explanation-area");
					echo "<p/>".T_("You can write explanation in the user friendly URL.")."<br/>".
						T_("Example")."; <b>$short_url/$user_friendly_url</b><p/>";
					break;
				case 3:
					$data = $url_info[1]["data"];
					if (!has_html($data)) {
        	        	$data = str_replace("\n", "<br/>", $data);
					}

					echo "<p style='padding:20px;'>$data</p>";
					break;
				case 4:
					$_SESSION["image_key"] = uniqid();
					echo "<p style='padding:20px;'><img src='imagehandler.php?id=$code' /></p>";
					break;

			}

			echo '<div id="flashcopier"><embed src="flashcopier.swf" FlashVars="clipboard='.$short_url.
				'" width="0" height="0" type="application/x-shockwave-flash"></embed></div>'; 

			break;
		case "URL_IN_BLACKLIST":
			$domain = get_domain($url);
			echo sprintf(T_("<p/><b>%s</b> in the blacklist!</p>"), $url);
			break;
		case "URL_NOT_VALID":
			echo T_("Invalid your URL address !");
			break;
		case "PARAMETER_NOT_VALID":
			echo T_("Please, check your parameters!");
			break;

	}

include("footer.php");
?>


