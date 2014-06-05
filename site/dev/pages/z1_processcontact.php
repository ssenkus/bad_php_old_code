<? // processcontact.php 
session_start();

ini_set("SMTP","smtp.gmail.com");
ini_set("smtp_port","465");
ini_set('sendmail_from', 'toorphoto@gmail.com');

$dscemail="dscdesignworks@gmail.com";

$toemail=$_REQUEST['toemail'];
if ($toemail=='') {$toemail=$dscemail;}

$toemail2="dsc@webais.com";

$name=$_REQUEST['name'];
$phone=$_REQUEST['phone'];
$email=$_REQUEST['email'];
if ($email=='') {$email=$dscemail;}
$interest=$_REQUEST['interest'];
$startdate=$_REQUEST['startdate'];
$message=$_REQUEST['message'];

if (($name=='') || ($email=='') || ($phone=='') || ($interest=='')) {
	$_SESSION['msg']="Please enter at least a name, email and session type";
} else {

$wholemessage="name: ".$name."<br>";
$wholemessage.="phone: ".$phone."<br>";
$wholemessage.="email: ".$email."<br>";
$wholemessage.="interest: ".$interest."<br>";
$wholemessage.="start date: ".$startdate."<br>";
$wholemessage.="message: ".$message."<br>";

//$toname="DSC Design Works";
$toemail="cdvddw@gmail.com";

$subject="Message from DSCDesignWorks.com visitor";

require_once('PHPMailer/class.phpmailer.php');
$mail  = new PHPMailer(); // 

//$mail->SMTPDebug = 1;
$mail->IsSMTP();
$mail->SMTPAuth   = true;                  // enable SMTP authentication
$mail->SMTPSecure = "ssl";                 // sets the prefix to the servier
$mail->Host       = "smtp.gmail.com";      // sets GMAIL as the SMTP server
$mail->Port       = 465;                   // set the SMTP port
$mail->Username   = "toorphoto@gmail.com";		// GMAIL username
$mail->Password   = "tp23dotcom";            // GMAIL password

$mail->SetFrom($email, $name);
$mail->AddReplyTo($email, $name);
$mail->AddAddress($toemail, $toname);
//$mail->AddAddress($toemail2, $toname);
$mail->Subject = $subject;
$mail->MsgHTML($wholemessage);

if(!$mail->Send()) {
  $_SESSION['msg']="An error occurred: " . $mail->ErrorInfo;
} else {
  $_SESSION['msg']="Thank you for your interest.";
}

}

$gotopage="/?page=contact";

header("Location: ".$gotopage);
exit;
