<script src="jquery-2.1.1.js"></script>


<form method="post">

	<input name="routing_number" value="<?=$_POST["routing_number"]?>"placeholder="Routing Number">
	<br/>
	<input name="account_number" value="<?=$_POST["account_number"]?>"  placeholder="Account Number">
	<br/>
	<input name="bank_name" id="bank_name" value="<?=$_POST["bank_name"]?>" placeholder="Bank Name">
	<br/>
	<input name="customer_name" id="customer_name" value="<?=$_POST["customer_name"]?>" placeholder="Customer Name on Bank Account">
	<br/>
	Bank Account Type:
	<br/>
	<select name="bank_account_type" id="bank_account_type">
		<option value="business checking"> Business Checking</option>
		<option value="checking"> Checking</option>
		<option value="savings"> Savings</option>
	</select>
	<br/>
	ECHECK Type<br/>
	<select name="echeck_type" >
		<option value="ppd"> PPD</option>
		<option value="web"> web</option>

	</select>
	<br/>

	<br/>
	<button >Drain!</button>

</form>
<script>
	function check_account_type(){

		console.log("called");

		if($("#bank_account_type").val()=="business checking") 
			$('[name=echeck_type]').attr("disabled",true) 
		else 
			$('[name=echeck_type]').removeAttr('disabled');
	}
	check_account_type();

	$("#bank_account_type").change(check_account_type).click(check_account_type);

</script>
<?
require("vendor/autoload.php");

if(!$_POST)
	die();

define("AUTHORIZENET_API_LOGIN_ID", "599uBzqfUB");
define("AUTHORIZENET_TRANSACTION_KEY", "9RS5T7gJ68b4t2xH");
define("AUTHORIZENET_SANDBOX", true);
$auth         = new AuthorizeNetAIM;
$auth->amount = "45.00";

// Use eCheck:
$auth->setECheck(
    $_POST['routing_number'],
    $_POST["account_number"],
    strtoupper($_POST["bank_account_type"]),
    $_POST["bank_name"],
    $_POST["customer_name"],
    strtoupper($_POST["bank_account_type"]=="business checking"?"ccd":$_POST["echeck_type"])
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

?>
<h2>Authorize Response</h2>
<table>

	<?php foreach($response as $key=>$value){
	?>

	<tr><td><?=$key?></td><td><?=$value?></td></tr>

	<?

	}?>
</table>

<?php
if ($response->approved) {


    $auth_code = $response->transaction_id;
	
    // Now capture:
    $capture = new AuthorizeNetAIM;
    $capture_response = $capture->priorAuthCapture($auth_code);
?>

<h2>Capture Response</h2>
<table>
	<?php foreach($capture_response as $key=>$value){
	?>

	<tr><td><?=$key?></td><td><?=$value?></td></tr>

	<?

	}?>
</table>

<?}?>

