<?php
  session_start();
  if(isset($_GET['message']))
		$message = $_GET['message'];
	else
		$message = "null";
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
  <body onload="parse_message(<?php echo($message); ?>)">
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
                  <li style="color: #000000;background-color: #b7d7f0;"><a href="account.php"><span class="glyphicon glyphicon-user" aria-hidden="true"></span>&nbsp My Account</a></li>
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

          <div class="col-sm-9 col-sm-offset-3 col-md-10 col-md-offset-2 main">
            <div class="col-sm-12 col-md-12">
              <div class="mn-dashboard-header">
                <h3>User Account Details</h3>
                <hr>
                <ul class="nav nav-tabs">
                  <li class="active"><a href="#mn-user-panel" data-toggle="tab">My Account</a></li>
                </ul>
              </div>

              <div class="tab-content">
                <div class="tab-pane active" id="mn-user-panel">
                  <div class="panel panel-primary">
                    <?php
                      require('./../../controller/db_connect.php');
                      $preparedStmt = "SELECT * FROM `tbl_user` WHERE `user_username`= :user_username";
                      $stmt = $connection->prepare($preparedStmt);
                      $stmt->bindParam(':user_username', $_SESSION['user_username']);
                      $stmt->execute();
                      $row = $stmt->fetch(PDO::FETCH_ASSOC);?>
                    <div class="panel-heading" id="mn-user-panel">
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
      var path = "./../views/user/account.php";

      $(document).ready(function(){

        $(".user_update").click(function() {
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
          });
        });
      });
    </script>
  </body>
</html>
