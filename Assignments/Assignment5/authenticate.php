<?php
require_once 'login.php';

$conn = new mysqli($hn, $un, $pw, $db); // Creates $conn by calling a new instance of the mysqli method.
if ($conn->connect_error) die(mysql_fatal_error()); // Error checking.

// Sign up form.
// Users creates a username and password.
echo <<<_END
<html><head><title>Credentials</title></head><body>
<form method='post' action='authenticate.php'>
<b>SIGN UP:</b>
Username <input type='text' name='createUsername' size='10'>&emsp;
Password <input type='text' name='createPassword' size='10'>&emsp;
<input type='submit' value='Create User'></form>
_END;

echo "</body></html>";

// If $_POST['createUsername'] and $_POST['createPassword'] exist, they represent the username and password entered.
if (isset($_POST['createUsername']) && isset($_POST['createPassword'])) {
    $username = mysql_entities_fix_string($conn, $_POST['createUsername']); // Stores the username entered by the user.
    $password = mysql_entities_fix_string($conn, $_POST['createPassword']); // Stores the password entered by the user.
    $salt = generate_salt(); // Generates a salt.
    $token = hash('ripemd128', "$salt$password"); // Produces a token.
    add_user($conn, $username, $token, $salt); // Adds the user to the database.
}

// Login in form.
// User enters their username and password.
echo <<<_END
<html><head><title>Credentials</title></head><body>
<form method='post' action='authenticate.php'>
<b>LOG IN:</b>&nbsp;&nbsp;
Username <input type='text' name='loginUsername' size='10'>&emsp;
Password <input type='text' name='loginPassword' size='10'>&emsp;
<input type='submit' value='Log In'></form>
_END;

echo "</body></html>";

// If $_POST['loginUsername'] and $_POST['loginPassword'] exist, they represent the username and password entered.
if (isset($_POST['loginUsername']) && isset($_POST['loginPassword'])) {
    $username = mysql_entities_fix_string($conn, $_POST['loginUsername']); // Stores the username entered by the user.
    $password = mysql_entities_fix_string($conn, $_POST['loginPassword']); // Stores the password entered by the user.
    $query = "SELECT * FROM credentials WHERE username='$username'"; // Query into the 'credentials' table with specified username.
    $result = $conn->query($query);

    if (!$result) die(mysql_fatal_error()); // Error checking.

    // Checks if there is at least 1 record.
    else if ($result->num_rows) {
        $row = $result->fetch_array(MYSQLI_NUM); // Fetches each item of data.
        $result->close();
        $salt = $row[2]; // Retrieves the salt.
        $token = hash('ripemd128', "$salt$password"); // Generates the token.

        // If the tokens are the same, the user is authenticated.
        if ($token == $row[1]) {

            setcookie('username', $username, time() + 60 * 60 * 24 * 7); // Sets a cookie to save the logged in user.

            // User presses the 'continue' button.
            echo <<<_END
            <html><head><title>Input Information</title></head><body>
            <form method='post' action='fileContent.php'>
            Access granted! Click on the button to continue: <input type='submit' value='Continue' name='Submit'></form>
            _END;

            echo "</body></html>";
        }
        
        else {
            echo "Invalid username/password combination!<br>";
        }
    }

    else {
        echo "Invalid username/password combination!<br>";
    }
}

// Helper function to add a user to the 'credentials' table.
function add_user($connection, $un, $pw, $sa) {
    $query = "INSERT INTO credentials VALUES('$un', '$pw', '$sa')";
    $result = $connection->query($query);
    if (!$result) die('Error!');
}

// Helper function to sanitize a string.
function mysql_entities_fix_string($connection, $string) {
    return htmlentities(mysql_fix_string($connection, $string));
}

// Helper function to sanitize a string.
function mysql_fix_string($connection, $string) {
    return $connection->real_escape_string($string);
}

// Helper function to generate a random salt.
function generate_salt() {
    $allCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $salt = '';

    for ($i = 0; $i < 4; $i++) {
        $index = rand(0, strlen($allCharacters) - 1);
        $salt .= $allCharacters[$index];
    }

    return $salt;
}

// Helper function to sanitize string.
function get_post($connection, $var) {
    return $connection->real_escape_string($_POST[$var]);
}

// Helper function to generate a user friendly error message.
function mysql_fatal_error() {
    echo "Sorry! Something went wrong!";
}

$conn->close();

?>