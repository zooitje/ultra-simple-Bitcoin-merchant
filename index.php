<?
include("lib/config.php");
include("lib/Gox.class.php");

$gox = new Gox($apikey, $secret, $currency);
$ticker = $gox->ticker();
$btcprice = $ticker['return']['last_all']['value'];

echo "<center><br><br><img src='weAcceptBitcoin.png'><br><br><br><br>" . $lang_currentprice . round($btcprice,2) ." ".$currency?>
<hr>
<a href=payment.php?orderid=0.5_<?echo $lang_orderlabel;?>&ordertotal=0.5><?echo $lang_pay . " " . round(0.5/$btcprice,3) ." BTC (0.5 " . $currency .") ";?></a><br>
<a href=payment.php?orderid=1.0_<?echo $lang_orderlabel;?>&ordertotal=1.0><?echo $lang_pay . " " . round(1.0/$btcprice,3) ." BTC (1.0 " . $currency .") ";?></a>
<hr><?echo $lang_customtotal;?><br>
<form action='payment.php' method='get'>
<input type='hidden' name='orderid' value='<?echo $lang_orderlabel;?>'>
<input type='text' name='ordertotal' value='1.5'> <?echo $currency;?><br>
<input type='submit' value='<?echo $lang_topaymentsite;?>'>
</form>