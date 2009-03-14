<?php

	require_once("config.php");
	require_once("adodb/adodb.inc.php");
	require_once("php-gettext/gettext.inc");

	function get_connection()
	{
		return NewADOConnection(CONNECTION_STRING);
	}

	function save_content($content_data, $status=1)
	{
		$permitted_status = Array(1,2,3,4);
		if (!in_array($status, $permitted_status))
		{
			return Array("PARAMETER_NOT_VALID", ""); 
		}

		if (in_array($status, Array(1,2,3)) && (has_xss($content_data) || strlen($content_data) == 0))
		{
			return Array("PARAMETER_NOT_VALID", ""); 
		}

		if (in_array($status, Array(4)) && 
				(in_array($_FILES["content_data"]["type"], Array("image/png","image/jpeg")) == false)) 
				// file max upload limit = 300kb and png or jpeg
		{
			return Array("PARAMETER_NOT_VALID", ""); 
		}


		if (in_array($status, Array(1,2)) && validate_url($content_data) == false)
		{
			return Array("URL_NOT_VALID", ""); 
		}

		$fixed = fix_content($content_data, $status);

		if (in_array($fixed["status"], Array(1,2)))
		{
			$domain = get_domain($fixed["content"]);

			if (has_blacklist($domain)) 
			{
				return Array("URL_IN_BLACKLIST", ""); 
			}	
		}
		
		if (has_content($fixed["content"]))
		{
			$url_info = get_content_info($fixed["content"]); 
			return Array("SUCCESS", $url_info); 
		}

		if (in_array($fixed["status"], Array(4)))
		{
			file_uploader($_FILES["content_data"], $fixed["content"], $save_as="png", $v_pos="center", $h_pos="center", $wm_size=1);
		}

		$db = get_connection();
		$sql = sprintf("insert into content(id, data, domain, created_on, created_by, status) values('', '%s', '%s', '%s', '%s', '%s');", 
					si($fixed["content"]),
					$domain,
					date("Y-m-d H:i:s"),
					get_ip(),
					si($fixed["status"])
		);

//		mysql_query($sql);

		$db->BeginTrans();
		$ok = $db->Execute($sql);
		if (!$ok) { 
			echo $db->ErrorMsg();  
			$db->RollbackTrans();
		}
		$db->CompleteTrans();
 
		$url_info = get_content_info($fixed["content"]);
		return Array("SUCCESS", $url_info);
	}

	function get_image_count($beaty=false)
	{
		$db = get_connection();
		$ok = $db->GetOne("select count(id) from content where status=4");
		if (!$ok) { echo $db->ErrorMsg();}

		if ($beaty == true)
		{
    		return number_format($ok, 0, ',', '');
		}
		return $ok;
	}


	function get_mail_count($beaty=false)
	{
		$db = get_connection();
		$ok = $db->GetOne("select count(id) from content where status=2");
		if (!$ok) { echo $db->ErrorMsg();}

		if ($beaty == true)
		{
    		return number_format($ok, 0, ',', '');
		}
		return $ok;
	}

	function get_text_count($beaty=false)
	{
		$db = get_connection();
		$ok = $db->GetOne("select count(id) from content where status=3");
		if (!$ok) { echo $db->ErrorMsg();}

		if ($beaty == true)
		{
    	    return number_format($ok, 0, ',', '');
		}
		return $ok;
	}

	function get_url_count($beaty=false)
	{
		$db = get_connection();
		$ok = $db->GetOne("select count(id) from content where status=1");
		if (!$ok) { echo $db->ErrorMsg();}

		if ($beaty == true)
		{
    		    return number_format($ok, 0, ',', '');
		}
		return $ok;
	}
	
	function get_content_info_by_code($code)
	{
		$id = decode_url_id($code);
		$db = get_connection();
		$ok = $db->GetRow("select id, data, domain, status from content where id='{$id}'");
		if (!$ok) { echo $db->ErrorMsg();}
		
		return $ok;
	}

	function get_content_info($content_data)
	{
		$db = get_connection();
		$ok = $db->GetRow("select id, data, domain, status from content where data='".si($content_data)."'");
		if (!$ok) { echo $db->ErrorMsg();}
		
		return $ok;
	}

	function has_content($content_data)
	{
		$db = get_connection();
		$val = $db->GetOne("select count(*) from content where data='{$content_data}'");

		return ($val > 0);
	}

	function has_blacklist($domain)
	{
		$db = get_connection();
		$val = $db->GetOne("select count(*) from blacklist where domain like '%{$domain}%'");

		return ($val > 0);
	}
	
	function get_domain($url)
	{
		$url = ltrim($url, "mailto:");
		if (is_valid_email($url))
		{
			list($uname, $url) = split("@", $url);
			return $url;
		}
		return str_replace("www.", "", parse_url($url, PHP_URL_HOST));
	}	

	function fix_content($content_data, $status)
	{
		$content_data = str_replace("mailto:", "", $content_data);
		if (is_valid_email($content_data))
		{
			return Array (content=>"mailto:$content_data", status=>2);
		}
		else if ($status == 3)
		{
			return Array(content=>$content_data, status=>3);
		}
		else if ($status == 4)
		{
			$original=$_FILES['content_data']['tmp_name'];
			$size=$_FILES['content_data']['size'];
			$file = md5_file($original)."-".$size;
			$data = "{$file}.jpeg";

			return Array(content=>$data, status=>4);
		}
		else
		{
			$sch = parse_url($content_data, PHP_URL_SCHEME);

			return Array(content=>((strlen($sch)==0) ? "http://" : "") . $content_data, status=>1);
		}
	}

	function is_valid_email($addr)
	{
		$myReg = "/^([\w\-\.]+)@((\[([0-9]{1,3}\.){3}[0-9]{1,3}\])|(([\w\-]+\.)+)([a-zA-Z]{2,4}))$/";
		if(preg_match($myReg, $addr))
			return true;
		else
			return false;
	}


	function validate_url($content_data)
	{
		if (isset($content_data) == false || strlen($content_data) == 0)
		{
			return false;
		}
		
		if (strchr($content_data, ".") == "")
		{
			return false;
		}

		return true;
	}

	function has_url_id($url_id)
	{
		$db = get_connection();
		$val = $db->GetOne("select count(*) from content where `id`='$url_id'");
		if (!$val) echo $db->ErrorMsg();

		return ($val > 0) ? true : false;
	}

	function get_code_size()
	{
		$db = get_connection();
		$val = $db->GetOne("select `value` from config where `key`='CODE_SIZE'");
		if (!$val) echo $db->ErrorMsg();

		return $val;
	}

	function get_visitor_size($url_id)
	{
		$db = get_connection();
		$val = $db->GetOne("select COUNT(*) from visitor where `url_id`='$url_id'");
		if (!$val) echo $db->ErrorMsg();

		return $val;
	}

	function get_uniq_visitor_size($url_id)
	{
		$db = get_connection();
		$val = $db->GetOne("select COUNT(DISTINCT created_by) from visitor where `url_id`='$url_id'");
		if (!$val) echo $db->ErrorMsg();

		return $val;
	}

	function set_code_size($code_size)
	{
		$db = get_connection();
		$ok = $db->Execute("update config set `value`='{$code_size}'where `key`='CODE_SIZE'");
		if (!$ok) echo $db->ErrorMsg();

	}

	function add_visitor_log($code)
	{
		$url_id = decode_url_id($code);
		$db = get_connection();
		$date = date("Y-m-d H:i:s");
		$http_referer = $_SERVER['HTTP_REFERER'];
		$ip = get_ip();
		$ok = $db->Execute("insert into visitor(url_id, http_referer, created_on, created_by) values('$url_id', '$http_referer', '$date', '$ip')");
		if (!$ok) echo $db->ErrorMsg();

	}

	function get_ip()
	{
        // Get some server/environment variables values
        if (empty($REMOTE_ADDR)) {
            if (!empty($_SERVER) && isset($_SERVER['REMOTE_ADDR'])) {
                $REMOTE_ADDR = $_SERVER['REMOTE_ADDR'];
            }
            else if (!empty($_ENV) && isset($_ENV['REMOTE_ADDR'])) {
                $REMOTE_ADDR = $_ENV['REMOTE_ADDR'];
            }
            else if (@getenv('REMOTE_ADDR')) {
                $REMOTE_ADDR = getenv('REMOTE_ADDR');
            }
        } // end if
        if (empty($HTTP_X_FORWARDED_FOR)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
                $HTTP_X_FORWARDED_FOR = $_SERVER['HTTP_X_FORWARDED_FOR'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED_FOR'])) {
                $HTTP_X_FORWARDED_FOR = $_ENV['HTTP_X_FORWARDED_FOR'];
            }
            else if (@getenv('HTTP_X_FORWARDED_FOR')) {
                $HTTP_X_FORWARDED_FOR = getenv('HTTP_X_FORWARDED_FOR');
            }
        } // end if
        if (empty($HTTP_X_FORWARDED)) {
            if (!empty($_SERVER) && isset($_SERVER['HTTP_X_FORWARDED'])) {
                $HTTP_X_FORWARDED = $_SERVER['HTTP_X_FORWARDED'];
            }
            else if (!empty($_ENV) && isset($_ENV['HTTP_X_FORWARDED'])) {
                $HTTP_X_FORWARDED = $_ENV['HTTP_X_FORWARDED'];
            }
            else if (@getenv('HTTP_X_FORWARDED')) {
                $HTTP_X_FORWARDED = getenv('HTTP_X_FORWARDED');
            }
        } // end if


        $proxy_ip     = '';
        if (!empty($HTTP_X_FORWARDED_FOR)) {
            $proxy_ip = $HTTP_X_FORWARDED_FOR;
        } else if (!empty($HTTP_X_FORWARDED)) {
            $proxy_ip = $HTTP_X_FORWARDED;
        } else if (!empty($HTTP_FORWARDED_FOR)) {
            $proxy_ip = $HTTP_FORWARDED_FOR;
        } else if (!empty($HTTP_FORWARDED)) {
            $proxy_ip = $HTTP_FORWARDED;
        } else if (!empty($HTTP_VIA)) {
            $proxy_ip = $HTTP_VIA;
        } else if (!empty($HTTP_X_COMING_FROM)) {
            $proxy_ip = $HTTP_X_COMING_FROM;
        } else if (!empty($HTTP_COMING_FROM)) {
            $proxy_ip = $HTTP_COMING_FROM;
        } // end if... else if...
        
        return (($proxy_ip == '') ? $REMOTE_ADDR : $proxy_ip);
       
	
	}

	function decode_url_id($code)
	{	
		$scheme = "abcdefghijklmnoprstuqwxvyz0123456789ABCDEFGHIJKLMNOPRSTQWXUVYZ";
		$scheme_size = strlen($scheme);

		$number  = 0;
		$code_size = strlen($code);
		$code = strrev($code);
		for($i = 0; $i < $code_size; $i++)
		{
			$digit_value = strpos($scheme, $code[$i]);

			$number += ($digit_value * pow($scheme_size, $i));
		}

		return $number;
	}

	function encode_url_id($number, $code="")
	{
		$scheme = "abcdefghijklmnoprstuqwxvyz0123456789ABCDEFGHIJKLMNOPRSTQWXUVYZ";
		$scheme_size = strlen($scheme);
		
		if ($number >= $scheme_size)
		{
			$c = $number % $scheme_size;
			$code .= $scheme[$c];
			$number = floor($number / $scheme_size);
		
			return encode_url_id($number, $code);
		}
		else 
		{
			$code .= $scheme[$number];
			$code = strrev($code);
		}

		return $code;
	}

	// cross site scripting
	function has_xss($content)
	{
		$seclist = Array("<script", "<iframe", "</script", "</iframe");
		foreach ($seclist as $val)
		{
			if (stristr($content, $val))
			{
				return true;
			}
		}

		return false;
	}

	//sql enjection
	function si($content)
	{
		if (get_magic_quotes_gpc()) {
			$content = stripslashes($content);
		}
		return  mysql_real_escape_string($content);
	}

	// has html
	function has_html($content)
	{
		$html_list = Array("<a", "<div", "<p", "<br", "<span", "</");
		foreach ($html_list as $val)
		{
			if (stristr($content, $val))
			{
				return true;
			}
		}

		return false;
	}

	function file_uploader($image, $target_name)
	{
		$screen_resolution = 1280;

		$image_target=$image["tmp_name"];
		$image_dest=dirname(__FILE__).'/images/'.$target_name;
		$watermark_path=dirname(__FILE__).'/watermark.png';
		// Path the the requested file
		 $path = $image_target;
		
		// Load the requested image
		$image = imagecreatefromstring(file_get_contents($path));
		
		$w = imagesx($image);
		$h = imagesy($image);

		if ($w > $h && $w > $screen_resolution) 
		{
			$nw = $screen_resolution;
			$nh = $screen_resolution * $h / $w;
		}
		else if ($h > $w && $h > $screen_resolution)
		{
			$nh = $screen_resolution;
			$nw = $screen_resolution * $w / $h;
		}

		if ($nh > 0) // resizer
		{
			$thumb = imagecreatetruecolor($nw, $nh);
			imagecopyresized($thumb, $image, 0, 0, 0, 0, $nw, $nh, $w, $h);
			$res = imagejpeg($thumb, $image_dest);

			$image = imagecreatefromstring(file_get_contents($image_dest));
			$w = $nw;
			$h = $nh;
		}
	
		// Load the watermark image
		$watermark = imagecreatefrompng($watermark_path);
		$ww = imagesx($watermark);
		$wh = imagesy($watermark);

		// Merge watermark upon the original image
		imagecopy($image, $watermark, $w-$ww, $h-$wh, 0, 0, $ww, $wh);
		$res = imagejpeg($image, $image_dest);
	}
?>
