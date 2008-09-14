<?
	session_start();

	if (isset($_SESSION["image_key"]) == false)
	{
		die("SECURITY ERROR");
	}
	else
	{
		unset($_SESSION["image_key"]);
	}

	ob_start();

	require_once("functions.php");

	$code = $_GET["id"];
	$url_info = get_content_info_by_code($code);

	if (empty($url_info))
	{
		echo "not found";
		exit;
	}
	$filename = $url_info["data"];
	$file_path = dirname(__FILE__)."/images/uploads/$filename";

	$info = getimagesize($file_path);
	$image = imagecreatefromstring(file_get_contents($file_path));

	ob_clean();
	header("Content-type: {$info["mime"]}");
	imagejpeg($image);

?>
