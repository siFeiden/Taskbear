<?php
include_once('utils.php');

header('Content-Type: application/json');

$description = $_POST['description'];

if ( !isset($description) || strlen($description) < 1 )
  exit(nope('description missing'));

$db = db_conn(nope('internal issues'));

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
  echo nope('other failure');
}

?>

