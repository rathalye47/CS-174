<?php

class Primes {

    /**
     * Given a numerical parameter in input, the helper function determines whether the number is a prime number or not.
     */
    private function isPrime($num) {
        // Variable to keep track of the count of the given input's divisors.
        $count = 0;

        // If the given input has positive divisors, increment the count.
        for ($i = 1; $i <= $num; $i++) {
            if ($num % $i == 0) {
                $count = $count + 1;
            }
        }

        // If the count is 2, meaning the input's only divisors are 1 and the input, the input is a prime number.
        if ($count == 2) {
            return "TRUE";
        }

        // Otherwise, the input is not a prime number.
        else {
            return "FALSE";
        }
    }

    /**
     * Given 2 numerical parameters in input, the helper function determines whether they are invalid or not.
     */
    private function invalidInput($a, $b) {
        // If at least 1 of the inputs is not an integer, the input(s) is invalid.
        if (!is_int($a) || !is_int($b)) {
            return "Error 1";
        }
        
        // If at least 1 of the inputs is negative, the input(s) is invalid.
        else if ($a < 0 || $b < 0) {
            return "Error 2";
        }

        // If the 1st input is greater than the 2nd input, the inputs are invalid.
        else if ($a > $b) {
            return "Error 3";
        }
        
        // Otherwise, the inputs are valid.
        else {
            return "No error";
        }
    }

    /**
     * Given 2 numerical parameters in input, the function computes and prints all the prime numbers in between the 2 numerical values.
     */
    private function primesInRange($a, $b) {
        // Stores the output of the invalidInput() function.
        $invalid = $this->invalidInput($a, $b);

        // If $invalid outputs "Error 1", at least 1 of the inputs is not an integer, and an error message is given.
        if (strcmp($invalid, "Error 1") == 0) {
            return "Error! One or more of the inputs is not an integer.";
        }

        // If $invalid outputs "Error 2", at least 1 of the inputs is negative, and an error message is given.
        else if (strcmp($invalid, "Error 2") == 0) {
            return "Error! One or more of the inputs is negative.";
        }

        // If $invalid outputs "Error 3", the 1st input is greater than the 2nd input, and an error message is given.
        else if (strcmp($invalid, "Error 3") == 0) {
            return "Error! The first input is greater than the second input.";
        }

        // Otherwise, the inputs are valid.
        else {
            $primeNumsArr = array();
            $allPrimes = "";

            // Finds all prime numbers in between the 2 numerical inputs and stores these prime numbers in the primeNumsArr array.
            for ($i = $a; $i <= $b; $i++) {
                $result = $this->isPrime($i);

                if (strcmp($result, "TRUE") == 0) {
                    $primeNumsArr[] = $i;
                }
            }

            // Prints all the elements in the primeNumsArr array with each element separated by a comma.
            for ($i = 0; $i < count($primeNumsArr); $i++) {
                if ($i == count($primeNumsArr) - 1) {
                    $allPrimes = $allPrimes . $primeNumsArr[$i];
                }

                else {
                    $allPrimes = $allPrimes . $primeNumsArr[$i] . ", ";
                }
            }

            return $allPrimes;
        }
    }

    /**
     * Given a specific subset of inputs, the function tests the function above.
     */
    public function test() {
        // Normal test: the given inputs are valid.
        // Will print all the prime numbers between the given inputs.
        $test1 = $this->primesInRange(2, 24);

        if (strcmp($test1, "2, 3, 5, 7, 11, 13, 17, 19, 23") == 0) {
            echo "Test passed for primesInRange(2, 24).<br>";
        }
    
        else {
            echo "Test failed for primesInRange(2, 24).<br>";
        }

        // Normal test: the given inputs are valid.
        // Will print all the prime numbers between the given inputs.
        $test2 = $this->primesInRange(32, 47);

        if (strcmp($test2, "37, 41, 43, 47") == 0) {
            echo "Test passed for primesInRange(32, 47).<br>";
        }
    
        else {
            echo "Test failed for primesInRange(32, 47).<br>";
        }

        // Normal test: the given inputs are valid.
        // Will print an empty string since there are no prime numbers between the given inputs.
        $test3 = $this->primesInRange(74, 78);

        if (strcmp($test3, "") == 0) {
            echo "Test passed for primesInRange(74, 78).<br>";
        }
    
        else {
            echo "Test failed for primesInRange(74, 78).<br>";
        }

        // Normal test: the given inputs are valid.
        // Will print an empty string since there are no prime numbers between the given inputs.
        $test4 = $this->primesInRange(90, 96);

        if (strcmp($test4, "") == 0) {
            echo "Test passed for primesInRange(90, 96).<br>";
        }
    
        else {
            echo "Test failed for primesInRange(90, 96).<br>";
        }

        // Corner case test: the given inputs are valid.
        // Will print all the prime numbers between the given inputs, but should not print 0 or 1 since they are not prime numbers.
        $test5 = $this->primesInRange(0, 5);

        if (strcmp($test5, "2, 3, 5") == 0) {
            echo "Test passed for primesInRange(0, 5).<br>";
        }
    
        else {
            echo "Test failed for primesInRange(0, 5).<br>";
        }

        // Invalid input test: at least 1 of the given inputs is a negative number.
        // Will print an error message.
        $test6 = $this->primesInRange(-50, -35);

        if (strcmp($test6, "Error! One or more of the inputs is negative.") == 0) {
            echo "Test passed for primesInRange(-50, -35).<br>";
        }
    
        else {
            echo "Test failed for primesInRange(-50, -35).<br>";
        }

        // Invalid input test: the 1st input is greater than the 2nd input.
        // Will print an error message.
        $test7 = $this->primesInRange(100, 80);

        if (strcmp($test7, "Error! The first input is greater than the second input.") == 0) {
            echo "Test passed for primesInRange(100, 80).<br>";
        }
    
        else {
            echo "Test failed for primesInRange(100, 80).<br>";
        }

        // Invalid input test: at least 1 of the given inputs is a decimal.
        // Will print an error message.
        $test8 = $this->primesInRange(12, 34.93);

        if (strcmp($test8, "Error! One or more of the inputs is not an integer.") == 0) {
            echo "Test passed for primesInRange(12, 34.93).<br>";
        }
    
        else {
            echo "Test failed for primesInRange(12, 34.93).<br>";
        }

        // Invalid input test: at least 1 of the given inputs is a string.
        // Will print an error message.
        $test9 = $this->primesInRange('Pasta', 111);

        if (strcmp($test9, "Error! One or more of the inputs is not an integer.") == 0) {
            echo "Test passed for primesInRange('Pasta', 111).<br>";
        }
    
        else {
            echo "Test failed for primesInRange('Pasta', 111).<br>";
        }
    }
}

// Assigns an object to the Primes class.
$obj = new Primes(); 

// Calls the test() method.
$obj->test();

?>