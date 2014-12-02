<?php
header('Content-Type: application/json');

$id = $_GET['id'];
$done = $_GET['done'];

if ( !isset($id) || !ctype_digit($id) )
  exit('{ "success": false, "msg": "id invalid" }');

if ( !isset($done) || !in_array($done, array('0', '1')) )
  exit('{ "success": false, "msg": "done invalid" }');

$db = null;
try {
	$db = new PDO('mysql:host=localhost;dbname=taskbear',
                'taskbear',
                'tb@taskqueue');
} catch (PDOException $e) {
	echo 'it died :(';
}

$sql_statement = 'UPDATE Task SET Done = :done WHERE Id = :id';

$done = ( $done == '1' );

$query = $db->prepare($sql_statement);
$query->bindValue(':done', $done, PDO::PARAM_BOOL);
$query->bindValue(':id', intval($id), PDO::PARAM_INT);

if ( $query->execute() ) {
  echo '{ ' .
         '"success": true, ' .
         '"id": ' . $id . ', ' .
         '"done": ' . ($done ? 'true' : 'false') .
       ' }';
} else {
  echo '{ "success": false, "msg": "other failure" }';
}

?>

