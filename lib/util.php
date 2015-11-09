<?php

define("LOGPATH", "/var/log/httpd/sms_log");
date_default_timezone_set('Asia/Taipei');

/*
 *		check ACL
 */
function ACL()
{
	return;
	
	if (isset($_SESSION['check_flag']) && $_SESSION['check_flag'] == "itaiwan") {
		$flag = false;
		return;
	}
	
	$allow_ip[0] = "61.219.190";
	$allow_ip[1] = "124.199.83";
	$allow_ip[2] = "124.199.83";
	$allow_ip[3] = "210.69.230.13";
	
	$flag = true;
	
	foreach ($allow_ip as $check_ip) {
		
		if (strpos($_SERVER['REMOTE_ADDR'], $check_ip) !== false) {
			
			$flag = false;
			
		}
		
	}
	
	if ($flag) {
		header("Location: http://itaiwan.gov.tw/");
		exit(0);
	}
	
} //end of ACL

/*
 * send SMS
 */

function sms($phone, $input_msg, $type = 'add')
{
	
	$send_msg = mb_convert_encoding($input_msg, "BIG5", "UTF-8");
	
	// Get the IP address for the target host. 
	$address = "203.66.172.131";
	
	// Get the port for the Socket to AIR service. 
	$service_port = 8000;
	
	// Create a TCP/IP socket. 
	$socket = socket_create(AF_INET, SOCK_STREAM, SOL_TCP);
	
	if ($socket === false) {
		
		echo "socket_create() failed: reason: " . socket_strerror(socket_last_error()) . "\n";
		
	} else {
		
		//  echo "OK.\n";
		
	}
	
	//echo "Attempting to connect to '{$address}' on port {$service_port}...";
	
	$result = socket_connect($socket, $address, $service_port);
	
	if ($result === false) {
		
		echo "socket_connect() failed.\n Reason: ({$result}) " . socket_strerror(socket_last_error($socket)) . "\n";
		
	} else {
		
		//echo "OK.\n";
		logger("itaiwan", "[{$type}] {$phone}", "access");
	}
	
	$msg = "hinms\0hn2732\0";
	$in  = sprintf("%c%c%c%s", 0, 0, strlen($msg), $msg);
	$out = '';
	
	// Sending login request...
	socket_write($socket, $in, strlen($in));
	
	while ($out = socket_read($socket, 2048)) {
		//echo $out;
		break;
	}
	
	// Sending sms request...    
	$msg = "{$phone}\0" . $send_msg . "\0";
	
	$in = sprintf("%c%c%c%s%c%c", 0, 1, strlen($msg), $msg, 100, 0);
	
	socket_write($socket, $in, strlen($in));
	
	while ($out = socket_read($socket, 2048)) {
		
		logger("itaiwan", "[{$type}] {$phone}_" . $out, "success");
		
		//echo $out;exit;
		
		break;
	}
	
	//close
	socket_close($socket);
	
} //end of send sms

/*
 * sendMail
 */
function sendMail($title, $to_addr, $msg)
{
	include_once('class.phpmailer.php');
	
	$mail = new PHPMailer();
	
	$body = $msg;
	
	$mail->isSMTP();
	//$mail->Host = "msa.hinet.net";
	$mail->Host     = "168.95.4.211";
	$mail->From     = "eService@wifi.ntpc.gov.tw";
	$mail->CharSet  = "UTF-8";
	$mail->FromName = "NewTaipeiWiFi";
	$mail->Subject  = $title;
	$mail->AltBody  = "You need use HTML Viewer.";
	
	$mail->MsgHTML($body);
	$mail->AddAddress($to_addr, "New Taipei WiFi 使用者您好,");
	
	$retry = 0;
	
	$retInfo['ret'] = 1;
	$retInfo['msg'] = "default_msg";
	
	if (!$mail->Send()) {
		
		$retry++;
		
		if ($retry > 2) {
			
			$retInfo['ret'] = 2;
			$retInfo['msg'] = $mail->ErrorInfo;
			
			return $retInfo;
			
		}
		
	} else {
		
		$retInfo['ret'] = 0;
		$retInfo['msg'] = "default_msg";
		
		return $retInfo;
	}
	
	
}

/*
 * generate simple password
 */
function gen_smspass()
{
	
	srand((double) microtime() * 1000000);
	
	$array = array(
		"1",
		"2",
		"3",
		"4",
		"5",
		"6",
		"7",
		"8",
		"9",
		"0",
		"a",
		"b",
		"c",
		"d",
		"e",
		"f",
		"g",
		"h",
		"i",
		"j",
		"k",
		"m",
		"m",
		"n",
		"p",
		"p",
		"q",
		"r",
		"s",
		"t",
		"u",
		"v",
		"w",
		"x",
		"y",
		"z"
	);
	
	$password = "";
	
	for ($j = 0; $j < 6; $j++) {
		
		$pass = rand(0, 35);
		$password .= $array[$pass];
		
	}
	
	return $password;
	
}

/*
 * check password strong
 */
function checkPassword($password)
{
	
	$pwd      = strtolower($password);
	$strength = 0;
	
	$patterns = array(
		'#[a-z]#',
		'#[0-9]#'
	);
	
	foreach ($patterns as $pattern) {
		
		if (preg_match($pattern, $pwd, $matches)) {
			$strength++;
		}
		
	}
	
	return $strength;
	
} //end of checkPassword

/**
 *Function : find_ini_file
 *
 *$ini_data : An ini array or an ini file name
 *$key : The key which is used to compare with ths Section of $ini_data
 *$return_array : An array return ini values find in $ini_data
 *$loosely_compare : set to TRUE for loosely compare
 *
 */
function find_ini_file($ini_data, $key = "", &$return_array = Array(), $loosely_compare = FALSE)
{
	
	if (gettype($ini_data) === 'string') {
		//$ini_data is a file name
		$ini_array = parse_ini_file($ini_data, TRUE);
	} else if (gettype($ini_data) === 'array') {
		//$ini_data is an ini array
		$ini_array = $ini_data;
	}
	
	foreach ($ini_array as $section => $sub_array) {
		if ($loosely_compare) {
			if (strstr($key, $section)) {
				//find if current vendor match the wlan_dedicated_hotspot
				foreach ($sub_array as $item => $value) {
					$return_array[$item] = $value;
				}
				return TRUE;
			}
		} else {
			if (strcmp($key, $section) === 0) {
				//find if current vendor match the wlan_dedicated_hotspot
				foreach ($sub_array as $item => $value) {
					$return_array[$item] = $value;
				}
				return TRUE;
			}
		}
	}
	
	if ($section === 'default') {
		foreach ($ini_array['default'] as $item => $value) {
			$return_array[$item] = $value;
		}
		return TRUE;
	}
	
	return FALSE;
} // end of find_ini_file

/*
 *		check language
 */
function checkLang()
{
	$lang = 'tw';
	
	//check lang
	if (isset($_COOKIE['ntpc_lang']) && $_COOKIE['ntpc_lang'] == 'cn') {
		
		$lang = 'cn';
		
	} else if (isset($_COOKIE['ntpc_lang']) && $_COOKIE['ntpc_lang'] == 'en') {
		
		$lang = 'en';
		
	}
	
	return $lang;
}

/*
 * logger
 */
function logger($type, $message, $mtype)
{
	
	$time  = date("Ymd H:i:s");
	$today = date("Ymd");
	
	$path = LOGPATH . "/{$type}.{$mtype}.log.{$today}";
	
	$fp = fopen($path, "a");
	
	if ($fp) {
		
		//echo $message;
		fwrite($fp, "[" . $time . "] " . $message . "\n");
		
		fclose($fp);
		
	} else {
		
		echo "{$path} {$message}<br>";
		
	}
	
} //end of logger 

?>
