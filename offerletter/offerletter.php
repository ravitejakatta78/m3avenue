<?php
include('../functions.php');
$e_id = $_GET['id'];
$sql = runQuery("SELECT * FROM employee where ID = {$e_id}");
$unique_id = $sql['unique_id'];
// Function to Convert numbers to words 
function convertToWords($number)
{
    $no = round($number);
    $decimal = round($number - ($no = floor($number)), 2) * 100;
    $digits_length = strlen($no);
    $i = 0;
    $str = array();
    $words = array(
        0 => '',
        1 => 'One',
        2 => 'Two',
        3 => 'Three',
        4 => 'Four',
        5 => 'Five',
        6 => 'Six',
        7 => 'Seven',
        8 => 'Eight',
        9 => 'Nine',
        10 => 'Ten',
        11 => 'Eleven',
        12 => 'Twelve',
        13 => 'Thirteen',
        14 => 'Fourteen',
        15 => 'Fifteen',
        16 => 'Sixteen',
        17 => 'Seventeen',
        18 => 'Eighteen',
        19 => 'Nineteen',
        20 => 'Twenty',
        30 => 'Thirty',
        40 => 'Forty',
        50 => 'Fifty',
        60 => 'Sixty',
        70 => 'Seventy',
        80 => 'Eighty',
        90 => 'Ninety'
    );
    $digits = array('', 'Hundred', 'Thousand', 'Lakh', 'Crore');
    while ($i < $digits_length) {
        $divider = ($i == 2) ? 10 : 100;
        $number = floor($no % $divider);
        $no = floor($no / $divider);
        $i += $divider == 10 ? 1 : 2;
        if ($number) {
            $plural = (($counter = count($str)) && $number > 9) ? 's' : null;
            $str[] = ($number < 21) ? $words[$number] . ' ' . $digits[$counter] . $plural : $words[floor($number / 10) * 10] . ' ' . $words[$number % 10] . ' ' . $digits[$counter] . $plural;
        } else {
            $str[] = null;
        }
    }

    $Rupees = implode(' ', array_reverse($str));
    $paise = ($decimal) ? "And Paise " . ($words[$decimal - $decimal % 10]) . " " . ($words[$decimal % 10])  : '';
    return ($Rupees ? $Rupees . 'Rupees ' : '') . $paise . "Only";
}

//Function for Indian money system



function moneyFormatIndia($num)
{
    $explrestunits = "";
    if (strlen($num) > 3) {
        $lastthree = substr($num, strlen($num) - 3, strlen($num));
        $restunits = substr($num, 0, strlen($num) - 3); // extracts the last three digits  
        $restunits = (strlen($restunits) % 2 == 1) ? "0" . $restunits : $restunits; // explodes the remaining digits in 2's formats, adds a zero in the beginning to maintain the 2's grouping.  
        $expunit = str_split($restunits, 2);
        for ($i = 0; $i < sizeof($expunit); $i++) {
            // creates each of the 2's group and adds a comma to the end  
            if ($i == 0) {
                $explrestunits .= (int)$expunit[$i] . ","; // if is first value , convert into integer  
            } else {
                $explrestunits .= $expunit[$i] . ",";
            }
        }
        $thecash = $explrestunits . $lastthree;
    } else {
        $thecash = $num;
    }
    return $thecash; // writes the final format where $currency is the currency symbol.  
}

// Importing the variables
$reg_date = $sql["reg_date"];
$reg_date =  date("d-m-Y", strtotime($reg_date));
$fname = $sql["fname"];
$lname = $sql["lname"];
// $father_name = $_POST["father_name"];
$address = $sql["address"];
$landmark = $sql["landmark"];
$city = $sql["city"];
$state = $sql["state"];
$pincode = $sql["pincode"];
$p = str_replace(",", ",\n", $address);
// $emp_add = preg_split ("\,", $address); 
// $house_number = $emp_add[0];
// $street = $emp_add[1];
// $city = $emp_add[2];
// $district = $emp_add[3];
// $pin = $emp_add[4];
$join_date = $sql["joining_date"];
$join_date =  date("F d,Y", strtotime($join_date));
$payment = $sql["payment_type"];
//if ($payment != "royalty_pay") {
    $ctc = $sql["income"];
    $converted = convertToWords((float)$ctc);
    $ctc = moneyFormatIndia($ctc);
//}
$designation = $sql["designation"];
$location = $sql["location"];


$image1 = "../offerletter/sign.PNG";
// Creating instance for FPDF
require("fpdf/fpdf.php");
class PDF extends FPDF
{
    // Page header
    function Header()
    {
        // Logo
        $this->Image('../offerletter/logo_f.PNG', 0, 1, 210, 47);
        $this->Image('../offerletter/logo_w.PNG', 9, 60, 200, 200);

        $this->Ln(35);
    }


    protected $B = 0;
    protected $I = 0;
    protected $U = 0;
    protected $HREF = '';

    function WriteHTML($html)
    {
        // HTML parser
        $html = str_replace("\n",' ',$html);
        $a = preg_split('/<(.*)>/U',$html,-1,PREG_SPLIT_DELIM_CAPTURE);
        foreach($a as $i=>$e)
        {
            if($i%2==0)
            {
                // Text
                if($this->HREF)
                    $this->PutLink($this->HREF,$e);
                else
                    $this->Write(5,$e);
            }
            else
            {
                // Tag
                if($e[0]=='/')
                    $this->CloseTag(strtoupper(substr($e,1)));
                else
                {
                    // Extract attributes
                    $a2 = explode(' ',$e);
                    $tag = strtoupper(array_shift($a2));
                    $attr = array();
                    foreach($a2 as $v)
                    {
                        if(preg_match('/([^=]*)=["\']?([^"\']*)/',$v,$a3))
                            $attr[strtoupper($a3[1])] = $a3[2];
                    }
                    $this->OpenTag($tag,$attr);
                }
            }
        }
    }

    function OpenTag($tag, $attr)
    {
        // Opening tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,true);
        if($tag=='A')
            $this->HREF = $attr['HREF'];
        if($tag=='BR')
            $this->Ln(5);
    }

    function CloseTag($tag)
    {
        // Closing tag
        if($tag=='B' || $tag=='I' || $tag=='U')
            $this->SetStyle($tag,false);
        if($tag=='A')
            $this->HREF = '';
    }

    function SetStyle($tag, $enable)
    {
        // Modify style and select corresponding font
        $this->$tag += ($enable ? 1 : -1);
        $style = '';
        foreach(array('B', 'I', 'U') as $s)
        {
            if($this->$s>0)
                $style .= $s;
        }
        $this->SetFont('',$style);
    }

    function PutLink($URL, $txt)
    {
        // Put a hyperlink
        $this->SetTextColor(0,0,255);
        $this->SetStyle('U',true);
        $this->Write(5,$txt,$URL);
        $this->SetStyle('U',false);
        $this->SetTextColor(0);
    }
    var $col = 0;

    
}
$pdf = new PDF();

// First Page 
$pdf->AddPage();

$pdf->SetAutoPageBreak(true,30);
$pdf->SetFont('Times', 'B', 18);
// $pdf->Image('logo_w.png',9,60,200,200);
$pdf->SetFont('', '', 12);
$pdf->Cell(140, 7, "", 0, 0);
$pdf->Cell(100, 7, "Date: {$reg_date}", 0, 1);
$pdf->Cell(100, 7, "{$fname} {$lname},", 0, 1);
$pdf->Cell(140, 7, "{$address},", 0, 1);
$pdf->Cell(140, 7, "{$landmark},", 0, 1);
$pdf->Cell(140, 7, "{$city},{$state}.", 0, 1);
$pdf->Cell(140, 7, "{$pincode}", 0, 1);
$pdf->Cell(0, 7, "", 0, 1);
$pdf->Cell(100, 15, " Dear Mr. /Ms/ Mrs. {$fname},", 0, 1);
$pdf->SetFont('Times', 'BU', 18);
$pdf->Cell(0, 10, "Offer Letter", 0, 1, 'C');


$pdf->SetFont('', '', 12);
$pdf->MultiCell(190, 5, "With reference to your application and subsequent interview you had with us,
    ", 0, 1);
$pdf->SetFont('', 'B', 14);
$pdf->Cell(190, 7, "Joining:", 0, 1);
$pdf->SetFont('', '', 12);
$pdf->Cell(89, 7, "Your scheduled date of employment with us will be ", 0, 0);
$pdf->SetFont('', 'B', 12);
$pdf->Cell(0, 7, "{$join_date}.", 0, 1);
$pdf->Cell(190, 4, "", 0, 1);

$pdf->SetFont('', 'B', 14);
$pdf->Cell(190, 7, "Designation & Duties:", 0, 1);
$pdf->SetFont('', '', 12);
//$pdf->MultiCell(190, 7, "We wish to offer you the post of $designation,at $location  based on the terms and conditions.You will be responsible for achieving the goals and targets assigned to you.", 0);
$pdf->WriteHTML("We wish to offer you the post of <b>$designation</b>,at <b>$location</b>  based on the terms and conditions.You will be responsible for achieving the goals and targets assigned to you.<br>");
$pdf->Cell(190, 4, "", 0, 1);
$pdf->SetFont('', 'B', 14);
$pdf->Cell(190, 7, "Compensation:", 0, 1);
$pdf->Cell(190, 3, "", 0, 1);
$pdf->SetFont('', '', 13);
if ($payment === "review_pay") {
    $pdf->SetFont('', 'B', 12);
    $pdf->Cell(190, 7, "Review Pay", 0, 1);
    // $pdf->Cell(190,4,"",0,1);
    $pdf->SetFont('', '', 12);
    $pdf->Write(7, "");
    $pdf->Cell(85, 7, "Your Compensation will be as per our discussion ", 0, 0);
    $pdf->SetFont('', 'B', 12);
    $pdf->Write(7, "INR {$ctc} ({$converted}) per annum.");
    $pdf->SetFont('', '', 12);
    $pdf->Write(7, "You will be responsible for achieving the goals and tasks as per the timeline assigned to you.");
} else if ($payment === "variable_pay") 
{
    $pdf->SetFont('', 'B', 12);
    $pdf->Cell(190, 7, "Variable Pay", 0, 1);
    // $pdf->Cell(190,4,"",0,1);
    $pdf->SetFont('', '', 12);
    $pdf->Cell(85, 7, "Your Compensation will be as per our discussion ", 0, 0);
    $pdf->SetFont('', 'B', 12);
    $pdf->Write(7, "INR {$ctc} ({$converted}) per annum.");
    $pdf->SetFont('', '', 12);
    $pdf->Write(7, "You will be responsible for achieving the goals and tasks as per the timeline assigned to you.");
    $pdf->SetFont('', '', 12);
    $pdf->Cell(120, 7, "", 0, 1);
    $pdf->MultiCell(190, 7, "From the date of joining to the end of
1st month - minimum 50% of the points
2nd month - minimum of 75% of the points
3rd month onwards - regular 100% of the points allocated should be earned to receive
100% of the pay.", 0, 1);

    $pdf->Cell(190, 7, "Additionally, Below Incentive structure will be given :", 0, 1);
    $pdf->Cell(190, 4, "", 0, 1);
    $pdf->Cell(30, 5, "S.No", 1, 0);
    $pdf->Cell(40, 5, "%Allocated points", 1, 0);
    $pdf->Cell(50, 5, "Rs per extra points earned", 1, 1);
    $pdf->Cell(30, 7, "Slab-A", 1, 0);
    $pdf->Cell(40, 7, "125%-200%", 1, 0);
    $pdf->Cell(50, 7, "Rs.15/-", 1, 1);
    $pdf->Cell(30, 7, "Slab-B", 1, 0);
    $pdf->Cell(40, 7, "200%-250%", 1, 0);
    $pdf->Cell(50, 7, "Rs.20/-", 1, 1);
    $pdf->Cell(30, 7, "Slab-C", 1, 0);
    $pdf->Cell(40, 7, "250% and above", 1, 0);
    $pdf->Cell(50, 7, "Rs.25/-", 1, 1);
} else {
    $pdf->SetFont('', 'B', 14);
    $pdf->Cell(190, 7, "Royalty Pay", 0, 1);
    // $pdf->Cell(190,4,"",0,1);
    $pdf->SetFont('', '', 12);
    $pdf->Write(7, "");
    $pdf->Cell(85, 7, "Your Compensation will be as per our discussion ", 0, 0);
    $pdf->SetFont('', 'B', 12);
    $pdf->Write(7, "INR {$ctc} ({$converted}) per annum.");
    $pdf->SetFont('', '', 12);
    $pdf->Cell(190, 7, "", 0, 1);
    $pdf->WriteHTML('<p>You will be given Royalty pay as per the below structure<br>Types of Income<br>Direct pay<br>Personal points- Rs.25/-<br>Assigned points- Rs.20/- per each point earned
Team bonus (compared with team cost) (Rs.25/- per each point)
<p><br>');
   
    $pdf->Cell(190, 4, "", 0, 1);
    $pdf->Cell(30, 5, "Slabs", 1, 0);
    $pdf->Cell(50, 5, "% of team goal achieved", 1, 0);
    $pdf->Cell(50, 5, "% of points you will earn", 1, 1);
    $pdf->Cell(30, 7, "Slab-A", 1, 0);
    $pdf->Cell(50, 7, "Up to 75%", 1, 0);
    $pdf->Cell(50, 7, "5%", 1, 1);
    $pdf->Cell(30, 7, "Slab-B", 1, 0);
    $pdf->Cell(50, 7, "75%-100% ", 1, 0);
    $pdf->Cell(50, 7, "7.5%", 1, 1);
    $pdf->Cell(30, 7, "Slab-C", 1, 0);
    $pdf->Cell(50, 7, "100% -150%", 1, 0);
    $pdf->Cell(50, 7, "10%", 1, 1);
    $pdf->Cell(30, 7, "Slab-D", 1, 0);
    $pdf->Cell(50, 7, "150 and above", 1, 0);
    $pdf->Cell(50, 7, "12.5%", 1, 1);

    $pdf->Cell(190, 10, "", 0, 1);
    $pdf->WriteHTML("<p>Bonus Pay <br><br>You will earn 2.5% points from all the teams under you</p>", 0, 0);
}
$pdf->Cell(190, 7, "", 0, 1);
$pdf->WriteHTML("<p>Pay, Plans/Performance Linked Incentives & other perks are at discretion of the Management.</p>");
$pdf->Cell(190, 7, "", 0, 1);
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(190, 4, "", 0, 1);
$pdf->Cell(190, 7, "Documents to Submit:", 0, 1);
$pdf->SetFont('', '', 12);
// $pdf->Cell(190,4,"",0,1);
$pdf->MultiCell(190, 5, "You are required to submit the following documents before/ at the time of Joining.", 0, 1);
$pdf->Cell(5, 7, chr(127), 0, 0);
$pdf->Cell(190, 7, "Photocopy of experience certificate (if applicable).", 0, 1);
$pdf->Cell(5, 7, chr(127), 0, 0);
$pdf->Cell(190, 7, "Photocopies of certificates and mark-sheets of Higher educational / professional qualifications.", 0, 1);
$pdf->Cell(5, 7, chr(127), 0, 0);
$pdf->Cell(190, 7, "Copy of Aadhaar Card.", 0, 1);
$pdf->Cell(5, 7, chr(127), 0, 0);
$pdf->Cell(190, 7, "Copy of Pan Card.", 0, 1);
$pdf->Cell(5, 7, chr(127), 0, 0);
$pdf->Cell(190, 7, "Relieving letter (if applicable).", 0, 1);
$pdf->Cell(5, 7, chr(127), 0, 0);
$pdf->Cell(190, 7, "Passport size photographs.", 0, 1);

$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(190, 9, "", 0, 1);

$pdf->Cell(190, 7, "Probation:", 0, 1);
$pdf->SetFont('', '', 12);
$probation_html = '<p>You  will  be  on  probation  for  3  months  from  the  date  of  joining.  Your  probation  period  may  be
extended  or  reduced  on  the  sole  discretion  of  the  company.  Your  services  will  be  confirmed  for
full-time basis in the organization after satisfactory completion of probation period.</p><br>';
$pdf->WriteHTML($probation_html);

$notice_period_html='<p>You  need  to  serve  minimum  of  15  days  to  the  organization.  You  need  to give  100%  support,knowledge transfer, team transfer, client transfer and responsibilities to your reporting manager atleast before the last day of the notice period.</p><br>';

// $pdf->MultiCell(190, 5, "You will be on probation for 3 months from the date of joining. Your probation period may be extended or reduced on the sole discretion of the company. Your services will be confirmed for full-time basis in the organization after satisfactory completion of probation period.", 0, 0);
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(190, 4, "", 0, 1);
$pdf->Cell(190, 7, "Notice Period:", 0, 1);
$pdf->SetFont('', '', 13);
$pdf->WriteHTML($notice_period_html);
// $pdf->MultiCell(190, 5, "You need to serve minimum of 15 days to the organization. You need to give 100% support, knowledge transfer, team transfer, client transfer and responsibilities to your reporting manager atleast before the last day of the notice period.", 0, 0);

$termination_html='<p>Employee will be terminated from the Company for any bad behavior or irresponsible about timelines
by giving 15 days of notice.</p><br>';
$pdf->SetFont('Times', 'B', 12);
$pdf->Cell(190, 4, "", 0, 1);
$pdf->Cell(190, 7, "Termination:", 0, 1);
$pdf->SetFont('', '', 13);
$pdf->WriteHTML($termination_html);

// $pdf->MultiCell(190, 5, "Employee will be terminated from the Company for any bad behavior or irresponsible about timelines by giving 15 days of notice.", 0, 0);



// $pdf->SetFont('Times','BU',15);

$pdf->Cell(190, 6, "", 0, 1);
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(100, 7, "Terms and Conditions:", 0, 1);
$pdf->Cell(100, 1, "", 0, 1);
$pdf->SetFont('Times', '', 12);
$pdf->Cell(5, 7, chr(127), 0, 0);
$pdf->Cell(150, 7, "He/she should be punctual to Work.", 0, 1);
$pdf->Cell(5, 7, chr(127), 0, 0);
$pdf->Cell(150, 7, "No leaves are credited to you during the probation period.", 0, 1);
$pdf->Cell(5, 7, chr(127), 0, 0);
$pdf->Cell(150, 7, "Sudden Leaves are not accepted. So, inform to your Reporting Manager atleast 24hours before.", 0, 1);
$pdf->Cell(5, 7, chr(127), 0, 0);
$pdf->Cell(150, 7, "He/she should be active at working Hours.", 0, 1);
$pdf->Cell(5, 7, chr(127), 0, 0);
$pdf->Cell(150, 7, "Pay, Plans/Performance Linked Incentives & other perks are at discretion of the Management.", 0, 1);
$pdf->Cell(190, 8, "", 0, 1);

$note_html='Please  send  us  the  acceptance  of  this  offer  by  accepting  the  offer  with  a  signature,  the  details  of  which  are
shared in the email.<br>We look forward to have you as an active member of our M3 Avenue family.<br>';
// $pdf->Image('logo_w.png',9,60,200,200);
$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(100, 7, "Note:", 0, 1);
$pdf->SetFont('Times', '', 12);
$pdf->WriteHTML($note_html);
// $pdf->MultiCell(190, 5, "Please send us the acceptance of this offer by accepting the offer with a signature, the details of which are shared in the email.", 0, 0);
// $pdf->Cell(190, 7, "We look forward to have you as an active member of our M3 Avenue family.", 0, 1);
$pdf->Cell(100, 9, "", 0, 1);

$pdf->SetFont('Times', 'B', 14);
$pdf->Cell(100, 5, "Employee", 0, 0);
$pdf->Cell(100, 5, "M3 Avenue Financial Services Pvt.Ltd", 0, 1);
$pdf->SetFont('Times', '', 12);

$pdf->SetFont('Times', 'B', 12);
// $pdf->Image('sign.png',150,200,30,20);
$pdf->Image($image1, 120, $pdf->GetY(), 33.78);
$pdf->MultiCell(120, 10, "


", 0, 0);

$pdf->Cell(100, 7, "{$fname}'s Signature", 0, 0);

$pdf->Cell(80, 7, "Authorized Signature", 0, 1);
$pdf->Cell(100, 9, "Place:", 0, 0);
$pdf->Cell(100, 7, "Date: {$reg_date}", 0, 1);
$pdf->Cell(100, 9, "Date:", 0, 0);
$pdf->MultiCell(120, 7.5, "", 0, 1);
$pdf->SetFont('Times', 'B', 20);
$pdf->Cell(0, 7, "***THANK YOU***", 0, 0, 'C');


$filename = "../offerletter/Offer_letters/{$unique_id}_offerletter.pdf";
$pdf->Output($filename, 'F');
$pagerarray['status'] = '1';
$pagewererarray['ID'] = $sql['ID'];
$resultupdate = updateQuery($pagerarray, 'employee', $pagewererarray);
if($_GET['path'] == 'admin'){
    header("Location: ../admin/employe-list.php?osuccess=success");
}else{
    header("Location: ../super_admin_new/employee_list.php?osuccess=success");
}
