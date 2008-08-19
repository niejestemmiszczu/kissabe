<?php

	require_once("functions.php");

	global $page_title;
	$page_title = "Kıssalaştırıcı";

	include("header.php");

	$url = $_REQUEST["url"];
	$url_info = save_url($url);

	switch ($url_info[0])
	{
		case "SUCCESS":
			$code = encode_url_id($url_info[1]["id"]);
			$long_url = $url_info[1]["url"];

			$short_url = "http://kissa.be/$code";
			$short_url_size = strlen($short_url);
			$long_url_size = strlen($long_url);

			echo "<h2>$short_url</h2>";
			echo '<a href="javascript:///" id="copied" onclick="cSn(\''.$short_url.'\', \'copied\');">Panoya Kopyala</a><br/>';
			echo '<div id="blip" style="left: 339px; top: 198px; display:none; padding:7px 0 7px 0; font-weight:bold;">Panoya kopyalandı ve yapıştırılmaya hazır! ;-)</div><hr/>';

			echo "Kıssaltılmış web adresiniz <a href='$short_url'>$short_url</a> <a href='$short_url' ".
					"style='font-size:8px;' target='$code'>(yeni pencerede aç)</a> ".
					"($short_url_size) karakter tutmuştur.<p/>".
					"Orjinal web adresiniz <a href='$long_url'>$long_url</a> ".
					"<a href='$long_url' style='font-size:8px' target='$code-orj'>(yeni pencerede aç)</a> ".
					"($long_url_size) karakter tutmuştur.<p/>";
			echo '<div id="flashcopier"><embed src="flashcopier.swf" FlashVars="clipboard='.$short_url.
						'" width="0" height="0" type="application/x-shockwave-flash"></embed></div>'; 

			$per_size = 100 - ceil($short_url_size * 100 / $long_url_size);
			$diff_size = $long_url_size - $short_url_size;

			if ($diff_size > 0)
				echo  "Sizin adresinizi <b>%$per_size</b> oranında, ($diff_size) karakter küçülttük :-)";
			else
				echo "Sizin adresinizi kıssalaştıramadığımız için üzgünüz :-(";
			break;
	case "URL_IN_BLACKLIST":
			$domain = get_domain($url);
			echo "<p/><b>$domain</b> adresi karalistede olduğu için listeye eklenmemiştir !</p>";
			break;
		case "URL_NOT_VALID":
			echo "Bu adres geçerli değil !";
			break;
	}

include("footer.php");
?>


