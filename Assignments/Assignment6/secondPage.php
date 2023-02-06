<?php
require_once 'login.php';

$conn = new mysqli($hn, $un, $pw, $db); // Creates $conn by calling a new instance of the mysqli method.
if ($conn->connect_error) die(mysql_fatal_error()); // Error checking.

$count = 0;

$createName = "";
$createID = "";
$createEmail = "";
$createPassword = "";

// Checks if $_POST['createName'] exists.
if (isset($_POST['createName'])) {
    $createName = mysql_entities_fix_string($conn, $_POST['createName']); // Stores the name entered by user.
    $count = $count + 1; // Increments count.
}

// Checks if $_POST['createID'] exists.
if (isset($_POST['createID'])) {
    $createID = mysql_entities_fix_string($conn, $_POST['createID']); // Stores the ID entered by user.
    $count = $count + 1; // Increments count.
}

// Checks if $_POST['createEmail'] exists.
if (isset($_POST['createEmail'])) {
    $createEmail = mysql_entities_fix_string($conn, $_POST['createEmail']); // Stores the email entered by user.
    $count = $count + 1; // Increments count.
}

// Checks if $_POST['createPassword'] exists.
if (isset($_POST['createPassword'])) {
    $createPassword = mysql_entities_fix_string($conn, $_POST['createPassword']); // Stores the password entered by user.
    $count = $count + 1; // Increments count.
}

// Calls PHP validation functions and appends error messages together.
$fail = validate_name($createName);
$fail .= validate_ID($createID);
$fail .= validate_email($createEmail);
$fail .= validate_password($createPassword);

// Initially, before all the $_POST variables are set, $createName, $createID, $createEmail, and $createPassword are all "".
// If they are all "", meaning the user did not enter their information into the fields, they should normally give an error message.
// But since the user has not entered any information in yet, we set $fail to "".
if (($fail == "No name was entered.<br>No ID was entered.<br>No email was entered.<br>No password was entered.<br>") && ($count == 0)) {
    $fail = "";
}

echo "<!DOCTYPE html>\n<html><head><title>Credentials</title>";

// There are no validation errors, so we can add the user to the database.
if (($fail == "") && ($count > 0)) {
    $salt = generate_salt(); // Generates a salt.
    $token = hash('ripemd128', "$salt$createPassword"); // Produces a token.
    add_user($conn, $createName, $createID, $createEmail, $token, $salt); // Adds the user to the database.
}

echo <<<_END
<script>

// Calls 6 other validation functions to validate each of the form's input fields.
// They return an empty string if a field validates, or an error message if it fails.
// If there are any errors, the final line of the script pops up an alert box to display them.
function validate(form) {
    fail = validateName(form.createName.value)
    fail += validateID(form.createID.value)
    fail += validateEmail(form.createEmail.value)
    fail += validatePassword(form.createPassword.value)

    if (fail == "") return true

    else { alert(fail); return false }
}

// If the value of the name is an empty string, an error message is returned.
// Otherwise, an empty string is returned to signify that no error was encountered.
function validateName(field) {
    return (field == "") ? "No name was entered.\n" : ""
}

// If the value of the ID is an empty string, an error message is returned.
// If the value of the ID is not a number, an error message is returned.
// If the value of the ID is less than 1 or greater than 100, an error message is returned.
// Otherwise, an empty string is returned to signify that no error was encountered.
function validateID(field) {
    if (field == "") return "No ID was entered.\n"

    else if (isNaN(field)) return "ID must be a number.\n"

    else if (field < 1 || field > 100) 
        return "ID must be between 1 and 100.\n"

    return ""
}

// If the value of the email is an empty string, an error message is returned.
// If there isn't a period and an @ symbol somewhere from at least the 2nd character of the field, or if there is an invalid character, an error message is returned.
// Otherwise, an empty string is returned to signify that no error was encountered.
function validateEmail(field) {
    if (field == "") return "No email was entered.\n"

    else if (!((field.indexOf(".") > 0) && (field.indexOf("@") > 0)) || /[^a-zA-Z0-9.@_-]/.test(field))
        return "The email is invalid.\n"

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
<body>

<!-- Sign Up Form -->
<!-- User enters their name, ID, email, and password. -->
<!-- Users clicks the button after entering their information. -->
<h2>Sign Up</h2>

<p><font color=red><i>$fail</i></font></p>

<form method='post' action='secondPage.php' onSubmit='return validate(this)'>
<hr><br>

<label><b>Name</b></label><br>
<input type='text' name='createName' value='$createName' size='10'><br><br>

<label><b>ID</b></label><br>
<input type='text' name='createID' value='$createID' size='10'><br><br>

<label><b>Email</b></label><br>
<input type='text' name='createEmail' value='$createEmail' size='10'><br><br>

<label><b>Password</b></label><br>
<input type='password' name='createPassword' value='$createPassword' size='10'><br><br>
<hr><br>

<input type='submit' value='Create User'>
</form>
</body>
</html>
_END;


// Sign In Form
// User enters their ID and password. 
// Users clicks the button after entering their information.
echo <<<_END
<html><head><title>Credentials</title></head><body><br>
<h2>Sign In</h2>

<form method='post' action='secondPage.php'>
<hr><br>

<label><b>ID</b></label><br>
<input type='text' name='loginID' size='10'><br><br>

<label><b>Password</b></label><br>
<input type='password' name='loginPassword' size='10'><br><br>
<hr><br>

<input type='submit' value='Login'>
</form>
<br>
</body>
</html>
_END;

// Checks if $_POST['loginID'] and $_POST['loginPassword'] exist.
if (isset($_POST['loginID']) && isset($_POST['loginPassword'])) {
    $loginID = mysql_entities_fix_string($conn, $_POST['loginID']); // Stores the ID entered by user.
    $loginPassword = mysql_entities_fix_string($conn, $_POST['loginPassword']); // Stores the password entered by user.
    $query = "SELECT * FROM credentials WHERE id='$loginID'"; // Query into the 'credentials' table with specified ID.
    $result = $conn->query($query);

    if (!$result) mysql_fatal_error(); // Error checking.

    // Checks if there is at least 1 record.
    else if ($result->num_rows) {
        $row = $result->fetch_array(MYSQLI_NUM); // Fetches each item of data.
        $result->close();
        $salt = $row[4]; // Retrieves the salt.
        $token = hash('ripemd128', "$salt$loginPassword"); // Generates the token.

        // If the tokens are the same, the user is authenticated.
        if ($token == $row[3]) {
            session_start(); // Starts a session.
            $_SESSION['loginID'] = $loginID; // Saves 'loginID' session variable.
            $_SESSION['ip'] = $_SERVER['REMOTE_ADDR']; // Stores user's IP address.

            // Saves the combination of the user's remote address string and user agent string as a hash string.
            $_SESSION['check'] = hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT']);

            // User presses the 'continue' button to go to the next page.
            echo <<<_END
            <html><head><title>Advisor Information</title></head><body>
            <form method='post' action='firstPage.php'>
            Access granted! Click on the button to continue: <input type='submit' value='Continue'>
            </form>
            </body>
            </html>
            _END;
        }

        // Otherwise, return an error message.
        else {
            echo "Invalid ID/password combination!<br>";
        }
    }

    // If there is no record in the database, return an error message.
    else {
        echo "Invalid ID/password combination!<br>";
    }
}

// Helper function to validate the name input field.
// Equivalent of the JavaScript function validateName().
function validate_name($field) {
    return ($field == "") ? "No name was entered.<br>" : "";
}

// Helper function to validate the ID input field.
// Equivalent of the JavaScript function validateID().
function validate_ID($field) {
    if ($field == "") return "No ID was entered.<br>";

    else if (!is_numeric($field)) return "ID must be a number.<br>";

    else if ($field < 1 || $field > 100)
        return "ID must be between 1 and 100.<br>";

    return "";
}

// Helper function to validate the email input field.
// Equivalent of the JavaScript function validateEmail().
function validate_email($field) {
    if ($field == "") return "No email was entered.<br>";

    else if (!((strpos($field, ".") > 0) && (strpos($field, "@") > 0)) || preg_match("/[^a-zA-Z0-9.@_-]/", $field))
        return "The email is invalid.<br>";

    return "";
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
function add_user($connection, $nm, $id, $em, $pw, $sa) {
    $query = "INSERT INTO credentials VALUES('$nm', '$id', '$em', '$pw', '$sa')";
    $result = $connection->query($query);
    if (!$result) mysql_fatal_error();
}

// Helper function to generate a user friendly error message.
function mysql_fatal_error() {
    echo "Sorry! Something went wrong!";
}

$conn->close();

?>