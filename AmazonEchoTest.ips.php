<?
	IPSUtils_Include ("AmazonEchoInclude.ips.php");
	IPSUtils_Include ("AmazonEchoConfig.ips.php");
	
   	$debug = true;

   	$command = "dimme licht im arbeitszimmer";

	echo "\n" . $command;

	StartNewSession($parentDataID,$command);
	
	$RunningSession = intval(SearchingKeyWords($command) );

	echo "\nSession:" . $RunningSession;

	$RunningSession = intval(GetVariable('CommandID'));
		
	if ( $RunningSession > 0 ) 				
		{
		$ScriptName = $AlexaMasterKeyArray[$RunningSession-1][1];								
		$endsession = include($ScriptName);
		}

	$response = GetVariable("Response");

	echo "\nResponse : " .$response;

?>