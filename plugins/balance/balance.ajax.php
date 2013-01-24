<?PHP

/* ====================

[BEGIN_SED_EXTPLUGIN]
Code=balance
Part=ajax
File=balance.ajax
Hooks=ajax
Tags=
Minlevel=0
Order=10
[END_SED_EXTPLUGIN]
==================== */

if ( !defined('SED_CODE') ) { die("Wrong URL."); }

$a = sed_import('a','G','ALP');

if($a == 'result'){
	
	// регистрационная информация (пароль #2)
	// registration info (password #2)
	$mrh_pass2 = $cfg['plugin']['balance']['mrh_pass2'];

	//установка текущего времени
	//current date
	$tm=getdate(time()+9*3600);
	$date="$tm[year]-$tm[mon]-$tm[mday] $tm[hours]:$tm[minutes]:$tm[seconds]";

	// чтение параметров
	// read parameters
	$out_summ = $_REQUEST["OutSum"];
	$inv_id = $_REQUEST["InvId"];
	$shp_item = $_REQUEST["Shp_item"];
	$crc = $_REQUEST["SignatureValue"];

	$crc = strtoupper($crc);

	$my_crc = strtoupper(md5("$out_summ:$inv_id:$mrh_pass2:Shp_item=$shp_item"));
	
	// проверка корректности подписи
	// check signature
	if ($my_crc !=$crc){
		echo "bad sign\n";
	}
	else{
		$sql = sed_sql_query("UPDATE sed_balance SET 
			item_status=1, 
			item_date=".(int)$sys['now_offset']." 
			WHERE item_id=".$inv_id."");
			
		echo "OK$inv_id\n";
	}	
	
}

?>