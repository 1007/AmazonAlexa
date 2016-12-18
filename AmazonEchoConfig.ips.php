<?

	GLOBAL $applicationIdValidation;
	GLOBAL $userIdValidation;           
	GLOBAL $echoServiceDomain;         
	GLOBAL $debug;
	GLOBAL $logging;
	GLOBAL $parentDataID;
	GLOBAL $AlexaGeraetArray;

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

	$AlexaMasterKeyArray = array( 
				
	array("search('licht') AND ( search('schalte') OR search('dimme') ) "			,"AmazonEcho_Licht.ips.php"		),
	array("search('temperatur') AND search('hoch') "													,"AmazonEcho_Temperatur.ips.php"),
				);







?>