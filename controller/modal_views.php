<?php
  session_start();
  // treament/procedure
  if(isset($_POST['procedure_view_update']))
    procedure_view('update');
  else if(isset($_POST['procedure_view_delete']))
    procedure_view('delete');

  // Invoice
  else if(isset($_POST['patient_transac']))
    view_transaction_patient();
  else if(isset($_POST['view_invoice']))
    view_invoice_patient();
  else if(isset($_POST['view_update_invoice']))
    view_update_invoice_patient();
  else if(isset($_POST['create_new_invoice']))
    create_new_invoice_patient();
  else if(isset($_POST['check_out_invoice']))
    check_out_invoice();

  // Patient Updates and Deletes
  else if(isset($_POST['patient_view_update']))
    view_update_patient();
  else if(isset($_POST['patient_view_delete']))
    view_delete_patient();

  // Doctor
  else if(isset($_POST['doctor_view_update']))
    view_update_doctor();
  else if(isset($_POST['doctor_view_delete']))
    view_delete_doctor();

  // Items
  else if(isset($_POST['item_view_delete']))
    view_delete_item();
  else if(isset($_POST['item_view_update']))
    view_update_item();

  // Users
  else if(isset($_POST['user_view_update']))
    view_update_user();
  else if(isset($_POST['user_view_delete']))
    view_delete_user();
  else if(isset($_POST['user_view_activate']))
    view_activate_user();

  // modal views
  function procedure_view($mode)
  {
    require('db_connect.php');
    $procedure_id = $_POST['procedure_id'];
    $preparedStmt = "SELECT * FROM `tbl_procedure` WHERE `procedure_id` = :procedure_id";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->bindParam(':procedure_id', $procedure_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $procedure_name = $row['procedure_name'];
    $procedure_cost = $row['procedure_cost'];
    $path = $_POST['path'];

    $output = '
    <form class="form-horizontal" role="form" method="post" action="./../../controller/transactions.php">
      <div class="container-fluid">
        <div class="form-group">
          <div class="col-md-12">';
            if($mode == 'update')
              $output .= '<input type="hidden" value="0" name="procedure_update">';
            else
              $output .= '<input type="hidden" value="0" name="procedure_delete">';
            $output .= '
            <input type="hidden" value="'.$procedure_id.'" name="procedure_id">
            <input type="hidden" value="'.$path.'" name="path">
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-md-10">
              <h5>Procedure Name: </h5>
            </div>
          </div>
          <div class="row">
            <div class="col-md-12">
              <input type="text" class="form-control" placeholder="Procedure Name" name="procedure_name" id="procedure_name" value="'.$procedure_name.'" required/>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-md-3">
              <h5>Item/s Needed:</h5>
            </div>
          </div>
        </div>
        <div class="form-group" id="update_field">';

        $preparedStmt = "SELECT item_name, item_id FROM `tbl_item`";
        $stmt = $connection->prepare($preparedStmt);
        $stmt->bindParam(':procedure_id', $procedure_id);
        $stmt->execute();
        $item_names = array();
        $item_id = array();
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
          array_push($item_names, $row['item_name']);
          array_push($item_id, $row['item_id']);
        }
        $preparedStmt = "SELECT tbl_item.item_name, tbl_procedure_item.quantity, tbl_procedure_item.procedure_id_fk
                        FROM tbl_procedure_item
                        INNER JOIN tbl_procedure ON tbl_procedure.procedure_id = tbl_procedure_item.procedure_id_fk
                        INNER JOIN tbl_item ON tbl_item.item_id = tbl_procedure_item.item_id_fk WHERE `procedure_id` = :procedure_id";
        $stmt = $connection->prepare($preparedStmt);
        $stmt->bindParam(':procedure_id', $procedure_id);
        $stmt->execute();
        $counter=0;
        while($row = $stmt->fetch(PDO::FETCH_ASSOC))
        {
          $output .='
          <div class="row">
            <div class="col-md-6">
              <select class="form-control" name="procedure_item[]" id="procedure_item">';
                $output .='<option selected disabled>Choose an Item</option>';
                for($i=0;$i<count($item_names);$i++)
                {
                  if($row['item_name'] == $item_names[$i])
                    $output .= '<option value="'.$item_id[$i].'" selected>'.$item_names[$i].'</option>';
                  else
                    $output .= '<option value="'.$item_id[$i].'">'.$item_names[$i].'</option>';
                }
              $output .= '
              </select>
            </div>
            <div class="col-md-2">
              <h5>Quantity: </h5>
            </div>
            <div class="col-md-2">
              <input type="number" class="form-control" name="procedure_item_qty[]" min="1" max="100" value="'.$row['quantity'].'" required/>
            </div>';
            $output .='
            <div class="col-md-2">';
            if($counter==0)
            {
              $output .= '
              <button type="button" class="btn btn-default btn-md" id="btn_add_update_item"><span class="glyphicon glyphicon-plus"></span></button>';
              $counter++;
            }
            else
              $output .= '
              <button type="button" class="btn btn-default btn-md btn_remove_update_item"><span class="glyphicon glyphicon-minus"></span></button>';
              $output .= '
          </div>
        </div>';
          }
        $output .='
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-md-3">
              <h5>Procedure Cost: </h5>
            </div>
          </div>
          <div class="row">
            <div class="col-md-9">
              <input type="text" class="form-control" name="procedure_cost" id="procedure_cost" value="'.$procedure_cost.'" required/>
            </div>
          </div>
        </div>
        <div class="form-group">
          <div class="row">
            <div class="col-md-10">';
            if($mode == 'update')
              $output .= '<input type="submit" class="btn btn-default pull-right" value="Update"/>';
            else
              $output .= '<input type="submit" class="btn btn-danger pull-right" value="Delete"/>';
            $output .='
            </div>
          </div>
        </div>
      </div>
    </form>';
    echo $output;
  }

  function view_transaction_patient()
  {
    require('db_connect.php');
    $patient_id = $_POST['patient_id'];
    $output = "
    <div class='container-fluid'>
      <div class='row'>
        <div class='col-md-12'>
          <h5>Patient:</h5>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-12'>
          <p>".$_POST['patient_name']."</p>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-12'>
          <h5>ID:</h5>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-12'>
          <p>".$patient_id."</p>
        </div>
      </div>
      <input type='hidden' name='transac_add' id='invoice_add' value='0'/>
      <input type='hidden' name='path' id='path' value='".$_POST['path']."'/>
      <input type='hidden' name='patient_id_fk' id='patient_id_fk' value='".$patient_id."'/>";
    $output .= '
      <div class="row">
        <div class="col-md-12">
          <input type="hidden" name="user_id_fk" value="'.$_POST['user_id'].'"/>
          <input type="hidden" name="patient_id_fk" value="'.$patient_id.'"/>
          <h5>Procedures: </h5>
        </div>
      </div>
      <div class="row">
        <div class="col-md-10">
          <select id="procedure_select" class="form-control" name="procedures[]">
            <option selected value=0//0>Choose an Item</option>';

    // Query Procedures
    $preparedStmt = "SELECT * FROM `tbl_procedure`";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $procedure_id = $row['procedure_id'];
      $procedure_name = $row['procedure_name'];
      $procedure_cost = $row['procedure_cost'];

      $output .= '<option value="'.$procedure_id.'//'.$procedure_cost.'">'.$procedure_name.' - '.$procedure_cost.'</option>';
    }
    $output .='
          </select>
        </div>
        <div class="col-md-2">
          <a href="#" class="btn btn-default" id="add_procedure_field_btn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
        </div>
      </div>
      <div id="procedures_div">

      </div>';
    // Query Doctors
    $output .= '
      <div class="row">
        <div class="col-md-12">
          <h5>Doctors: </h5>
        </div>
      </div>
      <div class="row">
        <div class="col-md-10">
          <select id="doctor_select" class="form-control" name="doctors[]">
            <option selected disabled value=0//0>Choose an Item</option>';
    $preparedStmt = "SELECT * FROM `tbl_doctor`";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $doctor_id = $row['doctor_id'];
      $doctor_name = $row['doctor_name'];
      $doctor_professional_fee = $row['doctor_professional_fee'];

      $output .= '<option value="'.$doctor_id.'//'.$doctor_professional_fee.'">'.$doctor_name.' - '.$doctor_professional_fee.'</option>';
    }
    $output .='
          </select>
        </div>
        <div class="col-md-2">
          <a href="#" class="btn btn-default" id="add_doctor_field_btn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
        </div>
      </div>
      <div id="doctors_div">

      </div>
      <div class="row">
        <div class="col-md-2 pull-right">
          <div class="pull-left">
            <p id="total_proc_cost">0</p>
          </div>
        </div>
        <div class="col-md-4 pull-right">
          <div class="pull-right">
            <p id="total_cost">Total Cost: </p>
          </div>
        </div>
      </div>
    </div>';
    echo $output;
  }

  function add_procedure() {

    require('db_connect.php');
    $procedure_id = get_smart_id("procedure");
    $procedure_name = $_POST['procedure_name'];
    $procedure_cost = $_POST['procedure_cost'];
    $stmt = $connection->prepare("INSERT INTO `tbl_procedure`(`procedure_id`, `procedure_name`, `procedure_cost`)
                                VALUES (:procedure_id, :procedure_name, :procedure_cost)");
    $stmt->bindParam(':procedure_id', $procedure_id);
    $stmt->bindParam(':procedure_name', $procedure_name);
    $stmt->bindParam(':procedure_cost', $procedure_cost);
    $stmt->execute();

    $procedure_item = $_POST['procedure_item'];
    $procedure_item_qty = $_POST['procedure_item_qty'];

    $insertQuery = array();
    $insertData = array();
    $preparedStatement = "INSERT INTO `tbl_procedure_item`(`procedure_id_fk`, `item_id_fk`, `quantity`) VALUES ";

    for($x=0;$x<count($procedure_item);$x++)
    {
        $insertQuery[] = '(:procedure_id_fk, :item_id_fk'.$x.', :quantity'.$x.')';
        $insertData[':procedure_id_fk'] = $procedure_id;
        $insertData[':item_id_fk'.$x] = $procedure_item[$x];
        $insertData[':quantity'.$x] = $procedure_item_qty[$x];
    }
    if(!empty($insertQuery))
    {
        $preparedStatement .= implode(',', $insertQuery);
        $stmt = $connection->prepare($preparedStatement);
        $stmt->execute($insertData);
    }
    $stmt = null;
    header("Location: ../views/treatmentsettings.php");
  }


  function add_invoice()
  {
    require('db_connect.php');
    $invoice_id = get_smart_id("invoice");
    $invoice_date = date("Y-m-d");
    $patient_id_fk = $_POST['patient_id_fk'];
    $user_id_fk = $_POST['user_id_fk'];

    $stmt = $connection->prepare("INSERT INTO `tbl_invoice`(`invoice_id`, `invoice_date`, `patient_id_fk`, `user_id_fk`)
      VALUES (:invoice_id, :invoice_date, :patient_id_fk, :user_id_fk)");

    $stmt->bindParam(':invoice_id', $invoice_id);
    $stmt->bindParam(':invoice_date', $invoice_date);
    $stmt->bindParam(':patient_id_fk', $patient_id_fk);
    $stmt->bindParam(':user_id_fk', $user_id_fk);
    $stmt->execute();

    $doctors = $_POST['doctors'];
    $doctor_id_fk = array();
    for($x=0;$x<count($doctors);$x++)
    {
      $split = explode("/",$doctors[$x]);
      array_push($doctor_id_fk,$split[0]);
    }
    $insertQuery = array();
    $insertData = array();
    $preparedStatement = "INSERT INTO `tbl_invoice_doctor`(`doctor_id_fk`, `invoice_id_fk`) VALUES ";
    for($x=0;$x<count($doctor_id_fk);$x++)
    {
      $insertQuery[] = '(:doctor_id_fk'.$x.', :invoice_id_fk)';
      $insertData[':doctor_id_fk'.$x] = $doctor_id_fk[$x];
      $insertData[':invoice_id_fk'] = $invoice_id;
    }

    if(!empty($insertQuery))
    {
      $preparedStatement .= implode(',', $insertQuery);
      $stmt = $connection->prepare($preparedStatement);
      $stmt->execute($insertData);
    }

    $procedures = $_POST['procedures'];
    $procedure_id_fk = array();
    for($x=0;$x<count($procedures);$x++)
    {
      $split = explode("/",$procedures[$x]);
      array_push($procedure_id_fk,$split[0]);
    }
      $insertQuery = array();
      $insertData = array();
      $preparedStatement = "INSERT INTO `tbl_invoice_procedure`(`invoice_id_fk`, `procedure_id_fk`) VALUES ";
    for($x=0;$x<count($procedure_id_fk);$x++)
    {
      $insertQuery[] = '(:invoice_id_fk, :procedure_id_fk'.$x.')';
      $insertData[':invoice_id_fk'] = $invoice_id;
      $insertData[':procedure_id_fk'.$x] = $procedure_id_fk[$x];
    }
    if(!empty($insertQuery))
    {
      $preparedStatement .= implode(',', $insertQuery);
      $stmt = $connection->prepare($preparedStatement);
      $stmt->execute($insertData);
    }
    $stmt = null;
    //header("Location: ../forms/uom.html");
  }

  function check_out_invoice()
  {
    require('db_connect.php');
    $output = "
    <div class='container-fluid'>
    <input type='hidden' name='invoice_param' value='0'/>
    <input type='hidden' name='invoice_id' value='".$_POST['invoice_id_fk']."'/>
      <input type='hidden' name='path' value='".$_POST['path']."'/>";
    $output .= "
      <div class='row'>
        <div class='col-sm-12'>
          <h5>Payment Mode:</h5>
        </div>
      </div>
      <div class='row'>
        <div class='col-sm-12'>
          <select class='form-control' id='payment_mode_select' name='payment_method'>
            <option value='cash'>Cash</option>
            <option value='credit_card'>Credit Card</option>
            <option value='debit_card'>Credit Card</option>
          </select>
        </div>
      </div>
      <hr>
      <div class='row payment_cash'>
        <div class='col-sm-4'>
          <h5>Amount paid:</h5>
        </div>
        <div class='col-sm-8'>
          <input type='number' class='form-control' id='payment_paid_amount' name='payment_paid_amount' id='paid_value' value='0' min='0' step='100'/>
        </div>
      </div>
      <div class='row'>
        <div class='col-sm-4'>
          <h6>Amout Due:</h6>
        </div>";
    $preparedStmt = "SELECT payment_cost FROM `tbl_payment` WHERE invoice_id_fk=:invoice_id_fk";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->bindParam(':invoice_id_fk', $_POST['invoice_id_fk']);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $output .="
        <div class='col-sm-4'>
          <input type='hidden' class='form-control' value='".$row['payment_cost']."' name='payment_cost' id='payment_cost'>
          <h6>PHP <b>".$row['payment_cost']."</b></h6>
        </div>
      </div>
      <div class='row payment_cash'>
        <div class='col-sm-4'>
          <h6>Change:</h6>
        </div>
        <div class='col-sm-4'>
          <h6 id='cash_change'>PHP 0.00</h6>
        </div>
      </div>
    </div>";
    echo $output;
  }

  function create_new_invoice_patient()
  {
    require('db_connect.php');

    // Get Patient Name and ID's
    $patient_name = array();
    $patient_id = array();
    $preparedStmt = "SELECT * FROM `tbl_patient`";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->execute();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      array_push($patient_id, $row['patient_id']);
      array_push($patient_name, $row['patient_name']);
    }

    // Get procedure_id/s
    $preparedStmt = " SELECT tbl_doctor.doctor_id
                      FROM tbl_doctor
                      INNER JOIN tbl_invoice_doctor ON tbl_invoice_doctor.doctor_id_fk = tbl_doctor.doctor_id
                      INNER JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_doctor.invoice_id_fk";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->bindParam(':invoice_id', $invoice_id);
    $stmt->execute();
    $doctor_id_array = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
      array_push($doctor_id_array, $row['doctor_id']);

    // Get procedure_id/s
    $preparedStmt = " SELECT *
                      FROM tbl_procedure
                      INNER JOIN tbl_invoice_procedure ON tbl_invoice_procedure.procedure_id_fk = tbl_procedure.procedure_id
                      INNER JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_procedure.invoice_id_fk ";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->bindParam(':invoice_id', $invoice_id);
    $stmt->execute();
    $procedure_id_array = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
      array_push($procedure_id_array, $row['procedure_id_fk']);

    $output = "
    <div class='container-fluid'>
      <div class='row'>
        <div class='col-md-12'>
          <h5>Patient:</h5>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-12'>
          <span class='badge'>Patient Name Filter</span>
          <input type='text' class='form-control patient_filter' id='patient_name_filter' value='' autocomplete='off'/>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-12'>
          <select name='patient_name' class='form-control' id='patient_name_list'>
            <option value='' disabled selected>Select patient name</option>";
      for($x=0;$x<count($patient_name);$x++)
        $output .= "<option value='".$patient_name[$x]."'>".$patient_name[$x]."</option>";
      $output .= "
          </select>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-12'>
          <h5>ID:</h5>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-12'>
          <span class='badge'>Patient ID Filter</span>
          <input type='text' class='form-control patient_filter' id='patient_id_filter' value='' autocomplete='off' />
        </div>
      </div>
      <div class='row'>
        <div class='col-md-12'>
          <select name='patient_id_fk' class='form-control' id='patient_id_list'>
            <option value='' disabled selected>Select patient ID</option>";
      for($x=0;$x<count($patient_id);$x++)
        $output .= "<option value='".$patient_id[$x]."'>".$patient_id[$x]."</option>";
      $output .= "
          </select>
        </div>
      </div>
      <input type='hidden' name='transac_add' id='transac_add' value='0'/>
      <input type='hidden' name='path' id='path' value='".$_POST['path']."'/>";
    $output .= '
      <div class="row">
        <div class="col-md-12">
          <input type="hidden" name="user_id_fk" value="'.$_SESSION['user_id'].'"/>
          <h5>Procedures: </h5>
        </div>
      </div>
      <div class="row">
        <div class="col-md-10">
          <select id="procedure_select" class="form-control costed_select" name="procedures[]">
            <option selected value=0//0>Choose an Item</option>';
    // Query Procedures
    $options = array();
    $options_value = array();
    $preparedStmt = "SELECT * FROM `tbl_procedure`";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $procedure_id = $row['procedure_id'];
      $procedure_name = $row['procedure_name'];
      $procedure_cost = $row['procedure_cost'];
      $output .= '<option value="'.$procedure_id.'//'.$procedure_cost.'">'.$procedure_name.' - '.$procedure_cost.'</option>';
    }
    // Insert other fields
      $output .='
          </select>
        </div>
        <div class="col-md-2">
          <a href="#" class="btn btn-default" id="add_procedure_field_btn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
        </div>
      </div>
      <div id="procedures_div">
      </div>';
    // Query Doctors
    $output .= '
      <div class="row">
        <div class="col-md-12">
          <h5>Doctors: </h5>
        </div>
      </div>
      <div class="row">
        <div class="col-md-10">
          <select id="doctor_select" class="form-control costed_select" name="doctors[]">
            <option selected disabled value=0//0>Choose an Item</option>';
    $options = array();
    $options_value = array();
    $preparedStmt = "SELECT * FROM `tbl_doctor`";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $doctor_id = $row['doctor_id'];
      $doctor_name = $row['doctor_name'];
      $doctor_professional_fee = $row['doctor_professional_fee'];
      $output .= '<option value="'.$doctor_id.'//'.$doctor_professional_fee.'">'.$doctor_name.' - '.$doctor_professional_fee.'</option>';
    }
    $output .='
          </select>
        </div>
        <div class="col-md-2">
          <a href="#" class="btn btn-default" id="add_doctor_field_btn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
        </div>
      </div>
      <div id="doctors_div">
      </div>
      <div class="row">
        <div class="col-md-2 pull-right">
          <div class="pull-left">
            <p id="total_proc_cost">0</p>
          </div>
        </div>
        <div class="col-md-4 pull-right">
          <div class="pull-right">
            <p id="total_cost">Total Cost: </p>
          </div>
        </div>
      </div>
    </div>';
    echo $output;
  }

  function view_update_invoice_patient()
  {
    require('db_connect.php');
    $invoice_id = $_POST['invoice_id'];
    // Prepare Data
    // Get doctors_id/s
    $preparedStmt = " SELECT tbl_doctor.doctor_id
                      FROM tbl_doctor
                      INNER JOIN tbl_invoice_doctor ON tbl_invoice_doctor.doctor_id_fk = tbl_doctor.doctor_id
                      INNER JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_doctor.invoice_id_fk
                      WHERE tbl_invoice.invoice_id = :invoice_id";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->bindParam(':invoice_id', $invoice_id);
    $stmt->execute();
    $doctor_id_array = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
      array_push($doctor_id_array, $row['doctor_id']);

    // Get procedure_id/s
    $preparedStmt = " SELECT *
                      FROM tbl_procedure
                      INNER JOIN tbl_invoice_procedure ON tbl_invoice_procedure.procedure_id_fk = tbl_procedure.procedure_id
                      INNER JOIN tbl_invoice ON tbl_invoice.invoice_id = tbl_invoice_procedure.invoice_id_fk
                      WHERE tbl_invoice.invoice_id = :invoice_id";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->bindParam(':invoice_id', $invoice_id);
    $stmt->execute();
    $procedure_id_array = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
      array_push($procedure_id_array, $row['procedure_id_fk']);
    //--------------------------------
    $patient_id = $_POST['patient_id'];
    $output = "
    <div class='container-fluid'>
      <div class='row'>
        <div class='col-md-12'>
          <h5>Patient:</h5>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-12'>
          <p>".$_POST['patient_name']."</p>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-12'>
          <h5>ID:</h5>
        </div>
      </div>
      <div class='row'>
        <div class='col-md-12'>
          <p>".$patient_id."</p>
        </div>
      </div>
      <input type='hidden' name='transac_update' id='invoice_add' value='0'/>
      <input type='hidden' name='path' id='path' value='".$_POST['path']."'/>
      <input type='hidden' name='patient_id_fk' id='patient_id_fk' value='".$patient_id."'/>";
    $output .= '
      <div class="row">
        <div class="col-md-12">
          <input type="hidden" name="user_id_fk" value="'.$_POST['user_id'].'"/>
          <input type="hidden" name="patient_id_fk" value="'.$patient_id.'"/>
          <h5>Procedures: </h5>
        </div>
      </div>
      <div class="row">
        <div class="col-md-10">
          <select id="procedure_select" class="form-control costed_select" name="procedures[]">
            <option value=0//0>Choose an Item</option>';
    // Query Procedures
    $options = array();
    $options_value = array();
    $preparedStmt = "SELECT * FROM `tbl_procedure`";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $procedure_id = $row['procedure_id'];
      $procedure_name = $row['procedure_name'];
      $procedure_cost = $row['procedure_cost'];

      if($procedure_id_array[0] == $procedure_id)
        $output .= '<option selected ';
      else
        $output .= '<option ';
      $output .= 'value="'.$procedure_id.'//'.$procedure_cost.'">'.$procedure_name.' - '.$procedure_cost.'</option>';
      // for Select tags
      $options[] = 'value="'.$procedure_id.'//'.$procedure_cost.'">'.$procedure_name.' - '.$procedure_cost.'</option>';
      $options_value[] = $procedure_id;
    }
    // Insert other fields
      $output .='
          </select>
        </div>
        <div class="col-md-2">
          <a href="#" class="btn btn-default" id="add_procedure_field_btn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
        </div>
      </div>
      <div id="procedures_div">';
    for($x=1;$x<count($procedure_id_array);$x++)
    {
      $output .= '
      <div class="row">
        <div class="col-md-10">
          <select class="form-control costed_select" name="procedures[]">';
          for($y=0;$y<count($options_value);$y++)
          {
            if($procedure_id_array[$x] == $options_value[$y])
              $output .= '<option selected ';
            else
              $output .= '<option ';
            $output .= $options[$y];
          }
      $output .='
          </select>
        </div>
        <div class="col-md-2">
          <a href="#" class="btn btn-default remove_procedure_field_btn"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a>
        </div>
      </div>';
    }
    $output .='
      </div>';
    // Query Doctors
    $output .= '
      <div class="row">
        <div class="col-md-12">
          <h5>Doctors: </h5>
        </div>
      </div>
      <div class="row">
        <div class="col-md-10">
          <select id="doctor_select" class="form-control" name="doctors[]">
            <option selected disabled value=0//0>Choose an Item</option>';
    $options = array();
    $options_value = array();
    $preparedStmt = "SELECT * FROM `tbl_doctor`";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $doctor_id = $row['doctor_id'];
      $doctor_name = $row['doctor_name'];
      $doctor_professional_fee = $row['doctor_professional_fee'];

      if($doctor_id_array[0] == $doctor_id)
        $output .= '<option selected ';
      else
        $output .= '<option ';
      $output .= 'value="'.$doctor_id.'//'.$doctor_professional_fee.'">'.$doctor_name.' - '.$doctor_professional_fee.'</option>';
      // for Select tags
      $options[] = 'value="'.$doctor_id.'//'.$doctor_professional_fee.'">'.$doctor_name.' - '.$doctor_professional_fee.'</option>';
      $options_value[] = $doctor_id;
    }
    $output .='
          </select>
        </div>
        <div class="col-md-2">
          <a href="#" class="btn btn-default" id="add_doctor_field_btn"><span class="glyphicon glyphicon-plus" aria-hidden="true"></span></a>
        </div>
      </div>
      <div id="doctors_div">';
    for($x=1;$x<count($doctor_id_array);$x++)
    {
      $output .= '
      <div class="row">
        <div class="col-md-10">
          <select class="form-control" name="doctors[]">';
          for($y=0;$y<count($options_value);$y++)
          {
            if($doctor_id_array[$x] == $options_value[$y])
              $output .= '<option selected ';
            else
              $output .= '<option ';
            $output .= $options[$y];
          }
      $output .='
          </select>
        </div>
        <div class="col-md-2">
          <a href="#" class="btn btn-default remove_doctor_field_btn"><span class="glyphicon glyphicon-minus" aria-hidden="true"></span></a>
        </div>
      </div>';
    }
      $output .='
      </div>
      <div class="row">
        <div class="col-md-2 pull-right">
          <div class="pull-left">
            <p id="total_proc_cost">0</p>
          </div>
        </div>
        <div class="col-md-4 pull-right">
          <div class="pull-right">
            <p id="total_cost">Total Cost: </p>
          </div>
        </div>
      </div>
    </div>';
    echo $output;
  }

  function view_invoice_patient()
  {
    require('db_connect.php');
    $total_amount = 0;
    $invoice_id = $_POST['invoice_id'];
    // Get Invoice Details
    $preparedStmt = "SELECT * FROM `tbl_invoice` WHERE invoice_id=:invoice_id";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->bindParam(':invoice_id', $invoice_id);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $invoice_date = $row['invoice_date'];
    $patient_id_fk = $row['patient_id_fk'];
    $user_id_fk = $row['user_id_fk'];
    // Get Patient Details
    $preparedStmt = "SELECT * FROM `tbl_patient` WHERE patient_id=:patient_id_fk";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->bindParam(':patient_id_fk', $patient_id_fk);
    $stmt->execute();
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    $patient_name = $row['patient_name'];
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
    $quantity = array();
    while($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $id_index = $row['procedure_id'];
      $item_name[$id_index][] = $row['item_name'];
      $quantity[$id_index][] = $row['quantity'];
    }
    //output
    $output = '
        <div class="container-fluid">
          </br>
          <div class="row">
            <div class="col-md-6">
              <p class="text-center pull-left"><strong>Patient:  </strong>'.$patient_name.'</p>
            </div>
            <div class="col-md-4 pull-right">
              <p class="text-center pull-left"><strong>Date:  </strong>'.$invoice_date.'</p>
            </div>
          </div>
          </br>
          <div class="row">
            <div class="table-responsive col-md-11">
              <table class="table table-striped">
                <tr>
                  <th>Procedure</th>
                  <th>Items Needed</th>
                  <th>Cost</th>
                </tr>';
    for($x=0;$x<count($procedure_id);$x++)
    {
      $output .= '<tr><td>'.$procedure_name[$x].'</td><td><table>';
      for($y=0;$y<count($item_name[$procedure_id[$x]]);$y++)
      {
          $output .= '<tr><td style="padding-right: 3px;">'.$item_name[$procedure_id[$x]][$y].'</td>';
          $output .= '<td style="padding-left: 4px;">'.$quantity[$procedure_id[$x]][$y].'x</td></tr>';
      }
      $output .= '</table></td>';
      $output .= '<td>'.$procedure_cost[$x].'</td></tr>';
      $total_amount += $procedure_cost[$x];
    }
    $output .= '
                <tr>
                  <th colspan="2">Doctors:</th>
                  <th>Fee</th>
                </tr>';
    for($x=0;$x<count($doctor_name);$x++)
    {
      $output .= '<tr><td colspan="2">'.$doctor_name[$x].'</td>';
      $output .= '<td>'.$doctor_fee[$x].'</td></tr>';
      $total_amount += $doctor_fee[$x];
    }
    $get_values = 'invoice_id='.$invoice_id;
    $output .= '
              </table>
            </div>
          </div>
          <div class="row">
            <div class="col-md-4 pull-right">
             <p class="pull-left"><strong>Total: </strong>'.$total_amount.'</p>
            </div>
          </div>';
    if($_POST['printable'] == 1)
    $output .= '
          <div class="row">
            <div class="col-md-4 pull-right">
             <button type="button" class="btn pull-left print_invoice" name="'.$get_values.'">Print</button>
            </div>
          </div>
        </div>';
        echo $output;
    }

  function view_update_patient() {

      require('db_connect.php');
      $patient_id = $_POST['patient_id'];
      $output = "";
      $preparedStmt = "SELECT * FROM `tbl_patient` WHERE `patient_id` = :patient_id";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':patient_id', $patient_id);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
      {
          $patient_name = $row["patient_name"];
          $output .= '
            <div class="container-fluid">
              <form class="form-horizontal" role="form" method="post" action="./../../controller/transactions.php">
                <input type="hidden" name="patient_id" id="patient_id" value="'.$row["patient_id"].'"/>
                <input type="hidden" name="path" id="path" value="'.$_POST["path"].'"/>
                <input type="hidden" name="patient_update" id="patient_update" value="0"/>
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                      <p>Patient Name:</p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                      <input type="text" class="form-control" name="patient_name" id="patient_name" value="'.$patient_name.'"/>
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
                        <input type="radio" value="Male" name="patient_gender" id="patient_gender"';
                        if($row['patient_gender'] == 'Male')
                          $output .= 'checked="checked"';
                        $output .= 'required/>Male
                      </label>
                    </div>

                    <div class="col-md-1 col-md-offset-1">
                      <label class="radio-inline">
                        <input type="radio" value="Female" name="patient_gender" id="patient_gender"';
                        if($row['patient_gender'] == 'Female')
                          $output .= 'checked="checked"';
                        $output .= 'required/>Female
                      </label>
                    </div>
                    <div class="col-md-6 col-md-4 col-md-offset-3">
                      <input type="text" class="form-control" name="patient_date_of_birth" id="patient_date_of_birth" value="'.$row["patient_date_of_birth"].'"/>
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
                      <input type="text" class="form-control" placeholder="Address" name="patient_address" id="patient_address" value="'.$row["patient_address"].'"/>
                    </div>
                  </div>
                </div>
                <div class="form-group">
                  <div class="row">
                    <div class="col-md-4 col-md-offset-8">
                        <input type="submit" class="btn btn-default pull-right" value="Update"/>
                    </div>
                  </div>
                </div>
            </form>
          </div>';
      }
      echo $output;
  }
  function view_delete_patient() {

      require('db_connect.php');
      $patient_id = $_POST['patient_id'];
      $output = "";
      $preparedStmt = "SELECT * FROM `tbl_patient` WHERE `patient_id` = :patient_id";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':patient_id', $patient_id);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
          $patient_name = $row["patient_name"];
          $output .= '
          <div class="container-fluid">
            <form class="form-horizontal" role="form" method="post" action="./../../controller/transactions.php">
            <input type="hidden" name="path" id="path" value="'.$_POST["path"].'"/>
              <input type="hidden" name="patient_id" id="patient_id" value="'.$row["patient_id"].'"/>
              <input type="hidden" name="patient_delete" id="patient_delete" value="1"/>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                    <p>Patient Name:</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                    <input type="text" class="form-control" name="patient_name" id="patient_name" value="'.$patient_name.'"/>
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
                      <input type="radio" value="Male" name="patient_gender" id="patient_gender"';
                      if($row['patient_gender'] == 'Male')
                        $output .= 'checked="checked"';
                      $output .= 'required/>Male
                    </label>
                  </div>

                  <div class="col-md-1 col-md-offset-1">
                    <label class="radio-inline">
                      <input type="radio" value="Female" name="patient_gender" id="patient_gender"';
                      if($row['patient_gender'] == 'Female')
                        $output .= 'checked="checked"';
                      $output .= 'required/>Female
                    </label>
                  </div>
                  <div class="col-md-6 col-md-4 col-md-offset-3">
                    <input type="text" class="form-control" name="patient_date_of_birth" id="patient_date_of_birth" value="'.$row["patient_date_of_birth"].'"/>
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
                    <input type="text" class="form-control" placeholder="Address" name="patient_address" id="patient_address" value="'.$row["patient_address"].'"/>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-4 col-md-offset-7">
                      <input type="submit" class="btn btn-default pull-right" id="patient_delete" name="1_delete" value="Delete"/>
                  </div>
                </div>
              </div>
          </form>
        </div>';
      }
      echo $output;
  }
  function view_update_doctor() {
      require('db_connect.php');
      $doctor_id = $_POST['doctor_id'];
      $output = "";
      $preparedStmt = "SELECT * FROM `tbl_doctor` WHERE `doctor_id` = :doctor_id";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':doctor_id', $doctor_id);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $output .= '
        <div class="container-fluid">
          <form class="form-horizontal" role="form" method="post" action="./../../controller/transactions.php">
            <div class="form-group">
                <div class="col-md-12">
                  <input type="hidden" name="doctor_id" id="doctor_id" value="'.$row["doctor_id"].'"/>
                  <input type="hidden" name="path" id="path" value="'.$_POST["path"].'"/>
                  <input type="hidden" name="doctor_update" id="doctor_update" value="0"/>
                </div>
            </div>

            <div class="row">
              <div class="form-group">
                <div class="col-md-10 col-md-offset-1">
                  <p>Doctor Name:</p>
                </div>
                <div class="col-md-10 col-md-offset-1">
                  <input type="text" class="form-control" name="doctor_name" id="doctor_name" value="'.$row["doctor_name"].'" required/>
                </div>
              </div>
            </div>

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
                  <input type="radio" value="Male" name="doctor_gender"';
                  if($row['doctor_gender'] == 'Male')
                    $output .= 'checked="checked"';
                  $output .= 'required/>Male
                </label>
              </div>
              <div class="col-md-1 col-md-offset-1">
                <label class="radio-inline">
                  <input type="radio" value="Female" name="doctor_gender"';
                  if($row['doctor_gender'] == 'Female')
                    $output .= 'checked="checked"';
                  $output .= 'required/>Female
                </label>
              </div>
              <div class="col-md-6 col-md-4 col-md-offset-3">
                <input type="text" class="form-control" name="doctor_date_of_birth" id="doctor_date_of_birth" value="'.$row["doctor_date_of_birth"].'" required/>
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
                  <input type="text" class="form-control" placeholder="Address" name="doctor_address" id="doctor_address" value="'.$row["doctor_address"].'" required/>
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
                  <input type="text" class="form-control" name="doctor_contact_number" id="doctor_contact_number" value="'.$row["doctor_contact_number"].'" required/>
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
                  <input type="text" class="form-control" name="doctor_professional_fee" id="doctor_professional_fee" value="'.$row["doctor_professional_fee"].'" required/>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-4 col-md-offset-7">
                  <input type="submit" class="btn btn-default pull-right" id="doctor_update" name="doctor_update" value="Update"/>
                </div>
              </div>
            </div>
          </form>
        </div>';
    }
    echo $output;

  }
  function view_delete_doctor() {

      require('db_connect.php');
      $doctor_id = $_POST['doctor_id'];
      $output = "";
      $preparedStmt = "SELECT * FROM `tbl_doctor` WHERE `doctor_id` = :doctor_id";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':doctor_id', $doctor_id);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $output .= '
        <div class="container-fluid">
          <form class="form-horizontal" role="form" method="post" action="./../../controller/transactions.php">
            <div class="form-group">
                <div class="col-md-12">
                  <input type="hidden" name="doctor_id" id="doctor_id" value="'.$row["doctor_id"].'"/>
                  <input type="hidden" name="path" id="path" value="'.$_POST["path"].'"/>
                  <input type="hidden" name="doctor_delete" id="doctor_delete" value="0"/>
                </div>
            </div>

            <div class="row">
              <div class="form-group">
                <div class="col-md-10 col-md-offset-1">
                  <p>Doctor Name:</p>
                </div>
                <div class="col-md-10 col-md-offset-1">
                  <input type="text" class="form-control" name="doctor_name" id="doctor_name" value="'.$row["doctor_name"].'" required/>
                </div>
              </div>
            </div>

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
                  <input type="radio" value="Male" name="doctor_gender"';
                  if($row['doctor_gender'] == 'Male')
                    $output .= 'checked="checked"';
                  $output .= 'required/>Male
                </label>
              </div>
              <div class="col-md-1 col-md-offset-1">
                <label class="radio-inline">
                  <input type="radio" value="Female" name="doctor_gender"';
                  if($row['doctor_gender'] == 'Female')
                    $output .= 'checked="checked"';
                  $output .= 'required/>Female
                </label>
              </div>
              <div class="col-md-6 col-md-4 col-md-offset-3">
                <input type="text" class="form-control" name="doctor_date_of_birth" id="doctor_date_of_birth" value="'.$row["doctor_date_of_birth"].'" required/>
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
                  <input type="text" class="form-control" placeholder="Address" name="doctor_address" id="doctor_address" value="'.$row["doctor_address"].'" required/>
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
                  <input type="text" class="form-control" name="doctor_contact_number" id="doctor_contact_number" value="'.$row["doctor_contact_number"].'" required/>
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
                  <input type="text" class="form-control" name="doctor_professional_fee" id="doctor_professional_fee" value="'.$row["doctor_professional_fee"].'" required/>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-4 col-md-offset-7">
                  <input type="submit" class="btn btn-default pull-right" id="doctor_delete" name="doctor_delete" value="Delete"/>
                </div>
              </div>
            </div>
          </form>
        </div>';
      }
      echo $output;
  }
  function view_delete_item() {
    require('db_connect.php');
    $item_id = $_POST['item_id'];
    $output = "";
    $preparedStmt = "SELECT * FROM `tbl_item` WHERE `item_id` = :item_id";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->bindParam(':item_id', $item_id);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $output .= '
      <form class="form-horizontal" role="form" method="post" action="./../../controller/transactions.php">
        <div class="container-fluid">
          <div class="form-group">
              <div class="col-md-12">
                <input type="hidden" name="item_id" id="patient_id" value="'.$row["item_id"].'"/>
                <input type="hidden" name="path" id="path" value="'.$_POST["path"].'"/>
                <input type="hidden" name="item_delete" id="item_delete" value="0"/>
              </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <p>Name: </p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <input type="text" class="form-control" name="item_name" id="item_name" value=" '.$row["item_name"].'" required/>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <p>Quantity: </p>
              </div>
            </div>
            <div class="row">
              <div class="col-md-10 col-md-offset-1">
                <input type="text" class="form-control" name="item_qty" id="item_qty" value=" '.$row["item_qty"].'" required/>
              </div>
            </div>
          </div>
          <div class="form-group">
            <div class="row">
              <div class="col-md-4 col-md-offset-7">
                <input type="submit" class="btn btn-default pull-right" id="item_delete" name="item_delete" value="Delete"/>
              </div>
            </div>
          </div>
        </div>
      </form>
      ';
    }
    echo $output;
  }

  function view_update_item() {
      require('db_connect.php');
      $item_id = $_POST['item_id'];
      $output = "";
      $preparedStmt = "SELECT * FROM `tbl_item` WHERE `item_id` = :item_id";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':item_id', $item_id);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $output .= '
          <form class="form-horizontal" role="form" method="post" action="./../../controller/transactions.php">
            <div class="container-fluid">
              <div class="form-group">
                <div class="row">
                  <div class="col-md-12">
                    <input type="hidden" name="item_id" id="patient_id" value="'.$row["item_id"].'"/>
                    <input type="hidden" name="path" id="path" value="'.$_POST["path"].'"/>
                    <input type="hidden" name="item_update" id="item_update" value="0"/>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                    <p>Name: </p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                      <input type="text" class="form-control" name="item_name" id="item_name" value=" '.$row["item_name"].'" required/>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                    <p>Quantity: </p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                      <input type="text" class="form-control" name="item_qty" id="item_qty" value=" '.$row["item_qty"].'" required/>
                  </div>
                </div>
              </div>
              <div class="form-group">
                <div class="row">
                  <div class="col-md-4 col-md-offset-7">
                      <input type="submit" class="btn btn-default pull-right" id="item_update" name="item_update" value="Update"/>
                  </div>
                </div>
              </div>
            </div>
        </form>';
      }
      echo $output;
  }

  function view_update_user() {

      require('db_connect.php');
      $user_id = $_POST['user_id'];
      $output = "";
      $preparedStmt = "SELECT * FROM `tbl_user` WHERE `user_id` = :user_id";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':user_id', $user_id);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        $output .= '
          <form class="form-horizontal" role="form" method="post" action="./../../controller/transactions.php">
            <div class="container-fluid">
              <input type="hidden" name="user_id" id="user_id" value="'.$row["user_id"].'"/>
              <input type="hidden" name="path" id="path" value="'.$_POST["path"].'"/>
              <input type="hidden" name="user_update" id="user_update" value="0"/>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                    <p>User\'s Name:</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                    <input type="text" class="form-control" name="user_name" value="'.$row["user_name"].'"/>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                    <p>User Level:</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                    <select class="form-control" name="user_account_type" id="user_account_type">
                      <option disabled>Choose account role</option>
                      <option value="accounting" ';
                      if($row['user_account_type'] == 'accouting')
                        $output .= 'selected';
                      $output .= '>Accounting</option>
                      <option value="user" ';
                      if($row['user_account_type'] == 'user')
                        $output .= 'selected';
                      $output .= '>User</option>
                      <option value="admin" ';
                      if($row['user_account_type'] == 'admin')
                        $output .= 'selected';
                      $output .= '>Admin</option>
                    </select>
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
                      <input type="radio" value="Female" name="user_gender"';
                      if($row['user_gender'] == 'Female')
                        $output .= 'checked="checked"';
                      $output .= 'required/>Female
                    </label>
                  </div>
                  <div class="col-md-1 col-md-offset-1">
                    <label class="radio-inline">
                      <input type="radio" value="Male" name="user_gender"';
                      if($row['user_gender'] == 'Male')
                        $output .= 'checked="checked"';
                      $output .= 'required/>Male
                    </label>
                  </div>
                  <div class="col-md-6 col-md-4 col-md-offset-3">
                    <input type="text" class="form-control" name="user_date_of_birth" id="user_date_of_birth" value="'.$row["user_date_of_birth"].'"/>
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
                    <input type="text" class="form-control" placeholder="Address" name="user_address" id="user_address" value="'.$row["user_address"].'"/>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                    <p>username:</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                    <input type="text" class="form-control" name="user_username" id="user_username" value="'.$row["user_username"].'"/>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                    <p>Password:</p>
                  </div>
                </div>
                <div class="row">
                  <div class="col-md-10 col-md-offset-1">
                    <input type="password" class="form-control" name="user_password" id="user_password" value="'.$row["user_password"].'"/>
                  </div>
                </div>
              </div>

              <div class="form-group">
                <div class="row">
                  <div class="col-md-4 col-md-offset-7">
                    <input type="submit" class="btn btn-default pull-right" value="Update"/>
                  </div>
                </div>
              </div>
            </div>
          </form>';
      }
      echo $output;
  }

  function view_delete_user() {
    require('db_connect.php');
    $user_id = $_POST['user_id'];
    $output = "";
    $preparedStmt = "SELECT * FROM `tbl_user` WHERE `user_id` = :user_id";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $output .= '
        <form class="form-horizontal" role="form" method="post" action="./../../controller/transactions.php">
          <div class="container-fluid">
            <input type="hidden" name="user_id" id="user_id" value="'.$row["user_id"].'"/>
            <input type="hidden" name="path" id="path" value="'.$_POST["path"].'"/>
            <input type="hidden" name="user_delete" id="user_delete" value="0"/>

            <div class="form-group">
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                  <p>User\'s Name:</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                  <input type="text" class="form-control" name="user_name" value="'.$row["user_name"].'"/>
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
                    <input type="radio" value="Female" name="user_gender"';
                    if($row['user_gender'] == 'Female')
                      $output .= 'checked="checked"';
                    $output .= 'required/>Female
                  </label>
                </div>
                <div class="col-md-1 col-md-offset-1">
                  <label class="radio-inline">
                    <input type="radio" value="Male" name="user_gender"';
                    if($row['user_gender'] == 'Male')
                      $output .= 'checked="checked"';
                    $output .= 'required/>Male
                  </label>
                </div>
                <div class="col-md-6 col-md-4 col-md-offset-3">
                  <input type="text" class="form-control" name="user_date_of_birth" id="user_date_of_birth" value="'.$row["user_date_of_birth"].'"/>
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
                  <input type="text" class="form-control" placeholder="Address" name="user_address" id="user_address" value="'.$row["user_address"].'"/>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                  <p>Username:</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                  <input type="text" class="form-control" name="user_username" id="user_username" value="'.$row["user_username"].'"/>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                  <p>Password:</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                  <input type="text" class="form-control" name="user_password" id="user_password" value="'.$row["user_password"].'"/>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-4 col-md-offset-7">
                  <input type="submit" class="btn btn-default pull-right" id="user_delete" name="user_delete" value="Delete"/>
                </div>
              </div>
            </div>
          </div>
        </form>';
    }
    echo $output;
  }

  function view_activate_user()
  {
    require('db_connect.php');
    $user_id = $_POST['user_id'];
    $output = "";
    $preparedStmt = "SELECT * FROM `tbl_user` WHERE `user_id` = :user_id";
    $stmt = $connection->prepare($preparedStmt);
    $stmt->bindParam(':user_id', $user_id);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      $output .= '
        <form class="form-horizontal" role="form" method="post" action="./../../controller/transactions.php">
          <div class="container-fluid">
            <input type="hidden" name="user_id" id="user_id" value="'.$row["user_id"].'"/>
            <input type="hidden" name="user_activate" value="'.$_POST["path"].'"/>
            <input type="hidden" name="path" value="0"/>
            <div class="form-group">
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                  <p>User\'s Name:</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                  <input type="text" class="form-control" name="user_name" value="'.$row["user_name"].'"/>
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
                    <input type="radio" value="Female" name="user_gender"';
                    if($row['user_gender'] == 'Female')
                      $output .= 'checked="checked"';
                    $output .= 'required/>Female
                  </label>
                </div>
                <div class="col-md-1 col-md-offset-1">
                  <label class="radio-inline">
                    <input type="radio" value="Male" name="user_gender"';
                    if($row['user_gender'] == 'Male')
                      $output .= 'checked="checked"';
                    $output .= 'required/>Male
                  </label>
                </div>
                <div class="col-md-6 col-md-4 col-md-offset-3">
                  <input type="text" class="form-control" name="user_date_of_birth" id="user_date_of_birth" value="'.$row["user_date_of_birth"].'"/>
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
                  <input type="text" class="form-control" placeholder="Address" name="user_address" id="user_address" value="'.$row["user_address"].'"/>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                  <p>Username:</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                  <input type="text" class="form-control" name="user_username" id="user_username" value="'.$row["user_username"].'"/>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                  <p>Password:</p>
                </div>
              </div>
              <div class="row">
                <div class="col-md-10 col-md-offset-1">
                  <input type="text" class="form-control" name="user_password" id="user_password" value="'.$row["user_password"].'"/>
                </div>
              </div>
            </div>

            <div class="form-group">
              <div class="row">
                <div class="col-md-4 col-md-offset-7">
                  <input type="submit" class="btn btn-success pull-right" value="Activate"/>
                </div>
              </div>
            </div>
          </div>
        </form>';
    }
    echo $output;
  }
?>
