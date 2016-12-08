<?

	GLOBAL $applicationIdValidation;
	GLOBAL $userIdValidation;           
	GLOBAL $echoServiceDomain;         
	GLOBAL $debug;
	GLOBAL $logging;
	GLOBAL $parentDataID;
	GLOBAL $AlexaArray;

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
//
//	CommandID	
//		1	Licht
//		2	Temperatur
//		3	Duftsteuerung
//		4	Stromverbrauch
//		5	Aktiviere
//		6	Bericht
//		7	Rollladen
//		8	Schalte
//      9   Heizung
//      10  Starte Sequence
//      11
//		12
//      13
//      14
//		15
//
//******************************************************************************

	$AlexaArray = array( 
				
	array("search('licht') AND ( search('schalte') OR search('dimme') ) "								,"AmazonEcho_Licht.ips.php"		),
	array("search('temperatur') AND search('hoch') "													,"AmazonEcho_Temperatur.ips.php"),
	array("search('duft') OR  search('pyramide') OR  search('pyramiden') OR  search('lavendel') "		,"AmazonEcho_Duft.ips.php"		),		   	
	array("search('stromverbrauch') AND  search('hoch') "												,"AmazonEcho_Strom.ips.php"		),
	array("search('aktiviere') OR  search('deaktiviere')"												,"AmazonEcho_Modus.ips.php"		),
	array("search('bericht') "																			,"AmazonEcho_Bericht.ips.php"	),
	array("search('rollladen') AND  search('fahre') "													,"AmazonEcho_Rollladen.ips.php"	),
	array("search('schalte') "																			,"AmazonEcho_Schalte.ips.php"	),
	array(""																							,"AmazonEcho_Default.ips.php"	),
	array("search('starte') OR search('sequence') OR ('script') "										,"AmazonEcho_Starte.ips.php"	),
	array(""																							,"AmazonEcho_Default.ips.php"	),
	array(""																							,"AmazonEcho_Default.ips.php"	),
	array(""																							,"AmazonEcho_Default.ips.php"	),
	array(""																							,"AmazonEcho_Default.ips.php"	),
	array(""																							,"AmazonEcho_Default.ips.php"	),
	array(""																							,"AmazonEcho_Default.ips.php"	),
	array(""																							,"AmazonEcho_Default.ips.php"	),
	array(""																							,"AmazonEcho_Default.ips.php"	),
				);







?>