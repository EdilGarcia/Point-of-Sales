<?php
	if(isset($_POST['login_user']))
	{
		require("controller/db_connect.php");
		$preparedStmt = "SELECT * FROM `tbl_user` WHERE user_username='".$_POST['username']."' AND user_password='".$_POST['password']."'";
		$stmt = $connection->prepare($preparedStmt);
		$stmt->execute();
		$row = $stmt->fetch(PDO::FETCH_ASSOC);

		if (isset($row['user_id'])) {
			if($row['user_status'] == "1")
			{
				ob_start();
				session_start();
				$_SESSION['user_name'] = $row['user_name'];
				$_SESSION['user_username'] = $row['user_username'];
				$_SESSION['user_id'] = $row['user_id'];
				$_SESSION['user_account_type'] = $row['user_account_type'];

				if($_SESSION['user_account_type'] == "admin 2" || $_SESSION['user_account_type'] == "admin")
					header("Location: views/admin/");
				else if($_SESSION['user_account_type'] == "user")
					header("Location: views/user/");
				else if($_SESSION['user_account_type'] == "accounting")
					header("Location: views/accounting/");
			}
			else
				echo("<script>alert('Account not Activated');</script>");

		}
		else
			echo("<script>alert('Wrong Username or Password');</script>");
	}

	if(isset($_GET['message']))
	{
		$message = $_GET['message'];
	}
	else
	{
		$message = "null";
	}
?>

<!DOCTYPE html>

<html lang = "en">

  	<head>
	    <title>
	      Makati PET/CT Center
	    </title>

	    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
	    <link rel="stylesheet" type="text/css" href="https://netdna.bootstrapcdn.com/font-awesome/4.0.3/css/font-awesome.min.css">
	<!--     <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/mdbootstrap/4.5.0/css/mdb.css"/> -->
	    <link rel="stylesheet" type="text/css" href="./css/login.css">

	    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
	    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
  	</head>

  	<body onload="parse_message(<?php echo($message); ?>)">
	    <header>
		      <!--Navigation-->
		     <nav class="navbar navbar-default navbar-fixed-top" role="navigation">
		        <div class="container-fluid">
							<div class="row">
			          <div class="col-md-5 col-md-offset-7">
			            <form role="form" action="index.php" method="post">
			              <div class="form-group">
			                <input type="hidden" name="login_user" value="0">
			              </div>
			              <div class="form-group">
							<div class="row">
				                <div class="col-md-5">
				                  <label for="email_address"> Username: </label>
				                  <input type="text" class="form-control" id="email_address" name="username" required>
				                </div>
				                <div class="col-md-5">
				                  <label for="password"> Password: </label>
				                  <input type="password" class="form-control" id="password" name="password" required>
				                </div>
				                <div class="col-md-2">
				                  <button id="hd-btn-login" class="btn btn-md btn-primary" type="submit">Log In</button>
				                </div>
							</div>
							<div class="row">
								<div class="col-md-5 register-account">
									<a href="#" id="register_account" data-toggle="modal" data-target="#open_user">Create an account</a>
								</div>
							</div>
			              </div>
			            </form>
								</div>
		          </div>
		        </div>
		     </nav>
	    </header>

			<!-- Modal -->
	    <div id="open_user" class="modal fade" role="dialog">
	      <div class="modal-dialog">
	        <!-- Modal content-->
	        <div class="modal-content">
	          <div id="modal-header" class="modal-header">
	            <button type="button" class="close" data-dismiss="modal">&times;</button>
	            <h3 class="modal-title">Account Registration</h3>
	          </div>

	          <div id="modal-body" class="modal-body">
	            <div class="container-fluid">
	              <form class="form-horizontal" role="form" method="post" action="./controller/transactions.php">

	                <div class="form-group">
	                  	<div class="col-md-12">
	                    	<input type="hidden" value="0" name="user_add" id="user_add">
												<input type="hidden" value="./../" name="path">
												<input type="hidden" value="0" name="register_user">
	                  	</div>
	                </div>

	                <div class="form-group">
										<div class="row">
		                    <div class="col-md-10 col-md-offset-1">
		                    	<div class="col-md-3">
		                      		<label id="add_top_padding"> User Level </label>
		                      	</div>

		                      	<div class="col-md-9">
															<label class="radio-inline">
				                        <input type="radio" value="Admin" name="user_type" id="user_type_admin" required/>Admin
				                     </label>

				                    <label class="radio-inline">
				                        <input type="radio" value="Accountant" name="user_type" id="user_type_accountant" required/>Accountant
				                    </label>

				                    <label class="radio-inline">
				                        <input type="radio" value="User" name="user_type" id="user_type_user" required/>User
				                    </label>
		                      	</div>
		                    </div>
		                </div>
	                </div>

	                <div class="form-group">
						<div class="row">
		                    <div class="col-md-10 col-md-offset-1">
		                    	<div class="col-md-3">
		                      		<label id="add_top_padding"> Name </label>
		                      	</div>

		                      	<div class="col-md-9">
		                      		<input type="text" class="form-control" placeholder="First Name Middle Name Last Name" name="user_name" id="user_name" required/>
		                      	</div>
		                    </div>
		                </div>
	                </div>

	                <div class="form-group">
						<div class="row">
		                    <div class="col-md-10 col-md-offset-1">
		                    	<div class="col-md-6">
		                      		<label> Gender </label>
		                      	</div>

		                      	<div class="col-md-6">
		                      		<label> Birthday </label>
		                      	</div>
		                    </div>
		                </div>
	                </div>

	                <div class="form-group">
						<div class="row">
		                    <div class="col-md-10 col-md-offset-1">
		                    	<div class="col-md-6">
		                      		<label class="radio-inline">
				                        <input type="radio" value="Female" name="user_gender" id="user_gender_female" required/>Female
				                     </label>

				                    <label class="radio-inline">
				                        <input type="radio" value="Male" name="user_gender" id="user_gender_male" required/>Male
				                    </label>
		                      	</div>

		                      	<div class="col-md-6">
		                      		<input type="date" class="form-control" name="user_date_of_birth" id="user_date_of_birth" required/>
		                      	</div>
		                    </div>
		                </div>
	                </div>

	        	    <div class="form-group">
						<div class="row">
		                    <div class="col-md-10 col-md-offset-1">
		                    	<div class="col-md-3">
		                      		<label id="add_top_padding"> Address </label>
		                      	</div>

		                      	<div class="col-md-9">
		                      		<input type="text" class="form-control" placeholder="House No. Street, Brgy., District, City" name="user_address" id="user_address" required/>
		                      	</div>
		                    </div>
		                </div>
	                </div>

	                <div class="form-group">
						<div class="row">
		                    <div class="col-md-10 col-md-offset-1">
		                    	<div class="col-md-6">
		                      		<label> Username </label>
		                      	</div>

		                      	<div class="col-md-6">
		                      		<label> Password </label>
		                      	</div>
		                    </div>
		                </div>
	                </div>

	                <div class="form-group">
						<div class="row">
		                    <div class="col-md-10 col-md-offset-1">
		                    	<div class="col-md-6">
		                      		<input type="text" class="form-control" placeholder="username@email.com" name="user_username" id="user_username" required/>
		                      	</div>

		                      	<div class="col-md-6">
		                      		<input type="password" class="form-control" placeholder="Password123" name="user_password" id="user_password" required/>
		                      		<input type="checkbox" onclick="show_password()"> <label style="font-size: 12px;"> Show Password </label>
		                      	</div>
		                    </div>
		                </div>
	                </div>

	                <div class="form-group">
						<div class="row">
		                    <div class="col-md-10 col-md-offset-1">
		                      	<div class="col-md-6 col-md-offset-6">
		                      		<input type="submit"  class="btn btn-md btn-primary pull-right" id="user_add_btn" name="user_add_btn" value="Sign Up"/>
		                      	</div>
		                    </div>
		                </div>
	                </div>
	              </form>

	            </div>
	          </div>

	        </div>
	      </div>
	    </div>
	    <!--  Modal  -->

	    <!--Footer-->
	    <footer class="footer">
	      <div class="container-fluid">
	        <div class="col-md-12">
	        	<p class="text-center"><b> © <b id="year"></b> Makati PET/CT Center</b></p>
	        </div>
	      </div>
	    </footer>
	</body>

	<script type="text/javascript">

		$(document).ready(function(){

			var year = new Date();
		    var current_year = year.getFullYear();
		    document.getElementById("year").innerHTML = current_year;

		});

		function show_password() {
		    var password_field = document.getElementById("user_password");
		    if (password_field.type === "password") {
		        password_field.type = "text";
		    } else {
		        password_field.type = "password";
		    }
		}

		function parse_message(msg)
		{

			if(msg == "null")
				alert(msg);
			else if(msg == "1")
			{
				alert("Sorry! Username Already Taken! Please try again.");
				$('#user_name').val("<?php if(isset($_GET['user_name'])){echo($_GET['user_name']);}?>");
				$('#user_date_of_birth').val("<?php if(isset($_GET['user_date_of_birth'])){echo($_GET['user_date_of_birth']);}?>");
				$('#user_address').val("<?php if(isset($_GET['user_address'])){echo($_GET['user_address']);}?>");
				$('#user_username').val("<?php if(isset($_GET['user_username'])){echo($_GET['user_username']);}?>");
				$('#user_password').val("<?php if(isset($_GET['user_password'])){echo($_GET['user_password']);}?>");
				if("Female" == "<?php if(isset($_GET['user_gender'])){echo($_GET['user_gender']);}?>")
					$('#user_gender_female').prop("checked", true);
				else
					$('#user_gender_male').prop("checked", true);
				$('#user_account_type').val("<?php if(isset($_GET['user_account_type'])){echo($_GET['user_account_type']);}?>");
				$('#open_user').modal('show');
			}
			else if(msg == "2")
				alert("Account Registered! Awating for admin conirmation.");
		}
	</script>
</html>
