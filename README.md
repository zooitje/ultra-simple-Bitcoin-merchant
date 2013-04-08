ultra simple Bitcoin merchant

this is a (very) simple Bitcoin payment system in PHP written conform the Lazy API (https://en.bitcoin.it/wiki/Lazy_API)

features
- offer Bitcoin payments on your website in minutes
- quick setup
- easy configuration
- suitable for the incompetent webdeveloper
- few lines of code
- multi language (french, spanish, portuguese, dutch, english)
- multi currency (USD and EUR)
- no database
- no pesky filesystem rights issues
- no shopping cart
- no order handling
- no backend
- no formatting and stylesheets
- easy to implement in existing websites
- easy expandable
- no validation (not sure if that's a good thing ;-)

requirements
- PHP
- curl
- unverified mt Gox account

setup
- copy the files to a webfolder
- set the key and secret in /lib/config.php (you get them from your mtgox.com account)
- communicate your url
- BTC!

customize
- you can just place a link on your website to offer Bitcoin payments. here's an example of 0.5 USD or EUR you can copy paste to your website: 
<a href=payment.php?orderid=donation&ordertotal=0.5>donate BTC</a>
- you can change the currency from USD to EUR
- you can set the language

donations
- feel free to donate BTC: 1NbW9pAncJs9pPF4qZ57cGbpBhwaH9DEjh

disclaimer
- this software is not fully tested except that payments arrive at mt gox if all is well ;-)
- use it at your own risk