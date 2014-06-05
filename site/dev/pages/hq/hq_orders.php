<? // hq_orders.php 
require_once "hq_security.php";

$clientid=$_GET['clientid'];

		// get Client data
		$clientstr="select * from clients where c_id=".mysql_real_escape_string($clientid);
		$clientres=mysql_query($clientstr);
		$clientrow=mysql_fetch_array($clientres);
		$clientemail=$clientrow['c_email'];
		$clientname=$clientrow['c_first'].' '.$clientrow['c_last'];
		$clientpass=$clientrow['c_pass'];
		$subclientof=$clientrow['c_subclientof'];

		if ($subclientof!=0) {
		$parentclientstr="select * from clients where c_id=".mysql_real_escape_string($subclientof);
		$parentclientres=mysql_query($parentclientstr);
		$parentclientrow=mysql_fetch_array($parentclientres);
		$parentclientemail=$parentclientrow['c_email'];
		$parentclientname=$parentclientrow['c_first'].' '.$parentclientrow['c_last'];
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
?>
<span class="title">Orders for <?=$clientname?> <? if ($subclientof==0) { ?>and Subclients<? } else { ?>, Subclient of <?=$parentclientname?><? } ?></span><br>

<table>
<tr>
<td>#</td>
<td>Gallery</td>
<td>Subclient</td>
<td>Dates</td>
<td>Order Total</td>
<td>Notes</td>
<td></td>
</tr>
<? 
$str="select * from orders left join clients on o_cid=c_id where c_id=".mysql_real_escape_string($clientid)." or c_subclientof=".mysql_real_escape_string($clientid)." order by o_date desc";
$res=mysql_query($str);
while ($row=mysql_fetch_array($res)){
$clientid=$row['c_id'];
$subclientof=$row['c_subclientof'];
$passcode=$row['c_pass'];
$order_id=$row['o_id'];
if ($row['c_title']!='') {
	$clientname=$row['c_title'];
} else {
	$clientname=$row['c_first'].' '.$row['c_last'];
}
$clientnamewithemail=$clientname.'<br>'.$row['c_email'];
$directory=$row['c_directory'];

$subclientname='';
if ($subclientof!=0) {
	$clientstr="select * from clients where c_id=".$subclientof;
	$clientres=mysql_query($clientstr);
	$clientrow=mysql_fetch_array($clientres);
	$subclientname=$clientname; // this is confusing. But for showing on HQ the client and subclient are switched
	if ($clientrow['c_title']!='') {
		$clientname=$clientrow['c_title'];
	} else {
		$clientname=$clientrow['c_first'].' '.$clientrow['c_last'];
	}
	$clientnamewithemail=$clientname.'<br>'.$clientrow['c_email'];
	$directory=$clientrow['c_directory'];
}

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

$ordername="Order for ".$directory.' '.$clientname;
?>
<tr>
<td><?=$row['o_id']?></td>
<td><?=$clientnamewithemail?></td>
<td><?=$subclientname?></td>
<td>Started:<?=$date?><br>
Paid:<?=$paid?><br>
Shipped:
<form style="display:inline;" action="/2010/hq/hq_processupdateshippeddate.php" name="shippingform">
<input type="hidden" name="o_id" value="<?=$row['o_id']?>">
<input type="hidden" name="clientid" value="<?=$clientid?>">
<input type="text" name="dateshipped" style="width:70px;" value="<?=$shipped?>"><input type="submit" value="save"></form>
</td>
<td>$<?=$grandtotal?></td>
<td>
<form style="display:inline;" action="/2010/hq/hq_processupdateordernotes.php" name="shippingform">
<input type="hidden" name="o_id" value="<?=$row['o_id']?>">
<input type="hidden" name="clientid" value="<?=$clientid?>">
<input type="hidden" name="ordername" value="<?=$ordername?>">
Private notes:<br><textarea type="text" name="notes" style="width:300px;height:40px;"><?=$row['o_notes']?></textarea><br><br>
Notes for client:<br><textarea type="text" name="clientnotes" style="width:300px;height:40px;"><?=$row['o_clientnotes']?></textarea><br>
<input type="submit" value="update"><input type="checkbox" name="delete" style="margin-left:100px;">Delete Order

</form>

</td>
<td><a href="?page=clientorder&clientid=<?=$clientid?>&passcode=<?=$passcode?>&orderid=<?=$order_id?>" target="_blank">view</a></td>
</tr>
<? } ?>
</table>

<? if ($subclientof==0) { ?>
<br><br>
<span class="title">Subclients</span><br>

 Sort by 
 <a href="" onclick="sortlink('email');return false;">email</a> | 
 <a href="" onclick="sortlink('first');return false;">first</a> | 
 <a href="" onclick="sortlink('last');return false;">last</a> | 
 <a href="" onclick="sortlink('when');return false;">when sent</a><br>

 <script type="text/javascript">
  function sortlink(sortvalue) { // make sure it's okay to reload the page if any data has changed
		var link="?page=hqorders&clientid=<?=$clientid?>&passcode=<?=$passcode?>&sort="+sortvalue+"#list";
		document.location.href=link;
 }
 </script>
<? 
$sort=$_REQUEST['sort'];
if ($sort=='') {$sort='last';}
if ($sort=='first') {$orderby='c_first,c_last';}
if ($sort=='last') {$orderby='c_last,c_first';}
if ($sort=='email') {$orderby='c_email,c_last,c_first';}
if ($sort=='when') {$orderby='c_messagesentdate,c_last,c_first';}

$str="select * from clients where c_subclientof=".$clientid." order by ".$orderby;
$res=mysql_query($str);
while ($row=mysql_fetch_array($res)){
	$subcid=$row['c_id'];
	$scfirst=$row['c_first'];
	$sclast=$row['c_last'];
	$scname=$scfirst.' '.$sclast;
	$scemail=$row['c_email'];
	$sc_messagesentdate=$row['c_messagesentdate'];
	//if ($sc_messagesentdate=='') {$sc_messagesentdate='none';} else {$sc_messagesentdate='<a href="" title="'.htmlentities(urldecode($row['c_messagesenttext'])).'" onclick="return false;">'.$sc_messagesentdate.'</a>';}
	if ($sc_messagesentdate=='') {$sc_messagesentdate='none';}
	if (($scfirst!='') || ($sclast!='')) {$namepipe=" | ";} else {$namepipe="";}
	$messagesentpipe=" | message sent: ";

?>
<div id="subclientsenddiv<?=$subcid?>" style="margin:8px 0px 5px 0px;">
<div id="subclientdiv<?=$subcid?>" style="display:inline;">
<div id="subclientcontentdiv<?=$subcid?>" style="display:inline;"><?=$scname?><?=$namepipe?><?=$scemail?><?=$messagesentpipe?><?=$sc_messagesentdate?></div>&nbsp;
</div>
</div>
<? } ?>
<? } ?>

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
$logstr="select * from log where log_cid=".mysql_real_escape_string($clientid)." order by log_when desc";
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