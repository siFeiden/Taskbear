<?php
include_once('utils.php');

header('Content-Type: application/json');

$id = $_GET['id'];
$done = $_GET['done'];

if ( !isset($id) || !ctype_digit($id) )
  exit(nope('id invalid'));

if ( !isset($done) || !in_array($done, array('0', '1')) )
  exit(nope('done invalid'));

$db = db_conn(nope('internal issues'));

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
  echo nope('other failure');
}

?>

