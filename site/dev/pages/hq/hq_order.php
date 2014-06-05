<? // hq_order.php 
require_once "hq_security.php";

include "mcrypt.php";

$str="select * from orders left join customers on o_custid=cust_id where o_id=".mysql_real_escape_string($order_id);
$res=mysql_query($str);
$row=mysql_fetch_array($res);
$custid=$row['cust_id'];
$subclientof=$row['c_subclientof'];
$order_id=$row['o_id'];
$order_pass=$row['o_pass'];
$custname=$row['cust_first'].' '.$row['cust_last'];
$custemail=$row['cust_email'];
$custphone=$row['cust_phone'];
$custaddress=$row['cust_address'].' '.$row['cust_city'].' '.$row['cust_state'].' '.$row['cust_zip'];

$custnameoremail=$row['cust_first'].' '.$row['cust_last'];
if ($custnameoremail=='') {$custnameoremail=$row['cust_email'];}
$orderdate=$row['o_date'];
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
?>
<span class="title">Order for <?=$custnameoremail?> started on <?=$orderdate?></span><br>
<table>
<tr>
<td>#</td>
<td>Customer</td>
<td>Email</td>
<td>Phone</td>
<td>Address</td>
<td>Dates</td>
<td>Order Total</td>
<td>Notes</td>
<td></td>
</tr>
<? 
$orderpartstr="select * from order_parts left join orders on op_oid=o_id left join prices on op_pid=p_id where o_id=".$order_id;
$orderpartres=mysql_query($orderpartstr);
$grandtotal=0;
while ($orderpartrow=mysql_fetch_array($orderpartres)){
	$qty=$orderpartrow['op_qty'];
	$rawsubtotal=$qty * $orderpartrow['op_unitprice'];
	$subtotal=number_format($rawsubtotal,2);
	$grandtotal+=$subtotal;
}

$date=$row['o_date'];
if ($date!='') {$date=date("n/d/Y g:ia",strtotime($date));}
$paid=$row['o_datepaid'];
if ($paid!='') {$paid=date("n/d/Y g:ia",strtotime($paid));}
$shipped=$row['o_dateshipped'];
//if ($shipped!='') {$shipped=date("n/d/Y",strtotime($shipped));}

$ordername="Order for ".$directory.' '.$custname;
?>
<tr>
<td><?=$row['o_id']?></td>
<td><?=$custname?> <a href="/?page=hqcustomer&custid=<?=$custid?>">edit</a></td>
<td><a href="mailto:<?=$custemail?>" target="_blank"><?=$custemail?></a></td>
<td><?=$custphone?></td>
<td><?=$custaddress?></td>
<td>Started:<?=$date?><br>
Paid:<?=$paid?><br>
Shipped:
<form style="display:inline;" action="/2010/hq/hq_processupdateshippeddate_order.php" method="post" name="shippingform">
<input type="hidden" name="o_id" value="<?=$row['o_id']?>">
<input type="text" name="dateshipped" style="width:70px;" value="<?=$shipped?>"><input type="submit" value="save"></form>
</td>
<td>$<?=$grandtotal?></td>
<td>
<form style="display:inline;" action="/2010/hq/hq_processupdateordernotes_order.php" method="post" name="shippingform">
<input type="hidden" name="o_id" value="<?=$row['o_id']?>">
<input type="hidden" name="ordername" value="<?=$ordername?>">
Private order notes:<br><textarea type="text" name="notes" style="width:300px;height:40px;"><?=$row['o_notes']?></textarea><br><br>

Notes for customer:<br><textarea type="text" name="clientnotes" style="width:300px;height:40px;"><?=$row['o_clientnotes']?></textarea><br>
<input type="submit" value="update"><input type="checkbox" name="delete" style="margin-left:100px;">Delete Order

</form>

</td>
<td><a href="?page=artorder&orderid=<?=$order_id?>&orderpass=<?=$order_pass?>" target="_blank">view</a></td>
</tr>


<tr >
<td colspan="10"><b>Notes from Customer</b><Br>
<?=$row['cust_notes']?></td>
</tr>
<tr>
<td colspan="10">
<b>Order Details</b>
</tr>

<?
$orderpartstr="select * from order_parts left join orders on op_oid=o_id left join prices on op_pid=p_id where o_id=".$order_id;
$orderpartres=mysql_query($orderpartstr);
$grandtotal=0;
$orderdetails="";
while ($orderpartrow=mysql_fetch_array($orderpartres)){
	$qty=$orderpartrow['op_qty'];
	$rawsubtotal=$qty * $orderpartrow['op_unitprice'];
	$subtotal=number_format($rawsubtotal,2);
	$grandtotal+=$subtotal;

	$filencrypted=urlencode(simple_encrypt($orderpartrow['op_filename'],"zigzag"));

	$orderdetails=$qty." ".$orderpartrow['op_descrip']." ".$orderpartrow['op_finish']." ".$orderpartrow['op_directory']."/".$orderpartrow['op_filename']." $".$subtotal."<br>";
	echo "<tr><td colspan='7'>".$orderdetails."</td>";
	echo "<td colspan='2'><a href='?page=artgallery&clientid=".$orderpartrow['op_clientid']."&orderid=".$order_id."&orderpass=".$order_pass."&openpic=".$filencrypted."&p=1' target='_blank'><img src='/2010/galleries/".$orderpartrow['op_directory']."/med/".$orderpartrow['op_filename']."' height='100' border='0'></a></td>";
}
echo "<tr><td colspan='10'>Total: <b>$".$grandtotal."<b></td></tr>";
?>


</table>
<br>
<br><br>
<span class="title">Log</span><br>

<table>
<tr>
<td>When</td>
<td>Title</td>
<td>Content</td>
<td>IP</td>
</tr>
<? 
$logstr="select * from log where log_cid=".mysql_real_escape_string($custid)." order by log_when desc";
$logres=mysql_query($logstr);
while ($logrow=mysql_fetch_array($logres)){
?>
<tr>
<td><?=$logrow['log_when']?></td>
<td width="300"><?=$logrow['log_title']?></td>
<td width="400"><?=str_replace("\'","'",urldecode($logrow['log_content']))?></td>
<td><?=$logrow['log_IP']?></td>
</tr>
<? } ?>
</table>
</div><br><br>
</div>