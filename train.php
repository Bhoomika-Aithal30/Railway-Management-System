<?php
	require_once "pdo.php";
	
	session_start();
	
	if(isset($_SESSION['status']) and $_SESSION['status'] == 1) {
		if(isset($_SESSION['src']) and isset($_SESSION['dst'])) {
			$_SESSION['allow'] = 1;
		}
		else {
			header("Location: station.php");
			return;
		}
	}
	else {
		header("Location: index.php");
		return;
	}
	
	if(isset($_POST['book'])) {
		if($_POST['train'] == '-') {
			$_SESSION['failure'] = "Select the train.";
			header("Location: train.php");
			return;
		}
		else {
			$sql = "INSERT INTO PASSENGER (user_id, train_id, fare_id) VALUES (:user_id, :train_id, :fare_id)";
			$stmt = $pdo->prepare($sql);
			$stmt->execute(array(
				':user_id' => $_SESSION['user_id'],
				':train_id' => $_POST['train'],
				':fare_id' => $_POST['fare_id'],));
			
			$_SESSION['success'] = "Train Booked. Happy Journey";
			unset($_SESSION['src']);
			unset($_SESSION['dst']);
			header("Location: index1.php");
			return;
		}
	}
	
	if(isset($_POST['change'])) {
		unset($_SESSION['src']);
		unset($_SESSION['dst']);
		header("Location: station.php");
		return;
	}
	
	if(isset($_POST['cancel'])) {
		unset($_SESSION['src']);
		unset($_SESSION['dst']);
		header("Location: index1.php");
		return;
	}
?>

<html>
	<head>
		<title>Railway Management System Booking Page</title>
		<link rel="stylesheet" href="bootstrap.min.css">
		<link rel = "stylesheet" href = "style.css">
		<link rel="icon" href="logo.png" type = "image/x-icon">
	</head>
	<body class = "body_background">
		<div class="container text-center my-5 text-white">
			<h1>Railway Management System Booking Page</h1>
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
			
			<?php
				if($_SESSION['allow'] == 1) {
					$src_id = $_SESSION['src'];
					$dst_id = $_SESSION['dst'];
					
					$src_name = "";
					$dst_name = "";
					
					$sql = "SELECT * FROM STATION WHERE station_id = :src_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':src_id' => $src_id,
					));
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					$src_name = $result['name'];
					
					$sql = "SELECT * FROM STATION WHERE station_id = :dst_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':dst_id' => $dst_id,
					));
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
					$dst_name = $result['name'];
				}
			?>
			
			<form method="post">
                <div class="form-row">
                    <div class="col-xl-4 col-lg-4 col-md-2 col-sm-2 col-2"></div>
                    <div class="col-xl-4 col-lg-4 col-md-8 col-sm-8 col-8">
                    	<div class="form-group">
							<label>Boarding Point:</label>
							<?php
								echo("<p class=\"form-control\">".$src_name."</p>");
							?>
						</div>

						<div class="form-group">
							<label>Destination Point:</label>
							<?php
								echo("<p class=\"form-control\">".$dst_name."</p>");
							?>
						</div>
					</div>
					<div class="col-xl-4 col-lg-4 col-md-2 col-sm-2 col-2"></div>
				</div>

				<?php
					$sql = "SELECT V1.train_id from VISIT V1 WHERE V1.station_id = :src_id and V1.train_id in (SELECT V2.train_id from VISIT V2 WHERE V2.station_id = :dst_id);";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':src_id' => $src_id,
						':dst_id' => $dst_id,
					));
					
					$train_id_array = array();
					$train_name_array = array();
					$user_id_array = array();
					
					while($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
						array_push($train_id_array, $row['train_id']);
					}
					
					foreach($train_id_array as $t_id) {
						$sql = "SELECT * FROM TRAIN WHERE train_id = :train_id";
						$stmt = $pdo->prepare($sql);
						$stmt->execute(array(
							':train_id' => $t_id,
						));
						$result = $stmt->fetch(PDO::FETCH_ASSOC);
						array_push($train_name_array, $result['name']);
					}
					
					$sql = "SELECT * FROM FARE WHERE boarding = :src_id AND destination = :dst_id";
					$stmt = $pdo->prepare($sql);
					$stmt->execute(array(
						':src_id' => $src_id,
						':dst_id' => $dst_id,
					));
					$result = $stmt->fetch(PDO::FETCH_ASSOC);
						
					$ticket_fare = $result['price'];
					$fare_id = $result['id'];
				?>
				<div class="form-row">
                    <div class="col-xl-4 col-lg-4 col-md-2 col-sm-2 col-2"></div>
                    <div class="col-xl-4 col-lg-4 col-md-8 col-sm-8 col-8">
                    	<div class="form-group">
							<label>Select Train:</label>
							<select type="select" class="form-control" name="train">
								<option value="-">Select...</option>
								<?php
									for ($i = 0; $i < count($train_id_array); $i++) {
										echo("<option value=\"".$train_id_array[$i]."\">".$train_name_array[$i]."</option>");
									}
								?>
							</select>
						</div>

						<div class="form-group">
							<label>Ticket Fare:</label>
							<?php
								echo("<p class=\"form-control\">".$ticket_fare."</p>");
							?>
						</div>

						<?php
							echo("<input type=\"hidden\" name=\"fare_id\" value=\"".$fare_id."\">");
						?>
						
					</div>
					<div class="col-xl-4 col-lg-4 col-md-2 col-sm-2 col-2"></div>
				</div>

				<div class="form-row">
                    <div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-6">
                        <button type="submit" class="btn btn-warning btn-block rounded-pill" name="book" value="book-ticket">Book Ticket</button>
                    </div>
                    <div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
                </div>

                <br>

                <div class="form-row">
                	<div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-6">
                        <button type="submit" class="btn btn-primary btn-block rounded-pill" name="change" value="change-locations">Change Locations</button>
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