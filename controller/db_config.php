<?

	// $dbhost	= 'localhost';	
	// $dbuser	= 'user id';
	// $dbpass	= 'your password';	
	// $dbname	= 'database name';	
/*	
	$db_url = getenv("CLEARDB_DATABASE_URL");
	$dbhost	= getenv("CLEARDB_DATABASE_HOST");	
	$dbuser	= getenv("CLEARDB_DATABASE_USER");
	$dbpass	= getenv("CLEARDB_DATABASE_PASSWORD");
	$dbname	= getenv("CLEARDB_DATABASE_DB");	


	$conn = mysql_connect($dbhost, $dbuser, $dbpass) or die  ('Error connecting to mysql');
	mysql_select_db($dbname) or $str_info="Unable to open database";

	*/
	
	$url = parse_url(getenv("CLEARDB_DATABASE_URL"));

	$server = $url["host"];
	$username = $url["user"];
	$password = $url["pass"];
	$db = substr($url["path"], 1);

	
	$conn = new mysqli($server, $username, $password, $db);
	mysqli_set_charset($conn, "utf8")

	//mysqli_query('SET NAMES utf8');

?>