<?php
  session_start();
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
              <li><a href="./"><span class="glyphicon glyphicon-stats" aria-hidden="true"></span>&nbsp Dashboard <span class="sr-only">(current)</span> </a></li>
              <li><a style="color: #000000;background-color: #b7d7f0;"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp User Account <span class="sr-only">(current)</span></a></li>
            </ul>
          </div>

          <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="col-sm-12 col-md-12">
              <div class="mn-dashboard-header">
                <h3>User Account Details</h3>
                <hr>
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#mn-admin-panel" data-toggle="tab">My Account</a></li>
                  <li><a href="#mn-registered-user-panel" data-toggle="tab">Registered User</a></li>
                  <li><a href="#mn-pending-user-panel" data-toggle="tab">Pending User</a></li>
                  <li><a href="#mn-deleted-user-panel" data-toggle="tab">Deleted User</a></li>
                </ul>
              </div>

              <div class="tab-content">
                <div class="tab-pane active" id="mn-admin-panel">
                  <div class="panel panel-primary">
                    <?php
                      require('./../../controller/db_connect.php');
                      $preparedStmt = "SELECT * FROM `tbl_user` WHERE `user_username`= :user_username";
                      $stmt = $connection->prepare($preparedStmt);
                      $stmt->bindParam(':user_username', $_SESSION['user_username']);
                      $stmt->execute();
                      $row = $stmt->fetch(PDO::FETCH_ASSOC);?>
                    <div class="panel-heading" id="mn-admin-panel">
                      <h3 class="panel-title"><?php echo $row['user_name']; ?></h3>
                    </div>
                    <div class="panel-body">
                      <div class="row-fluid">
                        <div class="span6">
                          <table class="table table-condensed table-responsive">
                            <thead>
                              <tr>
                                <th>User Level:</th>
                                <th><?php echo $row['user_account_type']; ?></th>
                              </tr>
                            </thead>
                            <tbody>
                              <tr>
                                <td>Gender:</td>
                                <td><?php echo $row['user_gender']; ?></td>
                              </tr>
                              <tr>
                                <td>Date of Birth:</td>
                                <td><?php echo $row['user_date_of_birth']; ?></td>
                              </tr>
                              <tr>
                                <td>Address:</td>
                                <td><?php echo $row['user_address']; ?></td>
                              </tr>
                              <tr>
                                <td>Contact Number:</td>
                                <td><?php echo $row['user_contact']; ?></td>
                              </tr>
                              <tr>
                                <td>Username</td>
                                <td><?php echo $row['user_username']; ?></td>
                              </tr>
                              <tr>
                                <td>Password</td>
                                <td><?php echo $row['user_password']; ?></td>
                              </tr>
                            </tbody>
                          </table>
                        </div>
                      </div>
                      <input type="submit" class="btn btn-primary btn-md pull-right user_update" value="Update" id="<?php echo $row['user_id']; ?>" name="user_update" /></button>
                    </div>
                  </div>
                </div>

                <div class="tab-pane" id="mn-registered-user-panel" style="overflow-y:auto; height:400px;">
                  <!--well-->
                  <div class="well col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <?php
                    require('./../../controller/db_connect.php');
                    $preparedStmt = "SELECT * FROM `tbl_user` WHERE `user_status`='1' AND `user_account_type` != 'admin 2' ";
                    $stmt = $connection->prepare($preparedStmt);
                    $stmt->execute();
                    $counter = 0;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                    {
                  ?>
                    <div class="row user-row">
                      <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
                          <strong><?php echo $row['user_name']; ?></strong><br>
                          <span class="text-muted">User Level: <?php echo $row['user_account_type']; ?></span>
                      </div>
                      <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 dropdown-user" data-for=".mn-user<?php echo $counter;?>">
                          <i class="glyphicon glyphicon-chevron-down text-muted"></i>
                      </div>
                    </div>
                    <div class="row user-info mn-user<?php echo $counter;?>">
                      <div class="panel panel-primary">
                        <div class="panel-heading" id="mn-admin-panel">
                          <h3 class="panel-title"><?php echo $row['user_name']; ?></h3>
                        </div>
                      <div class="panel-body">
                        <div class="row-fluid">
                          <div>
                              <table class="table table-condensed table-responsive">
                                <thead>
                                   <tr>
                                      <th>User Level:</th>
                                      <th><?php echo $row['user_account_type']; ?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Gender:</td>
                                    <td><?php echo $row['user_gender']; ?></td>
                                  </tr>
                                  <tr>
                                    <td>Date of Birth:</td>
                                    <td><?php echo $row['user_date_of_birth']; ?></td>
                                  </tr>
                                  <tr>
                                      <td>Address:</td>
                                      <td><?php echo $row['user_address']; ?></td>
                                  </tr>
                                  <tr>
                                      <td>Contact Number:</td>
                                      <td><?php echo $row['user_contact']; ?></td>
                                  </tr>
                                  <tr>
                                      <td>Username</td>
                                      <td><?php echo $row['user_username']; ?></td>
                                  </tr>
                                  <tr>
                                      <td>Password</td>
                                      <td><?php echo $row['user_password']; ?></td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 pull-right">
                              <input type="submit" class="btn btn-primary btn-md pull-right user_update" value="Update" id="<?php echo $row['user_id']; ?>" name="user_update" />
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 pull-right">
                              <input type="submit" class="btn btn-danger btn-md pull-right user_delete" value="Delete" id="<?php echo $row['user_id']; ?>" name="user_delete" />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                      $counter++;
                      }
                    ?>
                  </div> <!--well-->
                  <button class="btn btn-primary btn-md pull-right" data-toggle="modal" data-target="#open_user">Add user</button>
                </div>

                <div class="tab-pane" id="mn-pending-user-panel" style="overflow-y:auto; height:400px;">
                  <div class="well col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <?php
                    require('./../../controller/db_connect.php');
                    $preparedStmt = "SELECT * FROM `tbl_user` WHERE `user_status`='2'";
                    $stmt = $connection->prepare($preparedStmt);
                    $stmt->execute();
                    $counter = 0;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                    {
                  ?>
                    <div class="row user-row">
                      <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
                          <strong><?php echo $row['user_name']; ?></strong><br>
                          <span class="text-muted">User Level: <?php echo $row['user_account_type']; ?></span>
                      </div>
                      <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 dropdown-user" data-for=".mn-user<?php echo $counter;?>">
                          <i class="glyphicon glyphicon-chevron-down text-muted"></i>
                      </div>
                    </div>
                    <div class="row user-info mn-user<?php echo $counter;?>">
                      <div class="panel panel-primary">
                        <div class="panel-heading" id="mn-admin-panel">
                          <h3 class="panel-title"><?php echo $row['user_name']; ?></h3>
                        </div>
                      <div class="panel-body">
                        <div class="row-fluid">
                          <div>
                              <table class="table table-condensed table-responsive">
                                <thead>
                                   <tr>
                                      <th>User Level:</th>
                                      <th><?php echo $row['user_account_type']; ?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Gender:</td>
                                    <td><?php echo $row['user_gender']; ?></td>
                                  </tr>
                                  <tr>
                                    <td>Date of Birth:</td>
                                    <td><?php echo $row['user_date_of_birth']; ?></td>
                                  </tr>
                                  <tr>
                                      <td>Address:</td>
                                      <td><?php echo $row['user_address']; ?></td>
                                  </tr>
                                  <tr>
                                      <td>Contact Number:</td>
                                      <td><?php echo $row['user_contact']; ?></td>
                                  </tr>
                                  <tr>
                                      <td>Username</td>
                                      <td><?php echo $row['user_username']; ?></td>
                                  </tr>
                                  <tr>
                                      <td>Password</td>
                                      <td><?php echo $row['user_password']; ?></td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 pull-right">
                              <input type="submit" class="btn btn-success btn-md pull-right user_activate" value="Activate" id="<?php echo $row['user_id']; ?>" name="user_activate" />
                            </div>
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 pull-right">
                              <input type="submit" class="btn btn-danger btn-md pull-right user_delete" value="Delete" id="<?php echo $row['user_id']; ?>" name="user_delete" />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                      $counter++;
                      }
                    ?>
                  </div> <!--well-->
                  <button class="btn btn-primary btn-md pull-right" data-toggle="modal" data-target="#open_user">Add</button>
                </div>
                <div class="tab-pane" id="mn-deleted-user-panel" style="overflow-y:auto; height:400px;">
                  <div class="well col-xs-12 col-sm-12 col-md-12 col-lg-12">
                  <?php
                    require('./../../controller/db_connect.php');
                    $preparedStmt = "SELECT * FROM `tbl_user` WHERE `user_status`='3'";
                    $stmt = $connection->prepare($preparedStmt);
                    $stmt->execute();
                    $counter = 0;
                    while ($row = $stmt->fetch(PDO::FETCH_ASSOC))
                    {
                  ?>
                    <div class="row user-row">
                      <div class="col-xs-11 col-sm-11 col-md-11 col-lg-11">
                          <strong><?php echo $row['user_name']; ?></strong><br>
                          <span class="text-muted">User Level: <?php echo $row['user_account_type']; ?></span>
                      </div>
                      <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 dropdown-user" data-for=".mn-user<?php echo $counter;?>">
                          <i class="glyphicon glyphicon-chevron-down text-muted"></i>
                      </div>
                    </div>
                    <div class="row user-info mn-user<?php echo $counter;?>">
                      <div class="panel panel-primary">
                        <div class="panel-heading" id="mn-admin-panel">
                          <h3 class="panel-title"><?php echo $row['user_name']; ?></h3>
                        </div>
                      <div class="panel-body">
                        <div class="row-fluid">
                          <div>
                              <table class="table table-condensed table-responsive">
                                <thead>
                                   <tr>
                                      <th>User Level:</th>
                                      <th><?php echo $row['user_account_type']; ?></th>
                                  </tr>
                                </thead>
                                <tbody>
                                  <tr>
                                    <td>Gender:</td>
                                    <td><?php echo $row['user_gender']; ?></td>
                                  </tr>
                                  <tr>
                                    <td>Date of Birth:</td>
                                    <td><?php echo $row['user_date_of_birth']; ?></td>
                                  </tr>
                                  <tr>
                                      <td>Address:</td>
                                      <td><?php echo $row['user_address']; ?></td>
                                  </tr>
                                  <tr>
                                      <td>Contact Number:</td>
                                      <td><?php echo $row['user_contact']; ?></td>
                                  </tr>
                                  <tr>
                                      <td>Username</td>
                                      <td><?php echo $row['user_username']; ?></td>
                                  </tr>
                                  <tr>
                                      <td>Password</td>
                                      <td><?php echo $row['user_password']; ?></td>
                                  </tr>
                                </tbody>
                              </table>
                            </div>
                          </div>
                          <div class="row">
                            <div class="col-xs-1 col-sm-1 col-md-1 col-lg-1 pull-right">
                              <input type="submit" class="btn btn-success btn-md pull-right user_activate" value="Activate" id="<?php echo $row['user_id']; ?>" name="user_activate" />
                            </div>
                          </div>
                        </div>
                      </div>
                    </div>
                    <?php
                      $counter++;
                      }
                    ?>
                  </div> <!--well-->
                  <button class="btn btn-primary btn-md pull-right" data-toggle="modal" data-target="#open_user">Add User</button>
                </div>
              </div>
            </div>
          </div> <!--main-->
        </div>
      </div>

    </main>

    <!--  Modal  -->
    <div id="update_delete_user" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!--  Modal content -->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">User Details</h4>
          </div>
          <div class="modal-body" id="user_info">
          </div>
          <div class="modal-footer">
            <button type="button" class="btn btn-default" data-dismiss="modal">Cancel</button>
          </div>
        </div>

      </div>
    </div>

    <!-- Modal -->
    <div id="open_user" class="modal fade" role="dialog">
      <div class="modal-dialog">

        <!-- Modal content-->
        <div class="modal-content">
          <div class="modal-header">
            <button type="button" class="close" data-dismiss="modal">&times;</button>
            <h4 class="modal-title">User Information</h4>
          </div>
          <div class="modal-body">
            <div class="container-fluid">
              <form class="form-horizontal" role="form" method="post" action="./../../controller/transactions.php">

                <div class="form-group">
                  <div class="col-md-12">
                    <input type="hidden" value="0" name="user_add" id="user_add">
                    <input type="hidden" value="./../views/admin/account.php" name="path" id="path">
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                      <p>User's Name:</p>
                    </div>
                  </div>
                  <div class="row">
                    <div class="col-md-10 col-md-offset-1">
                      <input type="text" class="form-control" placeholder="Last Name, First Name Middle Name" name="user_name" id="user_name" required/>
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
                        <option selected disabled>Choose account role</option>
                        <option value="Accounting">Accounting</option>
                        <option value="user">User</option>
                        <option value="admin">Admin</option>
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
                        <input type="radio" value="Female" name="user_gender" id="user_gender" required/>Female
                      </label>
                    </div>
                    <div class="col-md-1 col-md-offset-1">
                      <label class="radio-inline">
                        <input type="radio" value="Male" name="user_gender" id="user_gender" required/>Male
                      </label>
                    </div>
                    <div class="col-md-6 col-md-4 col-md-offset-3">
                      <input type="date" class="form-control" name="user_date_of_birth" id="user_date_of_birth" required/>
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
                      <input type="text" class="form-control" placeholder="Address" name="user_address" id="user_address" required/>
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
                      <input type="text" class="form-control" placeholder="Username" name="user_username" id="user_username" required/>
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
                      <input type="password" class="form-control" placeholder="Password" name="user_password" id="user_password" required/>
                    </div>
                  </div>
                </div>

                <div class="form-group">
                  <div class="row">
                    <div class="col-md-4 col-md-offset-7">
                      <input type="submit" class="btn btn-default pull-right" id="user_add_btn" name="user_add_btn" value="Add"/>
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
    <footer>

      <div class="container-fluid">
        <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2">
          <p class="text-center">© 2018 Makati PET/CT Center</p>
        </div>
      </div>

    </footer>

    <style type="text/css">

    .user-row {
      margin-bottom: 14px;
    }

    .user-row:last-child {
      margin-bottom: 0;
    }

      .dropdown-user {
          margin: 13px 0;
          padding: 5px;
          height: 100%;
      }

      .dropdown-user:hover {
          cursor: pointer;
      }
    </style>

    <!--Script-->

    <script type="text/javascript">
      var path = "./../views/admin/account.php";

      $(document).ready(function(){

        $(".user_update").click(function() {
          $('#update_delete_user').modal('show')
          var user_id = $(this).attr('id');

          $.ajax({
            url: "./../../controller/modal_views.php",
            method: "post",
            data: { user_id:user_id,
                    user_view_update: 0,
                    path:path},
            success: function(data) {
              $('#user_info').html(data);
              $('#update_delete_user').modal('show')
            }
          })
        });

        $(".user_delete").click(function() {
          $('#update_delete_user').modal('show')
          var user_id = $(this).attr('id');
          $.ajax({
            url: "./../../controller/modal_views.php",
            method: "post",
            data: { user_id:user_id,
                    user_view_delete: 1,
                    path:path},
            success: function(data) {
              $('#user_info').html(data);
              $('#update_delete_user').modal('show')
            }
          })
        });

        $(".user_activate").click(function() {
          $('#update_delete_user').modal('show')
          var user_id = $(this).attr('id');
          $.ajax({
            url: "./../../controller/modal_views.php",
            method: "post",
            data: { user_id:user_id,
                    user_view_activate: 1,
                    path:path},
            success: function(data) {
              $('#user_info').html(data);
              $('#update_delete_user').modal('show')
            }
          })
        });

        var panels = $('.user-info');
        var panelsButton = $('.dropdown-user');
        panels.hide();
        //Click dropdown
        panelsButton.click(function() {
          //get data-for attribute
          var dataFor = $(this).attr('data-for');
          var idFor = $(dataFor);
          //current button
          var currentButton = $(this);
          idFor.slideToggle(400, function() {
              //Completed slidetoggle
              if(idFor.is(':visible'))
                currentButton.html('<i class="glyphicon glyphicon-chevron-up text-muted"></i>');
              else
                currentButton.html('<i class="glyphicon glyphicon-chevron-down text-muted"></i>');
          })
        });
      });
    </script>
  </body>
</html>
