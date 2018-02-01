<?php
include './db-connect.class.php';

        $conn = new MyRealator_DB;

        $all_props = "SELECT * FROM Properties LIMIT 20";

        $props = $conn->select($all_props);

       /* echo "<pre>";
        echo print_r($props[0]);
        echo "</pre>";*/

?>

	<div class="tab-pane fade in active well">
		<ul>
		<?php
		foreach($props as $prop) {

			echo "<li class='list-group-item'>";
			echo "<form class='form-inline'>";
			echo "<div class='row'>";
			echo "<div class='col-xs-1'>".$prop["MlsId"]."</div>";
			echo "<div class='col-xs-3'>".$prop["Street1"]." ".$prop["City"].", ".$prop["State"]." ".$prop["Zip"]."</div>";
			echo "<div class='col-xs-5'>$".number_format($prop["SalesPrice"]).", ".$prop["Bedrooms"]." bedrooms ".$prop["Bathrooms"]." bathrooms ".number_format($prop["SquareFeet"])."sqft";
			echo " ".$prop["Photos"];
			if ($prop["Photos"] > 0){
				$link = "http://www.".$_SERVER[HTTP_HOST];
				echo "<img class='pull-right' width='80px' src='".$link."/myrealator/propertyImages/".$prop["MlsId"]."_0.jpg'/>";
			}
			echo "</div>";
			echo "<div class='col-xs-3'><span class='btn btn-sm btn-success editProp' onclick='editProperty(".$prop["MlsId"].")'>Edit</span>";
			echo "<span class='btn btn-sm btn-danger deleteProp' onclick='deleteProperty(".$prop["MlsId"].")'>Delete</span>";
			echo "</div></div></form></li>";
		}

		?>

		</ul>
	</div>

	<script type="text/javascript">

		function editProperty(mlsId) {
			var url = window.location.href;
                        var urlArray = url.split("?");
                        var url = urlArray[0]+"?tp=update&mls="+mlsId;
                        //alert('redirecting to: '+url);
                        window.location.replace(url);
		}


		function deleteProperty(mlsId) {

			var url = window.location.href;
			var urlArray = url.split("?");
			var url = urlArray[0]+"?tp=delete&mls="+mlsId;
			//alert('redirecting to: '+url);
			window.location.replace(url);

		}

	</script>
