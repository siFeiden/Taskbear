<?
function db_conn ($msg = null) {
  try {
    $db = new PDO('mysql:host=localhost;dbname=taskbear',
                  'taskbear',
                  'tb@taskqueue');
    return $db;
  } catch (PDOException $e) {
    if ( isset($msg) )
      exit($msg);

    return null;
  }
}

function nope ($message) {
  return '{ "success": false, "msg": "' . $message . '" }';
}



?>
