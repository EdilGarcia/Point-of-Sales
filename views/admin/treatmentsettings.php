<!DOCTYPE html>

<html>

  <head>

    <title>
      Makati PET/CT Center
    </title>

    <link rel="stylesheet" href="./../../css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <!-- <link rel="stylesheet" type="text/css" href="./../../css/bootstrap.min.css"> -->
    <link rel="stylesheet" href="https://www.w3schools.com/w3css/4/w3.css">
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
              <li>
                <a href="dashboard.php"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>&nbsp Dashboard <span class="sr-only">(current)</span> </a>
                <ul class="nav" id="mn-sub-menu">
                  <li><a href="patientsearch.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp Search Patient</a></li>
                  <li style="color: #000000;background-color: #b7d7f0;"><a href="invoicecreation.php"><span class="glyphicon glyphicon-barcode" aria-hidden="true"></span>&nbsp Search Transactions</a></li>
                </ul>
              </li>

              <li>
                  <a style="background-color: #edf0f5; color: #000000;"><span class="glyphicon glyphicon-edit" aria-hidden="true"></span>&nbsp Maintenance</a>
                  <ul class="nav" id="mn-sub-menu">
                      <li><a href="patientsettings.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp Patient</a></li>
                      <li><a href="doctorsettings.php"><span class="glyphicon glyphicon-briefcase" aria-hidden="true"></span>&nbsp Doctor</a></li>
                      <li><a href="itemsettings.php"><span class="glyphicon glyphicon-pushpin" aria-hidden="true"></span>&nbsp Item</a></li>
                      <li style="color: #000000;background-color: #b7d7f0;"><a href="treatmentsettings.php"><span class="glyphicon glyphicon-align-justify" aria-hidden="true"></span>&nbsp Procedure</a></li>
                  </ul>
              </li>
            </ul>
          </div>

          <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="col-sm-12 col-md-12">
              <div class="mn-dashboard-header">
                <h3>Treatment Details</h3>
                <hr>
              </div>

              <div class="panel panel-primary filterable">
                <div class="panel-heading" style="height: 50px;">
                    <h3 class="panel-title">Treatments</h3>
                    <div class="pull-right">
                        <button class="btn btn-default btn-md btn-filter"><span class="fa fa-filter"></span> Filter</button>
                        <button class="btn btn-default btn-md" data-toggle="modal" data-target="#open_treament"><span class="fa fa-plus"></span> Add</button>
                    </div>
                </div>

                <table class="table table-hover">
                    <thead>
                        <tr class="filters">
                            <th><input type="text" class="form-control" placeholder="Name" disabled></th>
                            <th><input type="text" class="form-control" placeholder="Cost" disabled></th>
                            <th>Action</th>
                        </tr>
                    </thead>

                    <tbody>
                      <?php
                        require('./../../controller/db_connect.php');
                        $preparedStmt = "SELECT * FROM `tbl_procedure` ORDER BY `procedure_name`";
                        $stmt = $connection->prepare($preparedStmt);
                        $stmt->execute();
                        while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                        {
                        ?>

                        <tr class="clickable-row">
                            <td><?php echo $row['procedure_name']; ?></td>
                            <td><?php echo $row['procedure_cost']; ?></td>
                            <td><input type="submit" class="btn btn-info btn-sm procedure_update" value="Update" id="<?php echo $row['procedure_id']; ?>" name="procedure_update" />
                                <input type="submit" class="btn btn-danger btn-sm procedure_delete" value="Delete" id="<?php echo $row['procedure_id']; ?>" name="procedure_delete" /></td>
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
      </div>
    </main>

     <!-- Modal -->
    <div id="open_treament" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Treatment Information</h4>
          </div>
          <div class="modal-body" style="overflow-y:auto; height:100%;">

            <form class="form-horizontal" role="form" method="post" action="./../../controller/transactions.php">
              <div class="container-fluid">
                <input type="hidden" value="0" name="add_procedure">

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-6">
                      <h5>Procedure Name: </h5>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-12">
                      <input type="text" class="form-control" placeholder="Procedure Name" name="procedure_name" id="procedure_name" required/>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-3">
                      <h5>Item/s Needed:</h5>
                    </div>
                  </div>

                  <div id="add_field">
                    <div class="row">
                      <div class="col-md-6">
                        <select class="form-control" name="procedure_item[]" id="procedure_item">
                          <option selected disabled>Choose an Item</option>
                          <?php
                            require('././../../controller/db_connect.php');
                            $preparedStmt = "SELECT * FROM `tbl_item`";
                            $stmt = $connection->prepare($preparedStmt);
                            $stmt->execute();
                            while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                            {
                              echo '<option value="'.$row['item_id'].'">'.$row['item_name'].'</option>';
                            }
                          ?>
                        </select>
                      </div>

                      <div class="col-md-2">
                        <h5>Quantity: </h5>
                      </div>

                      <div class="col-md-2">
                        <input type="number" class="form-control" name="procedure_item_qty[]" min="1" max="100" value="1" required/>
                      </div>

                      <div class="col-md-2">
                        <button type="button" class="btn btn-default btn-md" id="btn_add_item"><span class="glyphicon glyphicon-plus"></span></button>
                      </div>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-3">
                      <h5>Procedure Cost: </h5>
                    </div>
                  </div>

                  <div class="row">
                    <div class="col-md-9">
                      <input type="text" class="form-control" name="procedure_cost" id="procedure_cost" required/>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-10">
                        <input type="submit" class="btn btn-default pull-right" value="Add"/>
                    </div>
                  </div>
                </div>
              </div>
            </form>
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div>

      </div>
    </div>


    <!--  Modal  -->
    <div id="update_delete_procedure" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!--  Modal content -->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">Edit Procedure Details</h4>
          </div>
          <div class="modal-body" id="procedure_info" style="overflow-y:auto; height:100%;">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
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

    <!--Script-->

    <script type="text/javascript">

      var field_count = 0;
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

        $(document).on("click", "#btn_add_item", function() {
          field_count = field_count + 1;
          var procedure_items = document.getElementById('procedure_item');
          var options = procedure_items.innerHTML;

          var $add_field = $('#add_field');
          var added_field = ' \
          <div class="row">\
            <div class="col-md-6 input_item_field"> \
             <select class="form-control" name="procedure_item[]">'
             + options +
             '</select> \
            </div>  \
            <div class="col-md-2">  \
              <h5>Quantity: </h5>  \
            </div> \
            <div class="col-md-2"> \
              <input type="number" class="form-control" name="procedure_item_qty[]" min="1" max="100" value="1" required/> \
            </div> \
            <div class="col-md-2"> \
              <button class="btn btn-default btn-md remove_item"><span class="glyphicon glyphicon-minus"></span></button> \
            </div>\
          </div>';
          $add_field.append(added_field);
        });

        $(document).on("click", ".remove_item", function() {
          var $div = $(this).parent().parent();
          $div.remove();
        });

        $(document).on("click", "#btn_add_update_item", function() {
          field_count = field_count + 1;
          var procedure_items = document.getElementById('procedure_item');
          var options = procedure_items.innerHTML;

          var $add_field = $('#update_field');
          var added_field = ' \
          <div class="row"> \
            <div class="col-md-6 input_item_field"> \
             <select class="form-control" name="procedure_item[]">'
             + options +
              '</select> \
            </div>  \
            <div class="col-md-2">  \
              <h5>Quantity: </h5>  \
            </div> <div class="col-md-2"> \
              <input type="number" class="form-control" name="procedure_item_qty[]" min="1" max="100" value="1" required/> \
            </div> \
            <div class="col-md-2"> \
              <button class="btn btn-default btn-md btn_remove_update_item"><span class="glyphicon glyphicon-minus"></span></button> \
            </div>\
          </div>';
          $add_field.append(added_field);
        });

        $(document).on("click", ".btn_remove_update_item", function() {
          var $div = $(this).parent().parent();
          $div.remove();
        });

        $(document).on("click", ".procedure_update", function() {
          var procedure_id = $(this).attr('id');
          var path = "./../views/admin/treatmentsettings.php";
          $.ajax({
            url: "./../../controller/modal_views.php",
            method: "post",
            data: {procedure_id:procedure_id,
                    path:path,
                    procedure_view_update: 0},
            success: function(data) {
              $('#procedure_info').html(data);
              $('#update_delete_procedure').modal('show')
            }
          })
        });

        $(document).on("click", ".procedure_delete", function() {
          var procedure_id = $(this).attr('id');
          var path = "./../views/admin/treatmentsettings.php";
          $.ajax({
            url: "./../../controller/modal_views.php",
            method: "post",
            data: {procedure_id:procedure_id,
                    path:path,
                    procedure_view_delete: 1},
            success: function(data) {
              $('#procedure_info').html(data);
              $('#update_delete_procedure').modal('show')
            }
          })
        });

    });

    </script>

  </body>

</html>
