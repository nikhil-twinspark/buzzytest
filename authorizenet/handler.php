<?
require("vendor/autoload.php");

define("AUTHORIZENET_API_LOGIN_ID", "599uBzqfUB");
define("AUTHORIZENET_TRANSACTION_KEY", "9RS5T7gJ68b4t2xH");
define("AUTHORIZENET_SANDBOX", true);
$auth         = new AuthorizeNetAIM;
$auth->amount = "45.00";
$_POST=$_GET;
// Use eCheck:
$auth->setECheck(
    $_POST['routing_number'],
    $_POST["account_number"],
    strtoupper($_POST["bank_account_type"]),
    $_POST["bank_name"],
    $_POST["customer_name"],
    strtoupper($_POST["echeck_type"])
);

// Set multiple line items:
$auth->addLineItem('item1', 'Golf tees', 'Blue tees', '2', '5.00', 'N');
$auth->addLineItem('item2', 'Golf shirt', 'XL', '1', '40.00', 'N');

// Set Invoice Number:
$auth->invoice_num = time();

// Set a Merchant Defined Field:
$auth->setCustomField("entrance_source", "Search Engine");

// Authorize Only:
$response  = $auth->authorizeOnly();


if ($response->approved) {
    $auth_code = $response->transaction_id;
	print_r($response);
    // Now capture:
    $capture = new AuthorizeNetAIM;
    $capture_response = $capture->priorAuthCapture($auth_code);

    // Now void:
    $void = new AuthorizeNetAIM;
   // $void_response = $void->void($capture_response->transaction_id);
}else{
echo $response->response_reason_text;
}
?>

