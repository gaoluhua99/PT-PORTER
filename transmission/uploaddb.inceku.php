<?php

function uploadTorrent_eku($torrent){

$ipurl='http://222.199.184.40/nexusphp';

/*********info neede for login ***********************/
$username='vip';
$password='vip123';
$loginurl=$ipurl.'/takelogin.php';

/*********info needed for upload**********************/
$uploadurl  = $ipurl.'/takeupload_api.php';
$referurl=$ipurl.'/upload.php';





/*********dir for saving torrent when uploaded suceessfully******************/


$dest_host=$ipurl."/download.php?id=";
$dest_passkey="&passkey=292f839cc1c4dcd3da580eb23963e105";
$dl_torrent_dir='/data/transmission/transmission_watch/'; 


/*************upload torrent *****************************/ 




$fields['file'] = '@'.$torrent['filename'];
$fields['name'] = $torrent['name'];
$fields['small_descr'] = $torrent['small_descr'];
$fields['url'] = $torrent['imdb'];
$fields['nfo'] = '';
$fields['descr'] = $torrent['descr'];
$fields['type'] = '401';
$fields['medium_sel'] = '';
$fields['codec_sel'] = '';
$fields['standard_sel'] = '';
$fields['audiocodec_sel'] = '';
$fields['team_sel'] = '';
$fields['doubanid']=$torrent['doubanid'];
$fields['passkey']='292f839cc1c4dcd3da580eb23963e105';

$ch = curl_init();

curl_setopt($ch, CURLOPT_URL, $uploadurl );
curl_setopt($ch, CURLOPT_POST, 1 );
curl_setopt($ch, CURLOPT_POSTFIELDS, $fields );
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt ($ch, CURLOPT_REFERER, $referurl);
//curl_setopt($ch, CURLOPT_COOKIEFILE, $cookie_jar);
curl_setopt($ch,CURLOPT_FOLLOWLOCATION,1);//get redirect content
curl_setopt($ch, CURLOPT_NOBODY, false);
//curl_exec( $ch );
$rs = curl_exec($ch);
if ($error = curl_error($ch) ) {
          die($error);
}

$str=sprintf("%s",$rs);
$strid=$str;
curl_close($ch);
//print_r(htmlspecialchars($rs));


echo "\n";



//redownload  torrent  
echo "begin redownload torrent"."\n";
echo $str."\n";

//$pattern='/userdetails\.php\?id=[0-9]+/';
$pattern='/download\.php\?id=[0-9]+/';
$count = preg_match_all($pattern,$str,$id,PREG_SET_ORDER);
if($count+0<=0)
{
	echo "no download url"."\n";
}

foreach ($id as  $item)
{
	echo $item[0]."\n";
	$middle=$item[0];
	echo "middle=".$middle."\n";
}
$host=$dest_host;
$tail=$dest_passkey;
$torrent_dir=$dl_torrent_dir;

$newid=substr($strid,32);

include_once('curl.download.php');
$torrent_url=$host.$newid.$tail;

$fn=curlTool::downloadFile($torrent_url,$torrent_dir);
echo "Grab file path =",$fn,"\n";
//var_dump(CurlTool::$attach_info);
print_r(CurlTool::$attach_info);


return $fn;

}
?>
