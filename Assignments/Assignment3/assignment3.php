<?php

/**
 * Given a string of numbers as input, the function finds the 5 adjacent numbers that added together give the largest sum.
 */
function findAdjacentFiveNums($strNums) {
    $maxSum = 0; // Variable to store the maximum sum.
    $adjacentFiveNums = ""; // Variable to store the string of 5 adjacent numbers.

    for ($i = 0; $i < strlen($strNums) - 4; $i++) {
        // Checks if 5 adjacent numbers starting from the ith position are a numeric string, they don't contain a '-' sign, and they don't have decimals.
        if (is_numeric(substr($strNums, $i, 5)) && !str_contains(substr($strNums, $i, 5), "-") && !str_contains(substr($strNums, $i, 5), ".")) {

            // Adds the 5 adjacent numbers starting from the ith position together.
            $sum = substr($strNums, $i, 1) + substr($strNums, $i + 1, 1) + substr($strNums, $i + 2, 1) + substr($strNums, $i + 3, 1) + substr($strNums, $i + 4, 1);
            
            // If their sum is greater than the maximum sum, set the maximum sum to the current sum and store the 5 adjacent numbers in a string.
            if ($sum > $maxSum) {
                $maxSum = $sum;
                $adjacentFiveNums = substr($strNums, $i, 5);
            }
        }

        // Otherwise, return an error message.
        else {
            return "Error! Input contains invalid characters.";
        }
    }

    // If the input length is less than 5, return an error message.
    if (strcmp($adjacentFiveNums, "") == 0) {
        return "Error! Input length is too small.";
    }

    // Otherwise, return the 5 adjacent numbers that added together give the largest sum.
    else {
        return $adjacentFiveNums;
    }
}

/**
 * Given the 5 adjacent numbers that added together give the largest sum, the function computes the largest sum.
 */
function computeLargestSum($adjacentFiveNums) {
    // Checks if the 5 adjacent numbers are a numeric string, they don't contain a '-' symbol, and they don't have decimals.
    if (is_numeric($adjacentFiveNums) && !str_contains($adjacentFiveNums, "-") && !str_contains($adjacentFiveNums, ".")) {

        // Adds the 5 adjacent numbers together.
        $largestSum = substr($adjacentFiveNums, 0, 1) + substr($adjacentFiveNums, 1, 1) + substr($adjacentFiveNums, 2, 1) + substr($adjacentFiveNums, 3, 1) + substr($adjacentFiveNums, 4, 1);
        return $largestSum;
    }

    // Return an error message that the input length is too small.
    else if (strcmp($adjacentFiveNums, "Error! Input length is too small.") == 0) {
        return "Error! Input length is too small.";
    }

    // Return an error message that the input contains invalid characters.
    else {
        return "Error! Input contains invalid characters.";
    }
}

/**
 * Given a number, the function calculates the factorial of that number.
 */
function computeFactorial($num) {
    $product = 1;

    for ($i = $num; $i > 0; $i--) {
        $product = $product * $i;
    }

    return $product;
}

/**
 * Given the 5 adjacent numbers that added together give the largest sum, the function computes the sum of the factorial of each term of the largest sum.
 */
function computeFactorialSum($adjacentFiveNums) {
    // Checks if the 5 adjacent numbers are a numeric string, they don't contain a '-' symbol, and they don't have decimals.
    if (is_numeric($adjacentFiveNums) && !str_contains($adjacentFiveNums, "-") && !str_contains($adjacentFiveNums, ".")) {
        $totalFactorialSum = 0; // Variable to store the total factorial sum. 

        // For each number in the 5 adjacent numbers, compute the factorial of that number and add it to the total factorial sum.
        for ($i = 0; $i < strlen($adjacentFiveNums); $i++) {
            $factorial = computeFactorial(substr($adjacentFiveNums, $i, 1));
            $totalFactorialSum = $totalFactorialSum + $factorial;
        }

        return $totalFactorialSum;
    }

    // Return an error message that the input length is too small.
    else if (strcmp($adjacentFiveNums, "Error! Input length is too small.") == 0) {
        return "Error! Input length is too small.";
    }

    // Return an error message that the input contains invalid characters.
    else {
        return "Error! Input contains invalid characters.";
    }
}

/**
 * Given a specific subset of inputs, the function tests the 2 main functions: findAdjacentFiveNums() and computeFactorialSum().
 */
function test() {
    // Normal input test: the given input is a string of numbers.
    // Will output a string of 5 numbers.
    $adj1 = findAdjacentFiveNums("21021879693345");
    
    if (strcmp($adj1, "87969") == 0) {
        echo "Test 1 passed!<br>";
    }

    else {
        echo "Test 1 failed!<br>";
    }

    // Normal input test: the given input is a string of 5 numbers since findAdjacentFiveNums("21021879693345") is a valid input.
    // Will output a number.
    $fac1 = computeFactorialSum($adj1);

    if ($fac1 == 771840) {
        echo "Test 2 passed!<br>";
    }

    else {
        echo "Test 2 failed!<br>";
    }

    // Normal input test: the given input is a string of numbers.
    // Will output a string of 5 numbers.
    $adj2 = findAdjacentFiveNums("9587703422192");

    if (strcmp($adj2, "95877") == 0) {
        echo "Test 3 passed!<br>";
    }

    else {
        echo "Test 3 failed!<br>";
    }

    // Normal input test: the given input is a string of 5 numbers since findAdjacentFiveNums("9587703422192") is a valid input.
    // Will output a number.
    $fac2 = computeFactorialSum($adj2);

    if ($fac2 == 413400) {
        echo "Test 4 passed!<br>";
    }

    else {
        echo "Test 4 failed!<br>";
    }

    // Invalid input test: the given input is an empty string.
    // Will output an error message.
    $adj3 = findAdjacentFiveNums("");
    
    if (strcmp($adj3, "Error! Input length is too small.") == 0) {
        echo "Test 5 passed!<br>";
    }

    else {
        echo "Test 5 failed!<br>";
    }

    // Invalid input test: the given input is an error message since findAdjacentFiveNums("") is an invalid input.
    // Will output an error message.
    $fac3 = computeFactorialSum($adj3);

    if (strcmp($fac3, "Error! Input length is too small.") == 0) {
        echo "Test 6 passed!<br>";
    }

    else {
        echo "Test 6 failed!<br>";
    }

    // Invalid input test: the given input contains negative numbers.
    // Will output an error message.
    $adj4 = findAdjacentFiveNums("-9462-5834-4-3-7110");

    if (strcmp($adj4, "Error! Input contains invalid characters.") == 0) {
        echo "Test 7 passed!<br>";
    }

    else {
        echo "Test 7 failed!<br>";
    }

    // Invalid input test: the given input is an error message since findAdjacentFiveNums("-9462-5834-4-3-7110") is an invalid input.
    // Will output an error message.
    $fac4 = computeFactorialSum($adj4);

    if (strcmp($fac4, "Error! Input contains invalid characters.") == 0) {
        echo "Test 8 passed!<br>";
    }

    else {
        echo "Test 8 failed!<br>";
    }

    // Invalid input test: the given input contains letters and symbols.
    // Will output an error message.
    $adj5 = findAdjacentFiveNums("et37fg%^@)82;>/9");

    if (strcmp($adj5, "Error! Input contains invalid characters.") == 0) {
        echo "Test 9 passed!<br>";
    }
 
    else {
        echo "Test 9 failed!<br>";
    }

    // Invalid input test: the given input is an error message since findAdjacentFiveNums("et37fg%^@)82;>/9") is an invalid input.
    // Will output an error message.
    $fac5 = computeFactorialSum($adj5);

    if (strcmp($fac5, "Error! Input contains invalid characters.") == 0) {
        echo "Test 10 passed!<br>";
    }

    else {
        echo "Test 10 failed!<br>";
    }

    // Invalid input test: the given input contains decimal values.
    // Will output an error message.
    $adj6 = findAdjacentFiveNums("8.9455.34.9.1");

    if (strcmp($adj6, "Error! Input contains invalid characters.") == 0) {
        echo "Test 11 passed!<br>";
    }
 
    else {
        echo "Test 11 failed!<br>";
    }

    // Invalid input test: the given input is an error message since findAdjacentFiveNums("8.9455.34.9.1") is an invalid input.
    // Will output an error message.
    $fac6 = computeFactorialSum($adj6);

    if (strcmp($fac6, "Error! Input contains invalid characters.") == 0) {
        echo "Test 12 passed!<br>";
    }

    else {
        echo "Test 12 failed!<br>";
    }

    // Invalid input test: the length of the given input is less than 5.
    // Will output an error message.
    $adj7 = findAdjacentFiveNums("7854");

    if (strcmp($adj7, "Error! Input length is too small.") == 0) {
        echo "Test 13 passed!<br>";
    }

    else {
        echo "Test 13 failed!<br>";
    }

    // Invalid input test: the given input is an error message since findAdjacentFiveNums("7854") is an invalid input.
    // Will output an error message.
    $fac7 = computeFactorialSum($adj7);

    if (strcmp($fac7, "Error! Input length is too small.") == 0) {
        echo "Test 14 passed!<br>";
    }

    else {
        echo "Test 14 failed!<br>";
    }
}

// HTML code for uploading a file.
echo <<<_END
    <html><head><title>Text File Upload</title></head><body>
    <form method='post' action='assignment3.php' enctype='multipart/form-data'>
    Select a TXT File:
    <input type='file' name='filename' size='10'>
    <input type='submit' value='Upload'></form>
_END;

if ($_FILES) {
    $name = htmlentities($_FILES['filename']['name']); // Gets the actual name of the uploaded file.
    $file_length = 1000;
    
    // If the file content type is 'text/plain', make the file extension as 'txt'.
    // Otherwise, make the file extension an empty string.
    switch(htmlentities($_FILES['filename']['type'])) {
        case 'text/plain' : $ext = 'txt'; break;
        default : $ext = ''; break;
    }

    // The file extension is not empty.
    if ($ext) {
        $file = htmlentities(file_get_contents($name)); // Reads the entire file.

        // Replaces all new lines and carriage returns with an empty string.
        $file = str_replace("\n", "", $file);
        $file = str_replace("\r", "", $file);

        // The file length should be exactly 1000.
        if (strlen($file) == $file_length) {
            $adjacentFiveNums = findAdjacentFiveNums($file); // Stores the output of findFiveAdjacentNums().

            // If the file contains invalid characters, return an error message.
            if (strcmp($adjacentFiveNums, "Error! Input contains invalid characters.") == 0) {
                echo "$name contains invalid characters.<br>";
            }

            // Prints the 5 adjacent numbers that added together give the largest sum, and prints the largest sum.
            // Prints the factorial of each term of the largest sum, and prints the total factorial sum.
            else {
                $largestSumFormula = substr($adjacentFiveNums, 0, 1) . " + " . substr($adjacentFiveNums, 1, 1) . " + " . substr($adjacentFiveNums, 2, 1) . " + " . substr($adjacentFiveNums, 3, 1) . " + " . substr($adjacentFiveNums, 4, 1) . " = ";
                $largestSum = computeLargestSum($adjacentFiveNums);
                $factorialSumFormula = substr($adjacentFiveNums, 0, 1) . "! + " . substr($adjacentFiveNums, 1, 1) . "! + " . substr($adjacentFiveNums, 2, 1) . "! + " . substr($adjacentFiveNums, 3, 1) . "! + " . substr($adjacentFiveNums, 4, 1) . "! = ";
                $factorialSum = computeFactorialSum($adjacentFiveNums);
                echo "The 5 adjacent numbers that added together give the largest sum are: $largestSumFormula $largestSum<br>";
                echo "The sum of the factorial of each term of the largest sum is: $factorialSumFormula $factorialSum<br>";
            }
        }

        // The file length is not exactly 1000, so an error message is printed.
        else {
            echo "$name does not contain exactly 1000 numbers.<br>";
        }
    }

    // If the file extension is empty, the file does not have the correct extension, so an error message is printed.
    else {
        echo "$name is not an accepted text file.<br>";
    }
}

echo "</body></html>";
echo "<br>----------TESTER----------<br>";

test(); // Calls the test() function.

?>