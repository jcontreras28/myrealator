<?php

	include './db-connect.class.php';

        $conn = new MyRealator_DB;

	if (!empty($_POST)){
		if (!empty($_POST['mls'])){
			$query = "SELECT * FROM Properties WHERE MlsId = ".$_POST['mls'];
		} else {
			$haveData = false;
			$query = "SELECT * FROM Properties WHERE ";
			if (!empty($_POST['city'])){ 
				$query .= "City = '".$_POST['city']."'";
				$haveData = true;
			}
			if (!empty($_POST['state'])) {
				if ($haveData)
					$query .= " AND ";
				$query .= "State = '".$_POST['state']."'";
				$haveData = true;
			}
			if (!empty($_POST['zip'])) {
                                if ($haveData)
                                        $query .= " AND ";
                                $query .= "Zip = ".$_POST['zip'];
                                $haveData = true;
                        }
			if (!empty($_POST['bedrooms'])) {
                                if ($haveData)
                                        $query .= " AND ";
                                $query .= "Bedrooms = ".$_POST['bedrooms'];
                                $haveData = true;
                        }
			if (!empty($_POST['bathrooms'])) {
                                if ($haveData)
                                        $query .= " AND ";
                                $query .= "Bathrooms = '".$_POST['bathrooms']."'";
                                $haveData = true;
                        }
			if (!empty($_POST['squareFeet'])) {
                                if ($haveData)
                                        $query .= " AND ";
                                $query .= "SquareFeet = '".$_POST['squareFeet']."'";
                                $haveData = true;
                        }
			if (!$haveData)
				$query = "SELECT * FROM Properties";
		}
	} else { 
        	$query = "SELECT * FROM Properties";
	}
        $props = $conn->select($query);


?>

<!doctype html>

<html lang="en">
	<head>
		<meta charset="utf-8">
		<title>Janelle contreras realator</title>

		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="icon" type="image/x-icon" href="favicon.ico">

		<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css" />
		<link rel="stylesheet" type="text/css" href="https://ajax.googleapis.com/ajax/libs/jqueryui/1.10.4/themes/smoothness/jquery-ui.css" />
		<link rel="stylesheet" href="./assets/css/style.css" />

		<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.0.3/jquery.min.js"></script>
		<script src="//maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js" integrity="sha384-Tc5IQib027qvyjSMfHjOMaLkfuWVxZxUPnCJA7l2mCWNIpG9mGCD8wGNIcPD7Txa" crossorigin="anonymous"></script>
	</head>
	<body>
		<div class='container'>
			<div class='searchForm'>
				 <form action="publicView.php" method="POST">
					<div><h3>Search By Mls Number OR by house traits</h3></div>
  					Mls #: <br>
  					<input type="number" name="mls" value=""><br>
					<div><h4>OR</h4></div>
  					City: <input type="text" id="city" name="city" value=""> State: <input type="text" id="state" name="state" value=""> 
					Zip: <input type="number" id="zip" name="zip" value=""><br>
					Bedrooms: <input type="number" id="bedrooms" name="bedrooms" value=""> Bathrooms: <input type="number" id="bathrooms" name="bathrooms" value=""> 
					Square Feet: <input type="number" id="squareFeet" name="squareFeet" value=""><br>
  					<input type="submit" value="Search">
				</form> 
				
			</div>

			<div class='listings'>
				<ul>
					<?php
					foreach($props as $prop) {
						echo "<li><div>";
						echo "Mls: ".$prop['MlsId']." Street: ".$prop['Street1']." ".$prop['Street2']." ".$prop['City'].", ".$prop['State']." ".$prop['Zip'];
						echo "</div><div>";
						echo "Neighboorhood: ".$prop['Neighborhood']." SalesPrice: ".$prop['SalesPrice']." DateListed: ".$prop['DateListed'];
						echo "</div><div>";
						echo "Bedrooms: ".$prop['Bedrooms']." Bathrooms: ".$prop['Bathrooms']." GarageSize: ".$prop['GarageSize']." SquareFeet: ";
						echo $prop['SquareFeet']." LotSize ".$prop['LotSize']." Number of photos: ".$prop['Photos'];
						echo "</div>";

						if ($prop['Photos'] > 0) {
							echo "<div>";
							$link = "http://www.".$_SERVER[HTTP_HOST];
							for($i=0; $i < $prop['Photos']; $i++) {
                                				echo "<img class='photoClass' width='200px' src='".$link."/myrealator/propertyImages/".$prop["MlsId"]."_".$i.".jpg'/>";
							}
							echo "</div>";
						}
						echo "</li>";
					}
					?>
				</ul>
				<?php if (count($props) < 1) echo "There were no listings found, please try a different search."; ?>
				<form action="publicView.php">
					<input type="submit" value="Show all Listings">
				</form>
			</div>
		</div>
	</body>
</html>












