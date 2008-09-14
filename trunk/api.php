<?

	require_once("functions.php");

	$url = $_REQUEST["url"];
	$url_info = save_content($url, 1);

	switch ($url_info[0])
	{
		case "SUCCESS":
			$code = encode_url_id($url_info[1]["id"]);
			echo "http://kissa.be/$code";
			break;
		case "URL_IN_BLACKLIST":
			$domain = get_domain($url);
			die("Hata: $domain adresi karalistede oldugu için listeye eklenmemistir !");
			break;
		case "URL_NOT_VALID":
			die("Hata: Bu adres gecerli degil !");
			break;

	}
?>
