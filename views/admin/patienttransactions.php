<?php
  session_start();
  $_SESSION['class_type'] = $_GET['class'];
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
                    <li style="color: #000000;background-color: #b7d7f0;"><a href="patientsearch.php"><span class="glyphicon glyphicon-bed" aria-hidden="true"></span>&nbsp Search Patient</a></li>
                    <li><a href="invoicecreation.php"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>&nbsp Search Transactions</a></li>
                </ul>
              </li>
              <li>
                  <a style="background-color: #edf0f5; color: #000000;"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>&nbsp Maintenance</a>
                  <ul class="nav" id="mn-sub-menu">
                    <li><a href="patientsettings.php"><span class="glyphicon glyphicon-bed" aria-hidden="true"></span>&nbsp Patient</a></li>
                    <li><a href="doctorsettings.php"><span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>&nbsp Doctor</a></li>
                    <li><a href="itemsettings.php"><span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>&nbsp Item</a></li>
                    <li><a href="treatmentsettings.php"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>&nbsp Procedure</a></li>
                  </ul>
              </li>
            </ul>
          </div>

          <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"  style="overflow-y:auto; height:100vh;">
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="mn-dashboard-header">
                  <h3><?php echo($_GET['pname']);?></h3>
                  <hr>
                </div>

                <div class="panel panel-primary filterable tab-pane active" id="mn-patient-panel" style="height: 60%; overflow-y:auto;">
                  <div class="panel-heading" style="height: 50px;">
                    <h3 class="panel-title">
                      <?php
                        if($_GET['class'] == 'invoice')
                          echo('Invoice List');
                        if($_GET['class'] == 'quotation')
                          echo('Quotation List');
                        if($_GET['class'] == 'receipt')
                          echo('Receipt List');
                      ?>
                    </h3>
                    <div class="pull-right">
                        <button class="btn btn-default btn-md btn-filter"><span class="fa fa-filter"></span> Filter</button>
                        <button class="btn btn-default btn-md" data-toggle="modal" data-target="#open_patient"><span class="fa fa-plus"></span> Add</button>
                    </div>
                  </div>

                  <table class="table table-hover">
                    <thead>
                      <tr class="filters">
                        <th class="col-md-3"><input type="text" class="form-control" placeholder="#" disabled></th>
                        <?php
                          if($_GET['class'] == "invoice" || $_GET['class'] == "receipt")
                            echo('<th class="col-md-3"><input type="text" class="form-control" placeholder="ID" disabled></th>');
                         ?>
                        <th class="col-md-2"><input type="text" class="form-control" placeholder="Date" disabled></th>
                        <th class="col-md-2"><input type="text" class="form-control" placeholder="Cost" disabled></th>
                        <th class="col-md-2">Actions</th>
                      </tr>
                    </thead>

                    <tbody>
                      <?php
                        require('./../../controller/db_connect.php');
                        $invoice_type = $_GET['class'];
                        $column_id = '`pending_invoice_id`';
                        $preparedStmt = " SELECT *
                                        FROM tbl_invoice
                                        INNER JOIN tbl_payment on tbl_payment.invoice_id_fk = tbl_invoice.invoice_id
                                        WHERE patient_id_fk=:pid and invoice_type=:invoice_type and invoice_status=1 ORDER BY `invoice_id` ASC";
                        $stmt = $connection->prepare($preparedStmt);
                        $stmt->bindParam(':pid', $_GET['pid']);
                        $stmt->bindParam(':invoice_type', $_GET['class']);
                        $stmt->execute();
                        $count = 1;
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                      ?>
                      <tr class="clickable-row" id="<?php echo $row["invoice_id"];?>">
                          <td class="col-md-3 patient_name"><?php echo $count++; ?></td>
                          <?php
                            if($_GET['class'] == "invoice" || $_GET['class'] == "receipt")
                              echo('<td class="col-md-2">'.$row["invoice_id"].'</td>');
                           ?>
                          <td class="col-md-2"><?php echo $row['invoice_date']; ?></td>
                          <td class="col-md-3"><?php echo $row['payment_cost']; ?></td>
                          <td class="col-md-2">
                            <table>
                              <tr>
                                <?php
                                  if($_GET['class'] == "invoice")
                                    echo('<td><button class="btn btn-sm btn-primary chech_out_btn">Check out</button></td>');
                                  else if($_GET['class'] == "quotation")
                                    echo('<td><button class="btn btn-sm btn-primary invoice_btn">Create Invoice</button></td>');
                                 ?>
                                <td><button class="btn btn-sm btn-primary view_btn">View</button></td>
                                <?php
                                if($_GET['class'] != "receipt")
                                {?>
                                <td>
                                  <form method="POST" action="./../../controller/transactions.php">
                                    <input type="hidden" value="./../views/admin/patienttransactions.php?<?php echo('pid='.$_GET['pid'].
                                    '&pname='.$_GET['pname'].'&class='.$_GET['class']);?>" name="path"/>
                                    <input type="hidden" value="<?php echo $row["invoice_id"];?>" name="invoice_id"/>
                                    <input type="hidden" value="0" name="delete_invoice"/>
                                    <input type="submit" class="btn btn-sm delete_btn"value="Delete"/>
                                  </form>
                                </td><?php
                                }?>
                              </tr>
                            </table>
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
    </main>

    <footer>
      <div class="container-fluid">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">
          <p class="text-center">© 2018 Makati PET/CT Center</p>
        </div>
      </div>
    </footer>

    <!-- Modal -->
    <div id="transaction" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Transaction Details</h4>
          </div>
          <div class="modal-body" id="invoice_container">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
          </div>
        </div>

      </div>
    </div>

    <!-- Modal -->
    <div id="transaction_confirmation" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-body">
            <h3>Confirm Transaction</h3>
          </div>
          <div class="modal-footer">
            <form method="POST" action="./../../controller/transactions.php">
              <?php
              if($_GET['class'] == "invoice")
                echo(' <input type="hidden" id="transaction_parameter" name="invoice_param" value="0"/>');
              else if($_GET['class'] == "quotation")
                echo('<input type="hidden" id="transaction_parameter" name="quotation_param" value="0"/>');
              ?>
              <input type="hidden" id="transaction_parameter_id" name="parameter_id" value=""/>
              <input type="hidden" name="path"
              value="admin/patienttransactions.php?<?php echo('pid='.$_GET['pid'].
              '&pname='.$_GET['pname'].'&class='.$_GET['class']);?>"/>
              <input type="submit" class="btn btn-primary" name="proceed" value="Proceed"/>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <!--Script-->
    <script type="text/javascript">
      $(document).ready(function() {
        $(".view_btn").on( "click", function( event ) {
          var invoice_id = $(this).closest('.clickable-row').attr('id');
          var class_type = "<?php echo($_GET['class']);?>";
          var printable = 0;
          if(class_type == "invoice")
            printable = 0;
          else
            printable = 1;
          $.ajax({
            url: "./../../controller/modal_views.php",
            method: "post",
            data: { invoice_id: invoice_id,
                    view_invoice: 0,
                    printable: printable},
            success: function(data) {
              $('#invoice_container').html(data);
              $('#transaction').modal('show');
            }
          });
        });

        $(".chech_out_btn, .invoice_btn").on( "click", function( event ) {
          var invoice_id = $(this).closest('.clickable-row').attr('id');
          $('#transaction_parameter_id').val(invoice_id);
          $('#transaction_confirmation').modal('show');
        });

      });

      $(document).on("click", ".print_invoice" , function(){
        var invoice_id = ($(this).attr('name'));
        var myWindow = window.open("./invoice.php?"+invoice_id, "InvWindow");
      });

    </script>
  </body>
</html>
