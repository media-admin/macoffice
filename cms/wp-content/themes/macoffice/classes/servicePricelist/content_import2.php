<?php

$query = "
    DROP DATABASE IF EXISTS web
";
mysqli_query($connect, $query);

$query = "
    CREATE DATABASE web
";
mysqli_query($connect, $query);

$conn =new mysqli('localhost', 'root', '' , 'web');

$query = '';
$sqlScript = file('import/import.sql');
foreach ($sqlScript as $line)	{
	
	$startWith = substr(trim($line), 0 ,2);
	$endWith = substr(trim($line), -1 ,1);
	
	if (empty($line) || $startWith == '--' || $startWith == '/*' || $startWith == '//') {
		continue;
	}
		
	$query = $query . $line;
	if ($endWith == ';') {
		mysqli_query($conn,$query) or die('<div class="error-response sql-import-response">Import fehlgeschlagen <b>' . $query. '</b></div>');
		$query= '';		
	}
}
echo '<h2 class="m-3 text-center success-response sql-import-response">Import erfolgreich</h2><div class="text-center mt-4"><a href="quickprice.php"><button type="button" class="text-center btn btn-success">Weiter</button></a></div>';
?>