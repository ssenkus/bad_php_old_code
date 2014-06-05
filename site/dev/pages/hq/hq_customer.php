<? // hq_order.php 
require_once "hq_security.php";

$custstr="select * from customers where cust_id='".mysql_real_escape_string($custid)."' order by cust_last,cust_first,cust_id";
$custres=mysql_query($custstr);
$custrow=mysql_fetch_array($custres);

//echo $custstr;

			$cust_email=$custrow['cust_email'];
			$cust_first=$custrow['cust_first'];
			$cust_last=$custrow['cust_last'];
			$cust_address=$custrow['cust_address'];
			$cust_city=$custrow['cust_city'];
			$cust_state=$custrow['cust_state'];
			$cust_zip=$custrow['cust_zip'];
			$cust_phone=$custrow['cust_phone'];
			$cust_notes=$custrow['cust_notes'];
			$cust_privatenotes=$custrow['cust_privatenotes'];

			$cust_useragent=$custrow['cust_useragent'];
			$cust_IP=$custrow['cust_IP'];


	$oktodelete=false;
	$orderstr="select * from orders where o_custid='$custid'";
	//echo $orderstr;
	$orderes=mysql_query($orderstr);
		if (mysql_num_rows($orderes)==0) {
		$oktodelete=true;
	}
?>

<style type="text/css">
.title {font-size:20px;font-weight:bold;padding-bottom:10px;}
</style>
<div style="padding:15px;overflow:auto;">

<br><a href="/2010/hq/">hq home</a> | <a href="2010/hq/hq_logout.php">logout</a><br><br>
<?
if ($_SESSION['msg']!='') {

?><span style="color:#339966;font-size:14px;font-weight:bold;"><?=$_SESSION['msg']?></span><br><br><?

$_SESSION['msg']='';
} 
?><span class="title">Customer: <?=$cust_first?> <?=$cust_last?></span><br>

<? if (!$oktodelete) { ?>
<br><b>Orders:</b>
<? while ($orderrow=mysql_fetch_array($orderes)) { ?>
<a href="/?page=hqorder&orderid=<?=$orderrow['o_id']?>"><?=date('n/j/Y',strtotime($orderrow['o_date']))?></a>&nbsp;
<? } ?>
<br>
IP: <?=$cust_IP?><br>
Browser info: <?=$cust_useragent?>
<br>
<? } ?>

<div id="customerform" >
	<form action="/2010/hq/hq_processupdatecustomer.php" method="post"  name="getcustomerinfoform" id="getcustomerinfoform"  target="getcustomerinfoiframe">
	<input type="hidden" name="custid" value="<?=$custid?>">
	<table>
	<tr>
	<tr>
		<td>*Email: </td><td><input type="text" name="email" value="<?=$cust_email?>" style="width:180px;"></td>
	</tr>
	<tr>
		<td>First name: </td><td><input type="text" name="first" value="<?=$cust_first?>" style="width:120px;"></td>
	</tr>
	<tr>
		<td>Last name: </td><td><input type="text" name="last" value="<?=$cust_last?>" style="width:120px;"></td>
	</tr>
	<tr>
		<td>Shipping Address: </td><td><input type="text" name="address" value="<?=$cust_address?>" style="width:240px;"></td>
	</tr>
	<tr>
		<td>City: </td><td><input type="text" name="city" value="<?=$cust_city?>" style="width:80px;">&nbsp;
	State: <input type="text" name="state" value="<?=$cust_state?>" maxlength="2" style="width:40px;">&nbsp;
	Zip: <input type="text" name="zip" value="<?=$cust_zip?>" style="width:80px;"></td>
	</tr>
	<tr>
		<td>Phone: </td><td><input type="text" name="phone" value="<?=$cust_phone?>" style="width:180px;"></td>
	</tr>
	<tr>
		<td colspan="2">Customer Notes:<br><textarea  name="notes" style="width:440px;height:50px;"><?=$cust_notes?></textarea></td>
	</tr>

	<tr>
		<td colspan="2">Private Notes:<br><textarea  name="privatenotes" style="width:440px;height:50px;"><?=$cust_privatenotes?></textarea></td>
	</tr>
	<tr>
		<td colspan="2" style="text-align:center;"><input type="submit" value="save info" name="save"><? if ($oktodelete) { ?><div style="float:right;"><input type="checkbox" name="delete" >delete</div><? } ?></td>
	</tr>
	</table></form>

	</div>
</div>