
function formhash(form, password)
{
        // create new element input, this will be our hashed password field
        var p = document.createElement("input");

        // add the new element to our form
        form.appendChild(p);
        p.name = "p";
        p.type = "hidden";
        p.value = hex_sha512(password.value);

        // make sure the plaintext password doesn't get sent
        password.value = "";
        // submit the form
        form.submit();
}

function regformhash(form, uid, email, password, conf)
{
        // check each field has a value
        if (uid.value == '' || email.value == '' || password.value == '' || conf.value == '')
        {
                alert('You must provide all the required details.  Pleas try again');
                return false;
        }

        // check the username
        re = /^\w+$/;
        if(!re.test(form.username.value))
        {   
                alert("Username must contain only letters, numbers and underscores.  Please try again.");
                form.username.focus();
                return false;
        }   

        // check that the password is at least 6 chars
        if (password.value.length < 6)
        {   
                alert("Passwords must be at least 6 characters long. Please try again");
                form.password.focus();
        }   

        // At least one number, one lowercase and one uppercase letter 
        // At least six characters 
 
        var re = /(?=.*\d)(?=.*[a-z])(?=.*[A-Z]).{6,}/; 
        if (!re.test(password.value)) {
                alert('Passwords must contain at least one number, one lowercase and one uppercase letter.  Please try again');
                return false;
        }

        // Check password and confirmation are the same
        if (password.value != conf.value) {
                alert('Your password and confirmation do not match. Please try again');
                form.password.focus();
                return false;
        }

        // Create a new element input, this will be our hashed password field. 
        var p = document.createElement("input");

        // Add the new element to our form. 
        form.appendChild(p);
        p.name = "p";
        p.type = "hidden";
        p.value = hex_sha512(password.value);

        // Make sure the plaintext password doesn't get sent. 
        password.value = "";
        conf.value = "";

        // Finally submit the form. 
        form.submit();
        return true;
}
