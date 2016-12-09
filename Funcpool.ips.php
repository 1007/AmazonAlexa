<?
//******************************************************************************
//	Name			:	Funcpool.ips.php
//	Aufruf		:	
//	Info			:	Funktionspool
//	Funktionen	:
//					
//					
//
//******************************************************************************

//******************************************************************************
// Wake on LAN
//******************************************************************************
function WakeOnLan($mac = "708BCD57E7E5",$port = 9)
	{
	$debug = true;
	
 	$ip = "255.255.255.255"; 		// Broadcast adresse
	
	$port = 7;
  	$nic = fsockopen("udp://" . $ip, $port);
  	if($nic)
		{
    	$packet = "";
    	for($i = 0; $i < 6; $i++)
      	$packet .= chr(0xFF);
    	for($j = 0; $j < 16; $j++)
    		{
      	for($k = 0; $k < 6; $k++)
      		{
        		$str = substr($mac, $k * 2, 2);
        		$dec = hexdec($str);
        		$packet .= chr($dec);
      		}
    		}
   	$ret = fwrite($nic, $packet);
   	fclose($nic);

		if ( $debug ) IPSLogger_Dbg(__FILE__,'WakeonLAN:'.$mac." Port: ". $port);
   	
		}
		
		
	$port = 9;
  	$nic = fsockopen("udp://" . $ip, $port);
  	if($nic)
		{
    	$packet = "";
    	for($i = 0; $i < 6; $i++)
      	$packet .= chr(0xFF);
    	for($j = 0; $j < 16; $j++)
    		{
      	for($k = 0; $k < 6; $k++)
      		{
        		$str = substr($mac, $k * 2, 2);
        		$dec = hexdec($str);
        		$packet .= chr($dec);
      		}
    		}
   	$ret = fwrite($nic, $packet);
   	fclose($nic);

		if ( $debug ) IPSLogger_Dbg(__FILE__,'WakeonLAN:'.$mac." Port: ". $port);
   	
		}	
	}
//******************************************************************************



?>