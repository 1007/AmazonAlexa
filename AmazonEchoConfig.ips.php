<?

	GLOBAL $applicationIdValidation;
	GLOBAL $userIdValidation;           
	GLOBAL $echoServiceDomain;         
	GLOBAL $debug;
	GLOBAL $logging;
	GLOBAL $parentDataID;

//******************************************************************************
//	AmazonEcho relevante Definitionen
//******************************************************************************
	
	$applicationIdValidation    = 'amzn1.ask.skill.*****************************';
	$userIdValidation           = 'amzn1.ask.account.A**************************';
	$echoServiceDomain          = 'echo-api.amazon.com';

//******************************************************************************
//	IPSymcon relevante Definitionen
//******************************************************************************
	$debug   = true;
	$logging = true;

	$parentDataID = IPSUtil_ObjectIDByPath("Program.IPSLibrary.data.privat.AmazonEcho");

//******************************************************************************
// Demo Konfiguration
// Suche nach Schluesselwoerter um dann das angegeben Script zu starten
//******************************************************************************
	GLOBAL $AlexaGeraetArray;
	$AlexaMasterKeyArray = array( 				
	   array("search('licht') AND ( search('schalte') OR search('dimme') ) "			,"AmazonEcho_Licht.ips.php"		),
	   array("search('temperatur') AND search('hoch') "													,"AmazonEcho_Temperatur.ips.php"),
				);


//******************************************************************************
//	Geraetenamen
//******************************************************************************
	GLOBAL $AlexaGeraetArray;
	$AlexaGeraetArray = array( 
				//			   spoken           Geraet           Typ           ID
			   array(""		,"computer"		,"Computer"		, "VARIABLE"    ,19350),
			   array(""		,"drucker"		,"Drucker"		, "FS20"   		,30543),
			   array(""		,"heizlüfter"	,"Heizlüfter"	, "FS20"		,32995),
			   array(""		,"box"			,"Dreambox"		, "VARIABLE"    ,19015),	   	
				);
				

//******************************************************************************
//	Lichtnamen
//******************************************************************************
	GLOBAL $AlexaLightArray;
	$AlexaLightArray = array( 
				//	   spoken         spoken           response         IPSLight Name
			   array("wohnzimmer"	,""				,"Wohnzimmer"	,"Wohnen Deckenlicht"			,true	,true	,false),
			   array("esszimmer"	,"decke"		,"Esszimmer"	,"Essen Deckenlicht"			,true	,true	,false),
			   array("esszimmer"	,"lampe"		,"Esszimmer"	,"Essen Steinlampe"				,true	,false	,false),
         );





?>