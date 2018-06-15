<?php
    include 'functions.php';
    print_r($_POST);

    //add
    if(isset($_POST['patient_add']))
      add_patient();
    else if(isset($_POST['doctor_add']))
      add_doctor();
    else if(isset($_POST['add_procedure']))
      add_procedure();
    else if(isset($_POST['user_add']))
      add_user();
    else if(isset($_POST['item_add']))
      add_item();
    else if(isset($_POST['transac_add']))
      add_transaction();

    //update
    else if(isset($_POST['user_update']))
      update_user();
    else if(isset($_POST['patient_update']))
      update_patient();
    else if(isset($_POST['doctor_update']))
      update_doctor();
    else if(isset($_POST['procedure_update']))
      update_procedure();
    else if(isset($_POST['item_update']))
      update_item();
    else if(isset($_POST['transac_update']))
      update_transaction();

    //transactions
    else if(isset($_POST['quotation_param']))
      create_to_invoice();
    else if(isset($_POST['invoice_param']))
      create_to_receipt();

    //delete
    else if(isset($_POST['user_delete']))
      delete_user();
    else if(isset($_POST['patient_delete']))
      delete_patient();
    else if(isset($_POST['doctor_delete']))
      delete_doctor();
    else if(isset($_POST['item_delete']))
      delete_item();
    else if(isset($_POST['procedure_delete']))
      delete_procedure();
    else if(isset($_POST['delete_invoice']))
      delete_invoice();

    //activate
    else if(isset($_POST['user_activate']))
      activate_user();
    else if(isset($_POST['invoice_activate']))
      activate_invoice();
    else if(isset($_POST['patient_activate']))
      activate_patient();
    else if(isset($_POST['doctor_activate']))
      activate_doctor();
    else if(isset($_POST['procedure_activate']))
      activate_procedure();
    else if(isset($_POST['item_activate']))
      activate_item();

    function add_item()
    {
      require('db_connect.php');
      $item_id = get_smart_id("item");
      $item_name = $_POST['item_name'];
      $item_qty = $_POST['item_qty'];

      $preparedStatement = "INSERT INTO `tbl_item`(`item_id`, `item_name`, `item_qty`, `item_status`) VALUES (:item_id, :item_name, :item_qty, 1)";
      $stmt = $connection->prepare($preparedStatement);
      $stmt->bindParam(':item_id', $item_id);
      $stmt->bindParam(':item_name', $item_name);
      $stmt->bindParam(':item_qty', $item_qty);
      $stmt->execute();

      $stmt = null;
      header("Location: ../views/itemsettings.php");
    }

    function add_user() {
      require('db_connect.php');
      $user_id = get_smart_id("user");
      $path = $_POST['path'];
      $user_name = $_POST['user_name'];
      $user_gender = $_POST['user_gender'];
      $user_date_of_birth = $_POST['user_date_of_birth'];
      $user_address = $_POST['user_address'];
      $user_username = $_POST['user_username'];
      $user_password = $_POST['user_password'];
      $user_account_type = $_POST['user_account_type'];
      $user_status = 2; // Pending
      $page_header = $_POST['page'];
      $page_data = "";
      $user_exist = "0"; // User Checker

      // Chech if username Exist
      $preparedStmt = "SELECT COUNT(*) as `users` FROM `tbl_user` WHERE `user_username` = :user_username";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':user_username', $user_username);
      $stmt->execute();
      $row = $stmt->fetch(PDO::FETCH_ASSOC);
      if($row['users'] >= 1)
      {
        $user_exist = "1";
        $page_data .= "&user_name=".$user_name."&user_gender=".$user_gender."&user_date_of_birth=".$user_date_of_birth."&user_address=".$user_address."&user_username=".$user_username."&user_password=".$user_password."&user_account_type=".$user_account_type;
      }
      // Add user in DB
      if($user_exist == "0")
      {
        $preparedStmt = "INSERT INTO `tbl_user`(`user_id`, `user_name`, `user_gender`, `user_date_of_birth`, `user_address`, `user_username`, `user_password`, `user_account_type`, `user_status`) VALUES (:user_id, :user_name, :user_gender, :user_date_of_birth, :user_address, :user_username, :user_password, :user_account_type, :user_status)";
        $stmt = $connection->prepare($preparedStmt);
        $stmt->bindParam(':user_id', $user_id);
        $stmt->bindParam(':user_name', $user_name);
        $stmt->bindParam(':user_gender', $user_gender);
        $stmt->bindParam(':user_date_of_birth', $user_date_of_birth);
        $stmt->bindParam(':user_address', $user_address);
        $stmt->bindParam(':user_username', $user_username);
        $stmt->bindParam(':user_password', $user_password);
        $stmt->bindParam(':user_account_type', $user_account_type);
        $stmt->bindParam(':user_status', $user_status);
        $stmt->execute();
        $stmt = null;
      }
      header("Location: ../".$page_header."?message=".$user_exist.$page_data);
    }

    function add_patient() {
      require('db_connect.php');
      $patient_id = get_smart_id("patient");
      $patient_name = $_POST['patient_name'];
      $patient_gender = $_POST['patient_gender'];
      $patient_date_of_birth = $_POST['patient_date_of_birth'];
      $patient_address = $_POST['patient_address'];

      $preparedStmt = "INSERT INTO `tbl_patient`(`patient_id`, `patient_name`, `patient_gender`, `patient_date_of_birth`, `patient_address`) VALUES (:patient_id, :patient_name, :patient_gender, :patient_date_of_birth, :patient_address)";

      $stmt = $connection->prepare($preparedStmt);

      $stmt->bindParam(':patient_id', $patient_id);
      $stmt->bindParam(':patient_name', $patient_name);
      $stmt->bindParam(':patient_gender', $patient_gender);
      $stmt->bindParam(':patient_date_of_birth', $patient_date_of_birth);
      $stmt->bindParam(':patient_address', $patient_address);
      $stmt->execute();
      $stmt = null;
      header("Location: ../views/patientsettings.php");
    }

    function add_doctor() {
      require('db_connect.php');
      $doctor_id = get_smart_id("doctor");
      $doctor_name = $_POST['doctor_name'];
      $doctor_gender = $_POST['doctor_gender'];
      $doctor_date_of_birth = $_POST['doctor_date_of_birth'];
      $doctor_address = $_POST['doctor_address'];
      $doctor_contact_number = $_POST['doctor_contact_number'];
      $doctor_professional_fee = $_POST['doctor_professional_fee'];

      $preparedStmt = "INSERT INTO `tbl_doctor`(`doctor_id`, `doctor_name`, `doctor_gender`, `doctor_date_of_birth`, `doctor_address`, `doctor_contact_number`, `doctor_professional_fee`, `doctor_professional_fee`) VALUES (:doctor_id, :doctor_name, :doctor_gender, :doctor_date_of_birth, :doctor_address, :doctor_contact_number, :doctor_professional_fee, 1)";

      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':doctor_id', $doctor_id);
      $stmt->bindParam(':doctor_name', $doctor_name);
      $stmt->bindParam(':doctor_gender', $doctor_gender);
      $stmt->bindParam(':doctor_date_of_birth', $doctor_date_of_birth);
      $stmt->bindParam(':doctor_address', $doctor_address);
      $stmt->bindParam(':doctor_contact_number', $doctor_contact_number);
      $stmt->bindParam(':doctor_professional_fee', $doctor_professional_fee);
      $stmt->execute();
      $stmt = null;
      header("Location: ../views/doctorsettings.php");
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

    function add_transaction() {
      require('db_connect.php');
      if(isset($_POST['path']))
        $path = $_POST['path'];
      else
        $path = "../forms/dashboard.html";
      $invoice_id = get_smart_id("invoice");
      $invoice_date = date("Y-m-d");
      $patient_id_fk = $_POST['patient_id_fk'];
      $user_id_fk = $_POST['user_id_fk'];
      $invoice_cost = 0;
      if(isset($_POST['transac_quotation']))
         $invoice_type = "quotation";
       if(isset($_POST['transac_invoice']))
         $invoice_type = "invoice";
      $doctors = $_POST['doctors'];
      $doctor_id_fk = array();
      for($x=0;$x<count($doctors);$x++)
      {
        $split = explode("//",$doctors[$x]);
        array_push($doctor_id_fk,$split[0]);
        $invoice_cost += intval($split[1]);
      }

      $procedures = $_POST['procedures'];
      $procedure_id_fk = array();
      for($x=0;$x<count($procedures);$x++)
      {
        $split = explode("//",$procedures[$x]);
        array_push($procedure_id_fk,$split[0]);
        $invoice_cost += intval($split[1]);
      }

      $stmt = $connection->prepare("INSERT INTO `tbl_invoice`(`invoice_id`, `invoice_date`, `patient_id_fk`, `user_id_fk`, `invoice_type`, `invoice_cost`)
        VALUES (:invoice_id, :invoice_date, :patient_id_fk, :user_id_fk, :invoice_type, :invoice_cost)");

      $stmt->bindParam(':invoice_id', $invoice_id);
      $stmt->bindParam(':invoice_date', $invoice_date);
      $stmt->bindParam(':patient_id_fk', $patient_id_fk);
      $stmt->bindParam(':user_id_fk', $user_id_fk);
      $stmt->bindParam(':invoice_type', $invoice_type);
      $stmt->bindParam(':invoice_cost', $invoice_cost);
      $stmt->execute();

      // DOCTORS
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

      // Procedure
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
      header("Location: ".$path);
    }

    function view_transaction_patient()
    {
      require('db_connect.php');
      $patient_id = $_POST['patient_id'];
      $output = "
      <div>
        <div class=row>
          <div class='col-md-12'>
            <h5><b>Patient:</b></h5>
          </div>
        </div>
        <div class=row>
          <div class='col-md-12'>
            <p>".$_POST['patient_name']."</p>
          </div>
        </div>
        <div class=row>
          <div class='col-md-12'>
            <h5><b>ID:</b></h5>
          </div>
        </div>
        <div class=row>
          <div class='col-md-12'>
            <p>".$patient_id."</p>
          </div>
        </div>
        <input type='hidden' name='invoice_add' id='invoice_add' value='0'/>
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
          <div class="col-md-12">
            <select id="procedure_select" class="form-control" name="procedures[]">
              <option selected disabled value=0/0>Choose an Item</option>';

      // Query Procedures
      $preparedStmt = "SELECT * FROM `tbl_procedure`";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
      {
        $procedure_id = $row['procedure_id'];
        $procedure_name = $row['procedure_name'];
        $procedure_cost = $row['procedure_cost'];

        $output .= '<option value="'.$procedure_id.'/'.$procedure_cost.'">'.$procedure_name.' - '.$procedure_cost.'</option>';
      }
      $output .='
            </select>
          </div>
        </div>
        <div id="procedures_div"></div>
        <div class="row">
            <div class="col-md-1">
              <a href="#" class="btn" id="add_procedure_field_btn"><i class="icon-plus"></i> <span>Add</span></a>
            </div>
            <div class="col-md-1">
              <a href="#" class="btn" id="remove_procedure_field_btn"><i class="icon-plus"></i> <span>remove</span></a>
            </div>
        </div>';

      // Query Doctors
      $output .= '
        <div class="row">
          <div class="col-md-12">
            <h5>Doctors: </h5>
          </div>
        </div>
        <div class="row">
          <div class="col-md-12">
            <select id="doctor_select" class="form-control" name="doctors[]">
              <option selected disabled value=0/0>Choose an Item</option>';
      $preparedStmt = "SELECT * FROM `tbl_doctor`";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->execute();
      while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
      {
        $doctor_id = $row['doctor_id'];
        $doctor_name = $row['doctor_name'];
        $doctor_professional_fee = $row['doctor_professional_fee'];

        $output .= '<option value="'.$doctor_id.'/'.$doctor_professional_fee.'">'.$doctor_name.' - '.$doctor_professional_fee.'</option>';
      }
      $output .='
            </select>
          </div>
        </div>
        <div id="doctors_div"></div>
        <div class="row">
          <div class="col-md-1">
            <a href="#" class="btn" id="add_doctor_field_btn"><i class="icon-plus"></i> <span>Add</span></a>
          </div>
          <div class="col-md-1">
            <a href="#" class="btn" id="remove_doctor_field_btn"><i class="icon-plus"></i> <span>remove</span></a>
          </div>
        </div>
        <div class="row">
          <div class="col-md-2 pull-right">
            <div class="pull-left">
              <p id="total_proc_cost">0</p>
            </div>
          </div>
          <div class="col-md-4 pull-right">
            <div class="pull-right">
              <p>Total Cost: </p>
            </div>
          </div>
        </div>
      </div>';
      echo $output;
    }

    //update functions
    function update_transaction() {
        require('db_connect.php');
        $invoice_id = $_POST['invoice_id'];
        $path = $_POST['path'];
        $procedures = $_POST['procedures'];
        $procedure_id_fk = array();
        $doctors = $_POST['doctors'];
        $doctor_id_fk = array();
        $invoice_cost = 0;

        for($x=0;$x<count($procedures);$x++)
        {
          $split = explode("//",$procedures[$x]);
          array_push($procedure_id_fk,$split[0]);
          $invoice_cost += intval($split[1]);
        }

        for($x=0;$x<count($doctors);$x++)
        {
          $split = explode("//",$doctors[$x]);
          array_push($doctor_id_fk,$split[0]);
          $invoice_cost += intval($split[1]);
        }

        $preparedStmt = "UPDATE `tbl_invoice` SET `invoice_cost`=:invoice_cost WHERE invoice_id=:invoice_id";
        $stmt = $connection->prepare($preparedStmt);
        $stmt->bindParam(':invoice_id', $invoice_id);
        $stmt->bindParam(':invoice_cost', $invoice_cost);
        $stmt->execute();

        $preparedStmt = "DELETE FROM `tbl_invoice_doctor` WHERE invoice_id_fk=:invoice_id_fk";
        $stmt = $connection->prepare($preparedStmt);
        $stmt->bindParam(':invoice_id_fk', $invoice_id);
        $stmt->execute();

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

        $preparedStmt = "DELETE FROM `tbl_invoice_procedure` WHERE invoice_id_fk=:invoice_id_fk";
        $stmt = $connection->prepare($preparedStmt);
        $stmt->bindParam(':invoice_id_fk', $invoice_id);
        $stmt->execute();

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

        header("Location: ".$path);
    }

    function update_patient() {

        require('db_connect.php');
        $patient_id = $_POST['patient_id'];
        $patient_name =$_POST['patient_name'];
        $patient_gender = $_POST['patient_gender'];
        $patient_date_of_birth = $_POST['patient_date_of_birth'];
        $patient_address = $_POST['patient_address'];
        $path = $_POST['path'];

        $preparedStmt = "UPDATE `tbl_patient` SET `patient_name`=:patient_name,`patient_gender`=:patient_gender,`patient_date_of_birth`=:patient_date_of_birth,`patient_address`=:patient_address WHERE `patient_id` = :patient_id;";
        $stmt = $connection->prepare($preparedStmt);
        $stmt->bindParam(':patient_id', $patient_id);
        $stmt->bindParam(':patient_name', $patient_name);
        $stmt->bindParam(':patient_gender', $patient_gender);
        $stmt->bindParam(':patient_date_of_birth', $patient_date_of_birth);
        $stmt->bindParam(':patient_address', $patient_address);
        $stmt->execute();

        header("Location: ".$path);
    }

    function update_doctor() {

        require('db_connect.php');
        $doctor_id = $_POST['doctor_id'];
        $doctor_name = $_POST['doctor_name'];
        $doctor_gender = $_POST['doctor_gender'];
        $doctor_date_of_birth = $_POST['doctor_date_of_birth'];
        $doctor_address = $_POST['doctor_address'];
        $doctor_contact_number = $_POST['doctor_contact_number'];
        $doctor_professional_fee = $_POST['doctor_professional_fee'];
        $path = $_POST['path'];

        $preparedStmt = "UPDATE `tbl_doctor` SET `doctor_name`=:doctor_name, `doctor_gender`=:doctor_gender, `doctor_date_of_birth`=:doctor_date_of_birth, `doctor_address`=:doctor_address, `doctor_contact_number`=:doctor_contact_number, `doctor_professional_fee`=:doctor_professional_fee WHERE `doctor_id` = :doctor_id;";

        $stmt = $connection->prepare($preparedStmt);
        $stmt->bindParam(':doctor_id', $doctor_id);
        $stmt->bindParam(':doctor_name', $doctor_name);
        $stmt->bindParam(':doctor_gender', $doctor_gender);
        $stmt->bindParam(':doctor_date_of_birth', $doctor_date_of_birth);
        $stmt->bindParam(':doctor_address', $doctor_address);
        $stmt->bindParam(':doctor_contact_number', $doctor_contact_number);
        $stmt->bindParam(':doctor_professional_fee', $doctor_professional_fee);
        $stmt->execute();

        header("Location: ".$path);
    }

    function update_user()
    {
       require('db_connect.php');
       $path = $_POST['path'];
       $user_id = $_POST['user_id'];
       $user_name = $_POST['user_name'];
       $user_gender = $_POST['user_gender'];
       $user_date_of_birth = $_POST['user_date_of_birth'];
       $user_address = $_POST['user_address'];
       $user_username = $_POST['user_username'];
       $user_account_type = $_POST['user_account_type'];
       $user_password = $_POST['user_password'];

       $preparedStmt = "UPDATE `tbl_user` SET `user_name`=:user_name, `user_gender`=:user_gender, `user_date_of_birth`=:user_date_of_birth, `user_address`=:user_address, `user_username`=:user_username, `user_password`=:user_password, `user_account_type`=:user_account_type WHERE `user_id` = :user_id;";

       $stmt = $connection->prepare($preparedStmt);
       $stmt->bindParam(':user_id', $user_id);
       $stmt->bindParam(':user_name', $user_name);
       $stmt->bindParam(':user_gender', $user_gender);
       $stmt->bindParam(':user_date_of_birth', $user_date_of_birth);
       $stmt->bindParam(':user_address', $user_address);
       $stmt->bindParam(':user_username', $user_username);
       $stmt->bindParam(':user_password', $user_password);
       $stmt->bindParam(':user_account_type', $user_account_type);
       $stmt->execute();

       header("Location: ".$path);
    }

    function update_item() {

      require('db_connect.php');
      $item_id = $_POST['item_id'];
      $item_name = $_POST['item_name'];
      $item_qty = $_POST['item_qty'];
      $path = $_POST['path'];

      $preparedStmt = "UPDATE `tbl_item` SET `item_name`=:item_name, `item_qty`=:item_qty WHERE `item_id` = :item_id;";

      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':item_id', $item_id);
      $stmt->bindParam(':item_name', $item_name);
      $stmt->bindParam(':item_qty', $item_qty);
      $stmt->execute();

      header("Location: ".$path);
    }

    function update_procedure()
    {
      require('db_connect.php');
      $procedure_id = $_POST['procedure_id'];
      $procedure_name = $_POST['procedure_name'];
      $procedure_cost = $_POST['procedure_cost'];
      $procedure_item = $_POST['procedure_item'];
      $procedure_item_qty = $_POST['procedure_item_qty'];
      $path = $_POST['path'];

      $preparedStmt = "UPDATE `tbl_procedure` SET `procedure_name`=:procedure_name ,`procedure_cost`=:procedure_cost  WHERE `procedure_id`=:procedure_id";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':procedure_id', $procedure_id);
      $stmt->bindParam(':procedure_name', $procedure_name);
      $stmt->bindParam(':procedure_cost', $procedure_cost);
      $stmt->execute();

      $preparedStmt = "DELETE FROM `tbl_procedure_item` WHERE `procedure_id_fk`=:procedure_id_fk";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':procedure_id_fk', $procedure_id);
      $stmt->execute();

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
      header("Location: ".$path);
    }

    //delete
    function delete_user()
    {
      require('db_connect.php');
      $user_id = $_POST['user_id'];
      $path = $_POST['path'];
      $preparedStmt = "UPDATE `tbl_user` SET `user_status`= 2 WHERE `user_id` = :user_id;";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':user_id', $user_id);
      $stmt->execute();
      header("Location: ".$path);
    }
    function delete_patient()
    {
        require('db_connect.php');
        $patient_id = $_POST['patient_id'];
        $path = $_POST['path'];
        $preparedStmt = "UPDATE `tbl_patient` SET `patient_status`= 2 WHERE `patient_id` = :patient_id;";
        $stmt = $connection->prepare($preparedStmt);
        $stmt->bindParam(':patient_id', $patient_id);
        $stmt->execute();
        header("Location: ".$path);
    }

    function delete_doctor() {

        require('db_connect.php');
        $doctor_id = $_POST['doctor_id'];
        $path = $_POST['path'];
        $preparedStmt = "UPDATE `tbl_doctor` SET `doctor_status`= 2 WHERE `doctor_id` = :doctor_id;";
        $stmt = $connection->prepare($preparedStmt);
        $stmt->bindParam(':doctor_id', $doctor_id);
        $stmt->execute();
        header("Location: ".$path);
    }

    function delete_item()
    {
      require('db_connect.php');
      $item_id = $_POST['item_id'];
      $path = $_POST['path'];
      $preparedStmt = "UPDATE `tbl_item` SET `item_status`= 2 WHERE `item_id` = :item_id;";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':item_id', $item_id);
      $stmt->execute();
      header("Location: ".$path);
    }

    function delete_procedure()
    {
      require('db_connect.php');
      $procedure_id = $_POST['procedure_id'];
      $path = $_POST['path'];
      $preparedStmt = "UPDATE `tbl_procedure` SET `procedure_status`= 2 WHERE `procedure_id` = :procedure_id;";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':procedure_id', $procedure_id);
      $stmt->execute();
      header("Location: ".$path);
    }

    function delete_invoice()
    {
      require('db_connect.php');
      $invoice_id = $_POST['invoice_id'];
      $path = $_POST['path'];
      $preparedStmt = "UPDATE `tbl_invoice` SET `invoice_status`= 2 WHERE `invoice_id` = :invoice_id;";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':invoice_id', $invoice_id);
      $stmt->execute();
      header("Location: ".$path);
    }

    // Re-activation
    function activate_user()
    {
      require('db_connect.php');
      $user_id = $_POST['user_id'];
      $path = $_POST['path'];
      $preparedStmt = "UPDATE `tbl_user` SET `user_status`= 1 WHERE `user_id` = :user_id;";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':user_id', $user_id);
      $stmt->execute();
      header("Location: ".$path);
    }

    function activate_patient()
    {
      require('db_connect.php');
      $patient_id = $_POST['patient_id'];
      $path = $_POST['path'];
      $preparedStmt = "UPDATE `tbl_patient` SET `patient_status`= 1 WHERE `patient_id` = :patient_id;";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':patient_id', $patient_id);
      $stmt->execute();
      header("Location: ".$path);
    }

    function activate_invoice()
    {
      require('db_connect.php');
      $invoice_id = $_POST['invoice_id'];
      $path = $_POST['path'];
      $preparedStmt = "UPDATE `tbl_invoice` SET `invoice_status`= 1 WHERE `invoice_id` = :invoice_id;";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':invoice_id', $invoice_id);
      $stmt->execute();
      header("Location: ".$path);
    }

    function activate_procedure()
    {
      require('db_connect.php');
      $procedure_id = $_POST['procedure_id'];
      $path = $_POST['path'];
      $preparedStmt = "UPDATE `tbl_procedure` SET `procedure_status`= 1 WHERE `procedure_id` = :procedure_id;";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':procedure_id', $procedure_id);
      $stmt->execute();
      header("Location: ".$path);
    }

    function activate_doctor()
    {
      require('db_connect.php');
      $doctor_id = $_POST['doctor_id'];
      $path = $_POST['path'];
      $preparedStmt = "UPDATE `tbl_doctor` SET `doctor_status`= 1 WHERE `doctor_id` = :doctor_id;";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':doctor_id', $doctor_id);
      $stmt->execute();
      header("Location: ".$path);
    }

    function activate_item()
    {
      require('db_connect.php');
      $item_id = $_POST['item_id'];
      $path = $_POST['path'];
      $preparedStmt = "UPDATE `tbl_item` SET `item_status`= 1 WHERE `item_id` = :item_id;";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':item_id', $item_id);
      $stmt->execute();
      header("Location: ".$path);
    }

    // Transactions
    function create_to_invoice()
    {
      require('db_connect.php');
      $path = $_POST['path'];
      $invoice_id = $_POST['parameter_id'];
      $preparedStmt = "UPDATE `tbl_invoice` SET `invoice_type`='invoice' WHERE `invoice_id`=:invoice_id";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':invoice_id', $invoice_id);
      $stmt->execute();
      header("Location: ../views/".$path);
    }

    function create_to_receipt()
    {
      require('db_connect.php');
      $path = $_POST['path'];
      $invoice_id = $_POST['parameter_id'];
      $preparedStmt = "UPDATE `tbl_invoice` SET `invoice_type`='receipt' WHERE `invoice_id`=:invoice_id";
      $stmt = $connection->prepare($preparedStmt);
      $stmt->bindParam(':invoice_id', $invoice_id);
      $stmt->execute();
      header("Location: ../views/".$path);
    }
?>
