<?php 
	include('../functions.php');
	
	$db_record = trim(mysqli_real_escape_string($conn,$_GET['emp']));
$query = "SELECT * FROM track_work where employee_id = '".$db_record."' order by ID desc";
$result = mysqli_query($conn, $query);

$num_column = mysqli_num_fields($result);		

$csv_header = '';
for($i=0;$i<$num_column;$i++) {
    $csv_header .= '"' . mysqli_fetch_field_direct($result,$i)->name . '",';
}	
$csv_header .= "\n";

$csv_row ='';
while($row = mysqli_fetch_row($result)) {
	for($i=0;$i<$num_column;$i++) {
		$csv_row .= '"' . $row[$i] . '",';
	}
	$csv_row .= "\n";
}	
$filename = $db_record.'-'.date('YmdHis').'.csv';
/* Download as CSV File */
header('Content-type: application/csv');
header('Content-Disposition: attachment; filename='.$filename);
echo $csv_header . $csv_row;
exit;
?>