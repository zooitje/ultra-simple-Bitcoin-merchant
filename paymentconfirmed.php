<?
include("lib/config.php");
include("lib/Gox.class.php");
$gox = new Gox($apikey, $secret, $currency);

$transaction = $gox->getreceivedbyaddress($addpost,0);
echo "<br><br><br><br><br><br><br><br><center>";
if ($transaction == 0){
	echo $lang_notfound;
} else {
	$received = $gox->getreceivedbyaddress($addpost,$confirmations);
	
	echo $received/100000000 . " BTC ".$lang_paid."<hr>";
	if ($confirmations == 0){
		echo $lang_unconfirmed;
	}else{
		echo $lang_confirmed;
		//embed your downloadable product here
	}
}
?>