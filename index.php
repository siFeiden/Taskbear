<?
  include_once('php/utils.php');
  session_start();
  if ( !$_SESSION['isauth'] )
    header('Location: ./login');
?>

<!DOCTYPE html>
<html>
  <head>
    <title>Taskbear</title>
    <link rel="stylesheet" type="text/css" href="css/index.css">

    <script src="components/webcomponentsjs/webcomponents.js"></script>
    <link rel="import" href="task-list.html">
    <!-- <link rel="import" href="task-box.html"> -->
    <link rel="import" href="components/paper-input/paper-input-decorator.html">

  	<script src="http://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
    <script src="http://cdnjs.cloudflare.com/ajax/libs/ramda/0.8.0/ramda.min.js"></script>
    <script type="text/javascript">
      var log = console.log.bind(console)

      document.addEventListener('DOMContentLoaded', function () {

        function newTaskClick (e) {
          e.preventDefault()

          var edit = document.getElementById('new_descr')
          var description = edit.value.trim()
          if ( !description )
            return

          edit.val('')

          var data = 'description=' + encodeURIComponent(description)

          $.post('new', data, function (resp) {
            if ( resp.success )
              document.getElementById('tasklist').addTask(resp.task)

          })
        }

        document.getElementById('addTask')
          .addEventListener('click', newTaskClick)

        document.getElementById('new_descr').addEventListener('keypress',
            R.cond([R.propEq('keyCode', 13), newTaskClick]), function (press) {
              if ( press.keyCode == 13 ) {
                newTaskClick.apply(this, arguments)
                this.value = ''
              }
            })
      })/*

      function debounce(func, wait) {
        var timer = -1, args = null, context = null;

        return function() {
          if ( timer === -1 )
            timer = window.setTimeout(function() { timer = -1; func.apply(context, args) }, wait)

          args = arguments
          context = this
        }
      }*/
    </script>
  </head>
  <body>
    <main>

      <task-list id="tasklist"></task-list>

      <div class="addtask">
        <paper-checkbox disabled unchecked class="statuscheck"></paper-checkbox>
        <div class="descr_twoline">
          <paper-input-decorator label="Description" style="padding: 0; font-size: 80%;">
            <input is="core-input" id="new_descr" value="">
          </paper-input-decorator>
        </div>
        <paper-icon-button icon="add" id="addTask"></paper-icon-button>
      </div>

    </main>
  </body>
</html>
