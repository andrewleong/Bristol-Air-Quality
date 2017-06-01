<?php
//Set my directory path for 6 files as array
$myFiles = array(                                                       
  'brislington.xml',     
  'fishponds.xml',
  'newfoundland_way.xml',
  'parson_st.xml',
  'rupert_st.xml',
  'wells_road.xml'
);

//XMLREADER read 6 files in loop.
foreach($myFiles as $myFile){

//Using XML Reader.	 
$XMLRead = new XMLReader();

//Stores the 6 files in variable.
$xml_file_path = $myFile;

//Then open it.   
$XMLRead -> open($xml_file_path);

//$names contains the name of location with lat and long that only needs to be called once.
$names = array('desc','lat', 'long');

//$readings are the date / time and value of no2 which needs to be called multiple times until it has all. 
$readings = array('date', 'time', 'no2');	

//MyData is an empty associative array for those keys in the reader that only needs to be called once. 	 
$myData = [];	

//These empty arrays will contain all readings value for each of the date, time and no2 that will be pushed here from the reader below.	 
$myDateReadings = [];	
$myTimeReadings = []; 
$myNo2Readings = []; 

//while loops the reader to read the required nodes.
while( $XMLRead->read()){
		 
		 //Sets the data for names of location with lat / long.
		 $data = $XMLRead-> localName;
		 $names = $data;
		 
		 //Sets the Readings data of date / time / no2.
		 $dataReadings = $XMLRead-> localName;
		 $readings = $dataReadings;	
	
	//If the reader reads element nodes.	 
	if ($XMLRead->nodeType == XMLReader::ELEMENT) {

		//Using Switch / case for the $names that needs to be called just once, if the names array matches the names here then read it. 
		switch ($names) {

			case 'desc':
				$locName = $XMLRead->getAttribute("val");
				//Pushes the location name as associative array (key / value).  
				$myData[2] = $locName;
				break;

			case 'lat':
				$locLat = $XMLRead->getAttribute("val"); 
				//Pushes the location lat as associative array (key / value). 
				$myData[3] = $locLat;
				break;

			case 'long':
				$locLong = $XMLRead->getAttribute("val"); 
				//Pushes the location long as associative array (key / value). 
				$myData[4] = $locLong;
				break;

		} //end of switch

		//For the $readings that needs to be called multiple times until it has all values.
		switch ($readings) {
			
			case 'date':
				$readingDate = $XMLRead->getAttribute("val"); 
			   	//Pushes the date into date array above.
				$myDateReadings[] = $readingDate;
				break;

			case 'time':
				$readingTime = $XMLRead->getAttribute("val"); 
				//Pushes the time into time array above.
				$myTimeReadings[] = $readingTime;
				break;	

			case 'no2':
				$no2Val = $XMLRead-> getAttribute("val");
				//Pushes the no2 value into no2 array above.  
				$myNo2Readings[] = $no2Val;
				break;	
		
		} //end of switch
		
	}//end of IF
	
}//End of while

//XML Writer
$XMLWrite = new XMLWriter();
$XMLWrite ->openMemory();
$XMLWrite ->setIndent(true);
$XMLWrite ->startDocument('1.0', 'UTF-8');

//Start writing with parent node 'data' type.
$XMLWrite->startElement("data");
	$XMLWrite->writeAttribute('type', 'nitrogen dioxide');

		//Writes the location with attributes that only needs to write once. 
		$XMLWrite->startElement("location");
			$XMLWrite->writeAttribute('id', $myData[2]);
			$XMLWrite->writeAttribute('lat', $myData[3]);
			$XMLWrite->writeAttribute('long', $myData[4]);
	
		//looping through arrays of all my readings array that needs to keep calling until it has all values.			
		 foreach ($myDateReadings as $index => $myDateReading ){

		 //Pulls the arrays from above and writes the readings accordingly to the number of nodes required.
		 		$XMLWrite->startElement("reading");
					$XMLWrite->writeAttribute('date',$myDateReadings[$index]);
					$XMLWrite->writeAttribute('time', $myTimeReadings[$index]);
					$XMLWrite->writeAttribute('value', $myNo2Readings[$index]);
				$XMLWrite ->endElement(); 
		
		}
		//End of location element.
		$XMLWrite ->endElement();

//End of data element.
$XMLWrite ->endElement();

//End of Document.
$XMLWrite->endDocument();	

//After that, use string replace for the file names to be called with _no2.xml.
$file_name = str_replace('.','_no2.', $xml_file_path);

//Puts the contents into the file.	
file_put_contents($file_name,  $XMLWrite->outputMemory());

//Flush the writer.
$XMLWrite ->flush();

}//End of Foreach statement.

?>