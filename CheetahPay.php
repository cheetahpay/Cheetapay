<?php

/**
 * CheetahPay API implementation
 *
 * PHP version 5
 *
 * @category Authentication
 * @package  CheetahPay
 * @author   CheetahPay Communications <b>cheetahfastpay@gmail.com</b>
 * @license  http://opensource.org/licenses/BSD-3-Clause 3-clause BSD
 * @link     https://github.com/cheetahpay
 */
class CheetahPay 
{
    private static $privateKey = ''; 
    private static $publicKey = ''; 
    private static $endpoint = 'https://cheetahpay.com/api/v1/';
    
    const NETWORK_9_MOBILE = '9 MOBILE';
    const NETWORK_AIRTEL = 'AIRTEL';
    const NETWORK_GLO = 'GLO';
    const NETWORK_MTN = 'MTN';
    const NETWORK_MTN_TRANSFER = 'MTN TRANSFER';
    
    /**
     * Initialize CheetahPay object with your private and public key
     * @param unknown $apiKey
     * @param unknown $merchantId
     */
    function __construct($privateKey, $publicKey) 
    {
        self::$privateKey = $privateKey;
        
        self::$publicKey = $publicKey;
    }
    
    private function formatPhoneNo($phone)
    {
        if(empty($phone)) return null; 
        
        $phone = ltrim($phone, '+234');
        
        $phone = ltrim($phone, '0');
        
        return '0' . $phone;
    }
    
    /**
     * Make a pin deposit
     * @param int $pin
     * @param int $amount
     * @param int $network
     * @param int $orderID A unique ID to identify this transaction. This iID will be return when airtime has been validated
     * @param unknown $depositorsPhoneNo
     * @return Array. The CheetahPay responses are already converted to array to ease use
     */
    function pinDeposit($pin, $amount, $network, $orderID = null, $depositorsPhoneNo = null) 
    {
        $this->verifyNetworkForPin($network);
        
        $curl_post_data = array
        (
            'amount' => $amount,
            'private_key' => self::$privateKey,
            'public_key' => self::$publicKey,
            'phone' =>  $this->formatPhoneNo($depositorsPhoneNo),
            'pin' => $pin,
            'network' => $network,
            'order_id' => $orderID,
        );
        
        $curl_response = $this->execute_curl($curl_post_data);
        
        return json_decode($curl_response, true);
        
    }
    
    private function verifyNetworkForPin($network)
    {
        if( $network == self::NETWORK_9_MOBILE || $network == self::NETWORK_AIRTEL
            || $network == self::NETWORK_GLO || $network == self::NETWORK_MTN) return true;
        
        $message = '<div>Only ';
        $message .= '<b>' . self::NETWORK_9_MOBILE . '</b>, ';
        $message .= '<b>' . self::NETWORK_AIRTEL . '</b>, ';
        $message .= '<b>' . self::NETWORK_GLO . '</b>, ';
        $message .= '<b>' . self::NETWORK_MTN . '</b>, ';
        $message .= ' are accepted for pin deposits';
        
        throw new \Exception($message);
    }
    
    /**
     * Deposit using airtime transfer
     * @param unknown $amount
     * @param unknown $network
     * @param unknown $depositorsPhoneNo
     * @param int $orderID A unique ID to identify this transaction. This iID will be return when airtime has been validated
     * @throws \Exception
     * @return mixed
     */
    function airtimeTransfer($amount, $network, $depositorsPhoneNo, $orderID) 
    {
        if( $network != self::NETWORK_MTN_TRANSFER)
        {
            $message = '<div>Only ';
            $message .= '<b>' . self::NETWORK_MTN_TRANSFER . '</b>, ';
            $message .= ' is accepted for airtime transfer';
            
            throw new \Exception($message);
        }
        
        if(empty($depositorsPhoneNo))
        {
            throw new \Exception('You must supply depositor\'s phone number');
        }
        
        $curl_post_data = array
        (
            'amount' => $amount,
            'private_key' => self::$privateKey,
            'public_key' => self::$publicKey,
            'phone' =>  $this->formatPhoneNo($depositorsPhoneNo),
            'network' => $network,
            'order_id' => $orderID,
        );
        
        $curl_response = $this->execute_curl($curl_post_data);
        
        return json_decode($curl_response, true);
    }
    
    /**
     * Helper function to create and execute curl requests
     * @param unknown $curl_post_data
     * @return mixed
     */
    private function execute_curl($curl_post_data) 
    {
        $curl = curl_init(self::$endpoint);
        
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($curl, CURLOPT_POST, true);
        curl_setopt($curl, CURLOPT_POSTFIELDS, $curl_post_data);
        
        $curl_response = curl_exec($curl);
        
        $httpErrorCode = curl_getinfo($curl, CURLINFO_HTTP_CODE);
        
        $error = curl_error($curl);
        curl_close($curl);
        
        if($error)
        {
            return json_encode(['success' => false, 'message' => $error]);
        }
        
        if(empty($curl_response) && $httpErrorCode != 200)
        {
            return json_encode(['success' => false,
                'message' => "Possibe error from server with status $httpErrorCode, try again later"]);
        }
        
        return $curl_response;
    }
    
    
}







