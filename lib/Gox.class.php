<?php

/**
 * @package MTGox API
 * @author Chris S AKA Someguy123 -> messed up by zooitje
 * @version 0.1
 * @access public
 * @license http://www.opensource.org/licenses/LGPL-3.0
 */

class Gox
{
	private $key;
	private $secret;
	public $redeemd;       // Redeemed code information
	public $withdrew;      // Withdrawal information
    public $info;          // Result from getInfo()
    public $ticker;        // Current ticker (getTicker())
    public $depth;        // Current ticker (getdepth())
    public $btcAddress;        // Current address (btcAddress())
	/**
	 * Gox::__construct()
	 * Sets required key and secret to allow the script to function
	 * @param MtGOX API Key $key
	 * @param MtGOX Secret $secret
	 * @return
	 */
	public function __construct($key, $secret, $currency)
	{
		if (isset($currency))
		{
			$this->currency = $currency;
		} else {
			die("CURRENCY NOT SET");
		}
		if (isset($secret) && isset($key))
		{
			$this->key = $key;
			$this->secret = $secret;
		} else
			die("NO KEY/SECRET");
	}
	/**
	 * Gox::mtgox_query()
	 * 
	 * @param API Path $path
	 * @param POST Data $req
	 * @return Array containing data returned from the API path
	 */
	public function mtgox_query($path, array $req = array())
	{
		// API settings
		$key = $this->key;
		$secret = $this->secret;

		// generate a nonce as microtime, with as-string handling to avoid problems with 32bits systems
		$mt = explode(' ', microtime());
		$req['nonce'] = $mt[1] . substr($mt[0], 2, 6);

		// generate the POST data string
		$post_data = http_build_query($req, '', '&');

		// generate the extra headers
		$headers = array(
			'Rest-Key: ' . $key,
			'Rest-Sign: ' . base64_encode(hash_hmac('sha512', $post_data, base64_decode($secret), true)),
			);

		// our curl handle (initialize if required)
		static $ch = null;
		if (is_null($ch))
		{
			$ch = curl_init();
			curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($ch, CURLOPT_USERAGENT,
				'Mozilla/4.0 (compatible; MtGox PHP client; ' . php_uname('s') . '; PHP/' .
				phpversion() . ')');
		}
		//curl_setopt($ch, CURLOPT_URL, 'https://mtgox.com/api/' . $path);
		curl_setopt($ch, CURLOPT_URL, $path);//http://data.mtgox.com/api/1/BTCUSD/ticker
		curl_setopt($ch, CURLOPT_POSTFIELDS, $post_data);
		curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

		// run the query
		$res = curl_exec($ch);
		if ($res === false)
			throw new Exception('Could not get reply: ' . curl_error($ch));
		$dec = json_decode($res, true);
		if (!$dec)
			throw new Exception('Invalid data received, please make sure connection is working and requested API exists');
		return $dec;
	}

    /**
     * Gox::getreceivedbyaddress()
	 * @param hash of receive address $hash
	 * @param number of confirmations of the payment $checks
     * Returns information about a payment, if returns zero: no money received, oherwise the amount of bitcoins received is displayed
     * @return string $ret
     */
	function getreceivedbyaddress($hash,$checks=0){
			$crl = curl_init();
			$timeout = 5;
			curl_setopt ($crl, CURLOPT_URL, "http://blockchain.info/nl/q/addressbalance/".$hash."?confirmations=".$checks);
			
			//if ($checks == 0) {
			//	curl_setopt ($crl, CURLOPT_URL, "http://blockexplorer.com/q/getreceivedbyaddress/".$hash);
			//}else{
			//	curl_setopt ($crl, CURLOPT_URL, "http://blockexplorer.com/q/getreceivedbyaddress/".$hash."/".$checks);
			//}
			curl_setopt ($crl, CURLOPT_RETURNTRANSFER, 1);
			curl_setopt ($crl, CURLOPT_CONNECTTIMEOUT, $timeout);
			$ret = curl_exec($crl);
			curl_close($crl);
			return $ret;
	}
    
    /**
     * Gox::getInfo()
     * Returns information about your account, including funds, fees, and API priviledges
     * @return array $info
     */
    function getInfo() {
        $info = $this->mtgox_query('https://mtgox.com/api/0/info.php');
        $this->info = $info; // Hold it in a variable for easy access
        return $info;
    }
    
    /**
     * Gox::ticker()
     * Returns current ticker from MtGOX
     * @return $ticker
     */
    function ticker() {
        //$ticker = $this->mtgox_query('0/ticker.php');
        $ticker = $this->mtgox_query('http://data.mtgox.com/api/1/BTC'.$this->currency.'/ticker');
        $this->ticker = $ticker; // Another variable to contain it.
        return $ticker;
    }
	
    /**
     * Gox::getDepth()
     * Returns current depth from MtGOX
     * @return $depth
     */
    function getDepth() {
        $depth = $this->mtgox_query('https://mtgox.com/api/0/data/getDepth.php');
        $this->depth = $depth; // Another variable to contain it.
        return $depth;
    }

	
    /**
     * Gox::btcAddress()
     * Returns btcaddress from MtGOX
     * @return $btcAddress
     */
    function btcAddress($addressid=null) {
		if (isset($addressid))
		{
			$btcAddress = $this->mtgox_query('https://mtgox.com/api/0/btcAddress.php', array('description' => $addressid));
        }else{
			$btcAddress = $this->mtgox_query('https://mtgox.com/api/0/btcAddress.php');
    	}
		$this->btcAddress = $btcAddress; // Another variable to contain it.
        return $btcAddress;
    }
	/**
	 * Gox::redeemCode()
	 * Redeems a MTGox code passed to it.
	 * Returns an array containing transaction information
	 * @param MTGox Code $code
	 * @return array($redeem['amount'],	$redeem['currency'], $redeem['reference'], $redeem['status']);
	 * @return on failure: FALSE
	 */
	function redeemCode($code)
	{
		if (isset($code))
		{
			$redeem = $this->mtgox_query('https://mtgox.com/api/0/redeemCode.php', array('code' => $code));
			if ($redeem['error']) // If there was an error
			{
				$this->redeemd = false; // Saving into a variable allows it to be captured much easier sometimes, so I'll let it do that.
				return false;
			} else // There wasn't an error... let's assume it was successful...
			{
				$this->redeemd = $redeem;
				return $redeem; // Returns array containing information from the server
			}
		} else
			die("Please specify a code.");
	}
	/**
	 * Gox::withdraw()
	 * 
	 * @param float $amount
	 * @param string USD2CODE BTC2CODE BTC (withdraw to address) $method
	 * @return array $withdraw = array returned by the server.
	 */
	function withdraw($amount, $method, $btca)
	{
	   /** Begin BTC/USDcode code */
		if ($method = "BTC2CODE" || $method = "USD2CODE")
		{ // Bitcoin code or USD code
			$withdraw = $this->mtgox_query('https://mtgox.com/api/0/withdraw.php', array('group1' => $method,
					'amount' => $amount));
			if ($withdraw['code'])
			{ // If a code came out
				$this->withdrew = $withdraw; // Legacy reporting into variables to make scripts slightly easier :)
				return $withdraw;
			} else // No code...
			{
				$this->withdrew = false;
				return false;
			}
		}
        /** End *code code */
        /** Begin BTC output code */
        /*
         * Warning, I have not yet experimented with direct bitcoin address withdrawals, USE AT YOUR OWN RISK!
         */
        if($method = "BTC" && isset($btca)) {
            $withdraw = $this->mtgox_query('https://mtgox.com/api/0/withdraw.php', array('group1' => 'BTC', 'amount' => $amount, 'btca' => $btca));
            $this->withdrew = $withdraw; // More legacy reporting... may remove in the future if needed.
            return $withdraw;
        }
	}
}