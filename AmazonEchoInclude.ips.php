<?
//******************************************************************************
//  Suche Schluesselwort
//******************************************************************************
function SearchingKeyWords($command)
	{
	GLOBAL $AlexaArray;
	GLOBAL $debug;
	
	$result = false;
	$count = 1;

	foreach($AlexaArray as $c)
		{	
		$result = false;
		if ( $c[0] == "" )
			continue;
				
		$eval = "\$result = (" .  $c[0] .");";	
		
		if ( $debug ) IPS_LogMessage(basename(__FILE__),"eval: [".$eval."]");

		
		eval ($eval);		
		
		if ( $result == true )
			{
			$script = $c[1];
			SetVariable("CommandID",$count);
			$result = true;
			break;
			
			}	
		$count = $count + 1;
		
		}

	
	if ( $result == true )
    if ( $debug ) IPS_LogMessage(basename(__FILE__),"SessionID gefunden: ".$count);
	

	if ( $result == true )
		return $count;
	else
		return 0;
		
	}
		

//******************************************************************************
// Suche Keywort 
//******************************************************************************
function search($word)
	{
	GLOBAL $spokenWords;
	GLOBAL $debug;
	
	$result = in_array($word, $spokenWords);
	
    if ( $debug ) 
		if ( $result == true )
			IPS_LogMessage(basename(__FILE__),"Search OK : ".$word);
		else
			IPS_LogMessage(basename(__FILE__),"Search NOK: ".$word);

	
	return $result;
	 
	}
	

//******************************************************************************
// Setze eine Variable
//******************************************************************************
function SetVariable($variable,$val)
	{
	Global $parentDataID;
	
	$id = @IPS_GetVariableIDByName($variable,$parentDataID);

	if ( $id == false )
		{
		$id = IPS_CreateVariable(3);
		IPS_SetName($id, $variable);
		SetValue($id, $val);
		IPS_SetParent($id, $parentDataID);
		}

	SetValue($id,$val);
	}
	
//******************************************************************************
// Hole eine Variable
//******************************************************************************
function GetVariable($variable)
	{
	GLOBAL $parentDataID;
	
	$id = @IPS_GetVariableIDByName($variable,$parentDataID);
	
	if ( $id == false )
		{
		$id = IPS_CreateVariable(3);
		IPS_SetName($id, $variable);
		
		IPS_SetParent($id, $parentDataID);
		}
	
	return GetValue($id);
	}
	

//******************************************************************************
// Starte eine neue Session
//******************************************************************************
function StartNewSession($parentDataID,$command)
	{
	GLOBAL $debug;
	
    if ( $debug ) IPS_LogMessage('AmazonEcho',"New Session");
	
	$childs = IPS_GetChildrenIDs($parentDataID);
	
	foreach($childs as $child )
		{
		$var = IPS_GetVariable($child);
		if ( $var['VariableType'] == 3 )
			SetValue($child , "");
		}
	
	$response = "Ich habe : " .$command . " nicht verstanden";
	SetVariable("Response",$response);
	
	SetVariable("SessionEnd",0);
	
	}
	
//******************************************************************************
// Beende Session
//******************************************************************************
function EndSession($parentDataID,$endsession)
	{
	GLOBAL $debug;

	if ( $debug ) IPS_LogMessage('AmazonEcho',"End Session");

	SetVariable("SessionEnd",1);
	$response = GetVariable("Response");
	AmazonEchoLogging("AmazonEcho Antwort : " . $response);
	respond($response,$endsession);

	}

//******************************************************************************
// Continue Session
//******************************************************************************
function ContinueSession($parentDataID,$endsession)
	{
	GLOBAL $debug;

	if ( $debug ) IPS_LogMessage('AmazonEcho',"Continue Session");

	$response = GetVariable("Response");
	AmazonEchoLogging("AmazonEcho Antwort : " . $response);
	respond($response,$endsession);
	

	}
	
//******************************************************************************
// Ersatz fuer apache_request_headers()
//******************************************************************************
//if( !function_exists('apache_request_headers') ) 
//	{

function apache_request_headers()
	{
	$arh = array();
	$rx_http = '/\AHTTP_/';
  	foreach($_SERVER as $key => $val)
		{
    	if( preg_match($rx_http, $key) )
			{
      		$arh_key = preg_replace($rx_http, '', $key);
      		$rx_matches = array();
      		// do some nasty string manipulations to restore the original letter case
      		// this should work in most cases
      		$rx_matches = explode('_', $arh_key);
      		if( count($rx_matches) > 0 and strlen($arh_key) > 2 )
				{
        		foreach($rx_matches as $ak_key => $ak_val) $rx_matches[$ak_key] = ucfirst($ak_val);
        			$arh_key = implode('-', $rx_matches);
      			}
      		$arh[$arh_key] = $val;
    		}
  		}
  	
	return( $arh );
	}

//	}



//******************************************************************************
// Fehlerfall
//******************************************************************************
function fail($message)
	{
	GLOBAL $debug;
	
    if ( $debug ) IPS_LogMessage('AmazonEcho',$message);
	AmazonEchoLogging($message);
    
	die();

	}

//******************************************************************************
// Sende Antwort an Amazon
//******************************************************************************
function respond($Response, $endSession = false)
	{
	

	header('Content-Type: application/json;charset=utf-8');

	// Soll Session beendet werden ?
	$shouldEndSession = $endSession ? 'true' : 'false';

	$text = '{"version" : "1.0","response" : {"outputSpeech" : {"type" : "PlainText","text" : "'.$Response.'" },"shouldEndSession" : '.$shouldEndSession.'}}';

	header('Content-Length: ' . strlen($text));
	
	echo ($text);

}




//******************************************************************************
// Logging
//******************************************************************************
function AmazonEchoLogging($text,$file = 'AmazonEcho.log')
	{
	GLOBAL $logging;
	
	if ( $logging == false )
		return;	
		
	$ordner = IPS_GetLogDir() . "AmazonEcho/";
	if ( !is_dir ( $ordner ) )
		mkdir($ordner,0777,true); // Ordner erstellen

   if ( !is_dir ( $ordner ) )
	   return;

	list($usec, $sec) = explode(" ", microtime());
    
	$time = date("d.m.Y H:i:s",$sec);
	$logdatei = IPS_GetLogDir() . "AmazonEcho/" . $file;
	$datei = fopen($logdatei,"a+");
	fwrite($datei, $time ." ". $text . chr(13));
	fclose($datei);

	}


//******************************************************************************
// Zahlwort ( string ) in Zahl ( int ) wandeln
//******************************************************************************
function ZahlWort($zahlwort)
	{
	
	$result = false;
	
	// Eins
	$array0 = array ('eins','eine','einen');
	// Zehner
	$array1 = array ('null','zehn','zwanzig','dreißig','vierzig','fünfzig','sechzig','siebzig','achtzig','neunzig','hundert');
	// 1 bis 19
	$array2 = array ('null','eins','zwei','drei','vier','fünf','sechs','sieben','acht','neun','zehn',
						'elf','zwölf','dreizehn','vierzehn','fünfzehn','sechzehn','siebzehn','achtzehn','neunzehn');
	
	$key = array_search($zahlwort,$array0);
		
	if ( $key !== false )
		{
		$result = 1;
		}
	
	if ( $result != 1 )
		{
		$key = array_search($zahlwort,$array1);
	
		if ( $key !== false )
			{
			$result = ($key) * 10;
			}
		else
			{
			$key = array_search($zahlwort,$array2);
		
			if ( $key !== false )
				{
				$result = ($key);
				}
			else
				{
				$s = explode('und',$zahlwort);
			
			
				$key = array_search($s[1],$array1);
				$result = ($key) * 10;
				$key = array_search($s[0],$array2);
				$result = $result + $key;
			
				}	
			}
		}
	
	
	return $result;
	
	}



?>