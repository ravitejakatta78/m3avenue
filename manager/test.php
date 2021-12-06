<?php 

$values="fname=offer%40gmail.com&lname=as&email=offer%40gmail.com&mobile=8899009900&pin_code=5555&join_date=2021-09-15&payment=review_pay&ctc=4567845&pannum=fgh78&bankdetails=&accntnum=&ifsccode=&designation=Telecaller&location=Hyderabad&address=ssss";

echo "<pre>";

$data=array();

$encode_values=explode('&',$values);

foreach(explode('&', $values) as $value)
{
    $value1 = explode('=', $value);
    $data[$value1[0]] = $value1[1];
    
}


echo "<pre>";

print_r(json_encode($data)); exit;

echo gettype($values);

$encode_values=json_encode($values);

//echo gettype($encode_values);


$decode_values= json_decode($values);

echo gettype($decode_values);


 ?>