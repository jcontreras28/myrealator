<?php
include_once './db_connect.php';
include_once './functions.php';
 
sec_session_start();

$tp = "none";
?>
<!DOCTYPE html>
<html>
    	<head>
        	<meta charset="UTF-8">
        	<title>My realator</title>
        	<?php include('./head.php'); ?>
    	</head>
    	<body>
	    
        <?php if (login_check($mysqli) == true) : ?>

		<?php  // check to see if we are doing a delete or update action
			if (isset($_GET['mls'])) {
				$mls = $_GET['mls'];
				$tp = $_GET['tp'];
				if ($tp == 'delete'){
					deleteProperty($mls, $mysqli);
				}
				else if ($tp=='updateSave')
					updateProperty($mls, $mysqli);
			}

		?>

		<div class='container'>
			<div class="row" style="margin-top: 20px">
                	</div>
			<hr></hr>

			<ul class="nav nav-tabs">
				<?php
					if ($tp == "update") {
				?>
				<li>
					<a href="#listingTab" data-toggle="tab">Top listings</a>
				</li>
				<li>
					<a href="#createTab" data-toggle="tab">Create listing</a>
				</li>
				<li class="active">
					<a href="#udpadeTab" data-toggle="tab">Update listing</a>
				</li>
				<?php
					} else {
				?>
				<li class="active">
                                        <a href="#listingTab" data-toggle="tab">Top listings</a>
                                </li>
                                <li>
                                        <a href="#createTab" data-toggle="tab">Create listing</a>
                                </li>
				<?php } ?>

				
			</ul>

			<div class="tab-content">
				<?php 
					if ($tp == "update") {
				?>
				<div class="tab-pane fade in" id="listingTab">
                                        Top listings
                                        <?php include('./showlistings.php'); ?>
                                </div>
                                <div class="tab-pane fade in" id="createTab">
                                        Create new form
                                        <?php include('./createListing.php'); ?>
                                </div>
				<div class="tab-pane fade in active" id="createTab">
                                        Update form
                                        <?php include('./updateListing.php'); ?>
                                </div>

				<?php
				} else {
				?>
				<div class="tab-pane fade in active" id="listingTab">
					Top listings
					<?php include('./showlistings.php'); ?>
				</div>
				<div class="tab-pane fade in" id="createTab">
                                       	Create new form
					<?php include('./createListing.php'); ?>
				</div>

				<?php } ?>
			</div>
		</div>
        <?php endif; ?>
    	</body>
</html>
