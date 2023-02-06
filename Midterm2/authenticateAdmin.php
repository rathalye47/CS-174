<?php
require_once 'login.php';

$conn = new mysqli($hn, $un, $pw, $db); // Creates $conn by calling a new instance of the mysqli method, passing all values retrieved from the login.php file.
if ($conn->connect_error) die(mysql_fatal_error()); // Error checking.

// Sign up form.
// Admin creates a username and password.
echo <<<_END
<html><head><title>Credentials</title></head><body>
<form method='post' action='authenticateAdmin.php'>
<b><u>ADMIN SIGN UP:</u></b>&nbsp;
Username <input type='text' name='createUsername' size='10'>&emsp;
Password <input type='text' name='createPassword' size='10'>&emsp;
<input type='submit' value='Create Admin'></form>
_END;

echo "</body></html>";

// If $_POST['createUsername'] and $_POST['createPassword'] exist, they represent the username and password entered by the admin.
if (isset($_POST['createUsername']) && isset($_POST['createPassword'])) {
    $username = mysql_entities_fix_string($conn, $_POST['createUsername']); // Stores the username entered by the admin.
    $password = mysql_entities_fix_string($conn, $_POST['createPassword']); // Stores the password entered by the admin.
    $salt = generate_salt(); // Generates a random salt.
    $token = hash('ripemd128', "$salt$password"); // Produces a token by hashing the concatenation of the salt with the password.
    add_admin($conn, $username, $token, $salt); // Adds the admin to the 'credentials' table.
}

// Login form.
// Admin signs in with their username and password.
echo <<<_END
<html><head><title>Credentials</title></head><body>
<form method='post' action='authenticateAdmin.php'>
<b><u>ADMIN SIGN IN:</u></b>&nbsp;&nbsp;
Username <input type='text' name='loginUsername' size='10'>&emsp;
Password <input type='text' name='loginPassword' size='10'>&emsp;
<input type='submit' value='Login'></form>
_END;

echo "</body></html>";

// If $_POST['loginUsername'] and $_POST['loginPassword'] exist, they represent the username and password entered by the admin.
if (isset($_POST['loginUsername']) && isset($_POST['loginPassword'])) {
    $username = mysql_entities_fix_string($conn, $_POST['loginUsername']); // Stores the username entered by the admin.
    $password = mysql_entities_fix_string($conn, $_POST['loginPassword']); // Stores the password entered by the admin.
    $query = "SELECT * FROM credentials WHERE username='$username'"; // Query into the 'credentials' table with specified username.
    $result = $conn->query($query);

    if (!$result) echo "Sorry! Something went wrong!"; // Error checking.

    // Checks if there is at least 1 record.
    else if ($result->num_rows) {
        $row = $result->fetch_array(MYSQLI_NUM); // Fetches each item of data.
        $result->close();
        $salt = $row[2]; // Retrieves the salt from the 'credentials' table.
        $token = hash('ripemd128', "$salt$password"); // Generates the token.

        // If the tokens are the same, the admin is authenticated.
        if ($token == $row[1]) {
            // Admin presses the 'continue' button.
            echo <<<_END
            <html><head><title>Malware Information</title></head><body>
            <form method='post' action='malwareContent.php'>
            Access granted! Click on the button to continue:&emsp; <input type='submit' value='Continue' name='Submit'></form>
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

$conn->close();

// Helper function to add an admin to the 'credentials' table.
function add_admin($connection, $un, $pw, $sa) {
    $query = "INSERT INTO credentials VALUES('$un', '$pw', '$sa')";
    $result = $connection->query($query);
    if (!$result) echo "Sorry! Something went wrong!";
}

// Helper function to sanitize a string.
function mysql_entities_fix_string($connection, $string) {
    return htmlentities(mysql_fix_string($connection, $string));
}

// Helper function to sanitize a string.
function mysql_fix_string($connection, $string) {
    return $connection->real_escape_string($string);
}

// Helper function to generate a random salt of 10 characters.
function generate_salt() {
    $allCharacters = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789';
    $salt = '';

    for ($i = 0; $i < 10; $i++) {
        $index = rand(0, strlen($allCharacters) - 1);
        $salt .= $allCharacters[$index];
    }

    return $salt;
}

// Helper function to generate a user friendly error message.
function mysql_fatal_error() {
    echo "Sorry! Something went wrong!";
}

?>