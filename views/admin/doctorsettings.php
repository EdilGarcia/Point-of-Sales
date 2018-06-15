<!DOCTYPE html>

<html>
  <head>
    <title>
      Makati PET/CT Center
    </title>
    <link rel="stylesheet" type="text/css" href="./../../css/bootstrap.min.css">
    <link rel="stylesheet" href="./../../css/w3.css">
    <link rel="stylesheet" type="text/css" href="./../../css/setting.css">

    <script src="./../../js/jquery-3.3.1.min.js"></script>
    <script src="./../../js/jquery-ui.min.js"></script>
    <script src="./../../js/bootstrap.min.js"></script>
  </head>

  <body>

   <header>
      <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">

          <div class="navbar-header">
            <a href="index.php" class="navbar-brand"><img src="./../../files/logo/brandimagev2.png" class="img-responsive"></a>
          </div>

          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">

            <ul class="nav navbar-nav navbar-right" id="navbar-right">
              <li><a href="#">Account</a></li>
              <li><a href="./../../controller/logout.php">Log Out</a></li>
            </ul>
          </div>
        </div>
      </nav>
    </header>

    <main>
      <div class="container-fluid">
        <div class="row">
          <div class="col-sm-3 col-md-2 sidebar">
            <ul class="nav nav-sidebar">
              <li>
                <a href="index.php"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>&nbsp Dashboard <span class="sr-only">(current)</span> </a>
                <ul class="nav" id="mn-sub-menu">
                  <li><a href="account.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp Accounts</a></li>
                  <li><a href="patientsearch.php"><span class="glyphicon glyphicon-bed" aria-hidden="true"></span>&nbsp Search Patient</a></li>
                  <li><a href="invoicecreation.php"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>&nbsp Search Transactions</a></li>
                </ul>
              </li>

              <li>
                  <a style="background-color: #edf0f5; color: #000000;"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>&nbsp Maintenance</a>
                  <ul class="nav" id="mn-sub-menu">
                      <li><a href="patientsettings.php"><span class="glyphicon glyphicon-bed" aria-hidden="true"></span>&nbsp Patient</a></li>
                      <li style="color: #000000;background-color: #b7d7f0;"><a href="doctorsettings.php"><span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>&nbsp Doctor</a></li>
                      <li><a href="itemsettings.php"><span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>&nbsp Item</a></li>
                      <li><a href="treatmentsettings.php"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>&nbsp Procedure</a></li>
                  </ul>
              </li>
            </ul>
          </div>

          <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">

            <div class="col-sm-12 col-md-12">
              <div class="mn-dashboard-header">
                <h3>Doctor Settings</h3>
                <hr>
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#mn-doctors-panel" data-toggle="tab">Doctors</a></li>
                  <li><a href="#mn-deleted-doctors-panel" data-toggle="tab">Deleted Doctors</a></li>
                </ul>
              </div>
              <div class="tab-content">
                <div class="panel panel-primary filterable tab-pane active" id="mn-doctors-panel">
                  <div class="panel-heading" style="height: 50px;">
                    <h3 class="panel-title">Doctors</h3>
                    <div class="pull-right">
                      <button class="btn btn-default btn-md btn-filter"><span class="fa fa-filter"></span> Filter</button>
                      <button class="btn btn-default btn-md" data-toggle="modal" data-target="#open_doctor"><span class="fa fa-plus"></span> Add</button>
                    </div>
                  </div>

                  <table class="table table-hover">
                      <thead>
                          <tr class="filters">
                              <th class="col-md-2"><input type="text" class="form-control" placeholder="Name" disabled></th>
                              <th class="col-md-1"><input type="text" class="form-control" placeholder="Gender" disabled></th>
                              <th class="col-md-2"><input type="text" class="form-control" placeholder="Date of Birth" disabled></th>
                              <th class="col-md-2"><input type="text" class="form-control" placeholder="Address" disabled></th>
                              <th class="col-md-2"><input type="text" class="form-control" placeholder="Contact No." disabled></th>
                              <th class="col-md-1"><input type="text" class="form-control" placeholder="Fee" disabled></th>
                              <th class="col-md-2">Action</th>
                          </tr>
                      </thead>

                      <tbody>
                        <?php
                          require('./../../controller/db_connect.php');
                          $preparedStmt = "SELECT * FROM `tbl_doctor` WHERE `doctor_status`= 1 ORDER BY `doctor_name`";
                          $stmt = $connection->prepare($preparedStmt);
                          $stmt->execute();
                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                          {
                          ?>

                          <tr class="clickable-row">
                              <td><?php echo $row['doctor_name']; ?></td>
                              <td><?php echo $row['doctor_gender']; ?></td>
                              <td><?php echo $row['doctor_date_of_birth']; ?></td>
                              <td><?php echo $row['doctor_address']; ?></td>
                              <td><?php echo $row['doctor_contact_number']; ?></td>
                              <td><?php echo $row['doctor_professional_fee']; ?></td>
                              <td><input type="submit" class="btn btn-info btn-sm doctor_update" value="Update" id="<?php echo $row['doctor_id']; ?>" name="doctor_update" />
                                  <input type="submit" class="btn btn-danger btn-sm doctor_delete" value="Delete" id="<?php echo $row['doctor_id']; ?>" name="doctor_delete" /></td>
                          </tr>

                          <?php
                          }
                          ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="panel panel-primary filterable tab-pane" id="mn-deleted-doctors-panel">
                    <div class="panel-heading" style="height: 50px;">
                      <h3 class="panel-title">Doctors</h3>
                      <div class="pull-right">
                        <button class="btn btn-default btn-md btn-filter"><span class="fa fa-filter"></span> Filter</button>
                      </div>
                    </div>

                    <table class="table table-hover">
                      <thead>
                          <tr class="filters">
                              <th class="col-md-2"><input type="text" class="form-control" placeholder="Name" disabled></th>
                              <th class="col-md-1"><input type="text" class="form-control" placeholder="Gender" disabled></th>
                              <th class="col-md-2"><input type="text" class="form-control" placeholder="Date of Birth" disabled></th>
                              <th class="col-md-2"><input type="text" class="form-control" placeholder="Address" disabled></th>
                              <th class="col-md-2"><input type="text" class="form-control" placeholder="Contact No." disabled></th>
                              <th class="col-md-1"><input type="text" class="form-control" placeholder="Fee" disabled></th>
                              <th class="col-md-2">Action</th>
                          </tr>
                      </thead>

                      <tbody>
                        <?php
                          require('./../../controller/db_connect.php');
                          $preparedStmt = "SELECT * FROM `tbl_doctor` WHERE `doctor_status`= 2 ORDER BY `doctor_name`";
                          $stmt = $connection->prepare($preparedStmt);
                          $stmt->execute();
                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                          {
                          ?>

                          <tr class="clickable-row">
                              <td><?php echo $row['doctor_name']; ?></td>
                              <td><?php echo $row['doctor_gender']; ?></td>
                              <td><?php echo $row['doctor_date_of_birth']; ?></td>
                              <td><?php echo $row['doctor_address']; ?></td>
                              <td><?php echo $row['doctor_contact_number']; ?></td>
                              <td><?php echo $row['doctor_professional_fee']; ?></td>
                              <td>
                                <form method="post" action='./../../controller/transactions.php'>
                                  <input type='hidden' value="<?php echo $row['doctor_id']; ?>" name="doctor_id"/>
                                  <input type='hidden' value="./../views/admin/doctorsettings.php" name="path"/>
                                  <input type="submit" class="btn btn-danger btn-sm doctor_restore" value="Restore" name="doctor_activate"/>
                                </form>
                              </td>
                          </tr>

                          <?php
                          }
                          ?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
            </div> <!--main-->

          </div>
        </div>
      </div>
    </main>

    <!-- Modal -->
    <div id="open_doctor" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Doctor Information</h4>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <form class="form-horizontal" role="form" method="post" action="./../../controller/transactions.php">

                <div class="form-group">
                  <div class="col-md-12">
                    <input type="hidden" value="0" name="doctor_add" id="doctor_add">
                  </div>
                </div>
                <div class="row">
                  <div class="form-group">
                    <div class="col-md-10 col-md-offset-1">
                      <p>Doctor Name:</p>
                    </div>
                    <div class="col-md-10 col-md-offset-1">
                      <input type="text" class="form-control" placeholder="Last Name, First Name Middle Name" name="doctor_name" id="doctor_name" required/>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-5 col-md-offset-1">
                      <p>Gender: </p>
                    </div>
                    <div class="col-md-5 col-md-offset-1">
                      <p>Birthday: </p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-1 col-md-offset-1">
                      <label class="radio-inline">
                        <input type="radio" value="Male" name="doctor_gender" id="doctor_gender" required/>Male
                      </label>
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                      <label class="radio-inline">
                        <input type="radio" value="Female" name="doctor_gender" id="doctor_gender" required/>Female
                      </label>
                    </div>
                    <div class="col-md-6 col-md-4 col-md-offset-3">
                      <input type="date" class="form-control" name="doctor_date_of_birth" id="doctor_date_of_birth" required/>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                      <p>Address: </p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                      <input type="text" class="form-control" placeholder="Address" name="doctor_address" id="doctor_address" required/>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                      <p>Contact Number: </p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                      <input type="text" class="form-control" placeholder="Contact Number" name="doctor_contact_number" id="doctor_contact_number" required/>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                      <p>Professional Fee: </p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                      <input type="text" class="form-control" placeholder="Professional Fee" name="doctor_professional_fee" id="doctor_professional_fee" required/>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-4 col-md-offset-7">
                        <input type="submit" class="btn btn-default pull-right" id="doctor_add_btn" name="doctor_add_btn" value="Add"/>
                    </div>
                  </div>
                </div>

              </form>
            </div>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div>

      </div>
    </div>


    <!--  Modal  -->
    <div id="update_delete_doctor" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!--  Modal content -->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Doctor Details</h4>
          </div>
          <div class="modal-body" id="doctor_info">
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
          <p class="text-center">© 2018 Makati PET/CT Center</p>
        </div>
      </div>

    </footer>

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

        $(".doctor_update").click(function() {
          var doctor_id = $(this).attr('id');
          var path = "./../views/admin/doctorsettings.php";
          $.ajax({
            url: "./../../controller/modal_views.php",
            method: "post",
            data: {doctor_id:doctor_id,
                  path:path,
                  doctor_view_update: 0},
            success: function(data) {
              $('#doctor_info').html(data);
              $('#update_delete_doctor').modal('show');
            }
          })
        });

        $(".doctor_delete").click(function() {
          var doctor_id = $(this).attr('id');
          var path = "./../views/admin/doctorsettings.php";
          $.ajax({
            url: "./../../controller/modal_views.php",
            method: "post",
            data: {doctor_id:doctor_id,
                  path:path,
                  doctor_view_delete: 1},
            success: function(data) {
              $('#doctor_info').html(data);
              $('#update_delete_doctor').modal('show');
            }
          })
        });
    });
    </script>
  </body>
</html>
