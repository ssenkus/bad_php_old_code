<? // hqlogin.php ?>

<div style="width:959px;height:583px;float:left;">

<div style="padding:20px;">

<? if ($_SESSION['msg']!='') {
echo $_SESSION['msg'];
$_SESSION['msg']='';
} ?>
<form name="login" action="pages/hq/hq_processlogin.php" method="post">
<input name="pw" type="password" style="width:100px;"><input type="submit" value=">">
</form>


</div>

</div>