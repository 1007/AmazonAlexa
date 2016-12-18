<?
//******************************************************************************
//
//	Beispiel :
//				Mensch : "Schalte Licht im Wohnzimmer ein"
//				Alexa  : "Licht im Wohnzimmer wird eingeschaltet"
//				
//			    Mensch : "Schalte Licht"
//				Alexa  : "Welches Licht soll geschaltet werden"
//				
//				etc
//				
//				
//
//******************************************************************************
/*	Beispiel fuer Konfiguration in AmazonEchoConfig.ips.php

	$AlexaLightArray = array( 
	//	   spoken         spoken           response         IPSLight Name       not used .............
	array("wohnzimmer"	,""				,"Wohnzimmer"	,"Wohnen Deckenlicht"	,true	,true	,false),
	array("esszimmer"	,"decke"		,"Esszimmer"	,"Essen Deckenlicht"	,true	,true	,false),
	array("esszimmer"	,"lampe"		,"Esszimmer"	,"Essen Steinlampe"		,true	,false	,false),
					);

*/
//******************************************************************************

	if ( $debug ) IPS_LogMessage(basename(__FILE__),"Start");
	
	$spokenWords = explode(' ', $command);

	// Als erstes Aktionswort finden
	$result = FindeAktionsWort($spokenWords);
	if ( $result == false )
		return false;
	
	// Welches Licht		
	$result = FindeLicht($spokenWords);
	if ( $result == false )
		return false;
	
	// Finde Dimmwert
	$result = FindeDimmWert($spokenWords);
	if ( $result == false )
		return false;
	
	// Schalte Licht
	$result = DoAktion();

	return $result;
	
//******************************************************************************
//	
//******************************************************************************
function FindeAktionsWort($spokenWords)
	{
	
	$aktion = GetVariable("Aktion");
	// Aktion schon bekannt
	if ( $aktion == "Schalte Ein" OR $aktion == "Schalte Aus" OR $aktion == "Dimme" )
		return true;

	if ( in_array('dimme', $spokenWords)  )
		{
		SetVariable("Aktion","Dimme");
		return true;
		}
		
	if ( in_array('schalte', $spokenWords) )
		{
		SetVariable("Aktion","Schalte");
	
		if ( in_array('ein', $spokenWords) )
			{
			SetVariable("Aktion","Schalte Ein");
			return true;
			}
		if ( in_array('aus', $spokenWords) )
			{
			SetVariable("Aktion","Schalte Aus");
			return true;
			}
			
		SetVariable("Response","Soll das Licht ein oder ausgeschaltet werden?");	
	 	return false;
		
		}
								
	}
	
	
	
//******************************************************************************
//	
//******************************************************************************
function FindeDimmWert($spokenWords)
	{
	GLOBAL $LightArray;
	
	$result = false;

	$aktion = GetVariable("Aktion");
	
	if ( $aktion != "Dimme" )
		return true;
		
	if ( in_array('prozent', $spokenWords) )
		{
		$key = array_search('prozent', $spokenWords);
		$prozent = ZahlWort($spokenWords[$key-1]);
		SetVariable("Aktion","Dimme " . $prozent);		
		return true;
		}
	else
		{
		SetVariable("Response","Auf welchen Wert soll gedimmt werden ?");	
	 	return false;	
		}	
		

	}
	
	
//******************************************************************************
//	
//******************************************************************************
function FindeLicht($spokenWords)
	{
	GLOBAL $AlexaLightArray;

	if ( GetVariable("Geraet") != "" )
		return true;	

	
	$result = false;
	
	$count = 0;
	
	foreach( $AlexaLightArray as $light )
		{
		
		if ( $light[1] == "" ) 
			{   
			if ( in_array($light[0], $spokenWords) )
		   		{   		
				SetVariable("Geraet",$AlexaLightArray[$count][3]);	
				$result = true;	 		   
		   		}
			}
		else
			{
			
			if ( in_array($light[0], $spokenWords) AND in_array($light[1], $spokenWords) )
		   		{   		
				SetVariable("Geraet",$AlexaLightArray[$count][3]);	
				$result = true;	 		   
		   		}			
			}
		
		$count = $count + 1;      
		}
		

	if ( $result == false )
		SetVariable("Response","Welches Licht meinst du ?");	
	 
	return $result;
		
	}
	
	
//******************************************************************************
//	
//******************************************************************************
function DoAktion()
	{

	IPSUtils_Include ('IPSLight.inc.php', 'IPSLibrary::app::modules::IPSLight');

	$response = "Licht konnte nicht geschaltet werden.";
	
	$aktion    = explode(" ", GetVariable("Aktion") );
	$lichtname = GetVariable("Geraet");
	$raumname  = GetVariable("Raum");
											
	// Ein oder Ausschalten			
	if ( $aktion[0] == "Schalte" )
		{ 	
	
		if ( $aktion[1] == "Ein" )
			$value = true;
		if ( $aktion[1] == "Aus" )
			$value = false;
				
		IPSLight_SetSwitchByName($lichtname, $value);
		
		if ( $value )
			$response = $lichtname. " ". $raumname ." eingeschaltet";
		else
			$response = $lichtname. " ". $raumname ." ausgeschaltet";
		
		}	
	
	
	// Dimme
	if ( $aktion[0] == "Dimme" )
		{ 	
		
		IPSLight_DimAbsoluteByName($lichtname, $aktion[1]);

		$response = $lichtname. " ". $raumname ." auf ".$aktion[1]." Prozent gedimmt";

		}
		
		
	SetVariable("Response",$response);

	return true;
		
	}
	

	



?>