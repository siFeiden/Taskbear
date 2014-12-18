<?php
	/* 	Contract:
	 * 		requires:
	 *			optional:username
	 *		returns:
	 *			JSON array of task objects, default: all tasks
	 *			                            with username: for user
	 */
  include_once('utils.php');

  header('Content-Type: application/json');

	$db = db_conn(nope('internal issues'));

	$sql_statement = '';
	$query = null;
	
	$username = $_GET['username'];
	
	if( isset($username) ) {
		$sql_statement = 'SELECT t.Id, t.Description, t.Done
                      FROM Task t, Assigned a
                      WHERE a.TaskId = t.Id AND a.Name = :username';

		$query = $db->prepare($sql_statement);
		$query->bindValue(':username', $username, PDO::PARAM_STR);
	} else {
		$sql_statement =   'SELECT t.Id as id,
                               t.Description as description,
                               t.Done as done,
                               a.Name as assigned
                        FROM Task t
                        LEFT JOIN Assigned a ON (t.Id = a.TaskId);';
		$query = $db->prepare($sql_statement);
	}

	$query->execute();
	$result = $query->fetchAll(PDO::FETCH_ASSOC);

  $normalizeData = function($data_row) {
    $data_row['id'] = intval($data_row['id']);
    $data_row['done'] = intval($data_row['done']);

    return $data_row;
  };

  $result = array_map($normalizeData, $result);

	print_r(json_encode($result));
?>

