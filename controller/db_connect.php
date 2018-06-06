<?php
	$servername = "localhost";
	$databasename = "db_pos_petct";
	$username = "root";
	$password = "";

	try
	{
		$connection = new PDO("mysql:host=$servername;dbname=$databasename",$username,$password);
		$connection->setAttribute(PDO::ATTR_ERRMODE,PDO::ERRMODE_EXCEPTION);
	}
	catch(PDOExeception $PDOExeception)
	{
		echo "connectionFailed " . $PDOExeception->getMessage();
	}
?>