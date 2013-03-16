<?
//required number of confirmations on the blockchain to accept the payment (0 is fine for donations), 
//a minimum of 1 is advised for products (downloads for example) but payment takes around 10 minutes
$confirmations = 0;

$currency = "USD"; //EUR or USD

$language = "ENG"; //NL, ES, FR, POR or ENG

//required keys from mtgox.com 
//get them here: mtgox.com > security center > advanced API key creation (rights: deposit)
$apikey = "u32YOURw-5KEY-GOES-432d-HERE34a45aa3"; //mt gox api
$secret = "jsJ6k232YOURkjsi446dj2SECRET9sd24kGOESiu34k2ef1HERE9k14m452kqj4J7eF92MH7hd8jd&jds8dsjs=="; //mt gox secret

//do not make changes below
include("lang_".$language.".php");
?>