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
                  <li style="color: #000000;background-color: #b7d7f0;"><a href="invoicecreation.php"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>&nbsp Search Transactions</a></li>
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
                  <h3>Transaction Search</h3>
                  <hr>
                  <ul class="nav nav-tabs">
                    <li class="active"><a href="#mn-transaction-panel" data-toggle="tab">Active Transactions</a></li>
                    <li><a href="#mn-deleted-transaction-panel" data-toggle="tab">Inactive Transactions</a></li>
                  </ul>
                </div>
                <div class="tab-content">
                  <div class="panel panel-primary filterable tab-pane active" id="mn-transaction-panel" style="height: 60%; overflow-y:auto;">
                    <div class="panel-heading" style="height: 50px;">
                      <h3 class="panel-title">Transaction</h3>
                      <div class="pull-right">
                          <button class="btn btn-default btn-md btn-filter"><span class="fa fa-filter"></span> Filter</button>
                          <button class="btn btn-default btn-md" data-toggle="modal" id="open_new_transaction"><span class="fa fa-plus"></span> Add</button>
                      </div>
                    </div>

                    <table class="table table-hover">
                      <thead>
                        <tr class="filters">
                          <th class="col-md-3"><input type="text" class="form-control" placeholder="Invoice ID" disabled></th>
                          <th class="col-md-2"><input type="text" class="form-control" placeholder="Date" disabled></th>
                          <th class="col-md-2"><input type="text" class="form-control" placeholder="Patient Name" disabled></th>
                          <th class="col-md-3"><input type="text" class="form-control" placeholder="Transaction Type" disabled></th>
                          <th class="col-md-2">Actions</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                          require('./../../controller/db_connect.php');
                          $preparedStmt = " SELECT * FROM `tbl_invoice`
                                            LEFT JOIN `tbl_patient`
                                            ON `tbl_invoice`.`patient_id_fk` = `tbl_patient`.`patient_id`
                                            WHERE invoice_status = 1";
                          $stmt = $connection->prepare($preparedStmt);
                          $stmt->execute();
                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                          {
                        ?>
                        <tr class="clickable-row">
                            <td class="col-md-3 invoice_id"><?php echo $row['invoice_id']; ?></td>
                            <td class="col-md-2"><?php echo $row['invoice_date']; ?></td>
                            <td class="col-md-2 patient_name"><?php echo $row['patient_name']; ?></td>
                            <td class="col-md-3 invoice_type"><?php echo $row['invoice_type']; ?></td>
                            <td class="col-md-2">
                              <table>
                                <tbody>
                                  <tr id="<?php echo $row['patient_id']; ?>" name="<?php echo $row['patient_name']; ?>">
                                    <td><button type="button" class="btn btn-sm btn-primary edit_btn">Edit</button></td>
                                    <td><button type="button" class="btn btn-sm btn-primary view_btn">View</button></td>
                                    <?php
                                      if($row['invoice_type'] == 'invoice')
                                        echo ('<td>
                                                <button type="button" class="btn btn-sm btn-primary check_out_btn">Check out</button>
                                              </td>
                                              <td>
                                                <form method="POST" action="./../../controller/transactions.php">
                                                  <input type="hidden" name="path" value="./../views/admin/invoicecreation.php"/>
                                                  <input type="hidden" name="invoice_id" value="'.$row['invoice_id'].'"/>
                                                  <input type="submit" class="btn btn-sm btn-danger" name="delete_invoice" value="Delete"/>
                                                </form>
                                              </td>');
                                      else if($row['invoice_type'] == 'quotation')
                                        echo ('<td>
                                                <form method="POST" action="./../../controller/transactions.php">
                                                  <input type="hidden" name="path" value="./../views/admin/invoicecreation.php"/>
                                                  <input type="hidden" name="parameter_id" value="'.$row['invoice_id'].'"/>
                                                  <input type="submit" class="btn btn-sm btn-primary" name="quotation_param" value="Create Invoice"/>
                                                </form>
                                              </td>
                                              <td>
                                                <form method="POST" action="./../../controller/transactions.php">
                                                  <input type="hidden" name="path" value="./../views/admin/invoicecreation.php"/>
                                                  <input type="hidden" name="invoice_id" value="'.$row['invoice_id'].'"/>
                                                  <input type="submit" class="btn btn-sm btn-danger" name="delete_invoice" value="Delete"/>
                                                </form>
                                              </td>');
                                    ?>
                                  </tr>
                                <tbody>
                              </table>
                            </td>
                          </tr>
                          <?php
                          }
                          ?>
                      </tbody>
                    </table>
                  </div>
                  <div class="panel panel-primary filterable tab-pane" id="mn-deleted-transaction-panel">
                    <div class="panel-heading" style="height: 50px;">
                      <h3 class="panel-title">Deleted Transactions</h3>
                      <div class="pull-right">
                        <button class="btn btn-default btn-md btn-filter"><span class="fa fa-filter"></span> Filter</button>
                      </div>
                    </div>
                    <table class="table table-hover">
                      <thead>
                        <tr class="filters">
                          <th class="col-md-3"><input type="text" class="form-control" placeholder="Invoice ID" disabled></th>
                          <th class="col-md-2"><input type="text" class="form-control" placeholder="Date" disabled></th>
                          <th class="col-md-2"><input type="text" class="form-control" placeholder="Patient Name" disabled></th>
                          <th class="col-md-3"><input type="text" class="form-control" placeholder="Transaction Type" disabled></th>
                          <th class="col-md-2">Actions</th>
                        </tr>
                      </thead>

                      <tbody>
                        <?php
                          require('./../../controller/db_connect.php');
                          $preparedStmt = " SELECT * FROM `tbl_invoice`
                                            LEFT JOIN `tbl_patient`
                                            ON `tbl_invoice`.`patient_id_fk` = `tbl_patient`.`patient_id`
                                            WHERE invoice_status = 2";
                          $stmt = $connection->prepare($preparedStmt);
                          $stmt->execute();
                          while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                          {
                        ?>
                        <tr class="clickable-row">
                            <td class="col-md-3 invoice_id"><?php echo $row['invoice_id']; ?></td>
                            <td class="col-md-2"><?php echo $row['invoice_date']; ?></td>
                            <td class="col-md-2 patient_name"><?php echo $row['patient_name']; ?></td>
                            <td class="col-md-3 invoice_type"><?php echo $row['invoice_type']; ?></td>
                            <td class="col-md-2">
                              <table>
                                <tbody>
                                  <tr id="<?php echo $row['patient_id']; ?>" name="<?php echo $row['patient_name']; ?>">
                                    <td><button type="button" class="btn btn-sm btn-primary view_btn">View</button></td>
                                    <td>
                                      <form method="POST" action="./../../controller/transactions.php">
                                        <input type="hidden" name="path" value="./../views/admin/invoicecreation.php"/>
                                        <input type="hidden" name="invoice_id" value="<?php echo($row['invoice_id']);?>"/>
                                        <input type="submit" class="btn btn-sm btn-primary" name="invoice_activate" value="Restore"/>
                                      </form>
                                    </td>
                                  </tr>
                                <tbody>
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
            </div>
          </div> <!--main-->
        </div>
      </div>
    </main>

        <!-- Modal -->
    <div id="open_transaction" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Create Transaction</h4>
          </div>
          <div class="modal-body">

          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div>

      </div>
    </div>

    <!-- Modal -->
    <div id="check_out_modal" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Invoice Check-out</h4>
          </div>

          <form method="post" action="./../../controller/transactions.php">
            <div class="modal-body">
              <div id="check_out_modal_body">
              </div>
            </div>

            <div class="modal-footer">
              <input type="submit" class="btn btn-primary" id="proceed_btn" name="proceed_btn" value="Proceed" disabled>
              <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
            </div>
          </div>
        </form>
      </div>
    </div>

    <div id="new_invoice" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Patient Transaction</h4>
          </div>
          <form class='form-horizontal' role='form' method='POST' action='./../../controller/transactions.php'>
            <div class="modal-body">
              <div id="new_invoice_body">
              </div>
            </div>

            <div class="modal-footer">
              <input type="submit" class="btn btn-primary" name="transac_quotation" value="Create Quatation"/>
              <input type="submit" class="btn btn-primary" name="transac_invoice" value="Create Invoice"/>
              <button type="button" class="btn btn-basic" data-dismiss="modal">Cancel</button>
            </div>
          </form>
        </div>
      </div>
    </div>


    <div id="modal_table" class="modal fade" role="dialog">
      <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title" id="modal_table_title"></h4>
          </div>

          <form class='form-horizontal' role='form' method='POST' action='./../../controller/transactions.php'>
            <input type="hidden" name="invoice_id" id="invoice_id" value="">
            <div class="modal-body" id="modal_table_data">
            </div>

            <div class="modal-footer">
              <input type="submit" class="btn btn-default" name="btn_edit_transaction" value="Save Changes"/>
              <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
          </form>
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
      var doc_field_count = 0;
      var proc_field_count = 0;
      var selected_patient_index = 0;
      function count_fields()
      {
        doc_field_count = $('.remove_doctor_field_btn').length;
        proc_field_count = $('.remove_procedure_field_btn').length;
      }


      function toggle_patient_fields(str)
      {
        if(str == 'id')
          document.getElementById("patient_name_list").selectedIndex = selected_patient_index;
        else
          document.getElementById("patient_id_list").selectedIndex = selected_patient_index;
      }

      function calc_total_cost()
      {
        // Calculate total Cost
        var selected = $('select.costed_select').map(function(){
            return this.value
        }).get();
        var total_proc_cost = 0;
        for(var x=0;x<selected.length;x++)
        {
          var split = selected[x].split('//');
          total_proc_cost += parseInt(split[1]);
        }
        $("#total_proc_cost").text(total_proc_cost);
      }

      function change_list_values(str)
      {
        if(str == 'id')
        {
          var index = document.getElementById("patient_name_list").selectedIndex;
          document.getElementById("patient_id_list").selectedIndex = index;
        }
        else
        {
          var index = document.getElementById("patient_id_list").selectedIndex;
          document.getElementById("patient_name_list").selectedIndex = index;
        }
      }

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



      $(document).on("click", "#add_procedure_field_btn", function() {
        var $add_select = $('#procedures_div');
        // Get Select Options
        var first = document.getElementById('procedure_select');
        var options = first.innerHTML;
        // Append To Div
        var txt_append = "<div class='row'><div class='col-md-10'><select class='form-control costed_select' name='procedures[]'>"+ options +"</select></div><div class='col-md-2'><a href='#' class='btn btn-default remove_procedure_field_btn'><span class='glyphicon glyphicon-minus' aria-hidden='true'></span></a></div></div>";
        $add_select.append(txt_append);
        proc_field_count++;
      });

      $(document).on("click", "#add_doctor_field_btn", function() {
        var $add_select = $('#doctors_div');
        // Get Select Options
        var first = document.getElementById('doctor_select');
        var options = first.innerHTML;
        // Append To Div
        var txt_append = "<div class='row'><div class='col-md-10'><select class='form-control costed_select' name='doctors[]'>"+ options +"</select></div><div class='col-md-2'><a href='#' class='btn btn-default remove_doctor_field_btn'><span class='glyphicon glyphicon-minus' aria-hidden='true'></span></a></div></div>";
        $add_select.append(txt_append);
        doc_field_count++;
      });

      $(document).on("click", ".remove_doctor_field_btn", function() {
        if(doc_field_count > 0)
        {
          $(this).closest('.row').remove();
          doc_field_count--;
        }
        else
          alert("No more Additional Fields")
      });

      $(document).on("click", ".remove_procedure_field_btn", function() {
        if(proc_field_count > 0)
        {
           $(this).closest('.row').remove();
          proc_field_count--;
        }
        else
          alert("No more Additional Fields")
      });

      $(document).on("change", "select", function() {
        calc_total_cost();
      });

      $(document).on("change", "#patient_name_list", function() {
        change_list_values('id');
      });

      $(document).on("change", "#patient_id_list", function() {
        change_list_values('name');
      });

      $(document).on("click", ".view_btn", function() {
        var invoice_id = $(this).closest('.clickable-row').find('.invoice_id').html();
        var class_type = $(this).closest('.clickable-row').find('.invoice_type').html();
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
            $('#modal_table_data').html(data);
            $('#modal_table_title').html('View Transaction');
            $('#modal_table').modal('show');
          }
        });
      });

      $(document).on("click", "#open_new_transaction", function() {
        var path = "./../views/admin/invoicecreation.php";
        $.ajax({
          url: "./../../controller/modal_views.php",
          method: "post",
          data: { create_new_invoice: 0,
                  path: path},
          success: function(data) {
            $('#new_invoice_body').html(data);
            $('#new_invoice').modal('show');
          }
        });
      });

      $(document).on("change", "#payment_mode_select", function() {
        var value = $('#payment_mode_select').val();
        if(value == 'cash')
        {
          $('.payment_cash').show();
        }
        else
        {
          $('.payment_cash').hide();
        }
      });

      $(document).on("click", ".check_out_btn", function() {
        var path = "./../views/admin/invoicecreation.php";
        var invoice_id_fk = $(this).closest('.clickable-row').find('.invoice_id').html();
        $.ajax({
          url: "./../../controller/modal_views.php",
          method: "post",
          data: { check_out_invoice: 0,
                  invoice_id_fk: invoice_id_fk,
                  path: path},
          success: function(data) {
            $('#check_out_modal_body').html(data);
            $('#check_out_modal').modal('show');
          }
        });
      });

      $(document).on("click", ".edit_btn", function() {
        var invoice_id = $(this).closest('.clickable-row').find('.invoice_id').html();
        var patient_id = $(this).closest('tr').attr('id');
        var patient_name = $(this).closest('.clickable-row').find('.patient_name').html();
        var class_type = $(this).closest('.clickable-row').find('.invoice_type').html();
        var printable = 0;
        var user_id = "<?php echo($_SESSION['user_id']);?>";
        var path = "./../views/admin/invoicecreation.php";
        $('#invoice_id').val(invoice_id);
        if(class_type == "invoice")
          printable = 0;
        else
          printable = 1;
        $.ajax({
          url: "./../../controller/modal_views.php",
          method: "post",
          data: { invoice_id: invoice_id,
                  patient_name: patient_name,
                  user_id:user_id,
                  patient_id: patient_id,
                  view_update_invoice: 0,
                  printable: printable,
                  path: path},
          success: function(data) {
            $('#modal_table_data').html(data);
            $('#modal_table_title').html('Edit Transaction');
            $('#modal_table').modal('show');
            count_fields();
            calc_total_cost();
          }
        });
      });

      $(document).on("focus", "#patient_name_filter", function(){
        setInterval(function()
        {
          var filter_word = document.getElementById("patient_name_filter").value;
          var select = document.getElementById("patient_name_list");
          var selected = false;
          if(filter_word.length > 0)
          {
            for(var x = 0 ; x < select.length; x++)
            {
              var txt = select.options[x].text;
              if (txt.substring(0, filter_word.length).toLowerCase() !== filter_word.toLowerCase() && filter_word.trim() !== ""){
                $(select.options[x]).attr('disabled','disabled').hide();
              }
              else{
                $(select.options[x]).removeAttr('disabled').show();
                if(selected == false)
                {
                  selected_patient_index = x;
                  select.selectedIndex = selected_patient_index;
                  change_list_values('id');
                }
              }
            }
          }
          else {
            for (var x = 0; x < select.length; x++){
              $(select.options[x]).removeAttr('disabled').show();
            }
          }
        }, 500);
      });

      $(document).on("focus", "#patient_id_filter", function(){
        setInterval(function()
        {
          var filter_word = document.getElementById("patient_id_filter").value.split('').reverse().join('');
          var select = document.getElementById("patient_id_list");
          var selected = false;
          if(filter_word.length > 0)
          {
            for(var x = 0 ; x < select.length; x++)
            {
              var txt = select.options[x].text.split('').reverse().join('');
              if (txt.substring(0, filter_word.length).toLowerCase() !== filter_word.toLowerCase() && filter_word.trim() !== ""){
                $(select.options[x]).attr('disabled','disabled').hide();
              }
              else{
                $(select.options[x]).removeAttr('disabled').show();
                if(selected == false)
                {
                  selected_patient_index = x;
                  select.selectedIndex = selected_patient_index;
                  change_list_values('name');
                }
              }
            }
          }
          else {
            for (var x = 0; x < select.length; x++){
              $(select.options[x]).removeAttr('disabled').show();
            }
          }
        }, 500);
      });

      $(document).on("click", ".print_invoice" , function(){
        var invoice_id = ($(this).attr('name'));
        var myWindow = window.open("./invoice.php?"+invoice_id, "InvWindow");
      });

      $(document).on("change", "#payment_paid_amount" , function(){
        var amount_paid = $('#payment_paid_amount').val();
        var amount_need = $('#payment_cost').val();
        if(amount_paid - amount_need >= 0)
        {
          $('#cash_change').html("PHP "+(amount_paid - amount_need));
          $("#proceed_btn").removeAttr("disabled");
        }
        else
        {
          $('#cash_change').html("PHP 0.00");
          $("#proceed_btn").attr("disabled", "disabled");
        }
      });

    </script>
  </body>
</html>
