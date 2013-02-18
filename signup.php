<?php

if (empty($joke_connection)) {
	include "common.php";
	session_start();
	session_variables();
	//$joke_connection = connect_sql();
}

if ($_SESSION['user_power'] > -1) {
    die("<p class='error'>You've already logged in. If you really want to discard your current Persona, log out first.</p>");
}

?>

<script src='md5.js'></script>
<script type='text/javascript'>
    function encrypt_passwords(form) {
        for (i in form.elements) {
            input = form.elements[i];
            if (input.type == "password") {
                password = input.value;
                if (password != "") {
                    input.setAttribute("maxlength", "32"); //Else, MD5 hash won't fit.
                    input.value = md5(password);
                }
            }
        }
    } //ToDo: make password fields empty and move the actual password to a hidden variable,
    //as in login form.
    
    function confirm_same(a, b) {
        if ((a == "") || (b == "")) {
            return false;
        }
        if (a == b) {
            return true;
        }
        return false;
    }
    
    function form_send(form) {
        //window.alert("1");
        email1 = document.getElementById('email1').value;
        email2 = document.getElementById('email2').value;
        //window.alert(email1);
        
        if (confirm_same(email1, email2)) {
            confirmed_email = true;
        }
        else {
            confirmed_email = false;
            window.alert("E-mail addresses don't match.");
        }
        
        password1 = document.getElementById('password1').value;
        password2 = document.getElementById('password2').value;
        //window.alert(password2);
        
        if (confirm_same(password1, password2)) {
            confirmed_password = true;
        }
        else {
            confirmed_password = false;
            window.alert("Passwords don't match.");
        }
        //window.alert(document.getElementById('humanquiz').value);
        if (document.getElementById('humanquiz').value == 'Inaba') {
            confirmed_human = true;
        }
        else {
            confirmed_human = false;
            window.alert("Your answer for the Anti-bot Quiz is wrong, badly spelled or not in romaji. Google can be your ally.");
        }
        //window.alert("2");
        if (confirmed_email && confirmed_password && confirmed_human) {
            encrypt_passwords(form);
            //window.alert("3");
            return true;
            //form.submit();
        }
        return false;
    }
    
    
</script>


<form id='signup_form' class='signup' action='signup2.php' method='post' onSubmit='return form_send(this)'>
    <fieldset>
        <label for='username'>User name:<br /><span class='miniwarning'>Don't write special characters nor spaces. Thank you.</span><br /></label>
        <input type='text' name='username' id='username' maxlength='24' required />
    </fieldset>
    <fieldset>
        <label for='email1'>E-mail address:<br /></label>
        <input type='email' name='email1' id='email1' required />
    </fieldset>
    <fieldset>
        <label for='email2'>Confirm e-mail:<br /></label>
        <input type='email' name='email2' id='email2' required />
    </fieldset>
    <fieldset>
        <label for='password1'>Password:<br /><span class='miniwarning'>Min 6 characters. Max 16 characters.</span><br /></label>
        <input type='password' name='password1' id='password1' maxlength='16' required />
    </fieldset>
    <fieldset>
        <label for='password2'>Confirm password:<br /></label>
        <input type='password' name='password2' id='password2' maxlength='16' required />
    </fieldset>
    <fieldset>
        <label for='humanquiz'>Anti-bot Quiz!<br /><span class='miniwarning'>In which town do the events in Persona4 happen?</span><br /></label>
        <input type='text' name='humanquiz' id='humanquiz' required />
    </fieldset>
    <fieldset>
        <input type='submit' id='submit' value='i can has velvet key' />
    </fieldset>
</form>