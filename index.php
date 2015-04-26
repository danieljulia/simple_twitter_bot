<?php
require "config.php";
require "twitteroauth/twitteroauth.php";



//buscar twitts con un tag y una posición determinadas..
$tag="gat";

$connection = new TwitterOAuth(CONSUMER_KEY, CONSUMER_SECRET, OAUTH_TOKEN,OAUTH_SECRET);
$content = $connection->get('search/tweets',array(
	'q'=>"%20".$tag."%20",
	'geocode'=>'41.387917,2.1699187,10km'));



$user="";
foreach($content->statuses as $twitt){
	print $twitt->user->screen_name;	
	//print_r($twitt);
	print "\r\n".$twitt->created_at;
	$dt=strtotime( $twitt->created_at)-time();
	print $dt;

	//si llamo al cron cada 30 min-- 30x60=1800 segundos
	if( strtolower($twitt->user->screen_name!="basiliobdn")){ //atención!!
		if($dt<1800){ //60*30 cada 30min
			$user=$twitt->user->screen_name;
			print "<br><br>Ha encontrado este twitt <h1>".$twitt->text." del usuario ".$twitt->user->screen_name."</h1>";
			print "<br>";
			break; //sale del bucle, sólo hago un twitt

		}
	}
}



if($user!=""){ //si ha encontrado un twitt apto
 
	//$content = $connection->get('account/verify_credentials');
 
	$res=$connection->post('statuses/update', array('status' => utf8_encode("hola @".$user.", oi que sóc maco? (perdona sóc un bot i m'estan provant, :=) la culpa és teva per dir 'gat')")));

	print_r($res);
	

	//seguir a este usuario (opcional)
	$res=$connection->post('friendships/create', array('screen_name'=>$user));

	print_r($res);
}


  