<? // hq_pages.php
require_once "hq_security.php";
$showclients=$_REQUEST['showclients'];

?>
<span class="title1">Manage Art Galleries and Orders</span><br><br>

<a href="/?page=hqlogin" <? if ($showclients=='') { ?>style="font-weight:bold;"<? } ?>>Art Galleries</a>&nbsp;&nbsp;
<a href="/?page=hqlogin&showclients=hidden" <? if ($showclients=='hidden') { ?>style="font-weight:bold;"<? } ?>>Show Hidden</a>&nbsp;&nbsp;
<a href="/?page=hqlogin&showclients=all" <? if ($showclients=='all') { ?>style="font-weight:bold;"<? } ?>>Show All</a><br>
<? 
if ($showclients=='hidden') {
	$str="select * from clients where c_subclientof=0 and c_hide=1 order by c_last";
} else if ($showclients=='sub') {
	$str="select * from clients where c_subclientof<>0 order by c_last";
} else if ($showclients=='all') {
	$str="select * from clients order by c_last";
} else {
	$str="select * from clients where c_subclientof=0 and c_hide=0 order by c_last";
}
$res=mysql_query($str);
while ($row=mysql_fetch_array($res)){
	$gallerytitlelink=$row['c_title'];
	$gallerytitlelink=str_replace(" ","_",$gallerytitlelink); // use underscore instead of space - makes it cleaner

$subclientof=$row['c_subclientof'];
if ($subclientof!=0) {
	$clientstr="select * from clients where c_id=".$subclientof;
	$clientres=mysql_query($clientstr);
	$clientrow=mysql_fetch_array($clientres);
	$subclientname=$clientname; // this is confusing. But for showing on HQ the client and subclient are switched
	$clientname=$clientrow['c_first'].' '.$clientrow['c_last'];
	$directory=$clientrow['c_directory'];
	$gallerytitlelink=$clientrow['c_title'];
	$gallerytitlelink=str_replace(" ","_",$gallerytitlelink); // use underscore instead of space - makes it cleaner
}
?>
<?=$row['c_title']?><? if ($subclientof!=0) { ?>, subclient of <?=$clientname?><? } ?>&nbsp;<a href="/?page=hqclient&clientid=<?=$row['c_id']?>">manage</a>

<?
if ($row['c_directory']!='') {

	if ($row['c_pass']!='') {
?>&nbsp;|&nbsp;<a href="/<?=$gallerytitlelink?>/<?=$row['c_pass']?>" target="_blank">view gallery</a>
  <? } else { 
?>&nbsp;|&nbsp;<a href="<?=$gallerytitlelink?>" target="_blank">view gallery</a>
	<? } ?>
&nbsp;|&nbsp;<a href="/?page=artpricelist&clientid=<?=$row['c_id']?>&passcode=<?=$row['c_pass']?>" target="_blank">pricelist</a>

<?
}

?><br>
<? } ?>

<br>
<form name="newpage" action="/2010/hq/hq_processnewclient.php" method="post">
New art gallery:<br>
Title:<input type="text" name="c_title"><input type="submit" value="create new art gallery">
</form>

<B>Standard Prices</B><br>
<? 
$str="select * from prices where p_cid=0 order by p_order";
$res=mysql_query($str);
while ($row=mysql_fetch_array($res)){
$pid=$row['p_id'];
$type=$row['p_type'];
$price=$row['p_price'];
$order=$row['p_order'];
$descrip=$row['p_descrip'];
?>
<div id="pricediv<?=$pid?>" style="display:inline;"><?=$descrip?>&nbsp;$<?=$price?>&nbsp;<a href="" onclick="$('#pricediv<?=$pid?>').fadeOut(300);$('#priceeditdiv<?=$pid?>').fadeIn(300);return false;">edit</a></div>
<div id="priceeditdiv<?=$pid?>" style="display:none;"><form name="editprice<?=$pid?>" action="/2010/hq/hq_processupdateprice.php"  method="post"><input type="hidden" name="p_id" value="<?=$pid?>">
Description:<input type="text" name="p_descrip" value="<?=$row['p_descrip']?>" style="width:200px">
Sort:<input type="text" name="p_order" value="<?=$row['p_order']?>" style="width:30px">&nbsp;
Price:$<input type="text" name="p_price" value="<?=$row['p_price']?>" style="width:50px"><br>
<select name="p_type">
<option <?=isselected($type,'Print')?>>Print</option>
<option <?=isselected($type,'Other')?>>Other</option>
</select>&nbsp;&nbsp;<input type="checkbox" name="delete">delete&nbsp;&nbsp;<input type="submit" value="update">
</form><a href="" onclick="$('#pricediv<?=$pid?>').fadeIn(300);$('#priceeditdiv<?=$pid?>').fadeOut(300);return false;">cancel</a></div>


<br>
<? } ?>
<br>
<form name="newprice" action="/2010/hq/hq_processnewprice.php" method="post">
<input type="hidden" name="p_cid" value="0">
New price:<Br>
Description:<input type="text" name="p_descrip" value="<?=$row['p_descrip']?>" style="width:200px">
Sort:<input type="text" name="p_order" value="<?=$row['p_order']?>" style="width:30px">&nbsp;
Price:$<input type="text" name="p_price" style="width:50px"><br>
<select name="p_type">
<option value="Print">Print</option>
<option value="Other">Other</option>
</select>&nbsp;
<input type="submit" value="create new price">
</form>



<B>Orders</B>&nbsp;&nbsp;&nbsp;
<? 
$showallorders=$_REQUEST['showallorders'];
if ($showallorders=='yes') {
?><a href="/?page=hqlogin&showallorders=no">show paid only</a><?
} else { 
?><a href="/?page=hqlogin&showallorders=yes">show all</a><?
}
?>
<br>
<table>
<tr>
<td>#</td>
<td>Customer</td>
<td>Dates</td>
<td>Order Total</td>
<td></td>
</tr>
<? 
if ($showallorders=='yes') {
$str="select * from orders left join customers on o_custid=cust_id order by o_date desc";
} else {
$str="select * from orders left join customers on o_custid=cust_id where o_datepaid is not null order by o_date desc";
}
$res=mysql_query($str);
while ($row=mysql_fetch_array($res)){
$custid=$row['cust_id'];
$subclientof=$row['c_subclientof'];
$order_id=$row['o_id'];
$order_pass=$row['o_pass'];
$custname=$row['cust_first'].' '.$row['cust_last'].' '.$row['cust_email'].' '.$row['cust_phone'];

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
if ($shipped!='') {$shipped=date("n/d/Y",strtotime($shipped));}
?>
<tr>
<td><?=$row['o_id']?></td>
<td style="width:180px;"><?=$custname?></td>
<td>Started:<?=$date?><br>Paid:<?=$paid?><br>Shipped:<?=$shipped?></td>
<td>$<?=$grandtotal?></td>
<td><a href="/?page=artorder&orderpass=<?=$order_pass?>&orderid=<?=$order_id?>" target="_blank">view</a><br><br>
<a href="/?page=hqorder&orderid=<?=$order_id?>">details</a>
</td>
</tr>
<? } ?>
</table>

<br><br><B>Customers</B>&nbsp;&nbsp;&nbsp;<br>

<table>
<tr>
<td>#</td>
<td>Email</td>
<td>Name</td>
</tr>
<? $custstr="select * from customers order by cust_last,cust_first,cust_id";
$custres=mysql_query($custstr);
while ($custrow=mysql_fetch_array($custres)){

$custid=$custrow['cust_id'];
$custname=$custrow['cust_first'].' '.$custrow['cust_last'];
$custemail=$custrow['cust_email'];
?><tr>
<td><a href="/?page=hqcustomer&custid=<?=$custid?>"><?=$custid?></a></td>
<td><a href="/?page=hqcustomer&custid=<?=$custid?>"><?=$custemail?></a></td>
<td><a href="/?page=hqcustomer&custid=<?=$custid?>"><?=$custname?></a></td>
</tr>
<? } ?>
</table>