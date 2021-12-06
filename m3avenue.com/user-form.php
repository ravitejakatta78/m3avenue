<?php
session_start();
error_reporting(E_ALL);
ini_set("display_errors","on");
include("functions.php");
$infomailid = ' info@m3avenue.com';
$recipient_email    = $infomailid; //recepient
$from_email         = "m3avenue <".$infomailid.">"; //from email using site domain.

if($_POST){
	date_default_timezone_set("Asia/Kolkata"); 

    $dt=date('d-m-Y h:i:s A');
$refno=uniqid();
	
    
    $sender_email 	= filter_var($_POST["user-email"], FILTER_SANITIZE_STRING); //capture sender email
	$password        = filter_var($_POST["user-password"], FILTER_SANITIZE_STRING);
	  
	  
	    //php validation
  

	
    if(!filter_var($sender_email, FILTER_VALIDATE_EMAIL)){
	echo json_encode(array('type'=>'error','text'=>'Please enter a valid email!'));
	exit();
    }


    if(strlen($password)<1){
	echo json_encode(array('type'=>'error','text'=>'Please enter a valid Password!.'));
	exit();
    }
	
				$userarray = array();
				$uniqueusers = (int)runQuery("select max(ID) as id from user order by ID desc")['id'];
				$newuniquid = $uniqueusers+1;
				
                $userarray['unique_id'] = 'M3US'.sprintf('%05d',$newuniquid);
$userarray['fname']=mysqli_real_escape_string($conn,$_POST['user-fname']);
$userarray['lname']=mysqli_real_escape_string($conn,$_POST['user-lname']);
$userarray['email']=mysqli_real_escape_string($conn,$_POST['user-email']);
$userarray['mobile']=mysqli_real_escape_string($conn,$_POST['user-mobile']);
$userarray['password']=password_hash(mysqli_real_escape_string($conn,$_POST['user-password']),PASSWORD_DEFAULT);
	$prevuser = runQuery("select * from user where email = '".$userarray['email']."' and mobile = '".$userarray['mobile']."'");
	
	
    if(!empty($prevuser['ID'])){
	echo json_encode(array('type'=>'error','text'=>'Email id or mobile number already exists.'));
	exit();
    }
	
			$result= insertQuery($userarray,'user');


    $subject1        = "New Lead As Been Generated From Contact US";
    $message 		= '
    <table width="640" cellspacing="0" style="font:12px/16px \'Times New Roman\', Times, serif;color:rgb(51,51,51);background-color:rgb(255,255,255);margin:0 auto" cellpadding="0"> 

   <tbody> 

    <tr> 

     <td valign="top" style="padding:20px 0px 10px 10px;width:100px;border-collapse:collapse"> <img alt="m3avenue" border="0" height="60" src="'.$_SERVER["SERVER_NAME"].'/images/m3-logo.png"  style="font-size:11px" width="168" class="CToWUd"></td> 

     <td style="text-align:right;padding:0px 20px"> 

      <table cellspacing="0" style="font:12px/16px \'Times New Roman\', Times, serif;color:rgb(51,51,51);margin:0 auto;border-collapse:collapse" cellpadding="0"> 

       <tbody> 

        <tr> 

         <td style="border-bottom:1px solid rgb(204,204,204);width:490px"> 

          <table align="right" style="font:12px/16px \'Times New Roman\', Times, serif;color:rgb(51,51,51)"> 

           <tbody> 

            <tr style="padding:5px 0px 5px 0px;white-space:nowrap;border-collapse:collapse;text-align:right;width:490px"> 

             <td> <a href="" style="text-decoration:none;color:rgb(0,102,153);font-family:Arial,san-serif" target="_blank">Date</a> </td> 

             <td> <span style="text-decoration:none;color:#ccc;font:15px Arial,san-serif">&nbsp;|&nbsp;</span> </td> 

             <td>'.$dt.

			 '</td> 

            </tr> 

           </tbody> 

          </table> </td> 

        </tr> 

       </tbody> 

      </table> </td> 

    </tr> 

   

    <tr> 

     <td colspan="2" valign="top" style="padding:10px 0px 20px 30px;width:640px"><table cellspacing="0" style="width:640px" cellpadding="0">
       <tbody>
         <tr>
           <td colspan="2" valign="top" style="font:18px \'Times New Roman\', Times, serif;color:#cc6600;border-bottom:1px solid #ccc;"> Message Details</td>
         </tr>
		
		
         <tr>
           <td width="102"  valign="top" style="font:14px \'Times New Roman\', Times, serif;padding:11px 0 14px 18px;background-color:#efefef"><p style="margin:2px 0">USERNAME</p></td>
           <td width="536" valign="top" style="font:14px \'Times New Roman\', Times, serif;padding:11px 0 14px 18px;background-color:#efefef"><p style="margin:2px 0">'.$sender_email.'</p></td>
         </tr>	
		
         <tr>
           <td width="102"  valign="top" style="font:14px \'Times New Roman\', Times, serif;padding:11px 0 14px 18px;background-color:#efefef"><p style="margin:2px 0">PASSWORD</p></td>
           <td width="536" valign="top" style="font:14px \'Times New Roman\', Times, serif;padding:11px 0 14px 18px;background-color:#efefef"><p style="margin:2px 0">'.$password.'</p></td>
         </tr>
       </tbody>
     </table></td> 

    </tr> 

     <tr> 

     <td colspan="2" style="width:640px"> <p style="font:22px \'Times New Roman\', Times, serif;color:#cc6600;margin:0px 20px 0 20px"></p> <p style="margin:0px 20px 12px 20px;width:640px; font:14px \'Times New Roman\', Times, serif;text-decoration:none;color:#666;  font-size:11px; line-height:14px;align:left;"> * Terms & conditions apply.<br />
In case you are receiving our e-mail in your Junk-mail/Spam, mark this e-mail as Not Junk/Spam or add it to your Safe Sender\'s list.<br />
If you are not the correct recipient of this mailer please Delete from your mail<br />

If you do not wish to receive such e-mail communication fromm3avenue in the future, Please mail to '.$infomailid.'<br />

Please do not reply to this e-mail, this is sent from an unattended mail box. In case you have any<br />

 queries/responses, please mail to '.$infomailid.'<br><br> </p> </td> 

    </tr>

    <tr> 

     <td colspan="2" style="font-size:10px;color:#666;padding:0 20px 20px 20px;line-height:16px;width:640px; border-top:1px solid #eaeaea"> <p>This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message.</p> </td> 

    </tr> 

   </tbody> 

  </table>
  
  '; //capture message
  $headers = 'MIME-Version: 1.0' . "\r\n";
 $headers .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n"; 

    // Additional headers
 $headers .= 'To:' . "\r\n";
 $headers .= 'From:'.$sender_email.'' . "\r\n"; 
        
        //message text
       
        $body = $message;

    $sentMail = mail($recipient_email, $subject1, $body, $headers);
    if($sentMail) //output success or failure messages
    {       
	  
	  $message1 = '  <table width="640" cellspacing="0" style="font:12px/16px \'Times New Roman\', Times, serif;color:rgb(51,51,51);background-color:rgb(255,255,255);margin:0 auto" cellpadding="0"> 

   <tbody> 

    <tr> 

     <td valign="top" style="padding:20px 0px 10px 10px;width:100px;border-collapse:collapse"> <img alt="m3avenue" border="0" height="60" src="'.$_SERVER["SERVER_NAME"].'/images/m3-logo.png" style="font-size:11px" width="168" class="CToWUd"></td>

     <td style="text-align:right;padding:0px 20px"> 

      <table cellspacing="0" style="font:12px/16px \'Times New Roman\', Times, serif;color:rgb(51,51,51);margin:0 auto;border-collapse:collapse" cellpadding="0"> 

       <tbody> 

        <tr> 

         <td style="border-bottom:1px solid rgb(204,204,204);width:490px"> 

          <table align="right" style="font:12px/16px \'Times New Roman\', Times, serif;color:rgb(51,51,51)"> 

           <tbody> 

            <tr style="padding:5px 0px 5px 0px;white-space:nowrap;border-collapse:collapse;text-align:right;width:490px"> 

             <td> <a href="" style="text-decoration:none;color:rgb(0,102,153);font-family:Arial,san-serif" target="_blank">Date</a> </td> 

             <td> <span style="text-decoration:none;color:#ccc;font:15px Arial,san-serif">&nbsp;|&nbsp;</span> </td> 

             <td>'.$dt.

			 '</td> 

            </tr> 

           </tbody> 

          </table> </td> 

        </tr> 
  </tbody> 

      </table> </td> 

    </tr> 

  

    <tr> 

     <td colspan="2" valign="top" style="padding:10px 0px 20px 30px;width:640px"><table cellspacing="0" style="width:640px" cellpadding="0">
       <tbody>
         <tr>
           <td colspan="2" valign="top" style="font:18px \'Times New Roman\', Times, serif;color:#cc6600;border-bottom:1px solid #ccc;"> Message Details</td>
         </tr>
        
			
         <tr>
           <td width="102"  valign="top" style="font:14px \'Times New Roman\', Times, serif;padding:11px 0 14px 18px;background-color:#efefef"><p style="margin:2px 0">USERNAME</p></td>
           <td width="536" valign="top" style="font:14px \'Times New Roman\', Times, serif;padding:11px 0 14px 18px;background-color:#efefef"><p style="margin:2px 0">'.$sender_email.'</p></td>
         </tr>	
		
         <tr>
           <td width="102"  valign="top" style="font:14px \'Times New Roman\', Times, serif;padding:11px 0 14px 18px;background-color:#efefef"><p style="margin:2px 0">PASSWORD</p></td>
           <td width="536" valign="top" style="font:14px \'Times New Roman\', Times, serif;padding:11px 0 14px 18px;background-color:#efefef"><p style="margin:2px 0">'.$password.'</p></td>
         </tr>
       </tbody>
     </table></td> 

    </tr> 
	
  <tr> 

     <td colspan="2" style="width:640px"> <p style="font:22px \'Times New Roman\', Times, serif;color:#cc6600;margin:0px 20px 0 20px"></p> <p style="margin:0px 20px 12px 20px;width:640px; font:14px \'Times New Roman\', Times, serif;text-decoration:none;color:#666;  font-size:11px; line-height:14px;align:left;"> * Terms & conditions apply.<br />
In case you are receiving our e-mail in your Junk-mail/Spam, mark this e-mail as Not Junk/Spam or add it to your Safe Sender\'s list.<br />
If you are not the correct recipient of this mailer please Delete from your mail<br />

If you do not wish to receive such e-mail communication fromm3avenue in the future, Please mail to '.$infomailid.'<br />

Please do not reply to this e-mail, this is sent from an unattended mail box. In case you have any<br />

 queries/responses, please mail to '.$infomailid.' <br><br> </p> </td> 

    </tr>

    <tr> 

     <td colspan="2" style="font-size:10px;color:#666;padding:0 20px 20px 20px;line-height:16px;width:640px; border-top:1px solid #eaeaea"> <p>This email was sent from a notification-only address that cannot accept incoming email. Please do not reply to this message.</p> </td> 

    </tr> 

   </tbody> 

  </table>';
  $subject1 = "Thank You for Submitting The Form";

 $headers1 = 'MIME-Version: 1.0' . "\r\n";
 $headers1 .= 'Content-type: text/html; charset=iso-8859-1' . "\r\n";

 $headers1 .= 'To:' . "\r\n";
 $headers1 .= 'From:m3avenue<'.$infomailid.'>' . "\r\n"; 
    // Mail it
    mail($sender_email, $subject1, $message1, $headers1);//customer, $sender_email

	   echo json_encode(array('type'=>'done','text'=>'Successfully Completed.Please Login...'));
		exit;
    }else{
		 echo json_encode(array('type'=>'error','text'=>'Couldnt Send mail Please try Later.'));
		 
		exit;
    }
}
?>