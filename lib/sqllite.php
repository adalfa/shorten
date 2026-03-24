<?
//creazione del database 

function open_db()
{

$data= new SQLiteDataBase("url.sqlite");
return $data;
}
function init_hash_db($conn)
{
echo "inizializzo\n";

	echo "Creo\n"; 
	$conn->queryExec("create table url_hash  (id INTEGER PRIMARY KEY ASC, hash TEXT,url TEXT)");
	 $conn->queryExec("create unique index  idx_hsh_url_hash on url_hash(hash)");	


}

function insert_url($conn,$hash,$url)
{
	$squery="insert into url_hash values(NULL,'".$hash."','".$url."')";
	$res=($conn->queryExec($squery,$err));
	if ($res)
		return $conn->lastInsertRowid();
	else return $err;	

}

function get_url($conn,$hash)
{
	$squery="select id,url from url_hash where hash='".$hash."'";
	
	$res=$conn->unbufferedQuery($squery);
	if ($res->valid())
	{
		//$res->fetch();
		return $res->column("url");
	}
	return "";
}

function get_lastid($conn)
{
$squery="select max(id) as id from url_hash";
	
	$res=$conn->unbufferedQuery($squery);
	if ($res->valid())
	{
		//$res->fetch();
		return $res->column("id");
	}
	return "";

}


?>
