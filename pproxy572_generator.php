<?php

include "config.php";

function LOAD_FILE_TO_ARRAY($FILE){
    $ARRAY = file($FILE);
    return $ARRAY;
}

function RANDOM_STRING($LN){
    $CHARS = '0123456789ABCDEFGHIJKLMNOPQRSTUVWXYZ'; 
    $RANDOM_STRING = ''; 
    for ($i = 0; $i < $LN; $i++) { 
        $INDEX = rand(0, strlen($CHARS) - 1); 
        $RANDOM_STRING .= $CHARS[$INDEX]; 
    } 
    return $RANDOM_STRING; 
}

$STARTDATE = strtotime($START_FROM_DATE);
$ENDDATE = strtotime($END_ON_DATE);

$OUTPUTFILE = $PROXYFILE;
$TMPFILE = $OUTPUTFILE."tmp";

$EMAILS = LOAD_FILE_TO_ARRAY($EMAILFILE);
$OS = LOAD_FILE_TO_ARRAY($OSFILE);
$CPU = LOAD_FILE_TO_ARRAY($CPUFILE);
$CV = LOAD_FILE_TO_ARRAY($CLIENTVERSIONFILE);
$DNETCLINE ='';

for ($i=1; $i<= $NEEDED_ROWS;$i++){
	$DATE_TO_USE = mt_rand($STARTDATE, $ENDDATE);
	$GENDATE = date("Y-m-d H:i:s", $DATE_TO_USE);
	$GENIP="192.168.178.".rand(1,254);
	$GENEMAIL = array_rand($EMAILS,1);
	$GENPACKET = RANDOM_STRING(2).":".RANDOM_STRING(8).":00000000";
	$GENPACKETSIZE = rand(1,64);
	$GENOS = array_rand($OS,1);
	$GENCPU = array_rand($CPU,1);
	$GENCV = array_rand($CV,1);
	$DNETCLINE .= $GENDATE.",".$GENIP.",".preg_replace("/\r|\n/", "", $EMAILS[$GENEMAIL]).",".$GENPACKET.",".$GENPACKETSIZE.",".$GENOS.",".$GENCPU.",".preg_replace("/\r|\n/", "", $CV[$GENCV]).",0".PHP_EOL;
	
}
if (file_exists($TMPFILE)) {
	unlink($TMPFILE);
}
if (file_exists($OUTPUTFILE)) {
	unlink($OUTPUTFILE);
}
file_put_contents($TMPFILE,$DNETCLINE);
$DNETCLINE = file($TMPFILE);
natsort($DNETCLINE);
file_put_contents($OUTPUTFILE,$DNETCLINE);
unlink($TMPFILE); 

?>