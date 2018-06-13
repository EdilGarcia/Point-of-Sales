<?php
  session_start();
  require('././../../controller/db_connect.php');
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
  // Get Patient Details
  $preparedStmt = "SELECT * FROM `tbl_patient` WHERE patient_id=:patient_id_fk";
  $stmt = $connection->prepare($preparedStmt);
  $stmt->bindParam(':patient_id_fk', $patient_id_fk);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $patient_name = $row['patient_name'];
  $patient_date_of_birth = $row['patient_date_of_birth'];
  $year = (explode("-",$patient_date_of_birth));
  $patient_age = (date("md", date("U", mktime(0, 0, 0, $year[1], $year[2], $year[0]))) > date("md")
    ? ((date("Y") - $year[0]) - 1)
    : (date("Y") - $year[0]));
  $patient_gender = $row['patient_gender'];
  // Get user/cashier name
  $preparedStmt = "SELECT * FROM `tbl_user` WHERE user_id=:user_id_fk";
  $stmt = $connection->prepare($preparedStmt);
  $stmt->bindParam(':user_id_fk', $user_id_fk);
  $stmt->execute();
  $row = $stmt->fetch(PDO::FETCH_ASSOC);
  $user_name = $row['user_name'];
  // Get doctors_id/s
  $preparedStmt = "SELECT * FROM `tbl_invoice_doctor` WHERE invoice_id_fk=:invoice_id_fk";
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
  $preparedStmt .= implode(' OR ', $insertQuery);
  $stmt = $connection->prepare($preparedStmt);
  $stmt->execute($insertData);
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
  $preparedStmt .= implode(' OR ', $insertQuery);
  $stmt = $connection->prepare($preparedStmt);
  $stmt->execute($insertData);
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
  $preparedStmt .= implode(' OR ', $insertQuery);
  $stmt = $connection->prepare($preparedStmt);
  $stmt->execute($insertData);
  $item_name = array();
  $item_qty = array();
  while($row = $stmt->fetch(PDO::FETCH_ASSOC))
  {
    $id_index = $row['procedure_id'];
    $item_name[$id_index][] = $row['item_name'];
    $item_qty[$id_index][] = $row['quantity'];
  }
?>
<!DOCTYPE>
<html>
    <head>
      <title>Invoice</title>
      <link rel="stylesheet" type="text/css" media="all" href="./../../css/bootstrap.min.css">
      <link rel="stylesheet" href="./../../css/invoice.css">
    </head>
    <body>
      <div class="container">

        <div class="row padding-top-1">
          <div class="col align-self-start">
            <h3 class="center">MAKATI PET-CT CENTER</h3>
          </div>
        </div>

        <div class="row">
          <div class="col align-self-center">
            <p class="center">East Avenue, Quezon City</p>
          </div>
        </div>

        <div class="row">
          <div class="col align-self-end">
            <p class="center">Address and email here</p>
          </div>
        </div>

        <div class="row padding-top-2 justify-content-center">
          <div class="col-xs-3 align-self-center">
            <p class="pull-right"><strong>Patient Name: </strong></p>
          </div>
          <div class="col-xs-3 align-self-center">
            <p class="pull-left"><?php echo $patient_name;?></p>
          </div>
          <div class="col-xs-3 align-self-center">
            <p class="pull-right"><strong>OR #: </strong></p>
          </div>
          <div class="col-xs-2 align-self-center">
            <p class="pull-left">
              <?php
                if(isset($_SESSION['class_type']))
                {
                  if($_SESSION['class_type'] == 'receipt')
                    echo $invoice_id;
                }
                else
                  echo $invoice_id;
              ?>
            </p>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-xs-3 align-self-center">
            <p class="pull-right"><strong>Age: </strong></p>
          </div>
          <div class="col-xs-3 align-self-center">
            <p class="pull-left"><?php echo $patient_age;?></p>
          </div>
          <div class="col-xs-3 align-self-center">
            <p class="pull-right"><strong>Date Requested: </strong></p>
          </div>
          <div class="col-xs-2 align-self-center">
            <p class="pull-left"><?php echo $invoice_requested;?></p>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-xs-3 align-self-center">
            <p class="pull-right"><strong>Gender: </strong></p>
          </div>
          <div class="col-xs-3 align-self-center">
            <p class="pull-left"><?php echo $patient_gender;?></p>
          </div>
          <div class="col-xs-3 align-self-center">
            <p class="pull-right"><strong>Date Charged: </strong></p>
          </div>
          <div class="col-xs-2 align-self-center">
            <p class="pull-left">
              <?php
                if(isset($_SESSION['class_type']))
                {
                  if($_SESSION['class_type'] == 'receipt')
                    echo $invoice_date;
                }
                else
                  echo $invoice_date;
              ?>
            </p>
          </div>
        </div>

        <div class="row justify-content-center">
          <div class="col-xs-3 align-self-center"></div>
          <div class="col-xs-3 align-self-center"></div>
          <div class="col-xs-3 align-self-center">
            <p class="pull-right"><strong>Cashier Name: </strong></p>
          </div>
          <div class="col-xs-2 align-self-center">
            <p class="pull-left">
              <?php
              if(isset($_SESSION['class_type']))
              {
                if($_SESSION['class_type'] == 'receipt')
                  echo $user_name;
              }
              else
                echo $user_name;
              ?>
            </p>
          </div>
        </div>

        <div class="row padding-top-2">
          <div class="col-md-12">
            <div class="panel panel-default">
              <div class="panel-heading">
                  <p class="text-center"><strong>Summary</strong></p>
              </div>
              <div class="panel-body">
                <div class="table-responsive">
                  <table class="table table-condensed">
                    <tr>
                      <th>Procedure</th>
                      <th>Items</th>
                      <th>Cost</th>
                    </tr>
        <?php
        for($x=0;$x<count($procedure_id);$x++)
        {
          echo '<tr>';
          echo '<td>'.$procedure_name[$x].'</td>';
          echo '<td><table>';
          for($y=0;$y<count($item_name[$procedure_id[$x]]);$y++)
          {
            echo '<tr>';
            echo '<td>'.$item_name[$procedure_id[$x]][$y].'</td>';
            echo '<td>'.$item_qty[$procedure_id[$x]][$y].'x </td>';
            echo '</tr>';
          }
          echo '</table></td>';
          echo '<td>'.$procedure_cost[$x].'</td>';
          echo '</tr>';
        }
        ?>
                    <tr><td class="medrow"></td><td class="medrow"></td><td class="medrow"></td></tr>
                    <tr>
                      <th colspan="2" align="left" class="emptyrow">Doctors</th>
                      <th class="emptyrow">Fee</th>
                    </tr>
        <?php
        for($x=0;$x<count($doctor_name);$x++)
        {
          echo '<tr>';
          echo '<td  colspan="2" align="left">'.$doctor_name[$x].'</td>';
          echo '<td>'.$doctor_fee[$x].'</td>';
          echo '</tr>';
        }
        ?>
                    <tr>
                      <td class="highrow"></td>
                      <td class="highrow"><p><strong>Total fee: </strong></p></td>
                      <td class="highrow"><?php echo $total_amount;?></td>
                    </tr>
                  </table>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
  </body>
</html>
