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
/*
	Beispiel in der Konfiguration
	
		$AlexaGeraetArray = array( 
		//			       spoken           Geraet           Typ           ID
		array(""		,"computer"		,"Computer"		, "VARIABLE"    ,19350),
		array(""		,"drucker"		,"Drucker"		, "FS20"   		,30543),
		array(""		,"heizlüfter"	,"Heizlüfter"	, "FS20"		,32995),
		array(""		,"box"			,"Dreambox"		, "VARIABLE"    ,19015),
			   	   	
				);


*/
//******************************************************************************

	if ( $debug ) IPS_LogMessage(basename(__FILE__),"Start");

	$endsession = false;
	
	$spokenWords = explode(' ', $command);

	$result = FindeGeraet($spokenWords);
	if( $result == false )
		return false;
			
	$result = FindeAktion($spokenWords);
	if( $result == false )
		return false;
			
	// Geraet schalten
	$result = DoAktion();
					
	return true;

//******************************************************************************
//	Suche Geraet
//******************************************************************************
function FindeAktion($spokenWords)
	{
	GLOBAL $AlexaGeraetArray;

	if ( in_array('ein', $spokenWords) OR in_array('einschalten', $spokenWords) )
		SetVariable("Aktion","Ein");
	if ( in_array('aus', $spokenWords) OR in_array('ausschalten', $spokenWords) )
		SetVariable("Aktion","Aus");

	// Nachfragen ob einschalten oder ausschalten
	if ( GetVariable("Aktion") == "" )
		{
		$geraet = $AlexaGeraetArray[intval(GetVariable("Geraet"))][2];
		$response = "Soll ich " . $geraet . " einschalten oder ausschalten ?";	
		SetVariable("Response",$response);		
		return false;		
		}
	else
		return true;


	}
	
//******************************************************************************
//	Suche Geraet
//******************************************************************************
function FindeGeraet($spokenWords)
	{
	GLOBAL $AlexaGeraetArray;
	
	$result = false;
	
	$count = 0;

	if ( GetVariable("Geraet") != "" )		// bereits Geraet gefunden
		return true;
	
	foreach($AlexaGeraetArray as $geraet )
		{
		if ( in_array($geraet[1], $spokenWords) )
			{
			SetVariable("Geraet",$count);		
			$result = true;
			break;
			}
			
		$count = $count + 1;
		}
		
	if ( $result == false )
		SetVariable("Response","Was soll ich für dich schalten ?");	

	return $result;				
							
	}
							

//******************************************************************************
//	Schalte Geraet
//******************************************************************************
function DoAktion()
	{
	GLOBAL $AlexaGeraetArray;

	IPSUtils_Include ('IPSComponent.class.php', 'IPSLibrary::app::core::IPSComponent');
	IPSUtils_Include ('IPSComponentSwitch_FS20.class.php', 'IPSLibrary::app::core::IPSComponent::IPSComponentSwitch');
	
	$aktion = GetVariable("Aktion");
	if ( $aktion == "Ein" )
	   $status = true;
	if ( $aktion == "Aus" )
	   $status = false;
	   
	$geraetearray = $AlexaGeraetArray[intval(GetVariable("Geraet"))]; 	
	
	$geraet = $geraetearray[2];
	
	if ( $geraetearray[3] == "VARIABLE" )
		{
		SetValue($geraetearray[4], $status);		
		SetVariable("Response",$geraet . " " . $aktion . " geschaltet");	
		return true;
		}
		
	if ( $geraetearray[3] == "FS20" )
		{
		$component =  new IPSComponentSwitch_FS20($geraetearray[4]);
		$component->SetState($status);
		SetVariable("Response",$geraet . " " . $aktion . " geschaltet");	
		return true;		
		}

	
	}

?>