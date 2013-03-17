<?
session_start();

include("lib/config.php");
include("lib/Gox.class.php");
$gox = new Gox($apikey, $secret, $currency);

$transaction = $gox->getreceivedbyaddress($addpost,0);
echo "<br><br><br><br><br><br><br><br><center>";
if ($transaction == 0){
	//transaction not found on the blockchain
	echo $lang_notfound;
} else {
	//how much BTC was send?
	$received = $gox->getreceivedbyaddress($addpost,$confirmations)/100000000;
	if ($received >= $_SESSION['ordertotalbtc'] && $addpost == $_SESSION['addr']){
		echo $received . " BTC ".$lang_paid."<hr>";
		if ($confirmations == 0){
			echo $lang_unconfirmed;
		}else{
			echo $lang_confirmed;
			//embed your downloadable product here
		}
	}else{
		//wrong hash or prices doesn't match received BTC
		echo $lang_error."<hr>";
		if	($received < $_SESSION['ordertotalbtc']) {
			echo $lang_received . "(" . $received . " BTC)" . $lang_wrongamount . "(" . $_SESSION['ordertotalbtc'] . " BTC)";
		}else {
			echo $lang_wronghash;
		}
	}
}
?>