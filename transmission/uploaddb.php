<?php 
//include_once('uploaddb.inc.php');
include_once('uploaddb.inceku.php');
include_once('dbconfig.php');

echo "upload</br>";
$hash=$argv[1];
//$hash="c2fc68bfed3ac1b5d65416e35ed74be3007a328d";
//$hash="a750ebe2c2701c98ce67f98e9bfc7cfb2c5abde3";
//67281b410f3af007ba7fb3e671d84fd47cc729c9
//e280526da897b4854170cf69bc1c63bf34e14bd6
//$hash="e280526da897b4854170cf69bc1c63bf34e14bd6";
echo "The hash for the torrent is.......".$hash."\n";
//af405576d7e425c58968147f150aae197c446238
dbconn();
$res=get_torrent_byhash($hash);

//$res=get_torrent_upload();
if(mysql_affected_rows())
{
	while ($item = mysql_fetch_array($res)) 
	{
		update_torrent_completed($item['hash']);
        	printf ("upload torrent: torrentID: %s name %s url: %s \n", $item['doubanid'], $item['filename'], $item['url']);
//		uploadTorrent($item);
              uploadTorrent_eku($item);
		$ret=update_torrent_uploaded($item['id']);
		if($ret)
		{
			if(mysql_affected_rows())
			{
				printf("torrent %s has been uploaded and updated\n",$item['filename']);
			}
		}
	}

		
}
else{
		printf("no torrents needed to be uploaded\n");	
	}
	
//free memory
mysql_free_result($res);
	
?>
