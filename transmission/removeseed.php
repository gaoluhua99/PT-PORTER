<?php
include_once('TransmissionRPC.class.php');
include_once('dbconfig.php');

$vodhash=$_POST['vodhash'];
$b=$_POST['vodsmalldes'];
echo "the vod hash is ".$vodhash."</br>";
echo "the vod small_des is ".$b."</br>";
$dblink=mysql_connect($mysql_host,$mysql_user,$mysql_pass);
mysql_query("SET NAMES UTF8");
mysql_select_db("nexus_rsss",$dblink);
$outres=mysql_query("SELECT * FROM torrents WHERE small_descr='$b'",$dblink);
while($info=mysql_fetch_assoc($outres)){
      $byrhash=$info["hash"];
}
mysql_close($dblink);
echo "the byr hash is ".$byrhash."</br>";

error_log("byrhash is $byrhash",3,"/var/www/html/transmission/error.log");

//use transmission rpc to remove
$rpc=new TransmissionRPC();

error_log("************new rpc generate",3,"/var/www/html/transmission/error.log");

$request=array("id");
$hash=array($vodhash,$byrhash);
$v=$rpc->get($hash,$request);
//print_r($v);
error_log("**************gogogogo1",3,"/var/www/html/transmission/error.log");
$id1=$v->arguments->torrents["0"]->id;
$id2=$v->arguments->torrents["1"]->id;
echo "</br>".$id1." ".$id2;

error_log("*************",3,"/var/www/html/transmission/error.log");
error_log("id1 is $id1",3,"/var/www/html/transmission/error.log");

$re1=$rpc->remove($id1);
$re2=$rpc->remove($id2);
print_r($re);

?>
