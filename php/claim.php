<?php
  include_once('utils.php');

  header('Content-Type: application/json');

  session_start();

  function nope ($message) {
    return '{ "success": false, "msg": "' . $message . '" }'
  }

  if ( $_SESSION['isauth'] ) {
    $db = db_conn(nope('internal issues'));

    $id = $_POST['id'];
    $name = $_SESSION['name'];

    $sql_statement = 'INSERT INTO Assigned
                      VALUES (:id, :name)';

    $query = $db->prepare($sql_statement);
    $query->bindValue(':id', $id);
    $query->bindValue(':name', $name);

    if ( $query->execute() ) {
      echo '{ ' .
              '"success": true,' .
              '"id": ' . $id .
              '"name": ' . $name .
           ' }';
    } else {
      echo nope('user invalid');
    }
  } else {
    echo nope('user invalid');
  }
      
?>
