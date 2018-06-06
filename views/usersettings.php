<?php
  // require("controller/db_connect.php");
?>

<!DOCTYPE html>
<html>
  <head>
    <title>
      Makati PET/CT Center
    </title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/font-awesome.min.css">
    <script src="../js/jquery-3.3.1.min.js"></script>
    <script src="../js/jquery-ui.min.js"></script>
    <script src="../js/bootstrap.min.js"></script>
  </head>
  <body>
    <nav class="navbar navbar-inverse navbar-fixed-top">

      <div class="container-fluid">
        <div class="navbar-header">
          <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
            <span class="sr-only">Toggle navigation</span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
            <span class="icon-bar"></span>
          </button>
          <a href="patientrecord.php" class="navbar-brand"><img src="../files/logo/brandimagev2.png" class="img-responsive"></a>
        </div>

        <div id="navbar" class="navbar-collapse collapse">
          <ul class="nav navbar-nav navbar-right" id="navbar-right">
            <li><a href="patientrecord.php" class="active">Dashboard</a></li>
            <li><a href="patientsettings.php">Settings</a></li>
            <li><a href="../controller/logout.php">Log Out</a></li>
          </ul>

          <form class="navbar-form navbar-right">
            <input type="text" class="form-control" placeholder="Search">
          </form>
        </div>
      </div>
    </nav>

    <div class="container-fluid">

      <div class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li> <a href="patientsettings.php"><i class="fa fa-user" aria-hidden="true"></i> &nbsp Patients </a> </li>
            <li><a href="doctorsettings.php"> <i class="fa fa-user-md" aria-hidden="true"></i> &nbsp Doctors </a></li>
            <li><a href="treatmentsettings.php"> <i class="fa fa-stethoscope" aria-hidden="true"></i> &nbsp Treatments</a></li>
            <li><a href="itemsettings.php"> <i class="fa fa-check-square" aria-hidden="true"></i> &nbsp Items</a></li>
            <li class="active"><a href="usersettings.php"> <i class="fa fa-user" aria-hidden="true"></i> &nbsp Users <span class="sr-only">(current)</span> </a></li>
          </ul>
        </div>

        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

          <div class="panel panel-primary filterable">
            <div class="panel-heading" style="height: 50px;">
                <h3 class="panel-title">Users</h3>
                <div class="pull-right">
                    <button class="btn btn-default btn-md btn-filter"><span class="fa fa-filter"></span> Filter</button>
                    <button class="btn btn-default btn-md" data-toggle="modal" data-target="#open_user"><span class="fa fa-plus"></span> Add</button>
                </div>
            </div>

            <table class="table table-hover">
                <thead>
                    <tr class="filters">
                        <th><input type="text" class="form-control" placeholder="Name" disabled></th>
                        <th><input type="text" class="form-control" placeholder="Gender" disabled></th>
                        <th><input type="text" class="form-control" placeholder="Date of Birth" disabled></th>
                        <th><input type="text" class="form-control" placeholder="Address" disabled></th>
                        <th><input type="text" class="form-control" placeholder="Username" disabled></th>
                        <th><input type="text" class="form-control" placeholder="Password" disabled></th>
                        <th>Action</th>
                    </tr>
                </thead>

                <tbody>
                  <?php
                    require('../controller/db_connect.php');
                    $preparedStmt = "SELECT * FROM `tbl_user` ORDER BY `user_name`";
                    $stmt = $connection->prepare($preparedStmt);
                    $stmt->execute();
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                    {
                    ?>

                    <tr class="clickable-row">
                        <td><?php echo $row['user_name']; ?></td>
                        <td><?php echo $row['user_gender']; ?></td>
                        <td><?php echo $row['user_date_of_birth']; ?></td>
                        <td><?php echo $row['user_address']; ?></td>
                        <td><?php echo $row['user_username']; ?></td>
                        <td><?php echo $row['user_password']; ?></td>
                        <td><input type="submit" class="btn btn-info btn-sm user_update" value="Update" id="<?php echo $row['user_id']; ?>" name="user_update" />
                            <input type="submit" class="btn btn-danger btn-sm user_delete" value="Delete" id="<?php echo $row['user_id']; ?>" name="user_delete" /></td>
                    </tr>

                    <?php
                    }
                    ?>
                </tbody>
            </table>
          </div>

        </div> <!--main-->
      </div> <!--row-->

    </div> <!--container-fluid-->

    <!-- Modal -->
    <div id="open_user" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">User Information</h4>
          </div>
          <div class="modal-body">

            <form class="form-horizontal" role="form" method="post" action="../controller/transactions.php">

              <div class="form-group">
                <div class="col-md-12">
                  <input type="hidden" value="0" name="user_add" id="user_add">
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-12">
                  <input type="text" class="form-control" placeholder="Last Name, First Name Middle Name" name="user_name" id="user_name" required/>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-2">
                  <h5>Gender: </h5>
                </div>

                <div class="col-md-2">
                  <label class="radio-inline">
                    <input type="radio" value="Male" name="user_gender" id="user_gender" required/>Male
                  </label>
                </div>

                <div class="col-md-2">
                  <label class="radio-inline">
                    <input type="radio" value="Female" name="user_gender" id="user_gender" required/>Female
                  </label>
                </div>

                <div class="col-md-2">
                  <h5>Birthday: </h5>
                </div>

                <div class="col-md-4">
                  <input type="date" class="form-control" name="user_date_of_birth" id="user_date_of_birth" required/>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-12">
                  <input type="text" class="form-control" placeholder="Address" name="user_address" id="user_address" required/>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-12">
                  <input type="text" class="form-control" placeholder="Username" name="user_username" id="user_username" required/>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-12">
                  <input type="text" class="form-control" placeholder="Password" name="user_password" id="user_password" required/>
                </div>
              </div>

              <div class="form-group">
                <div class="col-md-4 col-md-offset-8">
                    <input type="submit" class="btn btn-default pull-right" id="user_add_btn" name="user_add_btn" value="Add"/>
                </div>
              </div>

            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div>

      </div>
    </div>


    <!--  Modal  -->
    <div id="update_delete_user" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!--  Modal content -->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Doctor Details</h4>
          </div>
          <div class="modal-body" id="user_info">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div>
      </div>
    </div>

    <footer>

      <div class="container-fluid">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">
          <p class="text-center"><a href="index.php">Terms of Service</a> | <a href="index.php">Privacy</a><p>
          <p class="text-center">© 2018 Makati PET/CT Center</p>
        </div>

      </div>

    </footer>

    <!--CSS-->

    <style>

      /*
       * Body
       */

      body {
        padding-top: 50px;
        background-color: #edf0f5;
      }

      /*
       * Topbar Navigation
       */

      .navbar {
        background-color: #75b0b2;
        border: 0;

      }

      .navbar-brand>img {
        margin-top: -15px;
        max-width: 150px;
      }

      #navbar-right > li> a{
        color: #000000;
      }

      #navbar-right > li> a:hover{
        color: #ffffff;
      }

      /*
       * Sidebar Navigation
       */

      .sidebar {
        display: none;
      }

      @media (min-width: 768px) {
        .sidebar {
          position: fixed;
          top: 51px;
          bottom: 0;
          left: 0;
          z-index: 1000;
          display: block;
          padding: 20px;
          overflow-x: hidden;
          overflow-y: auto; /* Scrollable contents if viewport is shorter than content. */
          background-color: #c3d3d4;
          border-right: 1px solid #eee;
        }
      }

      .nav-sidebar {
        margin-right: -21px; /* 20px padding + 1px border */
        margin-bottom: 20px;
        margin-left: -20px;
      }

      .nav-sidebar > li > a {
        padding-right: 20px;
        padding-left: 20px;
      }

      .nav-sidebar > .active > a {
        color: #000000;
        background-color: #acc6c7;
      }

      .nav-sidebar li > a {
        color: #000000;
      }

      .nav-sidebar > .active > a:hover,
      .nav-sidebar > .active > a:focus {
        color: #ffffff;
        background-color: #75b0b2;
      }

      .nav-sidebar li > a:hover {
        color: #ffffff;
        background-color: #75b0b2;
      }


      /*
       * Main content
       */

      .main {
        padding: 20px;
      }

      @media (min-width: 768px) {
        .main {
          padding-right: 40px;
          padding-left: 40px;
        }
      }
      .main .page-header {
        margin-top: 0;
      }

      /*
       * Dashboard Content
       */

      .filterable {
        margin-top: 15px;
      }

      .filterable .panel-heading .pull-right {
        margin-top: -20px;
      }
      .filterable .filters input[disabled] {
        background-color: transparent;
        border: none;
        cursor: auto;
        box-shadow: none;
        padding: 0;
        height: auto;
      }

      .filterable .filters input[disabled]::-webkit-input-placeholder {
        color: #333;
      }

      .filterable .filters input[disabled]::-moz-placeholder {
        color: #333;
      }

      .filterable .filters input[disabled]:-ms-input-placeholder {
        color: #333;
      }

      footer {
        margin-top: 30px;
        background-color: #75b0b2;
        height: auto;
        padding: 20px;
      }

    </style>


    <!--Script-->

    <script type="text/javascript">

      $(document).ready(function(){
        $('.filterable .btn-filter').click(function(){
            var $panel = $(this).parents('.filterable'),
            $filters = $panel.find('.filters input'),
            $tbody = $panel.find('.table tbody');
            if ($filters.prop('disabled') == true) {
                $filters.prop('disabled', false);
                $filters.first().focus();
            } else {
                $filters.val('').prop('disabled', true);
                $tbody.find('.no-result').remove();
                $tbody.find('tr').show();
            }
        });

        $('.filterable .filters input').keyup(function(e){
            /* Ignore tab key */
            var code = e.keyCode || e.which;
            if (code == '9') return;
            /* Useful DOM data and selectors */
            var $input = $(this),
            inputContent = $input.val().toLowerCase(),
            $panel = $input.parents('.filterable'),
            column = $panel.find('.filters th').index($input.parents('th')),
            $table = $panel.find('.table'),
            $rows = $table.find('tbody tr');
            /* Dirtiest filter function ever ;) */
            var $filteredRows = $rows.filter(function(){
                var value = $(this).find('td').eq(column).text().toLowerCase();
                return value.indexOf(inputContent) === -1;
            });
            /* Clean previous no-result if exist */
            $table.find('tbody .no-result').remove();
            /* Show all rows, hide filtered ones (never do that outside of a demo ! xD) */
            $rows.show();
            $filteredRows.hide();
            /* Prepend no-result row if all rows are filtered */
            if ($filteredRows.length === $rows.length) {
                $table.find('tbody').prepend($('<tr class="no-result text-center"><td colspan="'+ $table.find('.filters th').length +'">No result found</td></tr>'));
            }
        });


        $(".user_update").click(function() {
          $('#update_delete_user').modal('show')
          var user_id = $(this).attr('id');

          $.ajax({
            url: "../controller/transactions.php",
            method: "post",
            data: {user_id:user_id,
                    user_view_update: 0},
            success: function(data) {
              $('#user_info').html(data);
              $('#update_delete_user').modal('show')
            }
          })
        });

        $(".user_delete").click(function() {
          $('#update_delete_user').modal('show')
          var user_id = $(this).attr('id');

          $.ajax({
            url: "../controller/transactions.php",
            method: "post",
            data: {user_id:user_id,
                    user_view_delete: 1},
            success: function(data) {
              $('#user_info').html(data);
              $('#update_delete_user').modal('show')
            }
          })
        });

    });

    </script>

  </body>

</html>
