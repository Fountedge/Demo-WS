<?php
error_reporting(E_ALL);
include('connection.php');

$request = "<?xml version=\"1.0\" encoding=\"UTF-8\"?>
<KIU_CancelRQ EchoToken=\"$EchoToken\" TimeStamp=\"$TimeStamp\" Target=\"$Target\" Version=\"3.0\" SequenceNmbr=\"$SequenceNmbr\" PrimaryLangID=\"en-us\">
	<POS>
		<Source AgentSine=\"LOS00DOSM\" TerminalID=\"$device\">
		</Source>
	</POS>
	<UniqueID Type=\"14\" ID=\"$_GET[pnr]\" />
	";

if ($_GET['ticket']) $request .= "<UniqueID Type=\"30\" ID=\"$_GET[ticket]\" />
	<Ticketing TicketTimeLimit=\"1\" />
";

	$request .= "</KIU_CancelRQ>";

$conn = new Connection($user, $password);
$response = $conn->SendMessage($request);
$xml = simplexml_load_string($response);

if ($xml->Error->ErrorCode) {
	echo 'Error ' . $xml->Error->ErrorCode . ' "' . $xml->Error->ErrorMsg . '"';
} else {
	if ($xml->xpath('/KIU_CancelRS/Ticketing') != array()) {
		$tl = $xml->Ticketing['TicketTimeLimit'];
		echo "<b>TICKET</b> $_GET[ticket] HAS BEEN VOIDED.<br/><b>NEW RESERVATION TIMELIMIT:</b> $tl.";
	} else {
		echo "<b>RESERVATION</b> $_GET[pnr] HAS BEEN CANCELLED.<br>";
	}
}

echo '<br/><input type="button" value="Back" onclick="avail_new_search()">';

/*echo "<pre>REQUEST: " . htmlentities(print_r($request, true)) . "<br>" . "RESPONSE: " . htmlentities(print_r($response, true)) . "</pre>";
*/
?>
