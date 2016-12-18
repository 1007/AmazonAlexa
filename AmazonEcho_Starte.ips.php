<?
//******************************************************************************
//	
//	Script mit Namen starten
//
//	Schluesselworte in Konfiguration
//				array("search('starte') OR search('sequence') OR search('script') "	,"AmazonEcho_Starte.ips.php"	),
//
//
//******************************************************************************

	if ( $debug ) IPS_LogMessage(basename(__FILE__),"Start");

	$endsession = false;
	
	$spokenWords = explode(' ', $command);

	$result = FindeSkript($spokenWords);

	if ( $result == false )
		return false;
	
	$script = GetVariable("Aktion");		
	
	IPS_RunScript($script);
			
	SetVariable("Response","Skript mit der <say-as interpret-as='spell-out'>ID</say-as>" . $script . " wurde gestartet .");

	return true;

//******************************************************************************
//	
//******************************************************************************
function FindeSkript($spokenWords)
	{

	$script = false;
		
	// finde erstes Wort welches kein Keywort ist
	foreach($spokenWords as $key)
		{
		if ( $key == "starte" ) 
			continue; 
		if ( $key == "sequence" ) 
			continue; 
		if ( $key == "script" ) 
			continue; 
		if ( !empty($key) )
			{
			$script = $key;
			break; 
			}	
		}
	
	if ( $script == false )
		return false;
								
	$ScriptID = @IPS_GetScriptIDByFile($script.".ips.php");
		
	if ($ScriptID != false)
		{
		SetVariable("Aktion",$ScriptID);
		return true;
		}
	else
		{
		SetVariable("Response","Script " . $script . " nicht gefunden .");
		return false;
		}		
			 			
	}
	

?>