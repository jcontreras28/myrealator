<?php
include_once './db_connect.php';
include_once './functions.php';

sec_session_start();

if (login_check($mysqli) == true) {

	include './db-connect.class.php';

        $conn = new MyRealator_DB;

    	$street1 = $_POST['street1'];

	$street2 = "";
	if (isset($_POST['street2']))
		$street2 = $_POST['street2'];
	$city = $_POST['city'];
	$state = $_POST['state'];
    	$zip = $_POST['zip'];
	$neighborhood = $_POST['neighborhood'];
    	$salesPrice = $_POST['salesPrice'];
    	$dateListed = date('Y-m-d');
    	$bedrooms = $_POST['bedrooms'];
    	$bathrooms = $_POST['bathrooms'];
    	$garageSize = $_POST['garageSize'];
    	$squareFeet = $_POST['zip'];
    	$lotSize = $_POST['lotSize'];
    	$description = $_POST['description'];


	if (isset($_FILES['files'])) {
		$myFile = $_FILES['files'];
                $photos = count($myFile["name"]);
        } else {
		$photos = 0;
        }

	$saveQuery = "INSERT INTO Properties (Street1, Street2, City, State, Zip, Neighborhood, SalesPrice, DateListed, Bedrooms, Photos, Bathrooms, GarageSize, SquareFeet, LotSize, Description)";
	$saveQuery .= "VALUES ('".$street1."', '".$street2."', '".$city."', '".$state."', ".$zip.", '".$neighborhood."', ".$salesPrice.", '".$dateListed."', ";
	$saveQuery .= $bedrooms.", ".$photos.", '".$bathrooms."', ".$garageSize.", ".$squareFeet.", ".$lotSize.", '".$description."'); ";
       
	echo "Query2: ".$saveQuery."<br>";
	$res = $conn->query($saveQuery);

	$getMls = "Select MAX(MlsId) from Properties";
	$res = $conn->select($getMls);

	
	$mlsId = $res[0]["MAX(MlsId)"];
	
	if (isset($_FILES['files'])) {
		var_dump($_FILES['files']);
                $myFile = $_FILES['files'];
                $fileCount = count($myFile["name"]);
                for ($i = 0; $i < $fileCount; $i++) {
                        $allowedExts = array("gif", "jpeg", "jpg", "png", "tif", "tiff");
                        $temp = explode(".", $myFile["name"][$i]);
                        $extension = strtolower(end($temp));
                        $fileType = strtolower($myFile["type"][$i]);
			$nm = $mlsId."_".$i;
                        // check to see if the file the user submitted is an accepted file type
                        if ((($fileType == "image/gif")
                                || ($fileType == "image/tiff")
                                || ($fileType == "image/jpeg")
                                || ($fileType == "image/jpg")
                                || ($fileType == "image/pjpeg")
                                || ($fileType == "image/x-png")
                                || ($fileType == "image/png")
                                || ($fileType == "application/octet-stream"))
                                && in_array($extension, $allowedExts))
                        {
                                //$mail->addAttachment($myFile["tmp_name"][$i], $myFile["name"][$i]);
				if(move_uploaded_file($myFile["tmp_name"][$i], "./propertyImages/" .$nm.".".$extension)); // save the photo on our server
                        }
                }
        }

?>
	<script type="text/javascript">
		var url = window.location.href;
                var url = url.replace("saveNewListing", "main");
		alert("redirecting to: "+url);
                window.location.replace(url);
	</script>
<?php
} else {

	echo "You are not authorized to access this page, please login.";


}
