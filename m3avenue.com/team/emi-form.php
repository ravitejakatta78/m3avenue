<?php 

function emi_calculator($p, $r, $t) 
{ 
    $emi; 
  
    // one month interest 
    $r = $r / (12 * 100); 
      
    // one month period 
 /*    $t = $t * 12; */  
      
    $emi = ($p * $r * pow(1 + $r, $t)) /  
                  (pow(1 + $r, $t) - 1); 
  
    return ($emi); 
} 
  
    // Driver Code 
    $principal = $_POST['pamount']; 
    $rate = $_POST['interestm']; 
    $time = $_POST['noofmonths'];
    $emi = emi_calculator($principal, $rate, $time); 
    echo json_encode(array("type"=>"done","text"=>"Monthly EMI is: ".number_format($emi,2))); 
?>