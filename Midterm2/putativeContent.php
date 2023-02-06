<?php
require_once 'login.php';

$conn = new mysqli($hn, $un, $pw, $db); // Creates $conn by calling a new instance of the mysqli method, passing all values retrieved from the login.php file.
if ($conn->connect_error) die(mysql_fatal_error()); // Error checking.

// Form for the user to upload a putative infected file.
echo <<<_END
<html><head><title>Putative Information</title></head><body>
<form method='post' action='putativeContent.php' enctype='multipart/form-data'>
Putative Infected File:&emsp; <input type='file' name='putativeContent' size='10'><br></br>
<input type='submit' value='Submit'></form>
_END;

// Checks if $_FILES isn't empty.
if ($_FILES) {
    $putativeFileName = htmlentities($_FILES['putativeContent']['tmp_name']); // Stores the name of the putative file.
    $putativeFileContent = htmlentities(file_get_contents($putativeFileName)); // Reads in the entire putative file.
    $count = 0;

    $query = "SELECT * FROM malware_information"; // Query to select all data in the 'malware_information' table.
    $result = $conn->query($query);

    if (!$result) echo "Sorry! Something went wrong!"; // Error checking.
    $rows = $result->num_rows;

    // Iterates through all of the records in the 'malware_information' table.
    for ($i = 0; $i < $rows; $i++) {
        $result->data_seek($i); // Seeks the ith row.
        $row = $result->fetch_array(MYSQLI_NUM); // Fetches the item of data in the ith row.

        // If the putative file contains one of the first 20 bytes of the malware files stored in the database, increment the count.
        if (str_contains($putativeFileContent, htmlentities($row[0]))) {
            $count = $count + 1;
        }
    }

    // If the count is greater than 0, the putative infected file is infected.
    if ($count > 0) {
        echo "The file is infected!<br>";
    }

    // Otherwise, the putative infected file is not infected.
    else {
        echo "The file is not infected!<br>";
    }

    $result->close();
}

echo "</body></html>";

$conn->close();

// Helper function to generate a user friendly error message.
function mysql_fatal_error() {
    echo "Sorry! Something went wrong!";
}

?>