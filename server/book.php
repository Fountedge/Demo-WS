<?php
error_reporting(E_ALL);
include('connection.php');


$city = substr($device, 0, 3);


$request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<KIU_AirBookRQ EchoToken=\"$EchoToken\" TimeStamp=\"$TimeStamp\" Target=\"$Target\" Version=\"3.0\" SequenceNmbr=\"$SequenceNmbr\" PrimaryLangID=\"en-us\">
	<POS>
		<Source AgentSine=\"$sine\" PseudoCityCode=\"$city\" ISOCountry=\"$country\" ISOCurrency=\"$currency\" TerminalID=\"$device\">
			<RequestorID Type=\"5\"/>
			<BookingChannel Type=\"1\"/>
		</Source>
	</POS>
	<AirItinerary>
		<OriginDestinationOptions>
			<OriginDestinationOption>";

for ($i=0;$i<$_POST['Segments'];$i++) {
	$passenger = $_POST["PassengerTypeQuantity"];	
	$ddatetime = $_POST["DepartureDateTime_$i"];
	$adatetime = $_POST["ArrivalDateTime_$i"];
	$flight = $_POST["FlightNumber_$i"];
	$class = $_POST["ResBookDesigCode_$i"];
	$source = $_POST["DepartureAirport_$i"];;
	$dest = $_POST["ArrivalAirport_$i"];
	$airline = $_POST["MarketingAirline_$i"];
	$rph = sprintf("%02d", $i + 1);
	
	$request .= "<FlightSegment PassengerTypeQuantity=\"$passenger\" DepartureDateTime=\"$ddatetime\" ArrivalDateTime=\"$adatetime\" FlightNumber=\"$flight\" ResBookDesigCode=\"$class\" RPH=\"$rph\">
					<DepartureAirport LocationCode=\"$source\"/>
					<ArrivalAirport LocationCode=\"$dest\"/>
					<MarketingAirline Code=\"$airline\"/>
				</FlightSegment>";
}
$request .= "</OriginDestinationOption>
		</OriginDestinationOptions>
	</AirItinerary>
	<TravelerInfo>
		<AirTraveler PassengerTypeQuantity=\"ADT\ Quantity =\"$passenger\">
			<PersonName>
				<GivenName>$_POST[FirstName]</GivenName>
				<Surname>$_POST[LastName]</Surname>
			</PersonName>
			<Telephone PhoneNumber=\"$_POST[Telephone]\"/>
			<Email>$_POST[Email]</Email>
			<Document DocID=\"$_POST[DocID]\" DocType=\"$_POST[DocType]\"></Document>
			<TravelerRefNumber RPH=\"01\"/>
		</AirTraveler>
	</TravelerInfo>
	<Ticketing TicketTimeLimit=\"1\" />
</KIU_AirBookRQ>";

$conn = new Connection($user, $password);
$response = $conn->SendMessage($request);

//echo "<pre>" . htmlspecialchars($response) . "</pre>";
//exit(1);

$xml = simplexml_load_string($response);

if ($xml->Error) {
	$ErrorCode = $xml->Error->ErrorCode;
	$ErrorMsg = $xml->Error->ErrorMsg;
	echo "<b>Error $ErrorCode: $ErrorMsg</b><br/><input type=\"button\" value=\"Back\" onclick=\"booking();\"/>";
} else {
	$i = 1;

	echo '<fieldset><legend>Booking:</legend>	<pre>';
	echo '<b>Code:</b><br/>' . $xml->BookingReferenceID['ID'] . '<br/><br/><b>Paxs:</b><br/>';
	foreach ($xml->TravelerInfo->AirTraveler as $pax) {
		echo $i . '. ' 
			. $pax->PersonName->GivenName . ' ' . $pax->PersonName->Surname . ' ' . $pax->Document['DocType'] . $pax->Document['DocID'] . ' (' . $pax['$passenger'] . '), '
			. 'Tel.' . $pax->Telephone['PhoneNumber'] . ' '
			. $pax->Email;
		$i++;
	}

	echo '<br/><br/><b>Itinerary:</b><br/>';

	$i = 1;
	foreach ($xml->AirItinerary->OriginDestinationOptions as $odo) {
		foreach ($odo->OriginDestinationOption as $o) {
			foreach ($o->FlightSegment as $f) {
				echo $i . '. ' 
					. $f->MarketingAirline['Code']
					. $f['FlightNumber'] . ' '
					. $f->DepartureAirport['LocationCode'] 
					. ' (' . $f['DepartureDateTime'] . ') -> '
					. $f->ArrivalAirport['LocationCode']
					. ' (' . $f['ArrivalDateTime'] . ') <br/>';
				$i++;
			}
		}
	}
	echo '</pre><input type="button" value="Back" onclick="booking();"/><input type="button" value="Issue" onclick="window.location=\'issue.php?Device=' . '&BookingID=' . $xml->BookingReferenceID['ID'] . '\'"/> </fieldset>';
}

/*echo "<pre>REQUEST: " . htmlentities(print_r($request, true)) . "<br>" . "RESPONSE: " . htmlentities(print_r($response, true)) . "</pre>";
*/
?>
