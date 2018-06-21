<?php
  include './../../controller/functions.php';
  session_start();
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
    <link rel="stylesheet" type="text/css" href="./../../css/dashboard.css">
    <script src="./../../js/jquery-3.3.1.min.js"></script>
    <script src="./../../js/jquery-ui.min.js"></script>
    <script src="./../../js/bootstrap.min.js"></script>
  </head>
  <body>
    <div class="container-fluid">

      <header class="row">
        <nav class="navbar navbar-default navbar-fixed-top">
          <div class="container-fluid">
            <div class="navbar-header">
              <a href="./" class="navbar-brand"><img src="./../../files/logo/brandimagev2.png" class="img-responsive"></a>
            </div>
            <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
              <ul class="nav navbar-nav navbar-right" id="navbar-right">
                <li><a href="./account.php">Account</a></li>
                <li><a href="./../../controller/logout.php">Log Out</a></li>
              </ul>
            </div>
          </div>
        </nav>
      </header>

      <main class="row">
        <div class="col-sm-3 col-md-2 sidebar">
          <ul class="nav nav-sidebar">
            <li class="active">
              <a href="#"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>&nbsp Dashboard <span class="sr-only">(current)</span> </a>
              <ul class="nav" id="mn-sub-menu">
                <li><a href="account.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp My Account</a></li>
                <li><a href="patientsearch.php"><span class="glyphicon glyphicon-bed" aria-hidden="true"></span>&nbsp Search Patient</a></li>
                <li><a href="invoicecreation.php"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>&nbsp Search Transactions</a></li>
              </ul>
            </li>
            <li>
              <a style="background-color: #edf0f5; color: #000000;"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>&nbsp Maintenance</a>
              <ul class="nav" id="mn-sub-menu">
                <li><a href="patientsettings.php"><span class="glyphicon glyphicon-bed" aria-hidden="true"></span>&nbsp Patient</a></li>
              </ul>
            </li>
          </ul>
        </div>
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2" style="overflow-y:auto; height:100vh; padding-top:20vh;">
            <div class="container-fluid">
              <!-- Shortcut Buttons  -->
              <div class="row" style="padding-left:5%;">
                <div class="col-sm-12 col-md-12">
                  <div class="row">
                    <div class="col-sm-12 col-md-12">
                      <p class="lead">Welcome <?php echo $_SESSION['user_name']?>!</p>
                    </div>
                  </div>
                  <!-- Search  -->
                  <div class="row" style="padding-left:5%;">
                    <div class="col-sm-12 col-md-12">
                      <div class="row">
                        <div class="col-sm-4 col-md-4">
                          <h4>Dashboard</h4>
                        </div>
                      </div>
                      <div class="row">
                        <div class="col-md-3">
                          <a href="patientsearch.php" class="btn btn-primary btn-lg">Search Patient</a>
                        </div>
                        <div class="col-md-3">
                          <a href="./invoicecreation.php" class="btn btn-primary btn-lg" id="btn_invoice_creation">Search Transaction</a>
                        </div>
                        <div class="col-md-3">
                          <a href="./patientsettings.php" class="btn btn-primary btn-lg" id="btn_patient_entry">Patient Entry</a>
                        </div>
                        <div class="col-md-3">
                            <a href="./account.php" class="btn btn-primary btn-lg" id="btn_account_settings">Account Settings</a>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>
              <!-- Patient Views  -->
              <div class="row">
                <div class="col-sm-12 col-md-12">
                  <div class="col-sm-12 col-md-12 col-lg-12 mn-dashboard-header">
                    <hr>
                    <div class="col-sm-3 col-sm-offset-9 col-md-3 col-md-offset-9 col-lg-3 col-lg-offset-9">
                      <div class="input-group">
                        <div class="input-group-btn">
                          <button type="button" class="btn btn-default"> Search </button>
                        </div>
                        <input type="text" class="form-control" id="txt_search"/>
                      </div>
                    </div>
                  </div>

                  <h3>Patient Records</h3>
                  <hr>

                  <div id="parent_filter" style="overflow-y:auto; height:400px;">
                    <?php
                      require('./../../controller/db_connect.php');
                      $preparedStmt = "SELECT * FROM `tbl_patient`";
                      $stmt = $connection->prepare($preparedStmt);
                      $stmt->execute();
                      while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                      {
                        $patient_date_of_birth = $row['patient_date_of_birth'];
                        $year = (explode("-",$patient_date_of_birth));
                        $patient_age = (date("md", date("U", mktime(0, 0, 0, $year[1], $year[2], $year[0]))) > date("md")
                          ? ((date("Y") - $year[0]) - 1)
                          : (date("Y") - $year[0]));
                    ?>
                    <div class="col-lg-3 col-md-3 col-sm-3 col-xs-12 mn-card <?php echo( ' '.strtoupper($row['patient_name']).
                      ' '.strtoupper($row['patient_gender']).' '.strtoupper($patient_age));?>">
                      <div class="w3-card-2">
                        <header class="w3-container w3-light-blue">
                          <label><?php echo $row['patient_name']; ?></label>
                        </header>
                        <div class="w3-container">
                          <p>Gender: <?php echo $row['patient_gender']; ?></p>
                          <p>Age:
                            <?php
                              echo $patient_age;
                              $preparedStmt2 = "SELECT invoice_date, invoice_id FROM tbl_invoice WHERE patient_id_fk ='".$row['patient_id']."' ORDER BY invoice_date DESC";
                              $stmt2 = $connection->prepare($preparedStmt2);
                              $stmt2->execute();
                              $row2 = $stmt2->fetch(PDO::FETCH_ASSOC);
                             ?>
                          <a href="patientprofile.php?patient_id=<?php echo $row['patient_id'];?>&invoice_id=<?php echo $row2['invoice_id'];?>" class="pull-right"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span></a></p>
                          <hr>
                          <p>Latest Transaction</p>
                            <?php
                            if(isset($row2['invoice_date']))
                              echo("<p>".$row2['invoice_date']."</p>");
                            else
                              echo("<p><i> No Transaction</i></p>");
                            ?>
                        </div>
                        <button class="w3-button w3-block w3-light-blue patient_transac_btn" id="<?php echo ($row['patient_id']."/".$row['patient_name']);?>" data-target="#transaction">Add Transactions</button>
                      </div>
                    </div>
                  <?php }?>
                  </div> <!--parent-filter-->
                </div>
              </div>
            </div> <!-- <div class="container-fluid" style="overflow-y:auto; height:100%;"> -->
          </div> <!--<div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2"> -->
      </main>
      <footer class="row">
        <div class="container-fluid">
          <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">
            <p class="text-center">© 2018 Makati PET/CT Center</p>
          </div>
        </div>
      </footer>
    </div>
  </body>

  <!-- Modal -->
  <div id="transaction" class="modal fade" role="dialog">
    <div class="modal-dialog">
      <!-- Modal content-->
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal">&times;</button>
          <h4 class="modal-title">Transaction</h4>
        </div>
        <form class='form-horizontal' role='form' method='POST' action='./../../controller/transactions.php'>
          <input type="hidden" id="user_id_fk" value="<?php echo $_SESSION['user_id']?>"/>
          <div class="modal-body">
            <div class="modal-body" id="patient_transac">

            </div>
          </div>
          <div class="modal-footer">
            <input type="submit" class="btn btn-default" id="transaction_add" name="transaction_add" value="Submit"/>
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </form>
      </div>
    </div>
  </div>

  <script type="text/javascript">
    var doc_field_count = 0;
    var proc_field_count = 0;
    var total = $('.item').length;
    var currentIndex = $('div.active').index() + 1;

    $(document).on("click", "#sub-menu", function() {
      $('.dropdown-toggle').dropdown()
    });

    $(".patient_transac_btn").click(function() {
      var str = $(this).attr('id');
      var split = str.split("/");
      var patient_id = split[0];
      var patient_name = split[1];
      var path = "./../views/user/";
      var user_id = $('#user_id_fk').val();
      $.ajax({
        url: "./../../controller/modal_views.php",
        method: "post",
        data: {patient_id: patient_id,
              patient_name: patient_name,
              user_id: user_id,
              path: path,
              patient_transac: 0},
        success: function(data) {
          console.log(data);
          $('#patient_transac').html(data);
          $('#transaction').modal('show')
        }
      })
    });
    $(document).on("click", "#add_procedure_field_btn", function() {
      var $add_select = $('#procedures_div');
      // Get Select Options
      var first = document.getElementById('procedure_select');
      var options = first.innerHTML;
      // Append To Div
      $add_select.append('<select class="form-control" name="procedures[]">'+ options +'</select>');
      proc_field_count++;
    });

    $(document).on("click", "#add_doctor_field_btn", function() {
      var $add_select = $('#doctors_div');
      // Get Select Options
      var first = document.getElementById('doctor_select');
      var options = first.innerHTML;
      // Append To Div
      $add_select.append('<select class="form-control" name="doctors[]">'+ options +'</select>');
      doc_field_count++;
    });

    $(document).on("click", "#remove_doctor_field_btn", function() {
      if(doc_field_count > 0)
      {
        $('#doctors_div select:last-child').remove();
        doc_field_count--;
      }
      else
        alert("No more Additional Fields")
    });

    $(document).on("click", "#remove_procedure_field_btn", function() {
      if(proc_field_count > 0)
      {
        $('#procedures_div select:last-child').remove();
        proc_field_count--;
      }
      else
        alert("No more Additional Fields")
    });

    $(document).on("change", "select", function() {
      // Calculate total Cost
      var selected = $('select').map(function(){
            return this.value
      }).get();
      var total_proc_cost = 0;
      for(var x=0;x<selected.length;x++)
      {
        var split = selected[x].split('/');
        total_proc_cost += parseInt(split[1]);
      }
      $("#total_proc_cost").text(total_proc_cost);
    });

    $(document).on("keyup paste", "#txt_search", function() {
      var value = ($('#txt_search').val()).toUpperCase();
      if(isEmptyOrSpaces(value))
      {
        $('#parent_filter > div').fadeIn(250);
      }
      else
      {
        var classes = '';
        split = value.trim().split(' ');
        lenght = split.length;
        for(var x=0;x<lenght;x++)
        {
          classes += '.'+split[x];
          if(x+1 != lenght)
            classes += ' ,'
        }
        var $el = $(classes).fadeIn(450);
        $('#parent_filter > div').not($el).hide();
      }
    });

    function isEmptyOrSpaces(str){
      return str === null || str.match(/^ *$/) !== null;
    }
  </script>
</html>
