<?php
require_once 'login.php';

$conn = new mysqli($hn, $un, $pw, $db);
if ($conn->connect_error) die('Error!');

$username = '';

// If the cookie is set, save the cookie's value.
if (isset($_COOKIE['username'])) {
    $username = $_COOKIE['username'];
}

// Form for the user to upload a text file and enter a string for the content name.
echo <<<_END
<html><head><title>Input Information</title></head><body>
<form method='post' action='fileContent.php' enctype='multipart/form-data'>
Select a Text File: <input type='file' name='fileContent' id='fileContent' size='10'><br></br>
Enter a Name: <input type='text' name='specName' size='10'><br></br>
<input type='submit' value='Submit'></form>
_END;

$query = "SELECT * FROM credentials WHERE username='$username'"; // Tries to make users have exclusive access to their private information by checking for their login usernames.
$result = $conn->query($query);
$row = $result->fetch_array(MYSQLI_NUM);
$result->close();
$user_id = $row[0]; // Stores login username in $user_id.

// Checks if $_POST['specName'] and $_FILES exist.
if (isset($_POST['specName']) && $_FILES) {
    $name = get_post($conn, 'specName'); // Stores the content name.

    // If the file content type is 'text/plain', make the file extension as 'txt'.
    // Otherwise, make the file extension an empty string.
    switch(htmlentities($_FILES['fileContent']['type'])) {
        case 'text/plain' : $ext = 'txt'; break;
        default : $ext = ''; break;
    }

    // File extension is txt.
    if ($ext) {
        $filename = htmlentities($_FILES['fileContent']['tmp_name']);
        $fileContent = htmlentities(file_get_contents($filename)); // Extracts the file content.
        $query = "INSERT INTO information VALUES"."('$fileContent', '$name', NULL, '$user_id')"; // Inserts file content, name, and user id into the 'information' table.
        $result = $conn->query($query);

        if (!$result) echo "Error!<br>";
    }

    else {
        echo "Sorry! Something went wrong!<br>";
    }
}

echo "</body></html>";

$query = "SELECT * FROM information WHERE user_id='$user_id'"; // Query the 'information' table where the user id matches the login username. 
                                                               // This is so the user has exclusive access to their private information.
$result = $conn->query($query);

if (!$result) die ("Error!");

$rows = $result->num_rows;

for ($i = 0; $i < $rows; $i++) {
    $result->data_seek($i); // Seeks the row.
    $row = $result->fetch_array(MYSQLI_NUM); // Fetches each item of data.
    
    // Prints the file content and content name for the specified user.
    echo <<<_END
    <pre>
    File Content: $row[0]
    Name: $row[1]
    </pre>
    _END;
}

$conn->close();
$result->close();

// Helper function to sanitize string.
function get_post($connection, $var) {
    return $connection->real_escape_string($_POST[$var]);
}

?>