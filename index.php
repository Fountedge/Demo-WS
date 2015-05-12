<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN"
   "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
	<link rel="stylesheet" href="//code.jquery.com/ui/1.11.4/themes/smoothness/jquery-ui.css">
  <script src="//code.jquery.com/jquery-1.10.2.js"></script>
  <script src="//code.jquery.com/ui/1.11.4/jquery-ui.js"></script>
	<title>WS3 Test Page</title>
	<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-15" /> 
	<script type="text/javascript" src="app.js"></script>
	<LINK REL=StyleSheet HREF="scc/style.css" TYPE="text/css"></LINK>
	
	<script>
  $(function() {
    $( "#datepicker" ).datepicker({
	 dateFormat: "dd-mm-yy"
	
	});
  });
  </script>
	
	<script>
  $(function() {
    $( "#datepicker2" ).datepicker({
	
	 dateFormat: "dd-mm-yy"
	});
  });
  </script>
	
	<script>
  $(function() {
    $( "#datepicker3" ).datepicker({
	
	 dateFormat: "dd-mm-yy"
	});
  });
  </script>
	
		
	

</head>
<body>
<div id="avail_search">
	
	
	
	<div>
				<form action="index.php"  name="avail_search_form" onsubmit="return avail_search()">
				  <fieldset>
					<legend>one way Flight:</legend>
					
				
					<br/>
					Flying from:
					  <select  name="source" >
                            <option value="ABV"  data-targets="LOS">Abuja</option>
                            <option value="LOS"  data-targets="ABV" >Lagos</option>
                     </select> 
					  <br/>
					Flying to: <select  type="text" name="dest">
					  		<option value="LOS"  data-targets="ABV" >Lagos</option>
					  		<option value="ABV"  data-targets="LOS" >Abuja</option>
					  		</select>
					<br/>
					  Date: 
					  	<input  type="text" name="date" id="datepicker3" /><br/>
					  
					 
					  
					  Passengers: 
					  <select name="passenger" id="passenger">
					  <option value="1">1</option>
					  <option value="2">2</option>
					  <option value="3">3</option>
					  </select><br/>
					  returning from:
					  <select  name="source2" >
                            
                            <option value="LOS"  data-targets="ABV" >Lagos</option>
						  <option value="ABV"  data-targets="LOS">Abuja</option>
                     </select> 
					  <br/>
						returning  to: <select  type="text" name="dest2">
					   		<option value="ABV"  data-targets="LOS" >Abuja</option>
					  		<option value="LOS"  data-targets="ABV" >Lagos</option>
					  		</select><br/>
					  
					  Date: 
					  	<input  type="text" name="returndate" id="datepicker" />
					  
					  <br/>  <br/>
					Direct Flights Only: <select name="direct"><option value="false">No</option><option value="true">Yes</option></select><br/>
					Return Combined Itineraries: <select name="combined"><option value="false">No</option><option value="true">Yes</option></select><br/>
					 <input type="text" hidden="hidden" name="maxresponses" value="10"> 
					Preferred Cabin: <select name="cabin"><option value="Economy">
					  Economy</option><option value="First">First</option><option value="Business">Business</option></select> <br/>
					 <br/>
					<input id="submit" name="submit" type="button" value="Search Flights" onclick="return avail_search()"/>
				  </fieldset>
				</form>
		</div>
	
		
		<div>
			<form action="index.php" name="check_itinerary_form" onsubmit="return check_itinerary()">
				<fieldset>
					<legend>Or check a previous reservation:</legend>
					
					PNR: <input type="text" name="pnr"/> <br/>
					<input id="submit" name="submit" type="button" value="Check PNR" onclick="return check_itinerary()"/>
				</fieldset>
			</form>
		</div>
</div>
<div id="buy" style="visibility:hidden" >	  
	<fieldset>
		<legend>Itinerary:</legend>
		<div id="selected_segments"></div>
		<div id="selected_segments_fare">Search and Add Flights to create your Itinerary</div>
	</fieldset>
</div>
	
<div id="avail_result" style="visibility:hidden" ></div>
<div id="checked_pnr" style="visibility:hidden" ></div>
<div id="cancelled_pnr"  style="visibility:hidden"></div>
<div id="voided_ticket" style="visibility:hidden" ></div>

<div id="booking_search"  style="visibility:hidden">
	<form action="index.php" name="booking_search_form" onsubmit="return booking_search()">
	  <fieldset>
		<legend>Booking:</legend>

		<div id="passengerDetails">
			
		</div>

		
		<input type="button" value="Back" onclick="booking_back(); "/> <input type="button" onclick="booking_submit();" value="Make Reservation"/>
	  </fieldset>
	</form>
</div>
<div id="booking_result" style="visibility:hidden"></div>
</body>
</html>
