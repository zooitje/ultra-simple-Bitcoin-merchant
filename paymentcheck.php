<?
include("lib/config.php");
include("lib/Gox.class.php");
$gox = new Gox($apikey, $secret, $currency);

$transaction = $gox->getreceivedbyaddress($addpost,0);
if ($transaction == 0){
	echo $lang_notfound;
}else{
	//transaction exists, wait for set amount of confirmations
	$received = $gox->getreceivedbyaddress($addpost,$confirmations);
	//show payment status
	if ($received == 0) {
		echo $lang_wait;
		$rnd = rand(1, 3);
		if ($rnd == 1){
			echo ".";
		} elseif ($rnd == 2){
			echo "..";
		} else {
			echo "...";
		}
	} else {
		echo "<div id='confirmed'>".$lang_found."</div>";
	}
}
?>