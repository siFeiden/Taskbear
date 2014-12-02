<?
  function authUser($mail, $pw) {
    $db = null;
    try {
        $db = new PDO('mysql:host=localhost;dbname=taskbear',
                      'taskbear', 'tb@taskqueue');
    } catch (PDOException $e) {
        die('conn fail');
    }

    $sql_statement = 'SELECT COUNT(Mail) as cnt, Name, Mail
                      FROM User
                      WHERE Mail = :mail
                        AND Password = PASSWORD(:pw);';

    $query = $db->prepare($sql_statement);
    $query->bindValue(':mail', $mail);
    $query->bindValue(':pw'  , $pw);
    $query->execute();

    $row = $query->fetch(PDO::FETCH_ASSOC);

    // return array with information about user
    if ( intval($row['cnt']) == 1 ) {
      return array (
        'isauth' => true,
        'mail'   => $row['Mail'],
        'name'   => $row['Name'],
      );
    } else {
      return array ( 'isauth' => false );
    }
  }


  $mail = $_POST['mail'];
  $pw   = $_POST['password'];
  $fail = false;

  if ( isset($mail) && isset($pw) ) {

    $auth = authUser($mail, $pw);
    if ( $auth['isauth'] ) {
      // start session?
      session_start();
      $_SESSION = $auth;

      header('Location: ./');

    } else {
      $fail = true;
    }
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <!-- Copyright (c) 2014 by Marco Biedermann (http://codepen.io/m412c0/pen/dsbFy) -->
    <meta charset="UTF-8">

    <title>Taskbear</title>

    <link rel="stylesheet" href="css/login.css" media="screen" type="text/css" />

  </head>
  <body>

    <div id="login-form" class="login-form">

      <h3>Taskbear</h3>

      <fieldset>

        <? if ( $fail) { ?>
          <div class="loginfail">Bummer! Your Login failed.</div>
        <? } ?>

        <form method="post" action="./login.php">

          <input type="email" name="mail" placeholder="Email" required autofocus>
          <input type="password" name="password" placeholder="Password" required>
          <input type="submit" value="Login">

        </form>

      </fieldset>

    </div>

  </body>
</html>

