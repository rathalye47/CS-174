<?php
require_once 'login.php';

$conn = new mysqli($hn, $un, $pw, $db); // Creates $conn by calling a new instance of the mysqli method.
if ($conn->connect_error) die(mysql_fatal_error()); // Error checking.

session_start(); // Starts a session.

// Provides security against session fixation.
if (!isset($_SESSION['initiated'])) {
	session_regenerate_id();
	$_SESSION['initiated'] = 1;
}

if (isset($_SESSION['loginID'])) {
    // If the stored IP address doesn't match the current one, different_user() is called.
    // Provides security against session hijacking.
    if ($_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) {
        different_user();
    }

    // Provides more security against session hijacking if users are on the same proxy server, share the same IP address on a home/business network, or have the same IP address.
    if ($_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])) {
        different_user();
    }

    // Form to enter a student's name and a student's ID.
    // The user will click the button to search into the database using the inputted data.
    echo <<<_END
    <html><head><title>Advisor Information</title></head><body>
    <h2>Find Advisor</h2>

    <form method='post' action='firstPage.php'>
    <hr><br>

    <label><b>Student Name</b></label><br>
    <input type='text' name='studentName' size='10'><br><br>

    <label><b>Student ID</b></label><br>
    <input type='text' name='studentID' size='10'><br><br>
    <hr><br>

    <input type='submit' value='Search'>
    </form>
    <br>
    </body>
    </html>
    _END;

    // Checks if $_POST['studentName'] and $_POST['studentID'] exist.
    if (isset($_POST['studentName']) && isset($_POST['studentID'])) {
        $name = mysql_entities_fix_string($conn, $_POST['studentName']); // Stores the name entered by user.
        $id = mysql_entities_fix_string($conn, $_POST['studentID']); // Stores the ID entered by user.
        $query = "SELECT * FROM credentials WHERE id='$id' AND name='$name'"; // Queries into 'credentials' table with the specified ID and name.
        $result = $conn->query($query);

        if (!$result) mysql_fatal_error(); // Error checking.

        // Checks if there is at least 1 record.
        else if ($result->num_rows) {
            $query = "SELECT * FROM advisors"; // Query to select all data in the 'advisors' table.
            $result = $conn->query($query);

            if (!$result) mysql_fatal_error(); // Error checking.
            $rows = $result->num_rows;

            // Iterates through all of the records in the 'advisors' table.
            for ($i = 0; $i < $rows; $i++) {
                $result->data_seek($i); // Seeks the ith row.
                $row = $result->fetch_array(MYSQLI_NUM); // Fetches the item of data in the ith row.

                // If the student's ID is in between an advisor's lower-bound ID number and upper-bound ID number, print that advisor's name, email, and telephone number.
                if (($id >= $row[3]) && ($id <= $row[4])) {
                    echo "Advisor Name: $row[0]<br>";
                    echo "Advisor Email: $row[1]<br>";
                    echo "Advisor Telephone Number: $row[2]<br>";
                }
            }
        }

        // The Student Name and/or Student ID is not found in the database.
        else {
            echo "The given Student Name/Student ID does not exist!<br>";
        }

        $result->close();
        destroy_session_and_data(); // Destroys the session and its data.
    } 
}

// The user's session has expired, and they will need to login again.
else {
    echo <<<_END
    <html><head><title>Credentials</title></head><body>
    <form method='post' action='secondPage.php'>
    Click on the button to login: <input type='submit' value='Login'>
    </form>
    </body>
    </html>
    _END;
}

// Helper function to sanitize a string.
function mysql_entities_fix_string($connection, $string) {
    return htmlentities(mysql_fix_string($connection, $string));
}

// Helper function to sanitize a string.
function mysql_fix_string($connection, $string) {
    return $connection->real_escape_string($string);
}

// Helper function to destroy a session and its data.
function destroy_session_and_data() {
    $_SESSION = array();
    setcookie(session_name(), "", time() - 2592000, "/");
    session_destroy();
}

// Helper function to generate a user friendly error message.
function mysql_fatal_error() {
    echo "Sorry! Something went wrong!";
}

// Helper function that deletes the current session and asks the user to login again.
function different_user() {
    session_destroy();
    echo <<<_END
    <html><head><title>Credentials</title></head><body>
    <form method='post' action='secondPage.php'>
    Sorry! Technical error! Please click on the button to login again: <input type='submit' value='Login'>
    </form>
    </body>
    </html>
    _END;
}

$conn->close();

?>