<?php
	require_once "pdo.php";
	session_start();

	if(isset($_SESSION['status']) and $_SESSION['status'] == 1) {
		header("Location: index1.php");
		return;
	}
	
	if(isset($_POST['cancel']) ) {
		header('Location: index.php');
		return;
	}
	
	if(isset($_POST['signup'])) {
		if(strlen($_POST['mail_id']) < 1 || strlen($_POST['pwd']) < 1 || strlen($_POST['fname']) < 1 || strlen($_POST['lname']) < 1 || strlen($_POST['contact_number']) < 1)  {
			$_SESSION['failure'] = "Please fill all the fields";
			header("Location: signup.php");
			return;
		}
		else if(strpos($_POST['mail_id'], "@") === false) {
			$_SESSION['failure'] = "Email must have an at-sign (@)";
			header("Location: signup.php");
			return;
		}
		else if(strpos($_POST['mail_id'], ".")=== false) {
			$_SESSION['failure'] = "Email must have a dot-sign (.)";
			header("Location: signup.php");
			return;
		}
		else if(((strpos($_POST['mail_id'], ".", (strpos($_POST['mail_id'], ".") + 1))) && ((strpos($_POST['mail_id'], "@")) > (strpos($_POST['mail_id'], ".", (strpos($_POST['mail_id'], ".") + 1)))))) {
			$_SESSION['failure'] = "Invalid email address!";
			header("Location: signup.php");
			return;
		}
		else if(is_numeric($_POST['contact_number']) === false || strlen($_POST['contact_number']) != 10) {
			$_SESSION['failure'] = "Please enter the valid contact number!!";
			header("Location: signup.php");
			return;
		}
		else {
			$sql = "SELECT * FROM LOGIN_USER where mail_id = :mail_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':mail_id' => $_POST['mail_id'],));
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			$bdate = null;
			$gender = null;
			
			if($_POST['gender'] != 'Null') {
				$gender = $_POST['gender'];
			}

			if(strlen($_POST['bdate']) > 0) {
				$bdate = $_POST['bdate'];
			}

			if(!$result) {
				$sql = "INSERT INTO LOGIN_USER (fname, lname, bdate, gender, mail_id, pwd, contact_number) VALUES (:fname, :lname, :bdate, :gender, :mail_id, :pwd, :contact_number)";
				$stmt = $pdo->prepare($sql);
				$stmt->execute(array(
					':fname' => $_POST['fname'],
					':lname' => $_POST['lname'],
					':mail_id' => $_POST['mail_id'],
					':pwd' => $_POST['pwd'],
					':contact_number' => $_POST['contact_number'],
					':bdate' => $bdate,
					':gender' => $gender,));
				
				$_SESSION['mail_id'] = $_POST['mail_id'];
				$_SESSION['status'] = 1;
				$_SESSION['success'] = "Successfully signed up!!";
				header("Location: index1.php");
				return;
			}
			else {
				$_SESSION['failure']= "Account already exists!!";
				header("Location: signup.php");
				return;
			}
		}
	}
?>

<html>
	<head>
		<title>Railway Management System Sign Up Page</title>
		<link rel="stylesheet" href="bootstrap.min.css">
		<link rel = "stylesheet" href = "style.css">
		<link rel="icon" href="logo.png" type = "image/x-icon">
	</head>
	<body class = "body_background">
		<div class="container text-center my-5 text-white">
			<h1>Please Sign Up</h1>
		</div>

		<div class="container text-white">
			<div class="row">
				<div class="col-xl-2 col-lg-2 col-md-1"></div>
				<div class="col-xl-8 col-lg-8 col-md-10 col-sm-12 col-12">
					<?php
						if(isset($_SESSION['failure'])) {
							echo("<div class=\"isa_warning\">");
							echo("<p>".htmlentities($_SESSION['failure'])."</p>\n");
							echo("</div>");
							unset($_SESSION['failure']);
						}

						if(isset($_SESSION['success'])) {
							echo("<div class=\"isa_success\">");
							echo("<p>".htmlentities($_SESSION['success'])."</p>\n");
							echo("</div>");
							unset($_SESSION['success']);
						}

						echo("<br>");
					?>
				</div>
				<div class="col-xl-2 col-lg-2 col-md-1"></div>
			</div>
			
			<form method="post">
				<div class="form-row">
					<div class="col-xl-4 col-lg-4 col-md-2 col-sm-2 col-2"></div>
					<div class="col-xl-4 col-lg-4 col-md-8 col-sm-8 col-8">
						<div class="form-group">
							<label>First Name:</label>
							<input type="text" class="form-control" placeholder ="Enter first name" name="fname">
						</div>

						<div class="form-group">
							<label>Last Name:</label>
							<input type="text" class="form-control" placeholder ="Enter last name" name="lname">
						</div>

						<div class="form-group">
							<label>E-mail Id:</label>
							<input type="email" class="form-control" placeholder ="Enter email-id" name="mail_id">
						</div>

						<div class="form-group">
							<label>Password:</label>
							<input type="password" class="form-control" placeholder ="Enter password" name="pwd">
						</div>
						
						<div class="form-group">
							<label>Contact Number:</label>
							<input type="text" class="form-control" placeholder ="Enter contact number" name="contact_number">
						</div>
						
						<div class="form-group">
							<label>Date of Birth:</label>
							<input type="date" class="form-control" name="bdate">
						</div>
						
						<div class="form-group">  
							<label>Gender:</label>
							<select type="select" class="form-control" name="gender">
								<option value="Null">Select...</option>
								<option value="M">Male</option>
								<option value="F">Female</option>
							</select>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-2 col-sm-2 col-2"></div>
				</div>
				
				<div class="form-row">
					<div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-6">
						<button type="submit" class="btn btn-warning btn-block rounded-pill" name="signup" value="signup">Sign Up</button>
					</div>
					<div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
				</div>
				
				<br>
				
				<div class="form-row">
					<div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-6">
						<button type="submit" class="btn btn-secondary btn-block rounded-pill" name="cancel" value="cancel">Cancel</button>
					</div>
					<div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
				</div>
			</form>
		</div>
	</body>
</html>