<?php
header('Content-Type: application/json');

$description = $_POST['description'];

if ( !isset($description) || strlen($description) < 1 )
  exit('{ "success": false, "msg": "description missing" }');

$db = null;
try {
	$db = new PDO('mysql:host=localhost;dbname=taskbear',
                'taskbear',
                'tb@taskqueue');
} catch (PDOException $e) {
	echo 'it died :(';
}

$sql_statement = 'INSERT INTO Task(Description)
                  VALUES (:description)';

$query = $db->prepare($sql_statement);
$query->bindValue(':description', $description);

if ( $query->execute() ) {
  echo '{ ' .
         '"success": true, ' .
         '"id": ' . $db->lastInsertId() . ', ' .
         '"description": "' . $description . '" ' .
       '}';
} else {
  echo '{ "success": false, "msg": "other failure" }';
}

?>

