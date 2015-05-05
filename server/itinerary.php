<?php
error_reporting(E_ALL);
include('connection.php');

$request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<KIU_TravelItineraryReadRQ EchoToken=\"$EchoToken\" TimeStamp=\"$TimeStamp\" Target=\"$Target\" Version=\"3.0\" SequenceNmbr=\"$SequenceNmbr\" PrimaryLangID=\"en-us\">
	<POS>
		<Source AgentSine=\"$sine\" TerminalID=\"$device\" >
		</Source>
	</POS>
	<UniqueID Type=\"14\" ID=\"$_GET[pnr]\" >
	</UniqueID>
</KIU_TravelItineraryReadRQ>";

$conn = new Connection($user, $password);
$response = $conn->SendMessage($request);
$xml = simplexml_load_string($response);

if ($xml->Error) {
	$ErrorCode = $xml->Error->ErrorCode;
	$ErrorMsg = $xml->Error->ErrorMsg;
	echo "<b>Error $ErrorCode: $ErrorMsg</b>";
} else {
	$i = 1;

	echo '<fieldset><legend>Reservation:</legend>	<pre>';
	echo '<b>Code:</b><br/>' . "\t" . $xml->TravelItinerary->ItineraryRef['ID'] . '<br/><br/><b>Paxs:</b><br/>';
	foreach ($xml->TravelItinerary->CustomerInfos->CustomerInfo as $pax) {
		echo "\t" . $i . '. ' 
			. $pax->Customer->PersonName->GivenName . ' ' . $pax->Customer->PersonName->Surname . ' ' . $pax->Customer->Document['DocType'] . $pax->Customer->Document['DocID'] . ' (' . $pax->Customer['PassengerTypeCode'] . '), '
			. 'Tel.' . $pax->Customer->ContactPerson->Telephone . ' '
			. $pax->Customer->ContactPerson->Email . '<br/>';
		$i++;
	}

	echo '<br/><b>Itinerary:</b><br/>';
	if ($xml->xpath('/KIU_TravelItineraryRS/TravelItinerary/ItineraryInfo/ReservationItems') != array()) {
		$i = 1;
		foreach ($xml->TravelItinerary->ItineraryInfo->ReservationItems->Item as $odo) {
			echo "\t" . $i . '. ' 
			. $odo->Air->Reservation->MarketingAirline
			. $odo->Air->Reservation['FlightNumber'] . ' '
			. $odo->Air->Reservation->DepartureAirport['LocationCode'] 
			. ' (' . $odo->Air->Reservation['DepartureDateTime'] . ') -> '
			. $odo->Air->Reservation->ArrivalAirport['LocationCode']
			. ' (' . $odo->Air->Reservation['ArrivalDateTime'] . ') <br/>';
			$i++;
		}
	} else {
		echo "\tNOT AVAILABLE.<br/>";
	}
	
	echo '<br/><b>Pricing:</b><br/>';
	
	
	if ($xml->xpath('/KIU_TravelItineraryRS/TravelItinerary/ItineraryInfo/ItineraryPricing/Cost') != array()) {
		$i = 1;
		echo "\t" . 'Amount before taxes: ' . $xml->TravelItinerary->ItineraryInfo->ItineraryPricing->Cost['AmountBeforeTax'] . '<br/>';
		
		foreach ($xml->TravelItinerary->ItineraryInfo->ItineraryPricing->Taxes->Tax as $t) {
			echo "\t\t($t[TaxCode]) " . str_pad($t['Amount'], 16, " ", STR_PAD_LEFT) . "($t[CurrencyCode])" . "<br/>";
		}
		
		if ($xml->xpath('/KIU_TravelItineraryRS/TravelItinerary/ItineraryInfo/ItineraryPricing/Fees') != array()) {
			foreach ($xml->TravelItinerary->ItineraryInfo->ItineraryPricing->Fees->Fee as $f) {
				echo "\t\t($f[FeeCode]) " . str_pad($f['Amount'], 10, " ", STR_PAD_LEFT) . "($f[CurrencyCode])" . "<br/>";
			}
		}
		echo "\t" . 'Total amount after taxes and fees: ' . $xml->TravelItinerary->ItineraryInfo->ItineraryPricing->Cost['AmountAfterTax'] . "<br/>";
	} else {
		echo "\tNOT AVAILABLE.<br/>";
	}
	
	if ($xml->xpath('/KIU_TravelItineraryRS/TravelItinerary/Remarks') != array()) {
		echo '<br/><b>Remarks:</b><br/>';
		
		foreach ($xml->TravelItinerary->Remarks->Remark as $rmk) {
			echo "\tRemark: " . $rmk . "<br/>";
		}
	}
	
	echo '<br/><b>Ticketing:</b><br/>';
	$tickets = array();
	
	$pnr_exists = true;
	foreach ($xml->TravelItinerary->ItineraryInfo->Ticketing as $tkt) {
		if ($tkt['TicketingStatus'] == "3") {
			$tickets[]= $tkt['eTicketNumber'];
			echo "\tTicket $tkt[eTicketNumber] ISSUED pax $tkt[TravelerRefNumber].<br/>";
		} elseif ($tkt['TicketingStatus'] == "1") {
			echo "\tRESERVATION NOT ISSUED Timelimit $tkt[TicketTimeLimit].<br/>";
		} elseif ($tkt['TicketingStatus'] == "5") {
			$pnr_exists = false;
			echo "\tRESERVATION EXPIRED OR CANCELLED.<br/>";
		}
	}
	
	$tkt_list = '<select name="ticket">';
	if (count($tickets)) {
		foreach ($tickets as $t) {
			$tkt_list .= '<option value="' . $t . '">' . $t . '</option>';
		}
		$tkt_list .= '</select>';
		
		echo "<br/><form action=\"index.php\" name=\"void_ticket_form\" onsubmit=\"return void_ticket('$user', '$password', '$device', '$sine', '$Target', '$_GET[pnr]')\"><fieldset><legend>Void ticket:</legend> " . $tkt_list . " <input id=\"submit\" name=\"submit\" type=\"button\" value=\"Void\" onclick=\"return void_ticket('$user', '$password', '$device', '$sine', '$Target', '$_GET[pnr]')\"/></fieldset></form></fieldset>";
	} elseif ($pnr_exists) {
		echo "<br/><input type=\"button\" value=\"Cancel PNR\" onclick=\"cancel_pnr('$user', '$password', '$device', '$sine', '$Target', '$_GET[pnr]')\" /> </fieldset>";
	} else {
		echo "</fieldset>";
	}
}

echo '<br/><input type="button" value="Back" onclick="avail_new_search()" />';


/*echo "<pre>REQUEST: " . htmlentities(print_r($request, true)) . "<br>" . "RESPONSE: " . htmlentities(print_r($response, true)) . "</pre>";
*/
?>
