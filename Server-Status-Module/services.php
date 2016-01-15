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

	function getServices($services){
		$services = "";
		foreach ($_POST as $param_name => $param_val) {
		    $services .= " -e ".$param_val;
		}
		exec ( "ps -e | grep".$services." | awk '{print $4}' | sort | uniq 2>&1", $result );
		echo json_encode($result);
	}

	$file = true;
	$myfile = fopen("restrictedIP.file", "r") or $file = false;
	if($file){
		//$ip = fread($myfile,filesize("webdictionary.txt"));
		fclose($myfile);
	
		// DNS Resolution may take from 0.5 to 4 seconds, thats why I check the GIV-OCT ip directly
		if( exec('grep '.get_client_ip().' restrictedIP.file')){
		//if (get_client_ip()===$ip){
			getServices($services);
		} 
		// But if we want to use a domain name instead of a IP
		//if (get_client_ip()===gethostbyname('giv-oct.uni-muenster.de.'){
		//	getServices($services);
		//} 
		else {
			echo "[]";
		} 
	} else {
		getServices($services);
	}
	
?>
