<?php 
$mysql_host='localhost';
$mysql_user='root';
$mysql_pass='yourpassword';
$mysql_db='nexus_rsss';
$baseurl='222.199.184.41/transmission';


function sqlerr($file = '', $line = '')
{
	die( mysql_error() . ($file != '' && $line != '' ? "in $file, line $line" : "")."\n");
}

function sql_query($query)
{
	return mysql_query($query);
}

function sqlesc($value) {
		$value = "'" . mysql_real_escape_string($value) . "'";
	return $value;
}



function dbconn()
{
	global $mysql_host, $mysql_user, $mysql_pass, $mysql_db;
	//echo $mysql_host."".$mysql_user."".$mysql_pass." ".$mysql_db."\n";
	if (!mysql_connect($mysql_host, $mysql_user, $mysql_pass))
	{
		switch (mysql_errno())
		{
			case 1040:
			case 2002:
				die("can't connect to database");
				default:
				die("[" . mysql_errno() . "] dbconn: mysql_connect: " . mysql_error());
		}
	}
	mysql_query("SET NAMES UTF8");
	mysql_query("SET collation_connection = 'utf8_general_ci'");
	mysql_query("SET sql_mode=''");
	mysql_select_db($mysql_db) or die('dbconn: mysql_select_db: ' + mysql_error());
}
function get_torrent_byname($filename)
{
	//$row = array('id', 'filename', 'name', 'small_descr', 'descr', 'type', 'url', 'uploadeded', 'completed');
		$res = sql_query("SELECT * FROM torrents WHERE torrents.filename = ".sqlesc($filename)) or sqlerr(__FILE__,__LINE__);
		return $res;

}
function get_torrent_bytid($tid)
{
	//$row = array('id', 'filename', 'name', 'small_descr', 'descr', 'type', 'url', 'uploadeded', 'completed');
		$res = sql_query("SELECT * FROM torrents WHERE torrents.tid = ".sqlesc($tid)) or sqlerr(__FILE__,__LINE__);
		return $res;

}
function get_torrent_byhash($hash)
{
	//$row = array('id', 'filename', 'name', 'small_descr', 'descr', 'type', 'url', 'uploadeded', 'completed');
		$res = sql_query("SELECT * FROM torrents WHERE torrents.hash=".sqlesc($hash)." and  torrents.uploaded=0 and torrents.completed=0 and torrents.downloaded=1"   ) or sqlerr(__FILE__,__LINE__);
		return $res;

}
function get_torrent_upload()
{
	//$row = array('id', 'filename', 'name', 'small_descr', 'descr', 'type', 'url', 'uploadeded', 'completed');
		$res = sql_query("SELECT * FROM torrents WHERE torrents.uploaded=0 and torrents.completed=1 and torrents.downloaded=1"   ) or sqlerr(__FILE__,__LINE__);
		return $res;

}
function get_torrent_downloaded()
{
	//$row = array('id', 'filename', 'name', 'small_descr', 'descr', 'type', 'url', 'uploadeded', 'completed');
		$res = sql_query("SELECT * FROM torrents WHERE uploaded=0 and downloaded=0 and completed=0 " ) or sqlerr(__FILE__,__LINE__);
		return $res;

}


function get_rss_source()
{
	$res=sql_query("SELECT * FROM rss_source where rss_source.enabled=1") or sqlerr(__FILE__,__LINE__);
	return $res;
}




function update_torrent_uploaded($id)
{
	//$row = array('id', 'filename', 'name', 'small_descr', 'descr', 'type', 'url', 'uploadeded', 'completed');
		$res = sql_query("update  torrents set torrents.uploaded=1 WHERE torrents.id = ".sqlesc($id)."and torrents.uploaded=0 and torrents.completed=1 and torrents.downloaded=1"   ) or sqlerr(__FILE__,__LINE__);
		return $res;

}
function update_torrent_downloaded($id,$filename,$hash)
{
	//$row = array('id', 'filename', 'name', 'small_descr', 'descr', 'type', 'url', 'uploadeded', 'completed');
	$res = sql_query("update  torrents set torrents.downloaded=1,torrents.filename=".sqlesc($filename).",torrents.hash=".sqlesc($hash). "WHERE torrents.id = ".sqlesc($id)." and torrents.downloaded=0 and torrents.uploaded=0 and torrents.completed=0" ) or sqlerr(__FILE__,__LINE__);
	return $res;

}
function update_torrent_completed($hash)
{
	//$row = array('id', 'filename', 'name', 'small_descr', 'descr', 'type', 'url', 'uploadeded', 'completed');
		$res = sql_query("update  torrents set torrents.completed=1 WHERE torrents.hash = ".sqlesc($hash)." and torrents.downloaded=1 and torrents.uploaded=0 and torrents.completed=0") or sqlerr(__FILE__,__LINE__);
		return $res;

}




function mysql_insert($table, $inserts) {
     $values = array_map('mysql_real_escape_string', array_values($inserts));
     $keys = array_keys($inserts);
         
    return mysql_query('INSERT INTO `'.$table.'` (`'.implode('`,`', $keys).'`) VALUES (\''.implode('\',\'', $values).'\')') or sqlerr(__FILE__,__LINE__);
 }
 

function insert_torrent($torrent)
{
	//$row = array('id', 'tid','filename', 'name', 'small_descr', 'url','dl_url','length','descr', 'type','imdb', 'downloaded','uploaded', 'completed');
	
	
	$row = array();
	$row['id']='';
	$row['tid']=sqlesc($torrent['tid']);
	$row['filename']=sqlesc($torrent['filename']);
	$row['name']=sqlesc($torrent['name']);
	$row['small_descr']=sqlesc($torrent['small_descr']);
	$row['url']=sqlesc($torrent['url']);
	$row['dl_url']=sqlesc($torrent['dl_url']);
	$row['length']=sqlesc($torrent['length']);
	$row['descr']=sqlesc($torrent['descr']);
	$row['type']=sqlesc($torrent['type']);
	$row['imdb']=sqlesc($torrent['url']);
	$row['downloaded']=sqlesc($torrent['downloaded']);
	$row['uploaded']=sqlesc($torrent['uploaded']);
	$row['completed']=sqlesc($torrent['completed']);
	$row['hash']=sqlesc($torrent['hash']);
	
	//$query_tail=sprintf("values(%d,%d,%s,%s,%s,%s,%s,%d,%s,%s,%s,%s,%d,%d,%d)",$row['id'],$row['tid'],$row['filename'],$row['name'],$row['small_descr'],$row['url'],$row['dl_url'],$row['length'],$row['descr'],$row['type'],$row['imdb'],$row['downloaded'],$row['uploaded'],$row['completed']);
	
	//echo $query_tail;
	//$ret=mysql_query("insert into torrents('id', 'filename', 'name', 'small_descr', 'url','dl_url','length','descr', 'type','imdb', 'downloaded','uploaded', 'completed') ".$query_tail) or  sqlerr(__FILE__,__LINE__) ;
	$ret=mysql_insert("torrents",$torrent);
	return $ret;
}



 



?>
