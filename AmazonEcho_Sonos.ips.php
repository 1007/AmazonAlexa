<?

	GLOBAL $debug;

	$SonosID = IPSUtil_ObjectIDByPath("Hardware.SONOS.SONOS-Arbeit");
	
	$endsession = false;
	
	if ( $debug ) IPS_LogMessage(basename(__FILE__),"Start");

	$spokenWords = explode(' ', $command);

	$response = "Sonos Kommando nicht bekannt";

	if ( in_array('stop', $spokenWords) OR in_array('stopp', $spokenWords) )
		$endsession = SonosStop();
	if ( in_array('start', $spokenWords) OR in_array('play', $spokenWords) )
		$endsession = SonosStart();
	if ( in_array('pause', $spokenWords)  )
		$endsession = SonosPause();
	if ( in_array('playlist', $spokenWords) OR in_array('playliste', $spokenWords) OR in_array('liste', $spokenWords)  )
		$endsession = SonosPlaylist($spokenWords);
					
	return $endsession;

function SonosStop()
	{
	GLOBAL $SonosID;
	
	SNS_Stop($SonosID);
	SetVariable("Response","SONOS Player gestoppt");	
	return true;
	
	}
	
function SonosStart()
	{
	GLOBAL $SonosID;
	
	SNS_Play($SonosID);
	SetVariable("Response","SONOS Player gestartet");	
	return true;
	
	}
	
function SonosPause()
	{
	GLOBAL $SonosID;
	
	SNS_Pause($SonosID);
	SetVariable("Response","SONOS Player pausiert");	
	return true;
	
	}
	
function SonosPlaylist($spokenwords)
	{
	GLOBAL $SonosID;
	
	//$list = "sonos liste Test1 test3 test";

	//$spokenwords = explode(" " ,$list);
	
	$key = array_search('sonos', $spokenwords);	
	if ( $key !== false )
		unset($spokenwords[$key]);
	$key = array_search('liste', $spokenwords);	
	if ( $key !== false )
		unset($spokenwords[$key]);
	$key = array_search('playliste', $spokenwords);	
	if ( $key !== false )
		unset($spokenwords[$key]);
	$key = array_search('playlist', $spokenwords);	
	if ( $key !== false )
		unset($spokenwords[$key]);
	
	$list = implode(" ",$spokenwords);
	
	//echo "\n" . $list;
	
	$playlists = IPS_GetVariableProfile('Playlist.SONOS');
	
	$gefunden = false;
	
	foreach($playlists['Associations'] as $playlist)
		{
		
		if ( $list == strtolower($playlist['Name']) )
			{
			$gefunden = true;
			$list = $playlist['Name'];
			break;
			}
		
		}

	if ( $gefunden == false )
		{
		SetVariable("Response","SONOS Playlist ". $list . " nicht gefunden");	
		return true;
		}
		
	
	SNS_SetPlaylist($SonosID,$list);
	
	SetVariable("Response","SONOS Playlist ". $list . " wird gestartet");	

	return true;
	
	}	
?>