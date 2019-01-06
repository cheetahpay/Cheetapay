# Cheetapay
Official Cheetahpay API integration script. Cheetahpay is a payment gateway that helps users convert their airtime to cash.

## Getting Started

This script helps you to easily make curl requests to cheetahpay API endpoints and return responses as an array

## Important Notice

**Test Mode Valid Pin** = 1111222233334444

Logon to your Cheetahpay account and set the developer mode to Test Mode, Do not forget to reset it back to Live Mode when you move to production mode.

On test mode, any pin aside the above stated valid pin (1111222233334444) is deemed invalid.


### Prerequisites

Minimum requirement is PHP 5.4 and above


### Installing

Download this Github CheetahPay.php file and incluce it in your project

```php
require_once 'CheetahPay.php';
```

Then, initialize the class CheetahPay with your private and public keys

```php
$cheetahPay = new CheetahPay(YOUR_PRIVATE_KEY, YOUR_PUBLIC_KEY);
```

Ofcourse, replace the YOUR_PRIVATE_KEY and YOUR_PUBLIC_KEY with the Keys obtained in the developer section of your Cheetahpay dahboard.
Next use the instance of the class to call either the Pin deposit or airtime transfer methods

```php
// For airtime deposit
$response = $cheetahPay->pinDeposit($pin, $amount, $network, $orderID, $depositorsPhoneNo); // For Pin Deposit

// For airtime transfer
$response = $cheetahPay->airtimeTransfer($amount, $network, $depositorsPhoneNo, $orderID);

```

*Params*
 * **$pin** *The airtime pin of the recharge card*
 * **$amount** *The value of the recharge pin*
 * **$network** *Network has to be any of the following keywords: **MTN**, **AIRTEL**, **9 MOBILE**, **GLO**, **MTN TRANSFER** *
 * **$orderID** *[optional] A unique ID that you can use to identify this transaction. This ID is part of the parameters returned in the callback*
 * **$depositorsPhoneNo** *[optional] Phone number of depositor. (**Note: ** For airtime tranfer, Depositor's phone number is needed.)*

Analyze the returned response. All responses contains an associative array of 2 key value pairs, a **success** and **message** keys. 

Deposits are recorded in the CheetahPay server if and only if the **success** key value is true.

```php
if($response['success'] == true){
  // Airtime has been received, now awaiting validation
}else{
  // Deposit failed, See print out message
  print($response['message']);
}
```

After submitting the airtime pin, CheetahPay platform will validate the transaction in approximately 2mins, 
then notify you on the validity of the airtime through the callback API you supplied on the developer section.

The Callback data is in this form

```php
$response_data = [
	
	'private_key' => YOUR_PRIVATE_KEY,
	
	'public_key ' => YOU_PUBLIC_KEY,
	
	'mode' => string (live|test),
	
	'order_id' => integer|string, // [optional] A unique ID you supplied to enable you identify this transaction
	
	'status' => string (pending|credited|invalid),
	
	' amount ' => float,
				
	'phone' => string,
	
	'network' => string,
	
	'module_phone_no' => string, // Phone no forairtime transfer.
	
	'reference' => integer,
	
	'transaction_charge' => float,
	
	'balance_before_transaction' => float,
	
	' balance_after_transaction ' => float,
];
```

Use the status field to know the validity of the transaction.

*Possible statuses:*
* **credited** *Airtime is valid and  has been loaded successfully.*
* **invalid** *Airtime is invalid*
* **pending** *Airtime has not been loaded yet.*

*Possible Modes:*
* **live** *You are in production mode and all supplied airtime is validated*
* **test** *In test Mode, All airtime supplied (except 1111222233334444) in this mode is deemed invalid.*


## Authors

* **Cheetahpay** - *Initial work* - [cheetahpay](https://github.com/cheetahpay)


## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

