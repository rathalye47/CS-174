<?php
require_once 'login.php';

$conn = new mysqli($hn, $un, $pw, $db); // Creates $conn by calling a new instance of the mysqli method.
if ($conn->connect_error) die(mysql_fatal_error()); // Error checking.

$count = 0;

$createUsername = "";
$createPassword = "";

// Checks if $_POST['createUsername'] exists.
if (isset($_POST['createUsername'])) {
    $createUsername = mysql_entities_fix_string($conn, $_POST['createUsername']); // Stores the username entered by user.
    $count = $count + 1; // Increments count.
}

// Checks if $_POST['createPassword'] exists.
if (isset($_POST['createPassword'])) {
    $createPassword = mysql_entities_fix_string($conn, $_POST['createPassword']); // Stores the password entered by user.
    $count = $count + 1; // Increments count.
}

// Calls PHP validation functions and appends error messages together.
$fail = validate_username($createUsername);
$fail .= validate_password($createPassword);

// Initially, before all the $_POST variables are set, $createUsername and $createPassword are "".
// If they are "", this means the user did not enter their information into the fields, and so error messages should be displayed.
// But since the user has not entered any information in yet, we set $fail to "".
if (($fail == "No username was entered.<br>No password was entered.<br>") && ($count == 0)) {
    $fail = "";
}

echo "<!DOCTYPE html>\n<html><head><title>Credentials</title>";

// There are no validation errors, so we can add the user to the database.
if (($fail == "") && ($count > 0)) {
    $salt = generate_salt(); // Generates a salt.
    $token = hash('ripemd128', "$salt$createPassword"); // Produces a token.
    add_user($conn, $createUsername, $token, $salt); // Adds the user to the database.
}

echo <<<_END
<script>

// Calls the 2 validation functions to validate each of the form's input fields.
// They return an empty string if a field validates, or an error message if it fails.
// If there are any errors, the final line of the script pops up an alert box to display them.
function validate(form) {
    fail = validateUsername(form.createUsername.value)
    fail += validatePassword(form.createPassword.value)

    if (fail == "") return true

    else { alert(fail); return false }
}

// If the value of the username is an empty string, an error message is returned.
// If the length of the username is less than 5, an error message is returned.
// If the username contains characters other than a-z, A-Z, 0-9, -, and _, an error message is returned.
// Otherwise, an empty string is returned to signify that no error was encountered.
function validateUsername(field) {
    if (field == "") return "No username was entered.\n"

    else if (field.length < 5)
        return "Usernames must be at least 5 characters long.\n"

    else if (/[^a-zA-Z0-9_-]/.test(field))
        return "Usernames must only contain a-z, A-Z, 0-9, -, and _.\n"

    return ""
}

// If the value of the password is an empty string, an error message is returned.
// If the length of the password is less than 6, an error message is returned.
// If the password doesn't contain at least 1 character from a-z, A-Z, and 0-9, an error message is returned.
// Otherwise, an empty string is returned to signify that no error was encountered.
function validatePassword(field) {
    if (field == "") return "No password was entered.\n"

    else if (field.length < 6) 
        return "Passwords must be at least 6 characters long.\n"

    else if (!/[a-z]/.test(field) || !/[A-Z]/.test(field) || !/[0-9]/.test(field))
        return "Passwords require one each of a-z, A-Z, and 0-9.\n"

    return ""
}

</script>
</head>
<style>
h1 {
  text-align: center;
}
</style>

<body style="background-color:blanchedalmond;">
<h1>Dessert Cucinare</h1>

<!-- Sign Up Form -->
<!-- User creates their username and password. -->
<!-- Users clicks the button after entering their information. -->
<h2>Sign Up</h2>

<p><font color=red><i>$fail</i></font></p>

<form method='post' action='authenticate.php' onSubmit='return validate(this)'>
<hr>

<label><b>Username</b></label><br>
<input type='text' name='createUsername' value='$createUsername' size='10'><br><br>

<label><b>Password</b></label><br>
<input type='password' name='createPassword' value='$createPassword' size='10'><br>
<hr><br>

<input type='submit' value='Create Account'>
</form>
</body>
</html>

<!-- Sign In Form -->
<!-- User enters their username and password -->
<!-- Users clicks the button after entering their information. -->
<html><head><title>Credentials</title></head><body><br>
<h2>Sign In</h2>

<form method='post' action='authenticate.php'>
<hr>

<label><b>Username</b></label><br>
<input type='text' name='loginUsername' size='10'><br><br>

<label><b>Password</b></label><br>
<input type='password' name='loginPassword' size='10'><br>
<hr><br>

<input type='submit' value='Login'>
</form>
<br>
</body>
</html>
_END;

// Checks if $_POST['loginUsername'] and $_POST['loginPassword'] exist.
if (isset($_POST['loginUsername']) && isset($_POST['loginPassword'])) {
    $loginUsername = mysql_entities_fix_string($conn, $_POST['loginUsername']); // Stores the username entered by user.
    $loginPassword = mysql_entities_fix_string($conn, $_POST['loginPassword']); // Stores the password entered by user.
    $query = "SELECT * FROM credentials WHERE username='$loginUsername'"; // Query into the 'credentials' table with specified username.
    $result = $conn->query($query);

    if (!$result) mysql_fatal_error(); // Error checking.

    // Checks if there is at least 1 record.
    else if ($result->num_rows) {
        $row = $result->fetch_array(MYSQLI_NUM); // Fetches each item of data.
        $result->close();
        $salt = $row[2]; // Retrieves the salt.
        $token = hash('ripemd128', "$salt$loginPassword"); // Generates the token.

        // If the tokens are the same, the user is authenticated.
        if ($token == $row[1]) {
            session_start(); // Starts a session.
            $_SESSION['loginUsername'] = $loginUsername; // Saves 'loginUsername' session variable.
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR']; // Stores user's IP address.

            // Saves the combination of the user's remote address string and user agent string as a hash string.
            $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

            // User presses the 'continue' button to go to the next page.
            echo <<<_END
            <html><head><title>Cookbook</title></head><body>
            <form method='post' action='recipe.php'>
            <font color=green><i>Access granted! Click on the button to continue to the next page:</i></font> <input type='submit' value='Continue'>
            </form>
            <br>
            </body>
            </html>
            _END;
        }

        // Otherwise, return an error message.
        else {
            echo "<font color=red><i>Invalid username/password combination!</i></font><br>";
        }
    }

    // If there is no record in the database, return an error message.
    else {
        echo "<font color=red><i>Invalid username/password combination!</i></font><br>";
    }
}

// Helper function to validate the username input field.
// Equivalent of the JavaScript function validateUsername().
function validate_username($field) {
    if ($field == "") return "No username was entered.<br>";

    else if (strlen($field) < 5)
        return "Usernames must be at least 5 characters long.<br>";

    else if (preg_match("/[^a-zA-Z0-9_-]/", $field))
        return "Usernames must only contain a-z, A-Z, 0-9, -, and _.<br>";
}

// Helper function to validate the password input field.
// Equivalent of the JavaScript function validatePassword().
function validate_password($field) {
    if ($field == "") return "No password was entered.<br>";

    else if (strlen($field) < 6)
        return "Passwords must be at least 6 characters long.<br>";

    else if (!preg_match("/[a-z]/", $field) || !preg_match("/[A-Z]/", $field) || !preg_match("/[0-9]/", $field)) 
        return "Passwords require one each of a-z, A-Z, and 0-9.<br>";

    return "";
}

// Helper function to sanitize a string.
function mysql_entities_fix_string($connection, $string) {
    return htmlentities(mysql_fix_string($connection, $string));
}

// Helper function to sanitize a string.
function mysql_fix_string($connection, $string) {
    return $connection->real_escape_string($string);
}

// Helper function to generate a random salt of 5 characters.
function generate_salt() {
    $allCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $salt = '';

    for ($i = 0; $i < 5; $i++) {
        $index = rand(0, strlen($allCharacters) - 1);
        $salt .= $allCharacters[$index];
    }

    return $salt;
}

// Helper function to add a user to the 'credentials' table.
function add_user($connection, $un, $pw, $sa) {
    $query = "INSERT INTO credentials VALUES('$un', '$pw', '$sa')";
    $result = $connection->query($query);
    if (!$result) mysql_fatal_error();
}

// Helper function to generate a user friendly error message.
function mysql_fatal_error() {
    echo "Sorry! Something went wrong!";
}

$conn->close();

?>