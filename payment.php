<?
/*
Copyright (c) 2013
Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
*/

//$orderid = "testorder";
//$ordertotal = 0.5;//euro or usd

include("lib/config.php");
include("lib/Gox.class.php");
$gox = new Gox($apikey, $secret, $currency);
echo "<center>";
//print_r($gox->getDepth());
//print_r($gox->getInfo());

//prepare order
$ticker = $gox->ticker();
$btcprice = $ticker['return']['last_all']['value'];
$ordertotalbtc = round($ordertotal/$btcprice,8);
	
//show order
echo $lang_orderid . $orderid;
echo "<br>" . $lang_total . $ordertotal ." " . $currency;
echo "<hr>" . $lang_currentprice. round($btcprice,2) ." " .$currency;
echo "<br>" . $lang_ordertotal . $ordertotalbtc." BTC";
echo"<hr>";

//show payment info
$add = $gox->btcAddress($orderid);
$addr = $add['addr'];

echo $lang_send . $ordertotalbtc . " BTC " . $lang_to . "<br>";
echo "<img src='qr.php?addr=".$addr."&amount=".$ordertotalbtc."&orderid=".$orderid."' width=264 height=264>";
echo "<hr>";
echo "<a href='bitcoin:".$addr."?amount=".$ordertotalbtc."&label=".$orderid."' title='".$lang_linktowallet."'>".$addr."</a>";
?>
<script language="Javascript">
function xmlhttpPost(strURL) {
    var xmlHttpReq = false;
    var self = this;
    // Mozilla/Safari
    if (window.XMLHttpRequest) {
        self.xmlHttpReq = new XMLHttpRequest();
    }
    // IE
    else if (window.ActiveXObject) {
        self.xmlHttpReq = new ActiveXObject("Microsoft.XMLHTTP");
    }
    self.xmlHttpReq.open('POST', strURL, true);
    self.xmlHttpReq.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    self.xmlHttpReq.onreadystatechange = function() {
        if (self.xmlHttpReq.readyState == 4) {
            updatepage(self.xmlHttpReq.responseText);
        }
    }
    self.xmlHttpReq.send(getquerystring());
}

function getquerystring() {
    var form = document.forms['f1'];
    var post = form.post.value;
    qstr = 'addpost=' + escape(post);  // no '?' before querystring
    return qstr;
}

function updatepage(str){
    document.getElementById("result").innerHTML = str;
	if (document.getElementById("confirmed")) {
		window.location.href = "paymentconfirmed.php?addpost=<?echo $addr;?>";
	}
	setTimeout ('xmlhttpPost("paymentcheck.php")', 7000);
}
</script>
<br><br><? echo $lang_help; ?><br><br>
<form name="f1">
  <input name="post" type="hidden" value="<?echo $addr;?>"> 
  <input value="<? echo $lang_paymentfinished;?>" type="button" name="btn" onclick="JavaScript:xmlhttpPost('paymentcheck.php');this.disabled=true"></p>
  <div id="result"></div>
</form>
</center>