<?php

/**
 * Given a numerical parameter in input, the function outputs if the number is a perfect number, and the divisors and proof of the conclusion.
 */
function isPerfectNumber($num) {
    $divisors = array();
    $sum = 0;
    $proof = "";

    // The given input is not an integer and is thus invalid.
    if (!is_int($num)) {
        return "Invalid input. This is not an integer.";
    }
    
    // If the given input is less than or equal to 1, it is not a perfect number.
    if ($num <= 1) {
        return "No, this is not a perfect number.";
    }

    // Finds all the positive divisors of the given input and stores them in the divisors array.
    for ($i = 1; $i < $num; $i++) {
        if ($num % $i == 0) {
            $divisors[] = $i;
        }
    }

    // Finds the sum of the divisors array.
    $sum = array_sum($divisors);

    // Starts to construct the proof for the given input.
    for ($i = 0; $i < count($divisors); $i++) {
        if ($i == count($divisors) - 1) {
            $proof = $proof . strval($divisors[$i]);
        }

        else {
            $proof = $proof . strval($divisors[$i]) . "+";
        }
    }

    // If the sum of the divisors array is equal to the given input, construct the entire equality proof.
    if ($sum == $num) {
        return "Yes, this is a perfect number. Proof: $proof = $num";
    }

    // Else, construct the entire "not equal" proof.
    else {
        return "No, this is not a perfect number. Proof: $proof != $num";
    }
}

/**
 * Given a specific subset of inputs, the tester function tests the function above.
 */
function test() {
    // Normal test: input is 6, which is a perfect number.
    $test1 = isPerfectNumber(6);

    if (strcmp($test1, "Yes, this is a perfect number. Proof: 1+2+3 = 6") == 0) {
        echo "Test passed for isPerfectNumber(6).<br>";
    }

    else {
        echo "Test failed for isPerfectNumber(6).<br>";
    }

    // Normal test: input is 28, which is a perfect number.
    $test2 = isPerfectNumber(28);

    if (strcmp($test2, "Yes, this is a perfect number. Proof: 1+2+4+7+14 = 28") == 0) {
        echo "Test passed for isPerfectNumber(28).<br>";
    }

    else {
        echo "Test failed for isPerfectNumber(28).<br>";
    }

    // Normal test: input is 10, which is not a perfect number.
    $test3 = isPerfectNumber(10);

    if (strcmp($test3, "No, this is not a perfect number. Proof: 1+2+5 != 10") == 0) {
        echo "Test passed for isPerfectNumber(10).<br>";
    }

    else {
        echo "Test failed for isPerfectNumber(10).<br>";
    }

    // Normal test: input is 21, which is not a perfect number.
    $test4 = isPerfectNumber(21);

    if (strcmp($test4, "No, this is not a perfect number. Proof: 1+3+7 != 21") == 0) {
        echo "Test passed for isPerfectNumber(21).<br>";
    }

    else {
        echo "Test failed for isPerfectNumber(21).<br>";
    }

    // Corner case test: input is 1, which is not a perfect number.
    $test5 = isPerfectNumber(1);

    if (strcmp($test5, "No, this is not a perfect number.") == 0) {
        echo "Test passed for isPerfectNumber(1).<br>";
    }

    else {
        echo "Test failed for isPerfectNumber(1).<br>";
    }

    // Corner case test: input is 0, which is not a perfect number.
    $test6 = isPerfectNumber(0);

    if (strcmp($test6, "No, this is not a perfect number.") == 0) {
        echo "Test passed for isPerfectNumber(0).<br>";
    }

    else {
        echo "Test failed for isPerfectNumber(0).<br>";
    }

    // Negative number test: input is -139, which is negative and thus not a perfect number.
    $test7 = isPerfectNumber(-139);

    if (strcmp($test7, "No, this is not a perfect number.") == 0) {
        echo "Test passed for isPerfectNumber(-139).<br>";
    }

    else {
        echo "Test failed for isPerfectNumber(-139).<br>";
    }

    // Wrong input test: input is 29.42, which is a decimal and thus not a perfect number.
    $test8 = isPerfectNumber(29.42);

    if (strcmp($test8, "Invalid input. This is not an integer.") == 0) {
        echo "Test passed for isPerfectNumber(29.42).<br>";
    }

    else {
        echo "Test failed for isPerfectNumber(29.42).<br>";
    }

    // Wrong input test: input is 'Cookies', which is a string and thus not a perfect number.
    $test9 = isPerfectNumber('Cookies');

    if (strcmp($test9, "Invalid input. This is not an integer.") == 0) {
        echo "Test passed for isPerfectNumber('Cookies').<br>";
    }

    else {
        echo "Test failed for isPerfectNumber('Cookies').<br>";
    }
}

test();

?>