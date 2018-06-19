<?php
  session_start();
?>
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
            <a href="./" class="navbar-brand"><img src="./../../files/logo/brandimagev2.png" class="img-responsive"></a>
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
                <a href="./"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>&nbsp Dashboard <span class="sr-only">(current)</span> </a>
                <ul class="nav" id="mn-sub-menu">
                  <li><a href="account.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp Accounts</a></li>
                  <li><a href="patientsearch.php"><span class="glyphicon glyphicon-bed" aria-hidden="true"></span>&nbsp Search Patient</a></li>
                  <li><a href="invoicecreation.php"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>&nbsp Search Transactions</a></li>
                </ul>
              </li>
              <li>
                  <a style="background-color: #edf0f5; color: #000000;"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>&nbsp Maintenance</a>
                  <ul class="nav" id="mn-sub-menu">
                      <li style="color: #000000;background-color: #b7d7f0;"><a href="patientsettings.php"><span class="glyphicon glyphicon-bed" aria-hidden="true"></span>&nbsp Patient</a></li>
                      <li><a href="doctorsettings.php"><span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>&nbsp Doctor</a></li>
                      <li><a href="itemsettings.php"><span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>&nbsp Item</a></li>
                      <li><a href="treatmentsettings.php"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>&nbsp Procedure</a></li>
                  </ul>
              </li>
            </ul>
          </div>

          <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"  style="overflow-y:auto; height:100vh;">
            <div class="col-sm-12 col-md-12">
              <div class="mn-dashboard-header">
                <h3>Patient Details</h3>
                <hr>
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#mn-patient-panel" data-toggle="tab">Patients</a></li>
                  <li><a href="#mn-deleted-patient-panel" data-toggle="tab">Deleted Patients</a></li>
                </ul>
              </div>
              <div class="tab-content">
                <div class="panel panel-primary filterable tab-pane active" id="mn-patient-panel">
                  <div class="panel-heading" style="height: 50px;">
                    <h3 class="panel-title">Patients</h3>
                    <div class="pull-right">
                        <button class="btn btn-default btn-md btn-filter"><span class="fa fa-filter"></span> Filter</button>
                        <button class="btn btn-default btn-md" data-toggle="modal" data-target="#open_patient"><span class="fa fa-plus"></span> Add</button>
                    </div>
                  </div>

                  <table class="table table-hover">
                    <thead>
                      <tr class="filters">
                        <th class="col-md-3"><input type="text" class="form-control" placeholder="Name" disabled></th>
                        <th class="col-md-2"><input type="text" class="form-control" placeholder="Gender" disabled></th>
                        <th class="col-md-2"><input type="text" class="form-control" placeholder="Date of Birth" disabled></th>
                        <th class="col-md-3"><input type="text" class="form-control" placeholder="Address" disabled></th>
                        <th class="col-md-2">Action</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                        require('./../../controller/db_connect.php');
                        $preparedStmt = "SELECT * FROM `tbl_patient` WHERE `patient_status`='1' ORDER BY `patient_name`";
                        $stmt = $connection->prepare($preparedStmt);
                        $stmt->execute();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                      ?>
                        <tr class="clickable-row">
                          <td class="col-md-3"><?php echo $row['patient_name']; ?></td>
                          <td class="col-md-2"><?php echo $row['patient_gender']; ?></td>
                          <td class="col-md-2"><?php echo $row['patient_date_of_birth']; ?></td>
                          <td class="col-md-3"><?php echo $row['patient_address']; ?></td>
                          <td class="col-md-2"><input type="submit" class="btn btn-info btn-sm patient_update" value="Update" id="<?php echo $row['patient_id']; ?>" name="patient_update" />
                          <input type="submit" class="btn btn-danger btn-sm patient_delete" value="Delete" id="<?php echo $row['patient_id']; ?>" name="patient_delete" /></td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                  </table>
                </div>

                <div class="panel panel-primary filterable tab-pane" id="mn-deleted-patient-panel">
                  <div class="panel-heading" style="height: 50px;">
                    <h3 class="panel-title">Archived Patients</h3>
                    <div class="pull-right">
                      <button class="btn btn-default btn-md btn-filter"><span class="fa fa-filter"></span> Filter</button>
                    </div>
                  </div>

                  <table class="table table-hover">
                    <thead>
                      <tr class="filters">
                        <th class="col-md-3"><input type="text" class="form-control" placeholder="Name" disabled></th>
                        <th class="col-md-2"><input type="text" class="form-control" placeholder="Gender" disabled></th>
                        <th class="col-md-2"><input type="text" class="form-control" placeholder="Date of Birth" disabled></th>
                        <th class="col-md-3"><input type="text" class="form-control" placeholder="Address" disabled></th>
                        <th class="col-md-2">Action</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                        require('./../../controller/db_connect.php');
                        $preparedStmt = "SELECT * FROM `tbl_patient` WHERE `patient_status`='2' ORDER BY `patient_name`";
                        $stmt = $connection->prepare($preparedStmt);
                        $stmt->execute();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                      ?>
                        <tr class="clickable-row">
                          <td class="col-md-3"><?php echo $row['patient_name']; ?></td>
                          <td class="col-md-2"><?php echo $row['patient_gender']; ?></td>
                          <td class="col-md-2"><?php echo $row['patient_date_of_birth']; ?></td>
                          <td class="col-md-3"><?php echo $row['patient_address']; ?></td>
                          <td class="col-md-2">
                            <form method="POST" action="./../../controller/transactions.php">
                              <input type="hidden" name=path value="./../views/admin/patientsettings.php"/>
                              <input type="hidden" name=patient_id value="<?php echo $row['patient_id']; ?>"/>
                              <input type="submit" class="btn btn-success btn-sm" value="Restore" name="patient_activate"/>
                            </form>
                          </td>
                        </tr>
                        <?php
                        }
                        ?>
                    </tbody>
                  </table>
                </div>

              </div> <!-- <div class="tab-content"> -->
            </div>
          </div> <!--main-->
        </div>
      </div>
    </main>

        <!-- Modal -->
    <div id="open_patient" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Patient Information</h4>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <form class="form-horizontal" role="form" method="post" action="./../../controller/transactions.php">

                <input type="hidden" value="0" name="patient_add" id="patient_add">
                <input type="hidden" value="./../views/admin/patientsettings.php" name="path" id="path">

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                      <p>Patient Name:</p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                      <input type="text" class="form-control" placeholder="Last Name, First Name M.I." name="patient_name" id="patient_name" required/>
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
                        <input type="radio" value="Male" name="patient_gender" id="patient_gender" required/>Male
                      </label>
                    </div>

                    <div class="col-md-1 col-md-offset-1">
                      <label class="radio-inline">
                        <input type="radio" value="Female" name="patient_gender" id="patient_gender" required/>Female
                      </label>
                    </div>

                    <div class="col-md-4 col-md-offset-3">
                      <input type="date" class="form-control" name="patient_date_of_birth" id="patient_date_of_birth" required/>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                        <p>Address:</p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                      <input type="text" class="form-control" placeholder="Address" name="patient_address" id="patient_address" required/>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-4 col-md-offset-7">
                        <input type="submit" class="btn btn-default pull-right" id="patient_add_btn" name="patient_add_btn" value="Add"/>
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
    <div id="update_delete_patient" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!--  Modal content -->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Patient Details</h4>
          </div>
          <div class="modal-body" id="patient_info">
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

        $(".patient_update").click(function() {
          var patient_id = $(this).attr('id');
          var path = "./../views/admin/patientsettings.php";
          $.ajax({
            url: "./../../controller/modal_views.php",
            method: "post",
            data: { patient_id:patient_id,
                    patient_view_update: 0,
                    path:path},
            success: function(data) {
              $('#patient_info').html(data);
              $('#update_delete_patient').modal('show')
            }
          })
        });

        $(".patient_delete").click(function() {
          var patient_id = $(this).attr('id');
          var path = "./../views/admin/patientsettings.php";
          $.ajax({
            url: "./../../controller/modal_views.php",
            method: "post",
            data: { patient_id:patient_id,
                    patient_view_delete: 1,
                    path:path},
            success: function(data) {
              $('#patient_info').html(data);
              $('#update_delete_patient').modal('show')
            }
          })
        });
      });

    </script>

  </body>

</html>
