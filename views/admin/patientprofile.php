<?php
  session_start();
  require('./../../controller/db_connect.php');
  $patient_id = $_GET['patient_id'];
  $preparedStmt = "SELECT * FROM `tbl_patient` WHERE patient_id = '".$patient_id."';";
  $stmt = $connection->prepare($preparedStmt);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);

  $patient_name = $row['patient_name'];
  $patient_date_of_birth = $row['patient_date_of_birth'];
  $patient_address = $row['patient_address'];
  $patient_gender = $row['patient_gender'];
  $year = (explode("-",$patient_date_of_birth));
  $patient_age = (date("md", date("U", mktime(0, 0, 0, $year[1], $year[2], $year[0]))) > date("md")
    ? ((date("Y") - $year[0]) - 1)
    : (date("Y") - $year[0]));

  $total_amount = 0;
  $invoice_id = $_GET['invoice_id'];
  // Get Invoice Details
  $preparedStmt = "SELECT * FROM `tbl_invoice` WHERE invoice_id=:invoice_id";
  $stmt = $connection->prepare($preparedStmt);
  $stmt->bindParam(':invoice_id', $invoice_id);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $invoice_date = $row['invoice_date'];
  $mydate=getdate(date("U"));
  $invoice_requested = $mydate['year'].'-'.$mydate['mon'].'-'.$mydate['mday'].'  /  '.$mydate['hours'].':'.$mydate['minutes'];
  $patient_id_fk = $row['patient_id_fk'];
  $user_id_fk = $row['user_id_fk'];
  // Get user/cashier name
  $preparedStmt = "SELECT * FROM `tbl_user` WHERE user_id=:user_id_fk";
  $stmt = $connection->prepare($preparedStmt);
  $stmt->bindParam(':user_id_fk', $user_id_fk);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $user_name = $row['user_name'];
  // Get doctors_id/s
  $preparedStmt = "SELECT * FROM `tbl_doctor_invoice` WHERE invoice_id_fk=:invoice_id_fk";
  $stmt = $connection->prepare($preparedStmt);
  $stmt->bindParam(':invoice_id_fk', $invoice_id);
  $stmt->execute();
  $doctor_id = array();
  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    array_push($doctor_id, $row['doctor_id_fk']);

  // Get Doctor name and Fee
  $preparedStmt = "SELECT * FROM `tbl_doctor` WHERE ";
  $insertQuery = array();
  $insertData = array();
  for($x=0;$x<count($doctor_id);$x++)
  {
    $insertQuery[] = 'doctor_id = :doctor_id'.$x;
    $insertData[':doctor_id'.$x] = $doctor_id[$x];
  }
  if(!empty($insertQuery))
  {
    $preparedStmt .= implode(' OR ', $insertQuery);
    $stmt = $connection->prepare($preparedStmt);
    $stmt->execute($insertData);
  }
  $doctor_name = array();
  $doctor_fee = array();
  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
  {
    array_push($doctor_name, $row['doctor_name']);
    array_push($doctor_fee, $row['doctor_professional_fee']);
    $total_amount += $row['doctor_professional_fee'];
  }

  // Get procedure_id/s
  $preparedStmt = "SELECT * FROM `tbl_invoice_procedure` WHERE invoice_id_fk=:invoice_id_fk";
  $stmt = $connection->prepare($preparedStmt);
  $stmt->bindParam(':invoice_id_fk', $invoice_id);
  $stmt->execute();
  $procedure_id = array();
  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    array_push($procedure_id, $row['procedure_id_fk']);

  // Get Procedure name and cost
  $preparedStmt = "SELECT * FROM `tbl_procedure` WHERE ";
  $insertQuery = array();
  $insertData = array();
  for($x=0;$x<count($procedure_id);$x++)
  {
    $insertQuery[] = 'procedure_id = :procedure_id'.$x;
    $insertData[':procedure_id'.$x] = $procedure_id[$x];
  }
  if(!empty($insertQuery))
  {
    $preparedStmt .= implode(' OR ', $insertQuery);
    $stmt = $connection->prepare($preparedStmt);
    $stmt->execute($insertData);
  }
  $procedure_name = array();
  $procedure_cost = array();
  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
  {
    array_push($procedure_name, $row['procedure_name']);
    array_push($procedure_cost, $row['procedure_cost']);
    $total_amount += $row['procedure_cost'];
  }
  // Get procedure items per procedure
  $preparedStmt = "SELECT tbl_procedure.procedure_id, tbl_item.item_name, tbl_procedure_item.quantity
                  FROM tbl_procedure_item
                  INNER JOIN tbl_procedure ON tbl_procedure.procedure_id = tbl_procedure_item.procedure_id_fk
                  INNER JOIN tbl_item ON tbl_item.item_id = tbl_procedure_item.item_id_fk WHERE ";
  $insertQuery = array();
  $insertData = array();
  for($x=0;$x<count($procedure_id);$x++)
  {
    $insertQuery[] = 'tbl_procedure_item.procedure_id_fk = :procedure_id'.$x;
    $insertData[':procedure_id'.$x] = $procedure_id[$x];
  }
  if(!empty($insertQuery))
  {
    $preparedStmt .= implode(' OR ', $insertQuery);
    $stmt = $connection->prepare($preparedStmt);
    $stmt->execute($insertData);
  }
  $item_name = array();
  $item_qty = array();
  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
  {
    $id_index = $row['procedure_id'];
    $item_name[$id_index][] = $row['item_name'];
    $item_qty[$id_index][] = $row['quantity'];
  }
?>
<!DOCTYPE html>
<html>
  <head>
    <title>
      Makati PET/CT Center
    </title>

  	<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="./../../css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
    <link rel="stylesheet" type="text/css" href="./../../css/patient.css">

    <script src="./../../js/jquery-3.3.1.min.js"></script>
    <script src="./../../js/jquery-ui.min.js"></script>
    <script src="./../../js/bootstrap.min.js"></script>

  </head>

  <body>

    <header>
      <nav class="navbar navbar-default navbar-fixed-top">
        <div class="container-fluid">
          <div class="navbar-header">
            <a href="dashboard.php" class="navbar-brand"><img src="./../../files/logo/brandimagev2.png" class="img-responsive"></a>
          </div>
          <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
            <ul class="nav navbar-nav navbar-right" id="navbar-right">
              <li><a href="account.php">Account</a></li>
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
              <li class="active"> <a href="./"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>&nbsp Dashboard <span class="sr-only">(current)</span> </a>
              </li>

              <li>
                  <a style="background-color: #edf0f5; color: #000000;"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>&nbsp Maintenance</a>
                  <ul class="nav" id="mn-sub-menu">
                      <li><a href="patientsettings.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp Patient</a></li>
                      <li><a href="doctorsettings.php"><span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>&nbsp Doctor</a></li>
                      <li><a href="itemsettings.php"><span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>&nbsp Item</a></li>
                      <li><a href="proceduresettings.php"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>&nbsp Procedure</a></li>
                  </ul>
              </li>
            </ul>
          </div>
          <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">

            <div class="col-sm-12 col-md-12">

              <div class="mn-dashboard-header">
              <h3>Patient Information</h3>
              <hr>

              <div class="col-sm-6 col-md-6 col-lg-6">
                <div class="w3-card-2">
                  <header class="w3-container w3-light-blue">
                    <h3><?php echo $patient_name; ?></h3>
                  </header>

                  <div class="w3-container">
                    <p>Gender: <?php echo $patient_gender; ?></p>
                    <p>Date of Birth: <?php echo $patient_date_of_birth; ?></p>
                    <p>Age: <?php echo $patient_age; ?></p>
                    <p>Address: <?php echo $patient_address; ?></p>

                    <hr>
                    <?php
                      require('./../../controller/db_connect.php');
                      $preparedStmt = "SELECT * FROM `tbl_invoice` WHERE patient_id_fk = '".$_GET['patient_id']."';";
                      $stmt = $connection->prepare($preparedStmt);
                      $stmt->execute();
                    ?>
                    <p>Recent Transactions</p>

                    <table class="table table-responsive table-condensed table-hover">
                      <thead>
                        <tr>
                          <th class="text-center" scope="col">#</th>
                          <th class="text-center" scope="col">ID</th>
                          <th class="text-center" scope="col">Date</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                          $counter = 1;
                          while($row = $stmt->fetch(PDO::FETCH_ASSOC))
                          {
                          ?>
                            <tr>
                              <th class="text-center" scope="row"><?php echo $counter;?></th>
                              <td class="text-center" ><?php echo $row['invoice_id'];?></td>
                              <td class="text-center" ><?php echo $row['invoice_date'];?></td>
                            </tr><?php
                            $counter++;
                          }?>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>

              <div class="col-sm-6 col-md-6 col-lg-6" style="overflow-y:auto; height:400px;">
                <div class="w3-card-2">
                  <header class="w3-container w3-light-grey">
                    <p class="text-center">Makati PET/CT Center </br> Makati City, Philippines</p>
                  </header>

                  <div class="w3-container">
                    <p>Patient Name: <?php echo $patient_name;?> </p>
                    <p>OR No: <?php echo $invoice_id;?> </p>
                    <p>Age: <?php echo $patient_age; ?> </p>
                    <p>Date of Birth:<?php echo $patient_date_of_birth; ?> </p>
                    <p>Gender: <?php echo $patient_gender; ?> </p>
                    <p>Date Charged: <?php echo $invoice_date;?></p>

                    <table class="table table-responsive table-bordered table-condensed">
                      <thead>
                        <tr>
                          <th class="text-center" scope="col">Code</th>
                          <th class="text-center" scope="col">Particular</th>
                          <th class="text-center" scope="col">Items</th>
                          <th class="text-center" scope="col">Price</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                          $procedure_total = 0;
                          for($x=0;$x < count($procedure_id);$x++)
                          {
                            $procedure_total += $procedure_cost[$x];
                            echo '<tr>';
                            echo '<td class="text-center">'.$procedure_id[$x].'</td>';
                            echo '<td class="text-center">'.$procedure_name[$x].'</td>';
                            echo '<td class="text-center"><table class="table table-responsive table-condensed">';
                            for($y=0;$y<count($item_name[$procedure_id[$x]]);$y++)
                            {
                              echo '<tr>';
                              echo '<td class="text-center">'.$item_name[$procedure_id[$x]][$y].'</td>';
                              echo '<td class="text-center">'.$item_qty[$procedure_id[$x]][$y].'x </td>';
                              echo '</tr>';
                            }
                            echo '</table></td>';
                            echo '<td class="text-center">'.$procedure_cost[$x].'</td>';
                            echo '</tr>';
                          }
                        ?>
                         <tr>
                          <td class="text-center" colspan="3">Sub Total:</td>
                          <td class="text-center"><?php echo $procedure_total;?></td>
                        </tr>
                      </tbody>
                    </table>
                    <table class="table table-responsive table-bordered table-condensed">
                      <thead>
                        <tr>
                          <th class="text-center" scope="col">ID</th>
                          <th class="text-center" scope="col">Doctor</th>
                          <th class="text-center" scope="col">Fee</th>
                        </tr>
                      </thead>
                      <tbody>
                        <?php
                        $doctor_fee_total = 0;
                        for($x=0;$x<count($doctor_name);$x++)
                        {
                          $doctor_fee_total += $doctor_fee[$x];
                          echo '<tr>';
                          echo '<td class="text-center">'.$doctor_id[$x].'</td>';
                          echo '<td class="text-center">'.$doctor_name[$x].'</td>';
                          echo '<td class="text-center">'.$doctor_fee[$x].'</td>';
                          echo '</tr>';
                        }
                        ?>
                       <tr>
                          <td class="text-center" colspan="2">Sub Total:</td>
                          <td class="text-center"><?php echo $doctor_fee_total;?></td>
                        </tr>
                       <tr>
                          <td class="text-center" colspan="2">Total:</td>
                          <td class="text-center"><?php echo $total_amount;?></td>
                        </tr>
                      </tbody>
                    </table>
                    <a href="#" class="pull-right print_invoice" name="invoice_id=<?php echo $invoice_id;?>"><span class="glyphicon glyphicon-print" aria-hidden="true"></span> Print</a>
                  </div>
                </div>
              </div>

            </div>
          </div>
        </div>
      </div>
    </main>
    <!-- Modal -->
    <div id="transaction" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Invoice Reciept</h4>
          </div>
          <div class="modal-body" id="invoice_container">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
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

    <script type="text/javascript">
      $('.table > tbody > tr').click(function() {
        event.preventDefault();
    		var invoice_id = $(this).children('td').html();
    		$.ajax({
              url: "./../../controller/modal_views.php",
              method: "post",
              data: {invoice_id: invoice_id,
                    view_invoice: 0},
              success: function(data) {
                $('#invoice_container').html(data);
                $('#transaction').modal('show');
              }
            })
          });

      $(document).on("click", ".print_invoice" , function(){
        var invoice_id = ($(this).attr('name'));
        var myWindow = window.open("./invoice.php?"+invoice_id, "InvWindow");
      });
    </script>

  </body>

</html>
