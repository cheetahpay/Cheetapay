# Cheetapay
Official Cheetahpay API integration script. Cheetahpay is a payment gateway that helps users convert their airtime to cash.

## Getting Started

This script helps you to easily make curl requests to cheetpay API endpoints and return responses as an array

### Prerequisites

Minimum requirement is PHP 5.4 and above


### Installing

Download this Github CheetahPay.php file and incluce it in your project

```
require_once 'CheetahPay.php';
```

Then, initialize the class CheetahPay with your private and public keys

```
$cheetahPay = new CheetahPay(YOUR_PRIVATE_KEY, YOUR_PUBLIC_KEY);
```

Ofcourse, replace the YOUR_PRIVATE_KEY and YOUR_PUBLIC_KEY with the Keys obtained in the developer section of your Cheetahpay dahboard.
Next use the instance of the class to call either the Pin deposit or airtime transfer methods

```
// For airtime deposit
$response = $cheetahPay->pinDeposit($pin, $amount, $network, $orderID, $depositorsPhoneNo); // For Pin Deposit

// For airtime transfer
$response = $cheetahPay->airtimeTransfer($amount, $network, $depositorsPhoneNo, $orderID);

```

*Params*
 * **$pin** *The airtime pin of the recharge card*
 * **$amount** *The value of the recharge pin*
 * **$network** *Network has to be any of the following keywords: **MTN**, **AIRTEL**, **9 MOBILE**, **GLO** *
 * **$orderID** *[optional] A unique ID that you can use to identify this transaction. This ID is part of the parameters returned in the callback*
 * **$depositorsPhoneNo** *[optional] Phone number of depositor. (**Note: ** For airtime tranfer, Depositor's phone number is needed.)*

Analyze the returned response.
```
if($response['success'] == true){
  // Airtime has been received, now awaiting validation
}else{
  // Deposit failed, See print out message
  print($response['message']);
}
```

## Authors

* **Cheetahpay** - *Initial work* - [cheetahpay](https://github.com/cheetahpay)


## License

This project is licensed under the MIT License - see the [LICENSE.md](LICENSE.md) file for details

