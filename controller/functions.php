<?php
  function get_smart_id($data)
  {
    $db_table = null;
    $id_name = null;
    $table_name = null;

    if($data == "uom")
    {
      $db_table = "tbl_uom";
      $id_name = "uom_id";
    }
    else if($data == "patient")
    {
      $db_table = "tbl_patient";
      $id_name = "patient_id";
    }
    else if($data == "user")
    {
      $db_table = "tbl_user";
      $id_name = "user_id";
    }
    else if($data == "doctor")
    {
      $db_table = "tbl_doctor";
      $id_name = "doctor_id";
    }
    else if($data == "procedure")
    {
      $db_table = "tbl_procedure";
      $id_name = "procedure_id";
    }
    else if($data == "invoice")
    {
      $db_table = "tbl_invoice";
      $id_name = "invoice_id";
    }
    else if($data == "item")
    {
      $db_table = "tbl_item";
      $id_name = "item_id";
    }
    else if($data == "quotation")
    {
      $db_table = "tbl_quotation";
      $id_name = "quotation_id";
    }
    else if($data == "pending_invoice")
    {
      $db_table = "tbl_pending_invoice";
      $id_name = "pending_invoice_id";
    }

    $id = get_lastest_id($db_table, $id_name);
    $new_id = smart_counter($db_table, $id);

    return $new_id;
  }

  function get_lastest_id($table, $id)
  {
    require('db_connect.php');
    $tempID = null;
    $stmt = $connection->prepare("SELECT * FROM `". $table ."`;");
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
    {
      $tempID = $row[$id];
    }
    return $tempID;
  }

  function smart_counter($table, $id)
  {
    $new_id = null;

    if($table == "tbl_uom")
    {
      if(!isset($id))
        $new_id = "uom_0000001";
      else
      {
        $str_split = explode("_", $id);
        $id_count = intval($str_split[1]) + 1;
        $new_id = "uom_" . str_pad(strval($id_count), 7, "0", STR_PAD_LEFT);
      }
    }

    else if($table == "tbl_patient")
    {
      if(!isset($id))
        $new_id = "ptnt_000001";
      else
      {
        $str_split = explode("_", $id);
        $id_count = intval($str_split[1]) + 1;
        $new_id = "ptnt_" . str_pad(strval($id_count), 6, "0", STR_PAD_LEFT);
      }
    }

    else if($table == "tbl_item")
    {
      if(!isset($id))
        $new_id = "item_000001";
      else
      {
        $str_split = explode("_", $id);
        $id_count = intval($str_split[1]) + 1;
        $new_id = "item_" . str_pad(strval($id_count), 6, "0", STR_PAD_LEFT);
      }
    }

    else if($table == "tbl_user")
    {
      if(!isset($id))
        $new_id = "usr_000001";
      else
      {
        $str_split = explode("_", $id);
        $id_count = intval($str_split[1]) + 1;
        $new_id = "usr_" . str_pad(strval($id_count), 7, "0", STR_PAD_LEFT);
      }
    }

    else if($table == "tbl_doctor")
    {
      if(!isset($id))
        $new_id = "doc_000001";
      else
      {
        $str_split = explode("_", $id);
        $id_count = intval($str_split[1]) + 1;
        $new_id = "doc_" . str_pad(strval($id_count), 7, "0", STR_PAD_LEFT);
      }
    }

    else if($table == "tbl_procedure")
    {
      if(!isset($id))
        $new_id = "proc_000001";
      else
      {
        $str_split = explode("_", $id);
        $id_count = intval($str_split[1]) + 1;
        $new_id = "proc_" . str_pad(strval($id_count), 6, "0", STR_PAD_LEFT);
      }
    }

    else if($table == "tbl_quotation")
    {
      if(!isset($id))
        $new_id = "quot_000001";
      else
      {
        $str_split = explode("_", $id);
        $id_count = intval($str_split[1]) + 1;
        $new_id = "quot_" . str_pad(strval($id_count), 6, "0", STR_PAD_LEFT);
      }
    }

    else if($table == "tbl_pending_invoice")
    {
      if(!isset($id))
        $new_id = "pinv_000001";
      else
      {
        $str_split = explode("_", $id);
        $id_count = intval($str_split[1]) + 1;
        $new_id = "pinv_" . str_pad(strval($id_count), 6, "0", STR_PAD_LEFT);
      }
    }

    else if($table == "tbl_invoice")
    {
      if(!isset($id))
        $new_id = date("ymd") ."_001";
      else
      {
        $str_split = explode("_", $id);
        if($str_split[0] == date("ymd"))
        {
          $id_count = intval($str_split[1]) + 1;
          $new_id = $str_split[0]."_". str_pad(strval($id_count), 3, "0", STR_PAD_LEFT);
        }
        else
          $new_id = date("ymd").str_pad("_001", 3, "0", STR_PAD_LEFT);
      }
    }
    return $new_id;
  }

  function check_logged_in()
  {
    if(isset($_SESSION['user_id']))
      header("Location: /Points_of_sales/index.php?message=3");
  }
?>
