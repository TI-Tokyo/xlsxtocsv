<?php
// This file contains no error or security checking. Use only for internal purposes or in trusted environments.
// You will need to customise lines 12 and 18 (the two starting "$csv[]=array") to your desired output.
// Optionally change the output filename in line 22.
// The example included is for converting a futures trading record from xlsx to csv for import to a different website.
require_once 'SimpleXLSX.php';
$firstrow=0; 
if ( $xlsx = SimpleXLSX::parse($_FILES["fileToUpload"]["tmp_name"]) ) {
	foreach( $xlsx->rows() as $r ) {
		if ($firstrow==0){
// Set the column titles of your output csv file here. If there are no column titles, set "$firstrow=1" in line 7.
			$csv[]=array("Timestamp","Action","Source","Base","DerivType","DerivDetails","Volume","Price","Counter","Fee","FeeCcy","Comment");
			$firstrow=1;
		}
		else {
// Edit the column data from the input file as needed to make your output.
// For reference, "$r[0]" corresponds to column A on the first sheet of the xlsx file. "$r[1]" is column B and so on.
			$csv[]=array($r[0],$r[2],"Binance",substr($r[1],0,3),"future",$r[8],$r[4],$r[3],substr($r[1],3),$r[6],$r[7],"Futures " . $r[1]);
		}
	}
// Optionally change the output filename. Current format is "output-2020-09-15.csv"
	$filename = "output-" . date('Y-m-d') . ".csv";
	$out = fopen('php://memory', 'w');
        	foreach( $csv as $row ) {
			fputcsv($out, $row);
		}
	fseek($out, 0);
	header('Content-Type: text/csv');
	header('Content-Disposition: attachment; filename="' . $filename . '";');
	fpassthru($out);
	fclose($out);
}
else {
//  Show a simple upload form if no valid xlsx sheet has been uploaded.
?>
<!DOCTYPE html>
<html>
<body>
<form action="" method="post" enctype="multipart/form-data">
  Please upload a compatible xlsx file:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload xlsx" name="submit">
</form>
</body>
</html>
<?php
}
?>
