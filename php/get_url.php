<?php
	function get_url($request_url) {
	  //    sudo apt-get install php5-curl
	  //	sudo /etc/init.d/apache2 restart
	  $ch = curl_init();
	  curl_setopt($ch, CURLOPT_URL, $request_url);
	  curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
	  curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
	  $response = curl_exec($ch);
	  curl_close($ch);
	  return $response;
	}

	function getServices($url, $args){
		//echo $url;
 		$fields = "";
	    foreach ($args as $i => $v) {
	    	$fields.= "s".$i.'='.$v.'&';
	    }
	    rtrim($fields,'&');
	    //echo $fields;
	    //open connection
		$ch = curl_init();

		//set the url, number of POST vars, POST data
		curl_setopt($ch,CURLOPT_URL,$url);
		curl_setopt($ch,CURLOPT_POST,count($args));
		curl_setopt($ch,CURLOPT_POSTFIELDS,$fields);

		//execute post
		$result = curl_exec($ch);
		curl_close($ch);
		echo $result;
		return $result;
	}

	function curl_post($url, array $post = NULL, array $options = array()) { 
	    $defaults = array( 
	        CURLOPT_POST => 1, 
	        CURLOPT_HEADER => 0, 
	        CURLOPT_URL => $url, 
	        CURLOPT_FRESH_CONNECT => 1, 
	        CURLOPT_RETURNTRANSFER => 1, 
	        CURLOPT_FORBID_REUSE => 1, 
	        CURLOPT_TIMEOUT => 4, 
	        CURLOPT_POSTFIELDS => http_build_query($post) 
	    ); 

	    $ch = curl_init(); 
	    curl_setopt_array($ch, ($options + $defaults)); 
	    if( ! $result = curl_exec($ch)) 
	    { 
	        trigger_error(curl_error($ch)); 
	    } 
	    curl_close($ch); 
	    return $result; 
	} 
?>