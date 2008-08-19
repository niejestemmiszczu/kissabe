<?php

	require_once("config.php");
	require_once("adodb/adodb.inc.php");

	function get_connection()
	{
		return NewADOConnection(CONNECTION_STRING);
	}

	function save_url($url_addr)
	{
		if (validate_url($url_addr) == false) 
		{
			return Array("URL_NOT_VALID", ""); 
		}

		$url = fix_url($url_addr);
		$domain = get_domain($url);

		if (has_blacklist($domain)) 
		{
			return Array("URL_IN_BLACKLIST", ""); 
		}

		if (has_url($url))
		{
			$url_info = get_url_info($url); 
			return Array("SUCCESS", $url_info); 
		}

		$db = get_connection();
		$sql = sprintf("insert into `url`(`id`, `url`, `domain`, `created`) values('', '%s', '%s', '%s');", 
					$url,
					$domain,
					date("Y-m-d H:i:s")
		);

		$db->BeginTrans();
		$ok = $db->Execute($sql);
		if (!$ok) { 
			echo $db->ErrorMsg();  
			$db->RollbackTrans();
		}
		$db->CompleteTrans();

		$url_info = get_url_info($url);
		return Array("SUCCESS", $url_info);
	}

	function get_url_count($beaty=false)
	{
		$db = get_connection();
		$ok = $db->GetOne("select count(id) from url");
		if (!$ok) { echo $db->ErrorMsg();}

		if ($beaty == true)
		{
    		    return number_format($ok, 0, ',', '');
		}
		return $ok;
	}
	
	function get_url_info_by_code($code)
	{
		$id = decode_url_id($code);
		$db = get_connection();
		$ok = $db->GetRow("select id, url, domain from url where id='{$id}'");
		if (!$ok) { echo $db->ErrorMsg();}
		
		return $ok;
	}

	function get_url_info($url)
	{
		$db = get_connection();
		$ok = $db->GetRow("select id, url, domain from url where url='{$url}'");
		if (!$ok) { echo $db->ErrorMsg();}
		
		return $ok;
	}

	function has_url($url)
	{
		$db = get_connection();
		$val = $db->GetOne("select count(*) from url where url='{$url}'");

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

	function fix_url($url_addr)
	{
		$url_addr = str_replace("mailto:", "", $url_addr);
		if (is_valid_email($url_addr))
		{
			return "mailto:$url_addr";
		}
		else
		{
			$sch = parse_url($url_addr, PHP_URL_SCHEME);

			return ((strlen($sch)==0) ? "http://" : "") . $url_addr;
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


	function validate_url($url_addr)
	{
		if (isset($url_addr) == false || strlen($url_addr) == 0)
		{
			return false;
		}
		
		if (strchr($url_addr, ".") == "")
		{
			return false;
		}

		return true;
	}

/*
	function get_url_id()
	{
 		$code_size = get_code_size();
		$url_id = create_uid($code_size);

		$counter = 0;
		while(has_url_id($url_id))
		{
			if ($counter == MAX_URL_ID_GENERATE_SIZE)
			{
				$code_size++; 
				set_code_size($code_size);
			}

			$url_id = create_uid($code_size);
			$counter++;
		}

		return $url_id;
	}
*/

	function has_url_id($url_id)
	{
		$db = get_connection();
		$val = $db->GetOne("select count(*) from url where `id`='$url_id'");
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

/*
	function create_uid($random_id_length)
	{
		//generate a random id encrypt it and store it in $rnd_id 
		$rnd_id = crypt(uniqid(rand(),1)); 

		//to remove any slashes that might have come 
		$rnd_id = strip_tags(stripslashes($rnd_id)); 

		//Removing any . or / and reversing the string 
		$rnd_id = str_replace(".", "", $rnd_id); 
		$rnd_id = strrev(str_replace("/", "", $rnd_id)); 

		//finally I take the first 10 characters from the $rnd_id 
		return substr($rnd_id,0,$random_id_length); 
	}
*/

	function decode_url_id($code)
	{	
		$scheme = "0123456789abcdefghijklmnoprstuqwxvyzABCDEFGHIJKLMNOPRSTUQWXVYZ";
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
		$scheme = "0123456789abcdefghijklmnoprstuqwxvyzABCDEFGHIJKLMNOPRSTUQWXVYZ";
		$scheme_size = strlen($scheme);
		
		if ($number > $scheme_size)
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




?>
