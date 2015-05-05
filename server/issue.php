<?php
error_reporting(E_ALL);
include('connection.php');



$request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<KIU_AirDemandTicketRQ EchoToken=\"$EchoToken\" TimeStamp=\"$TimeStamp\" Target=\"$Target\" Version=\"3.0\" SequenceNmbr=\"$SequenceNmbr\" PrimaryLangID=\"en-us\">
	<POS>
		<Source AgentSine=\"$sine\" TerminalID=\"$device\" ISOCountry=\"$country\" ISOCurrency=\"$currency\">
			<RequestorID Type=\"5\"/>
			<BookingChannel Type=\"1\"/>
		</Source>
	</POS>
	<DemandTicketDetail TourCode=\"$_GET[TourCode]\">
	<BookingReferenceID ID=\"$_GET[BookingID]\">
		<CompanyName Code=\"XX\"/>
	</BookingReferenceID>";
	switch ($_GET['PaymentType']) {
		case 5:
			$request .= "
	<PaymentInfo PaymentType=\"5\">
	<CreditCardInfo CardType=\"1\" CardCode=\"$_GET[CreditCardCode]\" CardNumber=\"$_GET[CreditCardNumber]\" SeriesCode=\"$_GET[CreditSeriesCode]\" ExpireDate=\"$_GET[CreditExpireDate]\"/>
	";
			break;
	
		case 6:
			$request .= "
	<PaymentInfo PaymentType=\"6\">
	<CreditCardInfo CardType=\"1\" CardCode=\"$_GET[DebitCardCode]\" CardNumber=\"$_GET[DebitCardNumber]\" SeriesCode=\"$_GET[DebitSeriesCode]\" />
	";
			break;
	
		case 34:
			$request .= "
	<PaymentInfo PaymentType=\"34\" InvoiceCode=\"$_GET[InvoiceCode]\">
	";
			break;
		case 37:
			$request .= "
	<PaymentInfo PaymentType=\"37\" MiscellaneousCode=\"$_GET[MiscellaneousCode]\" Text=\"$_GET[Text]\">
	";
			break;
		case 1:
			$request .= "
	<PaymentInfo PaymentType=\"1\">
	";
			break;
	}
	$request .= "<ValueAddedTax VAT=\"$_GET[VAT]\"/>
	</PaymentInfo>
	<Endorsement Info=\"THIS TICKET IS NONREFUNDABLE\"/>
	</DemandTicketDetail>
</KIU_AirDemandTicketRQ>";

$conn = new Connection($user, $password);
$response = $conn->SendMessage($request);

$xml = simplexml_load_string($response);

if ($xml->Error) {
	$ErrorCode = $xml->Error->ErrorCode;
	$ErrorMsg = $xml->Error->ErrorMsg;
	echo "<b>Error $ErrorCode: $ErrorMsg</b><br/><input type=\"button\" value=\"Back\" onclick=\"booking();\"/>";
} else {
	echo "<pre>";
	foreach ($xml->TicketItemInfo as $ticket) {
		$TicketNumber = $ticket['TicketNumber'];
		$CommissionAmount = $ticket['CommissionAmount'];
		$TotalAmount = $ticket['TotalAmount'];
		$GivenName = $ticket->PassengerName->GivenName;
		$Surname = $ticket->PassengerName->Surname;
		echo "<hr/>	
<b>Ticket:</b> $TicketNumber
<b>Amount:</b>  $TotalAmount
<b>Commision:</b>$CommissionAmount
<b>GivenName:</b>$GivenName
<b>Surname:</b>$Surname
		";
	}
	echo "</pre>";
}

/*echo "<pre>REQUEST: " . htmlentities(print_r($request, true)) . "<br>" . "RESPONSE: " . htmlentities(print_r($response, true)) . "</pre>";
*/
?>
