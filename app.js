var segments = new Array();

function getXMLDoc(url) {

	if (window.XMLHttpRequest) {
		xmlhttp=new XMLHttpRequest();
	} else {
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.open("GET", url, false);
	xmlhttp.send(null);

	return eval('(' + xmlhttp.responseText + ')');
}

function getXMLDocText(url) {

	if (window.XMLHttpRequest) {
		xmlhttp=new XMLHttpRequest();
	} else {
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.open("GET", url, false);
	xmlhttp.send(null);

	return xmlhttp.responseText;
}

function postXMLDoc(url, params) {

	if (window.XMLHttpRequest) {
		xmlhttp=new XMLHttpRequest();
	} else {
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.open("POST", url, false);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);

	try {
		var result = eval('(' + xmlhttp.responseText + ')');
	} catch(e) {
		document.write(xmlhttp.responseText);
	}

	return result;
}
function postXMLDocText(url, params) {

	if (window.XMLHttpRequest) {
		xmlhttp=new XMLHttpRequest();
	} else {
		xmlhttp=new ActiveXObject("Microsoft.XMLHTTP");
	}

	xmlhttp.open("POST", url, false);
	xmlhttp.setRequestHeader("Content-type", "application/x-www-form-urlencoded");
	xmlhttp.setRequestHeader("Content-length", params.length);
	xmlhttp.setRequestHeader("Connection", "close");
	xmlhttp.send(params);

	return xmlhttp.responseText ;
}
function avail_search() {
	var date = document.forms["avail_search_form"].elements["date"].value;
	var source = document.forms["avail_search_form"].elements["source"].value;
	var dest = document.forms["avail_search_form"].elements["dest"].value;
	var direct = document.forms["avail_search_form"].elements["direct"].value;
	var combined = document.forms["avail_search_form"].elements["combined"].value;
	var cabin = document.forms["avail_search_form"].elements["cabin"].value;
	var source2 = document.forms["avail_search_form"].elements["source2"].value;
	var dest2 = document.forms["avail_search_form"].elements["dest2"].value;
	var passenger = document.forms["avail_search_form"].elements["passenger"].value;
	var returndate = document.forms["avail_search_form"].elements["returndate"].value;

	var maxresponses = document.forms["avail_search_form"].elements["maxresponses"].value;
	

	hide('avail_search'); 
	hide('booking_search'); 
	show('avail_result'); 
	show('buy');


	set_text('avail_result', getXMLDocText('server/avail.php?date=' + date + '&source=' + source + '&dest=' + dest +  '&passenger=' + passenger +'&returndate=' + returndate + '&source2=' + source2 +'&dest2=' + dest2+ '&direct=' + direct + '&maxresponses=' + maxresponses + '&cabin=' + cabin + '&combined=' + combined));
	return false;
}


function avail_new_search() {
	
	show('avail_search'); 
	hide('avail_result');
	hide('buy');
	hide('cancelled_pnr');
	hide('voided_ticket');
	hide('checked_pnr');
	set_text('avail_result', '');
	return false;
}

function check_itinerary() {
	
	var pnr = document.forms["check_itinerary_form"].elements["pnr"].value.toUpperCase();
	
	hide('avail_search'); 
	hide('booking_search'); 
	hide('avail_result');
	hide('cancelled_pnr');
	hide('voided_ticket');
	show('checked_pnr');
	
	
	set_text('checked_pnr', getXMLDocText('server/itinerary.php?&user=' + '&pnr=' + pnr ));
	
	return false;
}

function void_ticket(user, pass, device, sine, partition, pnr) {
	var ticket = document.forms["void_ticket_form"].elements["ticket"].value;
	
	hide('avail_search'); 
	hide('booking_search'); 
	hide('avail_result');
	hide('checked_pnr');
	hide('cancelled_pnr');
	show('voided_ticket');
	
	set_text('voided_ticket', getXMLDocText('server/cancel.php?&user=' + user + '&pass=' + pass + '&device=' + device + '&sine=' + sine + '&pnr=' + pnr + '&partition=' + partition + '&ticket=' + ticket));
	
	return false;
}

function cancel_pnr(user, pass, device, sine, partition, pnr) {
	
	hide('avail_search'); 
	hide('booking_search'); 
	hide('avail_result');
	hide('checked_pnr');
	hide('voided_ticket');
	show('cancelled_pnr');
	
	set_text('cancelled_pnr', getXMLDocText('server/cancel.php?&user=' + user + '&pass=' + pass + '&device=' + device + '&sine=' + sine + '&pnr=' + pnr + '&partition=' + partition));
	
	return false;
}

function issue() {

	var BookingID = document.forms["issue_form"].elements["BookingID"].value;
	var TourCode = document.forms["issue_form"].elements["TourCode"].value;
	var PaymentType = document.forms["issue_form"].elements["PaymentType"].value;
	var CreditCardCode = document.forms["issue_form"].elements["CreditCardCode"].value;
	var CreditCardNumber = document.forms["issue_form"].elements["CreditCardNumber"].value;
	var CreditSeriesCode = document.forms["issue_form"].elements["CreditSeriesCode"].value;
	var CreditExpireDate = document.forms["issue_form"].elements["CreditExpireDate"].value;
	var DebitCardCode = document.forms["issue_form"].elements["DebitCardCode"].value;
	var DebitCardNumber = document.forms["issue_form"].elements["DebitCardNumber"].value;
	var DebitSeriesCode = document.forms["issue_form"].elements["DebitSeriesCode"].value;
	var InvoiceCode = document.forms["issue_form"].elements["InvoiceCode"].value;
	var MiscellaneousCode = document.forms["issue_form"].elements["MiscellaneousCode"].value;
	var Text = document.forms["issue_form"].elements["Text"].value;
	var VAT = document.forms["issue_form"].elements["VAT"].value;
	set_text('issue_result', getXMLDocText('server/issue.php?BookingID=' +  BookingID + '&TourCode=' + TourCode + '&PaymentType=' + PaymentType + 
		'&CreditCardCode=' + CreditCardCode + '&CreditCardNumber=' + CreditCardNumber + '&CreditSeriesCode=' + CreditSeriesCode + '&CreditExpireDate=' + CreditExpireDate +
		'&MiscellaneousCode=' + MiscellaneousCode + '&Text=' + Text + '&VAT=' + VAT + 
		'&DebitCardCode=' + DebitCardCode + '&DebitCardNumber=' + DebitCardNumber + '&DebitSeriesCode=' + DebitSeriesCode + '&InvoiceCode=' + InvoiceCode
		));
	return false;
}

function add_segment(segment) {
	var i;
	for (i = 0 ; i<segment['SegmentsCount']; i++) {
		segments.push(segment['Segments'][i]);
	}
	selected_segments_redraw();
	selected_segments_fare();
	}

function del_segment(segment) {
	segments.splice(segment,1);
	selected_segments_redraw();
	selected_segments_fare();
}

function change_class(segment, e) {
	segments[segment]['ResBookDesigCode'] = e.value;
	selected_segments_fare();
}

function selected_segments_redraw() {
	var text = '';
	var i = 1;
	for (segment in segments) {
		var class_list = '<select name="class_' + segment + '" onchange="change_class(' + segment + ', this)">';
		
		for (sclass in segments[segment]['Classes']) {
			if (segments[segment]['ResBookDesigCode'] == segments[segment]['Classes'][sclass]) {
				class_list = class_list + '<option selected="selected" value="' + segments[segment]['Classes'][sclass] + '">' + segments[segment]['Classes'][sclass] + '</option>';
			} else {
				class_list = class_list + '<option value="' + segments[segment]['Classes'][sclass] + '">' + segments[segment]['Classes'][sclass] + '</option>';
			}
		}
		
		text = text + i + '. ' 
					+ segments[segment]['MarketingAirline']
					+ segments[segment]['PassengerTypeQuantity']+' '
					+ segments[segment]['FlightNumber'] + ' '
					+ segments[segment]['DepartureAirport'] 
					+ ' (' + segments[segment]['DepartureDateTime'] + ') -> '
					+ segments[segment]['ArrivalAirport']
					+ ' (' + segments[segment]['ArrivalDateTime'] + ') ' + class_list + '</select>'
					+ ' <input type="button" value="Remove from Itinerary" onclick=del_segment(' + segment + ')><br/>';
		i++;
	}
	set_text('selected_segments', '<pre>' + text + '</pre>');
}
	
function selected_segments_fare() {
	
	
	var text = '';
	var user = '$user';
	var pass = '$password';
	var partition = '$Target';
	
	if (segments.length) {
		text = '&Segments=' + segments.length;
		for (segment in segments) {
			for (item in segments[segment]) {
				text = text + '&' + item + '_' + segment + '=' + segments[segment][item];
			}
		}
		set_text('selected_segments_fare', getXMLDocText('server/fare.php?' + user + pass +  partition + text));		
	} else {
		set_text('selected_segments_fare', 'Search and Add Flights to create your Itinerary');
		document.forms["avail_search_form"].elements["submit"].value = 'Search Flights';
	}
}

function booking(quantity) {
	//alert(quantity);
	hide('avail_search');
	hide('avail_result');
	hide('booking_result'); 
	hide('buy');
	

	var text='FirstName:<input type="text" name="FirstName"/> <br/>'+
		'LastName:<input type="text" name="LastName"/> <br/>'+
		'Document Type:<select type="text" name="DocType">'+
		  					'<option value="5">Passport</option>'+
		  			  '</select>'+
		  '<br/>'+
		'Document ID:<input type="text" name="DocID"/> <br/>'+
		'Telephone:<input type="text" name="Telephone"/> <br/>'+
		'Email:<input type="text" name="Email"/> <br/> <hr/>';

	var setText='';
	for(var i=1;i<=quantity;i++){
		//alert(i);
		setText = $('#passengerDetails').html() + text;
		//alert(setText);
		$('#passengerDetails').html(setText);

	}


	show('booking_search');
}

function booking_back() {
	hide('avail_search'); 
	hide('booking_search'); 
	show('avail_result'); 
	show('buy'); 
	return false;
	return false;
}

function booking_submit() {
	var text = '';
	var params = '';

	params = params + '&FirstName=' + document.forms["booking_search_form"].elements["FirstName"].value.toUpperCase();
	params = params + '&LastName=' + document.forms["booking_search_form"].elements["LastName"].value.toUpperCase();
	params = params + '&DocType=' + document.forms["booking_search_form"].elements["DocType"].value.toUpperCase();
	params = params + '&DocID=' + document.forms["booking_search_form"].elements["DocID"].value.toUpperCase();
	params = params + '&Telephone=' + document.forms["booking_search_form"].elements["Telephone"].value.toUpperCase();
	params = params + '&Email=' + document.forms["booking_search_form"].elements["Email"].value.toUpperCase();
	
	text = 'Segments=' + segments.length;
	for (segment in segments) {
		for (item in segments[segment]) {
			text = text + '&' + item + '_' + segment + '=' + segments[segment][item];
		}
	}		

	set_text('booking_result', postXMLDocText('server/book.php?', text + params));
hide('booking_search'); 
	show('booking_result'); 
	return false;
}

function show(element) {
	document.getElementById(element).style.visibility = 'visible'; 
	document.getElementById(element).style.height = ''; 	
}

function hide(element) {
	document.getElementById(element).style.visibility = 'hidden'; 
	document.getElementById(element).style.height = '0px'; 	
}

function set_text(element_id, text) {
	var e = document.getElementById(element_id);
	e.innerHTML = text;
}

