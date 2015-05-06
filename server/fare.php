<?php
error_reporting(E_ALL);

include('connection.php');


$city = substr($device, 0, 3);


// ID=\"$requestorid\"/>
$request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<KIU_AirPriceRQ EchoToken=\"$EchoToken\" TimeStamp=\"$TimeStamp\" Target=\"$Target\" Version=\"3.0\" SequenceNmbr=\"$SequenceNmbr\" PrimaryLangID=\"en-us\">
	<POS>
		<Source AgentSine=\"$sine\" PseudoCityCode=\"$city\" ISOCountry=\"$country\" ISOCurrency=\"$currency\" TerminalID=\"$device\">
			<RequestorID Type=\"5\"/>
			<BookingChannel Type=\"1\"/>
		</Source>
	</POS>
	<AirItinerary>
		<OriginDestinationOptions>
			<OriginDestinationOption>\n";
$passengerCount=0;

for ($i=0;$i<$_GET['Segments'];$i++) {
	
	$ddatetime = $_GET["DepartureDateTime_$i"];
	$adatetime = $_GET["ArrivalDateTime_$i"];
	$flight = $_GET["FlightNumber_$i"];
	$class = $_GET["ResBookDesigCode_$i"];
	$source = $_GET["DepartureAirport_$i"];;
	$dest = $_GET["ArrivalAirport_$i"];
	$airline = $_GET["MarketingAirline_$i"];
	
	$passengerCount= $_GET["PassengerTypeQuantity_$i"];
	
	$request .= "\t\t\t\t<FlightSegment   DepartureDateTime=\"$ddatetime\" ArrivalDateTime=\"$adatetime\" FlightNumber=\"$flight\" ResBookDesigCode=\"$class\" >
					<DepartureAirport LocationCode=\"$source\"/>
					<ArrivalAirport LocationCode=\"$dest\"/>
					<MarketingAirline Code=\"$airline\"/>
				</FlightSegment>\n";
}



$request .= "\t\t\t</OriginDestinationOption>
		</OriginDestinationOptions>
	</AirItinerary>
<TravelerInfoSummary>
		<AirTravelerAvail >
		<PassengerTypeQuantity Code=\"ADT\" Quantity=\"$passengerCount\"/>
		</AirTravelerAvail>
	</TravelerInfoSummary>
</KIU_AirPriceRQ>";

$conn = new Connection($user, $password);
$response = $conn->SendMessage($request);

//echo "<pre>" . htmlspecialchars($request) . "</pre>";
//echo "<pre>" . htmlspecialchars($response) . "</pre>";

$xml = simplexml_load_string($response);

if ($xml->Error) {
	$ErrorCode = $xml->Error->ErrorCode;
	$ErrorMsg = $xml->Error->ErrorMsg;
	echo "<b>Error $ErrorCode: $ErrorMsg</b>";
} else {
	echo "<pre>";
	foreach ($xml->PricedItineraries->PricedItinerary as $pi) {
		echo "<b>Fare: </b>" . $pi->AirItineraryPricingInfo->ItinTotalFare->BaseFare['Amount'] . "<br/>";
		echo "<b>Taxes: </b><br/>";
		foreach ($pi->AirItineraryPricingInfo->ItinTotalFare->Taxes->Tax as $t) {
			echo "\t($t[TaxCode]) " . str_pad($t['Amount'], 10, " ", STR_PAD_LEFT) . "<br/>";
		}
		echo "<b>Total: </b>" . $pi->AirItineraryPricingInfo->ItinTotalFare->TotalFare['Amount'];
	}
	echo "</pre>";
	echo '<input type="button" value="Book this Itinerary" onclick="booking();">';
}
echo "<pre>REQUEST: " . htmlentities(print_r($request, true)) . "<br>" . "RESPONSE: " . htmlentities(print_r($response, true)) . "</pre>";

?>
