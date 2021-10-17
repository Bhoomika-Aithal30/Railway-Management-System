<?php
	require_once "pdo.php";
	
	session_start();
	
	if(isset($_SESSION['status']) and $_SESSION['status'] == 1) {
		$_SESSION['allow'] = 1;
	}
	else {
		header("Location: index.php");
		return;
	}
	
	if(isset($_POST['book-ticket'])) {
		header("Location: station.php");
		return;
	}
	
	if(isset($_POST['logout'])) {
		session_destroy();
		header("Location: index.php");
		return;
	}

	if(isset($_POST['delete'])) {
		$_del_passenger_id = $_POST['delete'];
		
		$sql = "DELETE FROM PASSENGER WHERE passenger_id = :passenger_id";
		$stmt = $pdo->prepare($sql);
		$stmt->execute(array(
			':passenger_id' => $_del_passenger_id,
		));

		$_SESSION['success'] = "Ticket has been cancelled!";
		header("Location: index1.php");
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
	<body class = "body_background">
		<div class="container text-center my-5 text-white">
			<h1>Welcome to the Railway Management System</h1>
		</div>
		<div class="container">
			<div class="text-center text-white">
				<?php
					if($_SESSION['allow'] == 1) {
						$sql = "SELECT * FROM LOGIN_USER WHERE mail_id = :mail_id";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(
							':mail_id' => $_SESSION['mail_id'],
						));
						$result = $stmt->fetch(PDO::FETCH_ASSOC);

						$_SESSION['user_id'] = $result['user_id'];

						echo("<div>");
							echo "<h2>Welcome ".$result['fname'].".</h2>";
						echo("</div>");
					}
				?>
			</div>

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

			<br>

			<?php
				if($_SESSION['allow'] == 1) {
					$sql = "SELECT * FROM display_table where user_id = :user_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':user_id' => $_SESSION['user_id'],
					));

					if($stmt->rowcount()==0) {
						echo("<div class=\"text-center text-white\">");
						echo('<p>No tickets booked</p>');
						echo("</div>");
					}
					else {
						echo("<table class=\"table table-striped table-hover table-bordered table-dark\">");
							echo("<thead class=\"thead-light\">");
								echo("<tr class=\"text-center\">");
									echo("<th scope=\"col\">Sl.no</th>");
									echo("<th scope=\"col\">Train name</th>");
									echo("<th scope=\"col\">Boarding</th>");
									echo("<th scope=\"col\">Destination</th>");
									echo("<th scope=\"col\">Fare</th>");
									echo("<th scope=\"col\">Action</th>");
								echo("</tr>");
							echo("</thead>");
							echo("<tbody class=\"text-center\">");

							$i = 1;
							while ( $row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
								echo("<tr>");
									echo("<th scope=\"row\">");
										echo("$i");
									echo("</th>");
									echo("<td>");
										echo(htmlentities($row['name']));
									echo("</td>");
									echo("<td>");
										echo(htmlentities($row['BOARDING_STATION']));
									echo("</td>");
									echo("<td>");
										echo(htmlentities($row['DESTINATION_STATION']));
									echo("</td>");
									echo("<td>");
										echo(htmlentities($row['price']));
									echo("</td>");
									echo("<td>");
										echo("<form method=\"post\"><button type=\"submit\" class=\"btn btn-danger btn-block rounded-pill\" name=\"delete\" value=\"".$row['passenger_id']."\">Delete</button></form>");
									echo("</td>");
								echo("</tr>");
								$i++;
							}
							echo("</tbody>");
						echo("</table>");
					}
				}
			?>
			<form method="post">
				<div class="form-row">
					<div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-6">
						<button type="submit" class="btn btn-warning btn-block rounded-pill" name="book-ticket" value="Book Ticket">Book Ticket</button>
					</div>
					<div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
				</div>
								
				<br>
				
				<div class="form-row">
					<div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
					<div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-6">
						<button type="submit" class="btn btn-secondary btn-block rounded-pill" name="logout" value="Log Out">Log Out</button>
					</div>
					<div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
				</div>
			</form>
		</div>
	</body>
</html>