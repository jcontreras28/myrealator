<?php
include_once './db_connect.php';
include_once './functions.php';

sec_session_start();

if (login_check($mysqli) == true) {

	$mls = $_GET['mls'];

	$propData = getPropertyData($mls, $mysqli);

	var_dump($propData);

?>

<form class="sampleSubmitForm padding-horz padding-vert padding-horz-half-sm padding-vert-half-sm padding-horz-none-xs" enctype="multipart/form-data" action="main.php?tp=updateSave&mls=<?php echo $mls; ?>" method="POST" name="fmSampleEntry" id="fmSampleEntry">
        <div class="row">
                <div class="col-sm-6">
                        <div class="form-group">
                                <label for="street1">Street Address 1 </label>
                                <input type="text" name="street1" id="street1" maxlength="200" class="form-control required" value="<?php echo $propData['Street1']; ?>"/>
                        </div>
                </div>
                <div class="col-sm-6">
                        <div class="form-group">
                                <label for="street2">Street Address 2</label>
                                <input type="text" name="street2" id="street2"  maxlength="200" class="form-control" value="<?php echo $propData['Street2']; ?>" />
                        </div>
                </div>
        </div>
        <div class="row">
                <div class="col-sm-6">
                        <div class="form-group">
                                <label for="email">City</label>
                                <input type="text" name="city" id="city" placeholder="City" maxlength="50" class="form-control required" value="<?php echo $propData['City']; ?>" />
                        </div>
                </div>
                <div class="col-sm-6">
                        <div class="form-group">
                                <label for="state">State (2 letter code)</label>
                                <input type="text" name="state" id="state" placeholder="Two letter code" maxlength="2" class="form-control required" value="<?php echo $propData['State']; ?>" />
                        </div>
                </div>
        </div>
	<div class="row">
                <div class="col-sm-6">
                        <div class="form-group">
                                <label for="zip">Zip</label>
                                <input type="number" name="zip" id="zip" maxlength="7" class="form-control required" value="<?php echo $propData['Zip']; ?>" />
                        </div>
                </div>
                <div class="col-sm-6">
                        <div class="form-group">
                                <label for="neighborhood">Neighborhood</label>
                                <input type="text" name="neighborhood" id="neighborhood" maxlength="200" class="form-control required" value="i<?php echo $propData['Neighborhood']; ?>" />
                        </div>
                </div>
        </div>
	<div class="row">
                <div class="col-sm-6">
                        <div class="form-group">
                                <label for="price">Price</label>
                                <input type="number" name="salesPrice" id="salesPrice" class="form-control required" value="<?php echo $propData['SalesPrice']; ?>" />
                        </div>
                </div>
                <div class="col-sm-6">
                        <div class="form-group">
                                <label for="bedrooms">Number of Bedrooms</label>
                                <input type="number" name="bedrooms" id="bedrooms" class="form-control required" value="<?php echo $propData['Bedrooms']; ?>" />
                        </div>
                </div>
        </div>
	<div class="row">
                <div class="col-sm-6">
                        <div class="form-group">
                                <label for="bathrooms">Number of Bathrooms</label>
                                <input type="text" name="bathrooms" id="bathrooms" class="form-control required" value="<?php echo $propData['Bathrooms']; ?>" />
                        </div>
                </div>
                <div class="col-sm-6">
                        <div class="form-group">
                                <label for="garageSize">Garage Size (sqft)</label>
                                <input type="number" name="garageSize" id="grageSize" class="form-control required" value="<?php echo $propData['GarageSize']; ?>" />
                        </div>
                </div>
        </div>
	<div class="row">
                <div class="col-sm-6">
                        <div class="form-group">
                                <label for="squareFeet">Total House Square Footage</label>
                                <input type="number" name="squareFeet" id="squareFeet" class="form-control required" value="<?php echo $propData['SquareFeet']; ?>" />
                        </div>  
                </div>          
                <div class="col-sm-6">
                        <div class="form-group">
                                <label for="lotSize">Lot Size (sqft)</label>
                                <input type="number" name="lotSize" id="lotSize" class="form-control required" value="<?php echo $propData['LotSize']; ?>" />
                        </div>
                </div>
        </div> 
	<div class="row">
                <div class="col-sm-12">
                        <div class="form-group">
                                <label for="description">Description</label><br>
                                <textarea name="description" id="description" rows="6" class="form-control required" required value=""><?php echo $propData['Description']; ?></textarea>
                        </div>
                </div>
        </div>
	<div class="row">
                <div class="col-sm-12">
                        <div class="form-group">
                                <label for="uploadpubs">4. Upload Photos</label>
                                <input type="file" name="files[]" id="uploadpubs" multiple onchange="makeFileList();" autocomplete="off">
                                <ol id="filelist" class="sortable"></ol>
                        </div>
                </div>
        </div>

        <div class="submit-wrap text-center">
                <input type="text" name="extra" id="extra" class="hidden" value="" />
                <button type="submit" name="action" value="submitInfo" class="btn-subscribe" id="submitFormButton">Submit</button>
        </div>
</form>


<script type="text/javascript">

function makeFileList() {
  var dataString = "";

    var input = document.getElementById("uploadpubs");
    var ul = document.getElementById("filelist");

    while (ul.hasChildNodes()) {
      ul.removeChild(ul.firstChild);
    }
    for (var i = 0; i < input.files.length; i++) {
      var li = document.createElement("li");
      li.setAttribute("id", input.files[i].name);

      var liText = input.files[i].name;
      li.innerHTML = liText;

      ul.appendChild(li);

      if (i >0){
        dataString = dataString+"xxxx"+input.files[i].name;
      } else {
        dataString = input.files[i].name;
      }

    }
    if(!ul.hasChildNodes()) {
      var li = document.createElement("li");
      li.innerHTML = 'No Files Selected';
      ul.appendChild(li);
    }
    $("#dataString").val(dataString);

}

/*$('#fmSampleEntry').submit(function(event){
    if ($("#fmSampleEntry").valid())
        $('#loadingGif').show();
});*/


</script>



<?php 
	} else {
		echo "You are not authorized to use this page, you must login";

	}
