<?php
// Sets the default timezone used by all date/time functions in a script
date_default_timezone_set('GMT');

//The empty variable $file is called when user selected a location in client side.
$file;

//Interacts with AJAX from client. When user selects a location option && if it matches the keyword, push to $file above.
if(isset($_POST['get_option'])){
 
if($_POST['get_option'] == 'Brislington'){
	global $file;
	$file = 'brislington_no2.xml';	
}
if($_POST['get_option'] == 'fishponds'){
	global $file;
	$file = 'fishponds_no2.xml';	
}
if($_POST['get_option'] == 'newfoundland_way'){
	global $file;
	$file = 'newfoundland_way_no2.xml';	
}
if($_POST['get_option'] == 'parson_st'){
	global $file;
	$file = 'parson_st_no2.xml';	
}
if($_POST['get_option'] == 'rupert_st'){
	global $file;
	$file = 'rupert_st_no2.xml';	
}
if($_POST['get_option'] == 'wells_road'){
	global $file;
	$file = 'wells_road_no2.xml';	
}
 //exit;
}

//$table are the array for final output. This outputs the 'COLUMNS' for google chart with its key as 'cols'.
$table['cols'] = array(array('label' => 'date','type' => 'date'),array('label' =>'No2 value','type' => 'number'));

//The $rows array stores the values for rows which will be pulled into the main $table['rows'] below.
$rows = array();

//Using the XML reader.
$xmlReader = new XMLReader();

//The reader opens whichever location the user choses which is pulled from the $file above.
$xmlReader->open($file);

//Set the start & end date(2016-2017)
$start = strtotime('2016-01-01 00:00:00');
$end = strtotime('2016-12-31 23:59:59');

//while loops the reader to read the required nodes.
while($xmlReader->read()){	

	//If reader reads the name 'reading' then continue reading.
	if($xmlReader->nodeType == XMLReader::ELEMENT && $xmlReader->localName == 'reading'){
		
		//If the time is equals to 12pm. 
		if($xmlReader->getAttribute('time') == '12:00:00') {
		
		//$readings stores all the array for rows values below.
		$readings = array();

		//reads the date. 
		$current_date = $xmlReader->getAttribute('date');

		//reformat from the default date.
		$datetime = DateTime::createFromFormat('d/m/Y', $current_date);

		//gets the timestamp from datetime
        $timestamp = $datetime->getTimestamp();

        //if the timestamp is within 2016-2017
        if($timestamp >= $start && $timestamp <= $end) {

          //formats the $datestamp according to what google chart wants.	
          $datestamp = "Date(".$datetime->format('Y, m, d').")";

          //reads the NO2 value
          $value = $xmlReader->getAttribute('value');
          
          //Readings of array pushes to main $readings array above.  
		  $readings[] = array('v' => $datestamp); 
		  $readings[] = array('v' => $value);

		  //Finally, pushes the final $readings to the main $rows array, according to the format google chart wants. 
		  $rows[] = array('c' => $readings);
		 
		 } 
	  } 
   }
}

//the final $table output for rows with key ['rows'].
$table['rows'] = $rows;

//Encodes the xml files into JSON to output to google chart
die(json_encode($table));
 
?>
