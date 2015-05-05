<?
$var = '<KIU_AirBookRS EchoToken="1" TimeStamp="2011-05-04T14:30:34+00:00" Target="Testing" Version="3.0" SequenceNmbr="1">
	<Success/>
	<AirItinerary>
		<OriginDestinationOptions>
			<OriginDestinationOption><FlightSegment DepartureDateTime="2011-05-04 17:00" ArrivalDateTime="2011-05-04 18:00" FlightNumber="212" ResBookDesigCode="Y">
					<DepartureAirport LocationCode="AEP"/>
					<ArrivalAirport LocationCode="COR"/>
					<MarketingAirline Code="XX"/>
				</FlightSegment><FlightSegment DepartureDateTime="2011-05-05 17:00" ArrivalDateTime="2011-05-05 18:11" FlightNumber="4196" ResBookDesigCode="Y">
					<DepartureAirport LocationCode="COR"/>
					<ArrivalAirport LocationCode="SLA"/>
					<MarketingAirline Code="XX"/>
				</FlightSegment><FlightSegment DepartureDateTime="2011-05-06 13:00" ArrivalDateTime="2011-05-06 17:30" FlightNumber="1806" ResBookDesigCode="Y">
					<DepartureAirport LocationCode="SLA"/>
					<ArrivalAirport LocationCode="MDQ"/>
					<MarketingAirline Code="XX"/>
				</FlightSegment></OriginDestinationOption>
		</OriginDestinationOptions>
	</AirItinerary>
	<TravelerInfo>
		<AirTraveler PassengerTypeCode="ADT">
			<PersonName>
				<GivenName>IVAN</GivenName>
				<Surname>HERNANDEZ</Surname>
			</PersonName>
			<Telephone PhoneNumber="48781154"/>
			<Email>IHERNANDEZ@KIUSYS.COM</Email>
			<Document DocID="27310720" DocType="DNI"/>
			<TravelerRefNumber RPH="1"/>
		</AirTraveler>
	</TravelerInfo>
	<BookingReferenceID Type="1" ID="IQRXIH"/>
</KIU_AirBookRS>';

$xml = simplexml_load_string($var);

if ($xml->Error) {
	$ErrorCode = $xml->Error->ErrorCode;
	$ErrorMsg = $xml->Error->ErrorMsg;
	echo "<b>Error $ErrorCode: $ErrorMsg</b>";
} else {
	$i = 1;

	echo '<fieldset><legend>Booking:</legend>	<pre>';
	echo '<b>Code:</b><br/>' . $xml->BookingReferenceID['ID'] . '<br/><br/><b>Paxs:</b><br/>';
	foreach ($xml->TravelerInfo->AirTraveler as $pax) {
		echo $i . '. ' 
			. $pax->PersonName->GivenName . ' ' . $pax->PersonName->Surname . ' ' . $pax->Document['DocType'] . $pax->Document['DocID'] . ' (' . $pax['PassengerTypeCode'] . '), '
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
	echo '</pre><input type="button" value="Back" onclick="booking_search();"/></fieldset>';
}


?>
