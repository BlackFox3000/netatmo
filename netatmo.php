<?php
//création d'u objet Xml
$xml = new SimpleXMLElement('<xml/>');

$lat = $_COOKIE["lat"];
$lng = $_COOKIE["lng"];
$NE['lat'] = 44.63547724830724;
$NE['lon'] = 44.51313387403741;

$SW['lat'] = 6.19184289442785;
$SW['lon'] = 5.981886080951881;

//echo "document.getElementById('montest').innerHTML+=\"<br>coord | lat: ".$lat." lng:".$lat."\";";

/**
 * $test
* Example of Netatmo Public API
* For further information, please take a look at https://dev.netatmo.com/doc
*/
//echo "document.getElementById('montest').innerHTML+=\"Mon petit test:".$test."\";";
//echo '<br>';
if(isset($NE) && isset($SW)){
	define('__ROOT__', dirname(dirname(__FILE__)));
	require_once ('src/Netatmo/autoload.php');
	require_once ('Config.php');
	require_once ('Utils.php');

	$scope = Netatmo\Common\NAScopes::SCOPE_READ_THERM." " .Netatmo\Common\NAScopes::SCOPE_WRITE_THERM;
	//Client configuration from Config.php
	$conf = array("client_id" => $client_id,
				"client_secret" => $client_secret,
				"username" => $test_username,
				"password" => $test_password,
				"scope" => $scope);
	$client = new Netatmo\Clients\NAThermApiClient($conf);
	//Authenticate & Authorize

	try {
		$tokens = $client->getAccessToken();
        $error_msg=" c'est good";
	} catch(Netatmo\Exceptions\NAClientException $ex) {
		$error_msg = "An error happened  while trying to retrieve your tokens\n" . $ex->getMessage() . "\n";
		handleError($error_msg, TRUE);
	}


	//Initialize public API with same authentication
	$public = new Netatmo\Clients\NAPublicApiClient($tokens);
	try {
		$publicData = $public->getData($SW['lat'], $NE['lat'], $SW['lon'], $NE['lon'], 'temperature', 'true');
		
	} catch(Netatmo\Exceptions\NAClientException $ex) {
		handleError("An error occured while retrieving public data: " . $ex->getMessage() . "\n", TRUE);
	}
	// Parse and compute average temperature
	if ($publicData['status'] == 'ok') {
		//printMessageWithBorder(count($publicData['body']).' public results');
		
		$temp = 0;
		$count = 0;
/**
 * for ($i = 1; $i <= 8; ++$i) {
* $track = $xml->addChild('track');
* $track->addChild('path', "song$i.mp3");
* $track->addChild('title', "Track $i - Track Title");
 * }
 */
		foreach ($publicData['body'] as $body) {
			foreach ($body['measures'] as $measures) {
				if (isset($measures['type'][0]) && $measures['type'][0] == 'temperature') {
				    //echo "<station>"
					//echo 'num'.$count.': '.array_values($measures['res'])[0][0].PHP_EOL.'°C<br/>';
					$temp += array_values($measures['res'])[0][0];
					$count++;
				//	var_dump($measures);
				//	echo '<br>';
					
				}
				if (isset($measures['type'][1]) && $measures['type'][1] == 'temperature') {
					//echo $count.': '.array_values($measures['res'])[0][1].PHP_EOL;
					$temp += array_values($measures['res'])[0][1];
					$count++;
					
				}
			}
		}
	//	echo '</tableau>';
		$temp = $temp / $count;
//		echo 'Moyenne de température: '.round($temp,2).' °C <br>';
//		echo 'Nombre de stations dans la zone: '.$count.'';
	}
}

// spécifie le type du document, ici on souhaite obtenir un document type xml 
Header('Content-type: text/xml');
print($xml->asXML());
?>