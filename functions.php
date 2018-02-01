<?php
include_once 'db-config.php';
 
function sec_session_start() {
    $session_name = 'sec_session_id';   // Set a custom session name 
    $secure = SECURE;
    // This stops JavaScript being able to access the session id.
    $httponly = true;
    // Forces sessions to only use cookies.
    if (ini_set('session.use_only_cookies', 1) === FALSE) {
        header("Location: ../error.php?err=Could not initiate a safe session (ini_set)");
        exit();
    }
    // Gets current cookies params.
    $cookieParams = session_get_cookie_params();
    session_set_cookie_params($cookieParams["lifetime"], $cookieParams["path"], $cookieParams["domain"], $secure, $httponly);
    // Sets the session name to the one set above.
    session_name($session_name);
    session_start();            // Start the PHP session 
    session_regenerate_id();    // regenerated the session, delete the old one. 
}

function login($email, $password, $mysqli) {
    // Using prepared statements means that SQL injection is not possible. 
    if ($stmt = $mysqli->prepare("SELECT id, username, password 
        FROM members
        WHERE email = ?
        LIMIT 1")) {
        $stmt->bind_param('s', $email);  // Bind "$email" to parameter.
        $stmt->execute();    // Execute the prepared query.
        $stmt->store_result();
 
        // get variables from result.
        $stmt->bind_result($user_id, $username, $db_password);
        $stmt->fetch();
 
        if ($stmt->num_rows == 1) {
            // If the user exists we check if the account is locked
            // from too many login attempts 
 
            if (checkbrute($user_id, $mysqli) == true) {
                // Account is locked 
                // Send an email to user saying their account is locked
                return false;
	    } else {
                // Check if the password in the database matches
                // the password the user submitted. We are using
                // the password_verify function to avoid timing attacks.
                if (password_verify($password, $db_password)) {
                    // Password is correct!
                    // Get the user-agent string of the user.
                    $user_browser = $_SERVER['HTTP_USER_AGENT'];
                    // XSS protection as we might print this value
                    $user_id = preg_replace("/[^0-9]+/", "", $user_id);
                    $_SESSION['user_id'] = $user_id;
                    // XSS protection as we might print this value
                    $username = preg_replace("/[^a-zA-Z0-9_\-]+/", 
                                                                "", 
                                                                $username);
                    $_SESSION['username'] = $username;
                    $_SESSION['login_string'] = hash('sha512', 
                              $db_password . $user_browser);
                    // Login successful.
                    return true;
                } else {
                    // Password is not correct
                    // We record this attempt in the database
                    $now = time();
                    $mysqli->query("INSERT INTO login_attempts(user_id, time)
                                    VALUES ('$user_id', '$now')");
                    return false;
                }
	    }
        } else {
            // No user exists.
            return false;
        }
    }
}

function checkbrute($user_id, $mysqli) {
    // Get timestamp of current time 
    $now = time();
 
    // All login attempts are counted from the past 2 hours. 
    $valid_attempts = $now - (2 * 60 * 60);
 
    if ($stmt = $mysqli->prepare("SELECT time 
                             FROM login_attempts 
                             WHERE user_id = ? 
                            AND time > '$valid_attempts'")) {
        $stmt->bind_param('i', $user_id);
 
        // Execute the prepared query. 
        $stmt->execute();
        $stmt->store_result();
 
        // If there have been more than 5 failed logins 
        if ($stmt->num_rows > 5) {
            return true;
        } else {
            return false;
        }
    }
}

function login_check($mysqli) {
    // Check if all session variables are set 
    if (isset($_SESSION['user_id'], 
                        $_SESSION['username'], 
                        $_SESSION['login_string'])) {
 
        $user_id = $_SESSION['user_id'];
        $login_string = $_SESSION['login_string'];
        $username = $_SESSION['username'];
 
        // Get the user-agent string of the user.
        $user_browser = $_SERVER['HTTP_USER_AGENT'];
 
        if ($stmt = $mysqli->prepare("SELECT password 
                                      FROM members 
                                      WHERE id = ? LIMIT 1")) {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $user_id);
            $stmt->execute();   // Execute the prepared query.
            $stmt->store_result();
 
            if ($stmt->num_rows == 1) {
		// If the user exists get variables from result.
                $stmt->bind_result($password);
                $stmt->fetch();
                $login_check = hash('sha512', $password . $user_browser);
 
                //if (hash_equals($login_check, $login_string) ){
                if ($login_check == $login_string) {  
		  // Logged In!!!! 
                    return true;
                } else {
                    // Not logged in 
                    return false;
                }
            } else {
                // Not logged in 
                return false;
            }
        } else {
            // Not logged in 
            return false;
        }
    } else {
        // Not logged in 
        return false;
    }
}

function esc_url($url) {
 
    if ('' == $url) {
        return $url;
    }
 
    $url = preg_replace('|[^a-z0-9-~+_.?#=!&;,/:%@$\|*\'()\\x80-\\xff]|i', '', $url);
 
    $strip = array('%0d', '%0a', '%0D', '%0A');
    $url = (string) $url;
 
    $count = 1;
    while ($count) {
        $url = str_replace($strip, '', $url, $count);
    }
 
    $url = str_replace(';//', '://', $url);
 
    $url = htmlentities($url);
 
    $url = str_replace('&amp;', '&#038;', $url);
    $url = str_replace("'", '&#039;', $url);
 
    if ($url[0] !== '/') {
        // We're only interested in relative links from $_SERVER['PHP_SELF']
        return '';
    } else {
        return $url;
    }
}

    function hash_equals($str1, $str2)
    {
        if(strlen($str1) != strlen($str2))
        {
            return false;
        }
        else
        {
            $res = $str1 ^ $str2;
            $ret = 0;
            for($i = strlen($res) - 1; $i >= 0; $i--)
            {
                $ret |= ord($res[$i]);
            }
            return !$ret;
        }
    }

function deleteProperty($mls, $mysqli) {
	if ($stmt = $mysqli->prepare("DELETE FROM Properties WHERE MlsId = ?")) {
            // Bind "$user_id" to parameter. 
            $stmt->bind_param('i', $mls);
            $stmt->execute();   // Execute the prepared query.
	}

	echo "here!";

}


function updateProperty($mls, $mysqli) {

	$street1 = $_POST['street1'];

        $street2 = "";
        if (isset($_POST['street2']))
                $street2 = $_POST['street2'];
        $city = $_POST['city'];
        $state = $_POST['state'];
        $zip = $_POST['zip'];
        $neighborhood = $_POST['neighborhood'];
        $salesPrice = $_POST['salesPrice'];
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

	if ($stmt = $mysqli->prepare("Update Properties SET Street1 = ?, Street2 = ?, City = ?, State = ?, Zip = ?, Neighborhood = ?, SalesPrice = ?,
					Bedrooms = ?, Photos = ?, Bathrooms = ?, GarageSize = ?, SquareFeet = ?,
					LotSize = ?, Description = ? WHERE MlsId = ?")) {
            error_log("Error returned1: ".$stmt->error, 0);
		// Bind "$user_id" to parameter. 
            $stmt->bind_param('ssssisiiisiiisi',$street1,$street2,$city,$state,$zip,$neighborhood,$salesPrice,$bedrooms,$photos,$bathrooms,$garageSize,$squareFeet,$lotSize,$description,$mls);
            $stmt->execute();   // Execute the prepared query.

        } else {
		error_log("Error returned3: ".$mysqli->errno." - ".$mysqli->error);
	}

        if (isset($_FILES['files'])) {
                $myFile = $_FILES['files'];
                $fileCount = count($myFile["name"]);
                for ($i = 0; $i < $fileCount; $i++) {
                        $allowedExts = array("gif", "jpeg", "jpg", "png", "tif", "tiff");
                        $temp = explode(".", $myFile["name"][$i]);
                        $extension = strtolower(end($temp));
                        $fileType = strtolower($myFile["type"][$i]);
                        $nm = $mls."_".$i;
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


}

function getPropertyData($mls, $mysqli) {
	
	error_log("In getPropertyData", 0);
	if ($stmt = $mysqli->prepare("SELECT * 
                             FROM Properties 
                             WHERE MlsId = ?")) {
        $stmt->bind_param('i', $mls);

        // Execute the prepared query. 
        $stmt->execute();
        
	    $meta = $stmt->result_metadata();
    	while ($field = $meta->fetch_field())
    	{
        	$params[] = &$row[$field->name];
    	}

    	call_user_func_array(array($stmt, 'bind_result'), $params);

    	while ($stmt->fetch()) {
        	foreach($row as $key => $val)
        	{
            		$c[$key] = $val;
        	}
        	$result[] = $c;
    	}
   
    	$stmt->close(); 

	return $result[0];

	}
}
