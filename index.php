<?php

require_once 'SimpleXLSX.php';
$uploaded="Trade.xlsx";
$firstrow=0; 
if ( $xlsx = SimpleXLSX::parse($_FILES["fileToUpload"]["tmp_name"]) ) {
	foreach( $xlsx->rows() as $r ) {
		if ($firstrow==0){
			$csv[]=array("Timestamp","Action","Source","Base","DerivType","DerivDetails","Volume","Price","Counter","Fee","FeeCcy","Comment");
			$firstrow=1;
		}
		else {
			$csv[]=array($r[0],$r[2],"Binance",substr($r[1],0,3),"future",$r[8],$r[4],$r[3],substr($r[1],3),$r[6],$r[7],"Endotech " . $r[1]);
		}
	}
	$filename = "binancetocryptact" . date('Y-m-d') . ".csv";
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
//  echo SimpleXLSX::parse_error();
?>
<!DOCTYPE html>
<html>
<body>
<form action="" method="post" enctype="multipart/form-data">
  Please upload a compatible xlsx file:
  <input type="file" name="fileToUpload" id="fileToUpload">
  <input type="submit" value="Upload Image" name="submit">
</form>

</body>
</html>
<?php
}
?>
