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
	
	if(isset($_SESSION['src']) and $_SESSION['dst'] == 1) {
		unset($_SESSION['src']);
		unset($_SESSION['dst']);
	}
	
	if(isset($_POST['search'])) {
		if($_POST['src'] == '-' || $_POST['dst'] == '-') {
			$_SESSION['failure'] = "Select both the fields.";
            header("Location: station.php");
			return;
		}
		else {
			if($_POST['src'] == $_POST['dst']) {
				$_SESSION['failure'] = "Select different Boarding and Destination point.";
				header("Location: station.php");
				return;
			}
			else {
				$_SESSION['src'] = $_POST['src'];
				$_SESSION['dst'] = $_POST['dst'];
				header("Location: train.php");
				return;
			}
		}
	}

    if(isset($_POST['cancel'])) {
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
				    $src_name_array = array();
				    $src_id_array = array();
				    
                    $stmt = $pdo->query("SELECT * FROM STATION");
                    while($row = $stmt->fetch(PDO::FETCH_ASSOC) ) {
                        array_push($src_id_array, $row['station_id']);
                        array_push($src_name_array, $row['name']);
                    }
                }
            ?>

            <form method="post">
                <div class="form-row">
                    <div class="col-xl-4 col-lg-4 col-md-2 col-sm-2 col-2"></div>
                    <div class="col-xl-4 col-lg-4 col-md-8 col-sm-8 col-8">
                        <div class="form-group">
                            <label>Select Boarding Point:</label>
                            <select type="select" class="form-control" name="src">
                                <option value="-">Select...</option>
                                <?php
                                    for ($i = 0; $i < count($src_id_array); $i++) {
                                        echo("<option value=\"".$src_id_array[$i]."\">".$src_name_array[$i]."</option>");
                                    }
                                ?>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label>Select Destination Point:</label>
                            <select type="select" class="form-control" name="dst">
                                <option value="-">Select...</option>
                                <?php
                                    for ($i = 0; $i < count($src_id_array); $i++) {
                                        echo("<option value=\"".$src_id_array[$i]."\">".$src_name_array[$i]."</option>");
                                    }
                                ?>
                            </select>
                        </div>
                        <div class="col-xl-4 col-lg-4 col-md-2 col-sm-2 col-2"></div>
                    </div>
                </div>

                <div class="form-row">
                    <div class="col-xl-5 col-lg-5 col-md-3 col-sm-3 col-3"></div>
                    <div class="col-xl-2 col-lg-2 col-md-6 col-sm-6 col-6">
                        <button type="submit" class="btn btn-warning btn-block rounded-pill" name="search" value="search-trains">Search Trains</button>
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