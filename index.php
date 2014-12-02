<?
  session_start();

  if ( !$_SESSION['isauth'] )
    header('location: login.php');

?>
<!DOCTYPE html>
<html>
  <head>
    <title>Taskbear</title>
    <style type="text/css">
      
      main {
        text-align: center;
      }

      .task, .addtask {
        text-align: initial;
        display: inline-flex;
        margin: 10px 40px;
        padding: 13px 20px;
        box-shadow: 0px 1px 2px rgba(0, 0, 0, 0.5);
      }

      .addtask {
        border-top: 1px solid rgb(222, 222, 222);
      }

      .statuscheck {
        margin: auto 0;
        display: inline-block;
        background-size: 100% 100%;
      }

      .todo {
        background: #FBA504;
      }

      .done {
        background: #15B440;
      }

      .descr_twoline {
        min-width: 500px;
        margin: auto 50px;
        display: inline-block;
      }

      .description {
        color: #333;
        font-size: 95%;
        font-weight: bold;
      }

      .statustext {
        color: #595959;
        font-size: 0.8em;
      }

      a > img {
        display: block;
      }
      
      .avatar {
        margin: auto 0;
        border-radius: 25%;
        border: 1px solid rgba(0, 0, 0, 0.5);
      }

      .claimtask {
        margin: auto 0;
        
      }
    </style>
	<script src="//ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
  <script src="//cdnjs.cloudflare.com/ajax/libs/ramda/0.8.0/ramda.min.js"></script>
    <script type="text/javascript">
      /*
        url patterns
        /new
        /{id}/done/?(yes|no)?
        /{id}/claim

      */
      var log = console.log.bind(console)

      $(document).ready(function () {
        var checks = $('.task .statuscheck')

        // register two event listeners, one to notify the server of changes
        // and one for immediate style changes
        // the first one is debounced to prevent sending many request to the server in a short time
        checks.on('change', debounce(function () {
          var dad = $(this).parent()
          var url = dad.attr('data-id') + '/done/' + (this.checked ? 'yes' : 'no')
          $.post(url, R.I)
        }, 500))

        checks.on('change', function() { $(this).parent().toggleClass('done todo') })

        var new_task_handler = function (e) {
          e.preventDefault()

          var description = $('#new_descr').val().trim()
          if ( !description )
            return

          var data = 'description=' + description
          console.log(data)

          var reload = location.reload.bind(location)
          $.post('new', data, R.ifElse(R.prop('success'), reload, log))
          
          // optTODO create new element
        }

        $('#addTask').on('click', new_task_handler)
        $('#new_descr').on('keypress', R.cond([R.propEq('keyCode', 13), new_task_handler]))
      })

      function debounce(func, wait) {
        var timer = -1, args = null, context = null;

        return function() {
          if ( timer === -1 )
            timer = window.setTimeout(function() { timer = -1; func.apply(context, args) }, wait)

          args = arguments
          context = this
        }
      }
    </script>
  </head>
  <body>
	<main>

      <?php
          $db = null;
          try {
              $db = new PDO('mysql:host=localhost;dbname=taskbear',
                            'taskbear', 'tb@taskqueue');
          } catch (PDOException $e) {
              die('conn fail');
          }

		      $sql_statement = 'SELECT t.Id, t.Description, t.Done, a.Name
                            FROM Task t
                            LEFT JOIN Assigned a
                            ON(a.TaskId = t.Id)';

          $query = $db->prepare($sql_statement);
          $query->execute();

          $result = $query->fetchAll(PDO::FETCH_ASSOC);
          
          foreach ($result as $row) {
              $assigned = strlen($row['Name']) > 0;
              $descr = $row['Description'];
              $done  = $row['Done'];
              $name  = $row['Name'];
              $id = $row['Id'];
      ?>

      <div class="task <?php echo $done ? 'done' : 'todo'; ?>" data-id="<?php echo $id; ?>">

        <input type="checkbox" class="statuscheck"
          <?php if ( $done ) echo 'checked'; if ( !$assigned ) echo 'disabled'; ?>>

        <div class="descr_twoline">
          <span class="description"><?php echo $descr; ?></span><br>
          <span class="statustext"><?php echo $done ? 'Completed' : 'To be done'; ?></span>
        </div>

        <?php if ( $assigned ) { ?>
          <img width="25" height="25" class="avatar"
              src="http://api.adorable.io/avatars/80/<? echo hash('adler32', $name); ?>" title="<?php echo $name; ?>">
        <? } else { ?>
          <a href="#" class="claimtask"><img width="25" height="25" src="img/go.svg"></a>
        <? } ?>
      </div>

      <?php } ?>

      <div class="addtask">
        <input type="checkbox" class="statuscheck" disabled>
        <div class="descr_twoline">
          <input type="text" id="new_descr" size="50" placeholder="Description" style="padding: 0 5px;">
        </div>
        <a href="#" id="addTask" style="margin: auto 0;">
          <img width="25" height="25" src="img/add.svg" title="Add new task">
        </a>
      </div>

    </main>
  </body>
</html>

