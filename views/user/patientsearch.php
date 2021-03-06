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
                  <li><a href="account.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp My Account</a></li>
                  <li style="color: #000000;background-color: #b7d7f0;"><a href="patientsearch.php"><span class="glyphicon glyphicon-bed" aria-hidden="true"></span>&nbsp Search Patient</a></li>
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

          <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main"  style="overflow-y:auto; height:100vh;">
            <div class="row">
              <div class="col-sm-12 col-md-12">
                <div class="mn-dashboard-header">
                  <h3>Patient Search</h3>
                  <hr>
                </div>

                <div class="panel panel-primary filterable tab-pane active" id="mn-patient-panel" style="height: 60%; overflow-y:auto;">
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
                        <th class="col-md-2">Actions</th>
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
                          <td class="col-md-3 patient_name"><?php echo $row['patient_name']; ?></td>
                          <td class="col-md-2"><?php echo $row['patient_gender']; ?></td>
                          <td class="col-md-2"><?php echo $row['patient_date_of_birth']; ?></td>
                          <td class="col-md-3"><?php echo $row['patient_address']; ?></td>
                          <td class="col-md-2">
                          <table>
                            <tr>
                              <td><input type="submit" class="btn btn-sm btn-primary invoice" value="Invoice" id="<?php echo $row['patient_id']; ?>" name="<?php echo $row['patient_name']; ?>" /></td>
                              <td><input type="submit" class="btn btn-sm btn-primary quotation" value="Quotation" id="<?php echo $row['patient_id']; ?>" name="<?php echo $row['patient_name']; ?>" /></td>
                              <td><input type="submit" class="btn btn-sm btn-primary receipt" value="Recipts" id="<?php echo $row['patient_id']; ?>" name="<?php echo $row['patient_name']; ?>" /></td>
                              <td><input type="submit" class="btn btn-sm btn-primary patient_transaction" value="Transaction" id="<?php echo $row['patient_id']; ?>" name="transaction" /></td>
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

    <div id="patient_invoice" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">

          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Patient Transaction</h4>
          </div>
          <form class='form-horizontal' role='form' method='POST' action='./../../controller/transactions.php'>
            <div class="modal-body">
              <div id="patient_invoice_body">
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
            <div class="modal-body" id="modal_table_data">
            </div>

            <div class="modal-footer">
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



        $(document).on("click", ".patient_transaction", function() {
          var patient_id = $(this).attr('id');
          var patient_name = $(this).closest('tr').parent().closest('tr').find('.patient_name').text();
          var user_id = "<?php echo($_SESSION['user_id']);?>";
          var path = "./../views/user/patientsearch.php";
          $.ajax({
            url: "./../../controller/modal_views.php",
            method: "post",
            data: { patient_id:patient_id,
                    user_id:user_id,
                    patient_name:patient_name,
                    patient_transac: 1,
                    path: path},
            success: function(data) {
              $('#patient_invoice_body').html(data);
              $('#patient_invoice').modal('show');
            }
          })
        });

        $(document).on("click", ".invoice, .quotation, .receipt", function() {
          var id = $(this).attr('id');
          var name = $(this).attr('name');
          var classes = $(this).attr('class').split(' ')[3];
          var url = './patienttransactions.php?pid='+id+'&pname='+name+'&class='+classes;
          window.location = url;
        });

      });

      $(document).on("click", "#add_procedure_field_btn", function() {
        var $add_select = $('#procedures_div');
        // Get Select Options
        var first = document.getElementById('procedure_select');
        var options = first.innerHTML;
        // Append To Div
        var txt_append = "<div class='row'><div class='col-md-10'><select class='form-control' name='procedures[]'>"+ options +"</select></div><div class='col-md-2'><a href='#' class='btn btn-default remove_procedure_field_btn'><span class='glyphicon glyphicon-minus' aria-hidden='true'></span></a></div></div>";
        $add_select.append(txt_append);
        proc_field_count++;
      });

      $(document).on("click", "#add_doctor_field_btn", function() {
        var $add_select = $('#doctors_div');
        // Get Select Options
        var first = document.getElementById('doctor_select');
        var options = first.innerHTML;
        // Append To Div
        var txt_append = "<div class='row'><div class='col-md-10'><select class='form-control' name='doctors[]'>"+ options +"</select></div><div class='col-md-2'><a href='#' class='btn btn-default remove_doctor_field_btn'><span class='glyphicon glyphicon-minus' aria-hidden='true'></span></a></div></div>";
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
        // Calculate total Cost
        var selected = $('select').map(function(){
              return this.value
        }).get();
        var total_proc_cost = 0;
        for(var x=0;x<selected.length;x++)
        {
          var split = selected[x].split('//');
          total_proc_cost += parseInt(split[1]);
        }
        $("#total_proc_cost").text(total_proc_cost);
      });

    </script>
  </body>
</html>
