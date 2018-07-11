<?php
  session_start();
  include './../../controller/functions.php';
  check_logged_in();
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
                <a><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>&nbsp Maintenance</a>
                <ul class="nav" id="mn-sub-menu">
                  <li><a href="patientsettings.php"><span class="glyphicon glyphicon-bed" aria-hidden="true"></span>&nbsp Patient</a></li>
                  <li><a href="doctorsettings.php"><span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>&nbsp Doctor</a></li>
                  <li><a href="itemsettings.php"><span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>&nbsp Item</a></li>
                  <li><a href="treatmentsettings.php"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>&nbsp Procedure</a></li>
                </ul>
              </li>
              <li class="active">
                <a href="masterlist.php"><span class="glyphicon glyphicon-list-alt" aria-hidden="true"></span>&nbsp Masterlist</a>
              </li>
            </ul>
          </div>

          <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"  style="overflow-y:auto; height:100vh;">
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="mn-dashboard-header">
                  <h3>Masterlist and Reports</h3>
                  <hr>
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#mn-patient-panel" data-toggle="tab">Patients</a></li>
                    <li><a href="#mn-doctor-panel" data-toggle="tab">Doctors</a></li>
                    <li><a href="#mn-procedure-panel" data-toggle="tab">Procedures</a></li>
                    <li><a href="#mn-item-panel" data-toggle="tab">Items</a></li>
                    <li><a href="#mn-transaction-panel" data-toggle="tab">Transactions</a></li>
                    <li><a href="#mn-user-panel" data-toggle="tab">User</a></li>
                  </ul>
                </div>
                <div class="tab-content">
                  <div class="panel panel-primary filterable tab-pane active" id="mn-patient-panel">
                    <div class="panel-heading" style="height: 50px;">
                      <h3 class="panel-title">Patients</h3>
                      <div class="pull-right">
                        <button class="btn btn-default btn-md btn-filter"><span class="fa fa-filter"></span> Filter</button>
                      </div>
                    </div>

                    <table class="table table-hover">
                      <thead>
                        <tr class="filters">
                          <th><input type="text" class="form-control" placeholder="Name" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Gender" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Date of Birth" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Address" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Status" disabled></th>
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
                            <td><?php echo $row['patient_name']; ?></td>
                            <td><?php echo $row['patient_gender']; ?></td>
                            <td><?php echo $row['patient_date_of_birth']; ?></td>
                            <td><?php echo $row['patient_address']; ?></td>
                            <td><?php if($row['patient_status']){echo "Active";} else {echo"Inactive";} ?></td>
                          </tr>
                          <?php
                          }
                          ?>
                      </tbody>
                    </table>
                  </div><!--  mn-patient-panel -->

                  <div class="panel panel-primary filterable tab-pane" id="mn-doctor-panel">
                    <div class="panel-heading" style="height: 50px;">
                      <h3 class="panel-title">Doctors</h3>
                      <div class="pull-right">
                        <button class="btn btn-default btn-md btn-filter"><span class="fa fa-filter"></span> Filter</button>
                      </div>
                    </div>

                    <table class="table table-hover">
                      <thead>
                        <tr class="filters">
                          <th><input type="text" class="form-control" placeholder="Name" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Gender" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Date of Birth" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Address" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Status" disabled></th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                          require('./../../controller/db_connect.php');
                          $preparedStmt = "SELECT * FROM `tbl_doctor` WHERE `doctor_status`='1' ORDER BY `doctor_name`";
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
                            <td><?php if($row['doctor_status']){echo "Active";} else {echo"Inactive";} ?></td>
                          </tr>
                          <?php
                          }
                          ?>
                      </tbody>
                    </table>
                  </div><!--  mn-doctor-panel -->

                  <div class="panel panel-primary filterable tab-pane" id="mn-item-panel">
                    <div class="panel-heading" style="height: 50px;">
                      <h3 class="panel-title">Doctors</h3>
                      <div class="pull-right">
                        <button class="btn btn-default btn-md btn-filter"><span class="fa fa-filter"></span> Filter</button>
                      </div>
                    </div>

                    <table class="table table-hover">
                      <thead>
                        <tr class="filters">
                          <th><input type="text" class="form-control" placeholder="Name" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Quantity" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Status" disabled></th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                          require('./../../controller/db_connect.php');
                          $preparedStmt = "SELECT * FROM `tbl_item` ORDER BY `item_name`";
                          $stmt = $connection->prepare($preparedStmt);
                          $stmt->execute();
                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                          {
                        ?>
                        <tr class="clickable-row">
                            <td class="col-md-3"><?php echo $row['item_name']; ?></td>
                            <td class="col-md-2"><?php echo $row['item_qty']; ?></td>
                            <td class="col-md-2"><?php if($row['item_status']){echo "Active";} else {echo"Inactive";} ?></td>
                          </tr>
                          <?php
                          }
                          ?>
                      </tbody>
                    </table>
                  </div><!--  mn-item-panel -->

                  <div class="panel panel-primary filterable tab-pane" id="mn-procedure-panel">
                    <div class="panel-heading" style="height: 50px;">
                      <h3 class="panel-title">Procedures</h3>
                      <div class="pull-right">
                        <button class="btn btn-default btn-md btn-filter"><span class="fa fa-filter"></span> Filter</button>
                      </div>
                    </div>

                    <table class="table table-hover">
                      <thead>
                        <tr class="filters">
                          <th><input type="text" class="form-control" placeholder="Name" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Cost" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Items Needed" disabled></th>
                          <th><input type="text" class="form-control" placeholder="Status" disabled></th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                          require('./../../controller/db_connect.php');
                          $procedure_name = array();
                          $procedure_cost = array();
                          $proceudre_id = array();
                          $procedure_items = array();
                          $proceudre_status = array();
                          $preparedStmt = "SELECT * FROM `tbl_procedure`
                                          INNER JOIN tbl_procedure_item ON tbl_procedure_item.procedure_id_fk = tbl_procedure.procedure_id
                                          INNER JOIN tbl_item ON tbl_item.item_id = tbl_procedure_item.item_id_fk
                                          ORDER BY tbl_procedure.procedure_id";
                          $stmt = $connection->prepare($preparedStmt);
                          $stmt->execute();
                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                          {
                            if(count($proceudre_id)-1 < 0)
                            {
                              array_push($procedure_name, $row['procedure_name']);
                              array_push($procedure_cost, $row['procedure_cost']);
                              array_push($proceudre_id, $row['procedure_id']);
                              array_push($proceudre_status, $row['procedure_status']);
                            }
                            else if($proceudre_id[count($proceudre_id)-1] != $row['procedure_id'])
                            {
                              array_push($procedure_name, $row['procedure_name']);
                              array_push($procedure_cost, $row['procedure_cost']);
                              array_push($proceudre_id, $row['procedure_id']);
                              array_push($proceudre_status, $row['procedure_status']);
                            }

                            if(!isset($procedure_items[''.$row['procedure_id']]))
                              $procedure_items[''.$row['procedure_id']] = array();
                            array_push($procedure_items[''.$row['procedure_id']], $row['item_name']);
                          }
                          for($x=0; $x<count($proceudre_id); $x++)
                          {
                            ?>
                            <tr class="clickable-row">
                              <td><?php echo $procedure_name[$x]; ?></td>
                              <td><?php echo $procedure_cost[$x]; ?></td>
                              <td>
                                <table class="table">
                                  <?php
                                    for($y=0; $y<count($procedure_items[$proceudre_id[$x]]); $y++)
                                    {
                                      echo "<tr><td>".$procedure_items[$proceudre_id[$x]][$y]."</td></tr>";
                                    }
                                  ?>
                                </table>
                              </td>
                              <td><?php if($proceudre_status[$x]){echo "Active";} else {echo"Inactive";} ?></td>
                            </tr>
                            <?php
                          }
                          ?>
                      </tbody>
                    </table>
                  </div><!--  mn-procedure-panel -->

                  <div class="panel panel-primary filterable tab-pane" id="mn-transaction-panel">
                    <p>transactions</p>
                  </div><!--  mn-doctor-panel -->

                  <div class="panel panel-primary filterable tab-pane" id="mn-user-panel">
                    <p>user</p>
                  </div><!--  mn-doctor-panel -->

                </div> <!-- Tab content -->

              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <footer>
      <div class="container-fluid">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">
          <p class="text-center">© 2018 Makati PET/CT Center</p>
        </div>
      </div>
    </footer>

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
      });
    </script>
  </body>
</html>
