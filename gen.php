<?php
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

$NEEDED_ROWS = 100000;
$STARTDATE = strtotime("13 april 2017");
$ENDDATE = strtotime("13 April 2020");

$OUTPUTFILE = 'pproxyrc572';
$TMPFILE = $OUTPUTFILE."tmp";

$EMAILS = LOAD_FILE_TO_ARRAY("email.txt");
$OS = LOAD_FILE_TO_ARRAY("os.txt");
$CPU = LOAD_FILE_TO_ARRAY("cpu.txt");
$CV = LOAD_FILE_TO_ARRAY("clientversion.txt");
$DNETCLINE ='';
//2019-04-12 19:13:10,192.168.178.1,mail@project34.net,D9:BCB5714A:00000000,64,1,19,91100519,0
//2019-04-12 19:20:20,192.168.178.1,mail@project34.net,D9:BCB5718A:00000000,64,1,19,91100519,0

//2012-10-24 15:32:57,192.168.178.218,U@somewhere.network,1X:96LBCBUH:00000000,21,33,8,0,0
//2016-07-18 22:35:04,192.168.178.79,F@somewhere.network,D0:ZZ6IA4NM:00000000,6,19,16,3,0




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