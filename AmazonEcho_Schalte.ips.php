<?
//******************************************************************************
//
//	Beispiel :
//				Mensch : "Schalte Drucker ein"
//				Alexa  : "Drucker wird eingeschaltet"
//				
//			    Mensch : "Schalte"
//				Alexa  : "Was soll ich für dich schalten"
//				Mensch : "Drucker"
//				Alexa  : "Soll ich Drucker einschalten oder ausschalten"
//				Mensch : "einschalten"
//				Alexa  : "Drucker wird eingeschaltet"
//
//******************************************************************************

	IPSUtils_Include ("Funcpool.ips.php");

	
	
	if ( $debug ) IPS_LogMessage(basename(__FILE__),"Start");

	$endsession = false;
	
	$spokenWords = explode(' ', $command);

	$result = FindeGeraet($spokenWords);
	
	if( $result == false )
		
	// Kein Geraet gefunden - nachfragen
	if ( $result == false )
		{  
		$response = "Was soll ich für dich schalten ?";	
		SetVariable("Response",$response);		
		$endsession = false;		
		}
	
	if ( GetVariable("Geraet") != "" )
		{
			
		if ( in_array('ein', $spokenWords) OR in_array('einschalten', $spokenWords) )
			SetVariable("Aktion","Ein");
		if ( in_array('aus', $spokenWords) OR in_array('ausschalten', $spokenWords) )
			SetVariable("Aktion","Aus");
		
		// Nachfragen ob einschalten oder ausschalten
		if ( GetVariable("Aktion") == "" )
			{
			$response = "Soll ich " . GetVariable("Geraet") . " einschalten oder ausschalten ?";	
			SetVariable("Response",$response);		
			$endsession = false;		
			}
				
		}
	
	// Geraet schalten
	if ( GetVariable("Geraet") != "" AND  GetVariable("Aktion") != "" )
		{
		$response = DoAktion();
		SetVariable("Response",$response);		
		$endsession = true;
		}
					
	return $endsession;


//******************************************************************************
//	Suche Geraet
//******************************************************************************
function FindeGeraet($spokenWords)
	{
	
	$result = false;
	
	if ( in_array('computer', $spokenWords) )
		{
		SetVariable("Geraet","Computer");
		$result = true;
		}

	if ( in_array('drucker', $spokenWords) OR in_array('drucke', $spokenWords) )
		{
		SetVariable("Geraet","Drucker");
		$result = true;
		}
	
	if ( in_array('dreambox', $spokenWords) OR in_array('box', $spokenWords) )
		{
		SetVariable("Geraet","Dreambox");
		$result = true;
		}

	if ( in_array('heizlüfter', $spokenWords) )
		{
		SetVariable("Geraet","Heizlüfter");
		$result = true;
		}


	return $result;				
							
	}
							

//******************************************************************************
//	Schalte Geraet
//******************************************************************************
function DoAktion()
	{
	IPSUtils_Include ('IPSComponent.class.php', 'IPSLibrary::app::core::IPSComponent');
	IPSUtils_Include ('IPSComponentSwitch_FS20.class.php', 'IPSLibrary::app::core::IPSComponent::IPSComponentSwitch');
	IPSUtils_Include ("DreamboxFunktionen.ips.php");
	
	$geraet = GetVariable("Geraet");
	$aktion = GetVariable("Aktion");
	
	$response = "Konnte Geraet nicht schalten";
	
	if ( $geraet == 'Computer' )
		{
		WakeOnLan();
		$response = "Computer wird eingeschaltet";
		}

	if ( $geraet == 'Drucker' )
		{
		$drucker  = IPSUtil_ObjectIDByPath("Hardware.FS20.Ausgaenge.Arbeit.Drucker");

		$component =  new IPSComponentSwitch_FS20($drucker);
		
		if ( $aktion == "Ein"  )
			{
			$component->SetState(true);
			$response = "Drucker wird eingeschaltet";
			}

		if ( $aktion == "Aus" )
			{
			$component->SetState(false);
			$response = "Drucker wird ausgeschaltet";
			}
		}

	if ( $geraet == 'Heizlüfter' )
		{
		$luefter  = IPSUtil_ObjectIDByPath("Hardware.FS20.Ausgaenge.Arbeit.Heizluefter");

		$component =  new IPSComponentSwitch_FS20($luefter);
		
		if ( $aktion == "Ein"  )
			{
			$component->SetState(true);
			$response = "Heizlüfter wird eingeschaltet";
			}

		if ( $aktion == "Aus" )
			{
			$component->SetState(false);
			$response = "Heizlüfter wird ausgeschaltet";
			}
		}

	if ( $geraet == 'Dreambox' )
		{
		
		if ( $aktion == "Ein"  )
			{
			DreamboxWakeup();
			$response = "Dreambox eingeschaltet";
			}

		if ( $aktion == "Aus" )
			{
			DreamboxStandby();
			$response = "Dreambox ausgeschaltet";
			}
		}


	return $response;
	
	}

?>