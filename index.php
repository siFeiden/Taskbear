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

      $(document).ready(function () {

        function newTaskClick (e) {
          e.preventDefault()

          var edit = $('#new_descr')
          var description = edit.val().trim()
          if ( !description )
            return

          edit.val('')

          // var data = 'description=' + description
          // console.log(data)

          // $.post('new', data, R.ifElse(R.prop('success'), reload, log))
          
          var task = {
            id: Date.now(),
            done: false,
            description: description,
            assigned: null
          }

          document.getElementById('tasklist').addTask(task)
        }

        $('#addTask').on('click', newTaskClick)
        $('#new_descr').on('keypress', R.cond([R.propEq('keyCode', 13), newTaskClick]), function (press) {
          if ( press.keyCode == 13 ) {
            newTaskClick.apply(this, arguments)
            this.value = ''
          }
        })

        $('.claimtask').on('click', function () {
          var dad = $(this).parent()
          var id = dad.attr('data-id')
          var url = id + '/claim'
          log('claim', id, url)
          $.post(url, '', R.ifElse(R.prop('success'), reload, log))
        });
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
          <!-- <input type="text" size="50" placeholder="Description" style="padding: 0 5px;"> -->
          <paper-input-decorator label="Description" style="padding: 0; font-size: 80%;">
            <input is="core-input" id="new_descr">
          </paper-input-decorator>
          <!-- <paper-input label="Description" style="width: 100%;"></paper-input> -->
        </div>
        <paper-icon-button icon="add" id="addTask"></paper-icon-button>
      </div>

    </main>
  </body>
</html>
