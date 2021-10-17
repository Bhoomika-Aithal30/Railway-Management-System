<?php 
	require_once "pdo.php";
	
	session_start();
	
	if(isset($_SESSION['status']) and $_SESSION['status'] == 1) {
		header("Location: index1.php");
		return;
	}
	
	if(isset($_POST['login'])) {
		if(strlen($_POST['mail_id']) < 1 || strlen($_POST['pwd']) < 1) {
			$_SESSION['failure'] = "Please fill all the fields";
			header("Location: login.php");
			return;
		} 
		else if(strpos($_POST['mail_id'], "@") === false) {
			$_SESSION['failure'] = "Email must have an at-sign (@)";
			header("Location: login.php");
			return;
		}
		else if(strpos($_POST['mail_id'], ".") === false) {
			$_SESSION['failure'] = "Email must have a dot-sign (.)";
			header("Location: login.php");
			return;
		}
		else {
			$sql = "SELECT * FROM LOGIN_USER where mail_id = :mail_id";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':mail_id' => $_POST['mail_id'],
			));
			$result = $stmt->fetch(PDO::FETCH_ASSOC);
			
			if($result){
				if($result['pwd'] == $_POST['pwd']){
					$_SESSION['mail_id'] = $_POST['mail_id'];
					$_SESSION['status'] = 1;
					$_SESSION['success'] = "You have successfully logged-in!!";
					header("Location: index1.php");
					return;
				}
				else {
					$_SESSION['failure'] = "Password is incorrect!! Try again...";
					header("Location: login.php");
					return;
				}
			}
			else {
				$_SESSION['failure'] = "E-mail id is not found..Try with the other!!";
				header("Location: login.php");
				return;
			}
		}   
	}
	
	if(isset($_POST['cancel'])) {
		header('Location: index.php');
		return;
	}
?>

<html>
	<head>
		<title>Railway Management System Log In Page</title>
		<link rel="stylesheet" href="bootstrap.min.css">
		<link rel = "stylesheet" href = "style.css">
		<link rel="icon" href="logo.png" type = "image/x-icon">
	</head>
	
	<body class = "body_background">
		<div class="container text-center my-5 text-white">
			<h1>Please Log In</h1>
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
							<label>E-mail Id:</label>
							<input type="email" class="form-control" placeholder ="Enter email-id" name="mail_id">
						</div>
						
						<div class="form-group">
							<label>Password:</label>
							<input type="password" class="form-control" placeholder ="Enter password" name="pwd">
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-2 col-sm-2 col-2"></div>
				</div>
				
				<div class="form-row">
					<div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-6">
						<button type="submit" class="btn btn-warning btn-block rounded-pill" name="login" value="Log In">Log In</button>
					</div>
					<div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
				</div>

				<br>
				
				<div class="form-row">
					<div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-6">
						<button type="submit" class="btn btn-secondary btn-block rounded-pill" name="cancel" value="Cancel">Cancel</button>
					</div>
					<div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
				</div>
			</form>
		</div>

	</body>
</html>