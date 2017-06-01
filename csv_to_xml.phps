<?php
echo "working .. wait";
ob_flush();
flush();

//Opens the CSV file if its not false.
if (($handle = fopen("air_quality.csv", "r")) !== FALSE) {
    
	//The arrray for all the element names in the csv file.
	$header = array('id', 'desc', 'date', 'time', 'nox', 'no', 'no2', 'lat', 'long');
	
	//My 6 locations names stored in associative array with key / value.
	$myPlaces = array(
		 3=>"Brislington",
		 6=>"Fishponds",
		 8=>"parson st",
		 9=>"rupert st",
		 10=>"wells road",
		 11=>"newfoundland way"
		);
		 
	//throws away the first lines which are the field names
	fgetcsv($handle, 200, ",");
	
	//count the number of items in the $header array so we can loop using it
	$cols = count($header);
	
	//set row count to 2 - this is the row in the original csv file
	$row = 2;
	
	//set record count to 1
	$count = 1;
	$rec = '';
	
	//Loop My places as key to value
	foreach($myPlaces as $key => $val){
		
	//start output with records as parent node. 
	$output = '<records>';
	
	//In a loop, set all the data into rows.
	while (($data = fgetcsv($handle, 200, ",")) !== FALSE) {
        
        if ($data[0] == $key) {
			$rec = '<row count="' . $count . '" id="' . $row . '">';
		
			for ($c=0; $c < $cols; $c++) {
				$rec .= '<' . trim($header[$c]) . ' val="' . trim($data[$c]) . '"/>';
			}

			$rec .= '</row>';
			$count++;
			$output .= $rec;
		}
		$row++;
	}
	//Ends the tag with records.
	$output .= '</records>';
	
	//After the contents are inserted, name the file name according to the value of locations. 
	$file_name = str_replace(' ', '_', $val);

	//Set the file extension as .xml.
	$file_name = strtolower($file_name).'.xml';
	
	//Puts / writes the output contents into the files. 
	file_put_contents($file_name, $output);
	rewind($handle);
	
	}//End of foreach loop statement.

}//End of fopen.

//Close the handler	
fclose($handle);
?>