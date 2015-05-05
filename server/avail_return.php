<?php
error_reporting(E_ALL);
include('connection.php');

$request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<KIU_AirAvailRQ EchoToken=\"$EchoToken\" TimeStamp=\"$TimeStamp\" Target=\"$Target\" Version=\"3.0\" SequenceNmbr=\"$SequenceNmbr\" PrimaryLangID=\"en-us\" DirectFlightsOnly=\"$_GET[direct]\" MaxResponses=\"$_GET[maxresponses]\" CombinedItineraries=\"$_GET[combined]\">
	<POS>
		<Source AgentSine=\"$sine\" TerminalID=\"$device\">
		</Source>
	</POS>
	<SpecificFlightInfo>
		<Airline Code=\"$carrier\"/>
	</SpecificFlightInfo>
	<OriginDestinationInformation>
		<DepartureDateTime>$_GET[date]</DepartureDateTime>
		<OriginLocation LocationCode=\"$_GET[source]\"/>
		<DestinationLocation LocationCode=\"$_GET[dest]\"/>
	</OriginDestinationInformation>
	<OriginDestinationInformation>
		<DepartureDateTime>$_GET[returndate]</DepartureDateTime>
		<OriginLocation LocationCode=\"$_GET[source2]\"/>
		<DestinationLocation LocationCode=\"$_GET[dest2]\"/>
	</OriginDestinationInformation>
	<TravelPreferences>
		<CabinPref Cabin=\"$_GET[cabin]\"/>
	</TravelPreferences>
	
	<TravelerInfoSummary>
		<AirTravelerAvail>
			<PassengerTypeQuantity Code=\"ADT\" Quantity=\"1\"/>
		</AirTravelerAvail>
	</TravelerInfoSummary>
	
	
	
	
</KIU_AirAvailRQ>";

$conn = new Connection($user, $password);
$response = $conn->SendMessage($request);
$xml = simplexml_load_string($response);

if ($xml->Error->ErrorCode) {
	echo 'Error ' . $xml->Error->ErrorCode . ' "' . $xml->Error->ErrorMsg . '"';
} else {
	foreach ($xml->OriginDestinationInformation as $odi) {

		$option_number = 1;
		foreach ($odi->OriginDestinationOptions->OriginDestinationOption as $odo) {
			$option = true;
			$fn = 1;
			$option_params = "{'SegmentsCount':" . count($odo->FlightSegment) . ", 'Segments':Array(";
			$option_string = "<hr/><h4>Available Flights</h4>";
			$option_string .= '<table>
			';
			foreach ($odo->FlightSegment as $fs) {
				$dairport = $fs->DepartureAirport['LocationCode'];
				$aairport = $fs->ArrivalAirport['LocationCode'];
				$flight = $fs['FlightNumber'];
				$time =  $fs['JourneyDuration'];
				$ddatetime = $fs['DepartureDateTime'];
				$adatetime = $fs['ArrivalDateTime'];
				$stops = $fs['StopQuantity'];
				$equipment = $fs->Equipment['AirEquipType'];
				$airline = $fs->MarketingAirline['CompanyShortName'];
				$meal = $fs->Meal['MealCode'];
				$available_classes = array();
				foreach ($fs->BookingClassAvail as $bca) {
					if (($bca['ResBookDesigQuantity'] >= '1') && ($bca['ResBookDesigQuantity'] <= '9')) {
						$available_classes[] = $bca['ResBookDesigCode'];
					}
				}
				if ($available_classes == array()) {
					$option = false;
					break;
				}
				$class_list = "Array('" . implode("', '", $available_classes) . "')";
				$option_params .= "{MarketingAirline:'$airline', FlightNumber:$flight, DepartureDateTime:'$ddatetime', ArrivalDateTime:'$adatetime'";
				$option_params .= "DepartureAirport:'$dairport', ArrivalAirport:'$aairport', ResBookDesigCode:'$available_classes[0]', Classes:$class_list},";
				$option_string .= "
				<td>Flying from:</td><td>$dairport</td>
				<tr><td>Flying to:</td><td>$aairport</td></tr>
				<tr><td>Flight number:</td><td>$flight</td></tr>
				<tr><td>Departure:</td><td>$ddatetime</td></tr>
				<tr><td>Arrival :</td><td>$adatetime</td></tr>
				<tr><td>Duration:</td><td>$time</td></tr>
				<tr><td>Stops:</td><td>$stops</td></tr>
				<tr><td>Equipment:</td><td>$equipment</td></tr>
				<tr><td>Meal:</td><td>$meal</td></tr>";
				$fn++;
			}
			$option_params = substr($option_params,0,-1) . ")}";
			if ($option) echo "$option_string<tr><td colspan=\"10\"><input type=\"button\" value=\"Add\" onclick=\"add_segment($option_params)\"/></td></tr>";
			echo '</table>';
			if ($option) $option_number++;
		}
	}
}

echo '<br/><input type="button" value="Back" onclick="avail_new_search()">';

echo "<pre>REQUEST: " . htmlentities(print_r($request, true)) . "<br>" . "RESPONSE: " . htmlentities(print_r($response, true)) . "</pre>";

?>
