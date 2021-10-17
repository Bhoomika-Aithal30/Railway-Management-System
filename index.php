<?php
	require_once "pdo.php";

	session_start();
	
	if(isset($_SESSION['status']) and $_SESSION['status'] == 1) {
		header("Location: index1.php");
		return;
	}
	
	if(isset($_POST['login'])) {
		header("Location: login.php");
		return;
	}

	if (isset($_POST['signup'])) {
		header("Location: signup.php");
		return;
	}
?>

<html>
	<head>
		<title>Railway Management System Index Page</title>
		<link rel="stylesheet" href="bootstrap.min.css">
		<link rel = "stylesheet" href = "style.css">
		<link rel="icon" href="logo.png" type = "image/x-icon">
	</head>
	
	<body class="body_background">
		<div class="container text-center my-5 text-white">
			<h1>Welcome to the Railway Management System</h1>
		</div>
		
		<div class="container">
			<form method="post">
				<div class="form-row">
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-2 col-2"></div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-8 col-8">
						<button type="submit" class="btn btn-warning btn-block rounded-pill" name="login" value="Log In">Log In</button>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-2 col-2"></div>
				</div>

				<br>
				
				<div class="form-row">
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-2 col-2"></div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-8 col-8">
						<button type="submit" class="btn btn-secondary btn-block rounded-pill" name="signup" value="Sign Up">Sign Up</button>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-4 col-sm-2 col-2"></div>
				</div>
			</form>
		</div>
		
	</body>
</html>