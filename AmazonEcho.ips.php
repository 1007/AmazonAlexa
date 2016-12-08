<?
	IPSUtils_Include ("AmazonEchoConfig.ips.php");
	IPSUtils_Include ("AmazonEchoInclude.ips.php");

	if ( !isset($_SERVER['REQUEST_METHOD']) )
		return;

	// Alexa POST JSON request
	$jsonRequest    = file_get_contents('php://input');
	$data           = json_decode($jsonRequest, true);

	AmazonEchoLogging("AmazonEcho json empfangen");
	AmazonEchoLogging($jsonRequest);

	$sessionId          = @$data['session']['sessionId'];
	$applicationId      = @$data['session']['application']['applicationId'];
	$userId             = @$data['session']['user']['userId'];
	$newSession         = @$data['session']['new'];
	$requestTimestamp   = @$data['request']['timestamp'];
	$command            = @$data['request']['intent']['slots']['command']['value'];
	$requestType        = @$data['request']['type'];

	$command            = strtolower($command); 
	
	//Alexas Anfrage ist ein HTTP POST
	if ( $_SERVER['REQUEST_METHOD'] == 'GET' )
		{
		fail('Fehler : HTTP GET erhalten');
		}

    // Fehler wenn ApplicationID
    if ($applicationId != $applicationIdValidation)
		fail('Fehlerhafte Application ID : ' . $applicationId);
	
    // Fehler wenn UserID falsch
    if ($userId != $userIdValidation)
		fail('Fehlerhafte User ID : ' . $userId);

    // Fehler wenn Anfrage aelter als 60 Sekunden
	$localTime = time();
	$alexaTime = strtotime($requestTimestamp);
    if ($localTime - $alexaTime > 60)
        fail('Zeitstempelfehler : Local - ' . date("d.m.Y H:i",$localTime) . ' Alexa - ' . date("d.m.Y H:i",$alexaTime));

	AmazonEchoLogging("AmazonEcho sagt : " . $command);

	if ( $debug ) IPS_LogMessage("Alexa sagt Kommando :", $command);

	// Neue Session ?
	if ( $newSession == 'true' )
		StartNewSession($parentDataID,$command);
	
	$spokenWords = explode(' ', $command);
   
	// Wenn Satz angefangen, frage was ich tun soll und warten auf Antwort
	// Session nicht beenden
	if ($requestType == 'LaunchRequest')
		{
		respond("Was kann ich für dich tun ?",false);
		return;
		}
	
	$RunningSession = intval(GetVariable('CommandID'));
	
	if ( $RunningSession == 0 )
		{
		$RunningSession = SearchingKeyWords($command);
		$ScriptName = $AlexaArray[$RunningSession-1][1];
		}
	else	
		$ScriptName = $AlexaArray[$RunningSession-1][1];
				
	$endsession = include($ScriptName);
						
	if ( $endsession )
		EndSession($parentDataID,$endsession);
	else
		ContinueSession($parentDataID,$endsession)
		
?>