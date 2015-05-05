<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<title>WS3 Test Page</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" /> 
	<script type="text/javascript" src="app.js"></script>
	<LINK REL=StyleSheet HREF="scc/style.css" TYPE="text/css"></LINK>
<script>
function changepayment(s) {
	if (s.value == 5) {
		show('credit');
		hide('debit');
		hide('invoice');
		hide('misc');
		hide('cash');
	} else if(s.value == 6) {
		hide('credit');
		show('debit');
		hide('invoice');
		hide('misc');
		hide('cash');
	} else if(s.value == 34) {
		hide('credit');
		hide('debit');
		show('invoice');
		hide('misc');
		hide('cash');
	} else if(s.value == 37) {
		hide('credit');
		hide('debit');
		hide('invoice');
		show('misc');
		hide('cash');
	} else if (s.value == 1) {
		hide('credit');
		hide('debit');
		hide('invoice');
		hide('misc');
		show('cash');
	}
}
</script>
</head>
<body>

<div id="issue">
	<form action="https://stageserv.interswitchng.com/test_paydirect/pay" name="issue_form" >
	  <fieldset>
		<legend>Issue Ticket:</legend>
		
		Booking ID: <input type="text" name="BookingID" value="<?=$_GET['BookingID']?>"/><br/>
		Tour Code: <input type="text" name="TourCode"/><br/>
		Payment Type: <select name="PaymentType" onchange="changepayment(this);"> <option value="5">CreditCard</option><option value="6">DebitCard</option><option value="34">Invoice</option><option value="37">Miscellaneous</option><option value="1">Cash</option></select>
		<div id="credit">
			Card Code: <input type="text" name="CreditCardCode"/><br/>
			Card Number: <input type="text" name="CreditCardNumber"/><br/>
			Series Code: <input type="text" name="CreditSeriesCode"/><br/>
			Expire Date: <input type="text" name="CreditExpireDate"/><br/>
		</div>
		<div id="debit" style="visibility:hidden; height:0px;">
			Card Code: <input type="text" name="DebitCardCode"/><br/>
			Card Number: <input type="text" name="DebitCardNumber"/><br/>
			Series Code: <input type="text" name="DebitSeriesCode"/><br/>
		</div>
		<div id="invoice" style="visibility:hidden; height:0px;">
			InvoiceCode: <input type="text" name="InvoiceCode"/><br/>
		</div>
		<div id="misc" style="visibility:hidden; height:0px;">
			MiscellaneousCode: <input type="text" name="MiscellaneousCode"/><br/>
			Text: <input type="text" name="Text"/><br/>
		</div>
		<div id="cash" style="visibility:hidden; height:0px;">
		</div>
		 
		<input type="button" value="Issue" onclick="issue();"/>
	  </fieldset>
	</form>
	

<div id="issue_result"></div>
