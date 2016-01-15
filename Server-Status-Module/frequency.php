<?php

// Function to get the client IP address
    function get_client_ip() {
	$ipaddress = '';
	if ($_SERVER['HTTP_CLIENT_IP'])
	        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
	else if($_SERVER['HTTP_X_FORWARDED_FOR'])
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
	else if($_SERVER['HTTP_X_FORWARDED'])
	        $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
	else if($_SERVER['HTTP_FORWARDED_FOR'])
	        $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
	else if($_SERVER['HTTP_FORWARDED'])
	        $ipaddress = $_SERVER['HTTP_FORWARDED'];
	else if($_SERVER['REMOTE_ADDR'])
	        $ipaddress = $_SERVER['REMOTE_ADDR'];
	else
	        $ipaddress = 'UNKNOWN';
	return $ipaddress;
    }

    function changeFrequency(){
	$hours = $_POST["h"]; 

	if (is_null($hours)) {
		die("Please specify the frequency in hours!");
	}
	else if( 24 % $hours != 0 ){
    		die("The number of hours must be divisible by 24!");
	} else {
		$myfile = fopen("geocpath.file", "r") or die("Unable to open file!");
		$path = fread($myfile,filesize("geocpath.file"));
		$path = trim(preg_replace('/\s+/', ' ', $path));
		$message=shell_exec("sudo -u ".get_current_user()." ".$path."frequency.sh ".$hours." 2>&1");
		//ONLY DEBUG
	      	//print_r($message);
	      	fclose($myfile);
	}
    }

	$file = true;
	$myfile = fopen("restrictedIP.file", "r") or $file = false;
	if($file){
		fclose($myfile);
	
		// DNS Resolution may take from 0.5 to 4 seconds, thats why I check the GIV-OCT ip directly
		if( exec('grep '.get_client_ip().' restrictedIP.file')){
			changeFrequency();
		} 
		// But if we want to use a domain name instead of a IP
		//if (get_client_ip()===gethostbyname('giv-oct.uni-muenster.de.'){
		//	changeFrequency();
		//} 
		else {
			echo "[]";
		} 
	} else {
		changeFrequency();
	}
?>
