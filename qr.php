<?
include "phpqrcode/qrlib.php";

$backColor = 0xFFFF00;
$foreColor = 0xFF00FF;
QRcode::png("bitcoin:".$addr."?amount=".$amount."&label=".$orderid, false, "L", 8, 0, false, $backColor, $foreColor);
?>