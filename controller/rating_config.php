<?
	require('db_config.php');
	
	$rating_tableName     = 'ratings';
	$rating_path_db       = ''; // the path to your db.php file (not used yet!)
	$rating_path_rpc      = ''; // the path to your rpc.php file (not used yet!)
	
	$rating_unitwidth     = 20; // the width (in pixels) of each rating unit (star, etc.)
	// if you changed your graphic to be 50 pixels wide, you should change the value above
	
$rating_conn = mysql_connect($dbhost, $dbuser, $dbpass) or die  ('Error connecting to mysql');
	//mysql_select_db($rating_dbname);

?>