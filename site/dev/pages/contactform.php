<? // contactform.php ?>
<style type="text/css">
.text1 {color:#999;font-family:'Century Gothic',arial;font-size:14px;font-weight:normal;float:left;text-align:right;width:140px;}
.text2 {color:#ccc;font-family:'Century Gothic',arial;font-size:16px;font-weight:normal;float:left;text-align:left;display:block;vertical-align:top;}
.text3 {color:#ccc;font-family:'Century Gothic',arial;font-size:16px;font-weight:normal;float:left;text-align:right;width:140px;vertical-align:middle;height:40px;}
.text4 {color:#999;font-family:'Century Gothic',arial;font-size:14px;font-weight:normal;float:left;margin-left:140px;}
.text5 {color:#999;font-family:'Century Gothic',arial;font-size:14px;font-weight:normal;float:right;margin-left:140px;}

.msg {color:#ff3300;font-size:16px;font-weight:bold;font-family:'Century Gothic',arial;}

.input1 {background-color:#ccc;width:310px;height:20px;border:0;float:left;color:#333;font-family:arial;font-size:14px;font-weight:normal;padding:3px;}
.submit1 {color:#333;background-color:#ccc;font-family:'Century Gothic',arial;font-size:14px;font-weight:normal;border:2px solid #333;-webkit-border-radius: 10px;-moz-border-radius: 10px;border-radius: 10px;}

.textarea1 {background-color:#ccc;width:310px;height:150px;border:0;float:left;color:#333;font-family:arial;font-size:14px;font-weight:normal;padding:3px;overflow:auto;}
.form1 {height:24px;margin-bottom:4px;}
.form2 {height:24px;text-align:left;float:left;}
.form3 {height:24px;text-align:left;float:left;}
.form4 {overflow:auto;height:160px;}

.title {width:400px;text-align:right;margin:10px 0px 6px 0px;}
</style>
<script type="text/javascript">
function validate(form) {
	var msg='';
	var ok=true;
	if (form.name.value=='') {
		msg+="Please enter a name\n";
	}
	if (form.email.value=='') {
		msg+="Please enter an email\n";
	} else if ((form.email.value.indexOf(".") < 1) || (form.email.value.indexOf("@")< 1)) {
		msg+="Please enter a valid email\n";
	}
	if (form.phone.value=='') {
		msg+="Please enter a phone\n";
	}
	if (form.interest.value=='') {
		msg+="Please enter an interest\n";
	}
	if (msg=='') {
		form.submit();
	} else {
		alert(msg);
		ok=false;
	}
	return ok;
}
</script>

<div style="float:left;display:inline;padding:10px;display:inline;">

<? if ($_SESSION['msg']!='') {
?><span class="msg"><?=$_SESSION['msg']?></span><br><br><?
$_SESSION['msg']='';
} 
?>

<? $toemail=$_GET['toemail']; ?>

		<form name="contactform" action="/pages/processcontact.php" onsubmit="return validate(document.forms.contactform);" method="post" >
		<input type="hidden" name="toemail" value="<?=$toemail?>">
		<div class="form1">
			<div class="text1"><label for="name">*Name&nbsp;</label></div>
			<div class="form2"><input name="name" id="name" type="text" class="input1"></div>
		</div>
		<div class="form1">
			<div class="text1"><label for="email">*Email&nbsp;</label></div>
			<div class="form2"><input name="email" id="email" type="text" class="input1"></div>
		</div>
		<div class="form1">
			<div class="text1"><label for="phone">*Phone&nbsp;</label></div>
			<div class="form2"><input name="phone" type="text" id="phone" class="input1"></div>
		</div>
		<div class="form1">
			<div class="text1"><label for="interest">*Project Interest&nbsp;</label></div>
			<div class="form2"><input name="interest" type="text" id="interest" class="input1"></div>
		</div>
		<div class="form1">
			<div class="text1"><label for="startdate">Desired Start Date&nbsp;</label></div>
			<div class="form2"><input name="startdate" type="text" id="startdate" class="input1"></div>
		</div>
		<div class="form4" >
			<div class="text1"><label for="message">Details / Message&nbsp;</label></div>
			<div class="form2"><textarea name="message" id="message" class="textarea1" ></textarea></div>
		</div>
		<div class="text4">
			* indicates a required field
		</div>
		<br clear="all">
		<div class="text5">
			<input type="submit" value="send" class="submit1">
		</div>
	
		</form>

	</div>

