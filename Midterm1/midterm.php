<?php

class RomanNumerals {
    // Constant associative array that maps a few of the important Roman numerals to their equivalent Hindu-Arabic numerals.
    private const CONVERSIONS = array('L' => 50,
                              'XL' => 40,
                              'X' => 10,
                              'IX' => 9,
                              'V' => 5,
                              'IV' => 4,
                              'I' => 1);

    /**
     * Given a string of Roman numerals, the helper function determines if they are valid.
     */
    private static function isValidRomanNum($romanNums) {
        $pattern = "#^(XL|L?X{0,3})(IX|IV|V?I{0,3})$#";

        // If the input is empty, return an error message.
        if (strlen($romanNums) == 0) {
            return "Error! The numeral is empty.";
        }

        // If the input does not contain the regular expression, return an error message.
        else if (!preg_match($pattern, $romanNums)) {
            return "Error! $romanNums is not a valid numeral.";
        }

        // Otherwise, the input is valid.
        else {
            return "The given Roman numeral is valid.";
        }
    }

    /**
     * Given a string of Roman numerals, the function converts them to Hindu-Arabic numerals.
     */
    public static function RomanToInteger($romanNums) {
        $invalid = self::isValidRomanNum($romanNums); // Stores the output from isValidRomanNum().

        // If the output from isValidRomanNum() is an error message, return it.
        if (str_contains($invalid, "Error!")) {
            return $invalid;
        }

        else {
            $originalRomanNums = $romanNums; // Saves a copy of the original Roman numeral.
            $result = 0;

            // Iterates through the associative array where the key is the Roman numeral and value is its equivalent Hindu-Arabic numeral.
            foreach (self::CONVERSIONS as $key => $value) {
                // Executes while the position of the first occurrence of the key inside of the input is 0.
                while (strpos($romanNums, $key) === 0) {
                    $result = $result + $value; // Adds the key's value to a running total.
                    $romanNums = substr($romanNums, strlen($key)); // Shortens the input string by now starting from the index of the length of the key.
                }
            }

            // If the equivalent Hindu-Arabic numeral is greater than the maximum value of 50, return an error message.
            if ($result > 50) {
                return "Error! The given Roman numeral of $originalRomanNums has an equivalent Hindu-Arabic numeral that is greater than 50.";
            }

            // Otherwise, return the Hindu-Arabic numeral.
            else {
                return $result;
            }       
        }
    }

    /**
     * Given a string of Hindu-Arabic numerals, the helper function determines if they are valid.
     */
    private static function isValidHinduArabicNum($num) {
        // If the input is not numeric, or if the input starts with a '0', or if the input contains a decimal, return an error message.
        if (!is_numeric($num) || str_starts_with($num, '0') || str_contains($num, '.')) {
            return "Error! $num is not a valid numeral.";
        }

        // If the input is greater than the maximum value of 50, return an error message.
        else if ($num > 50) {
            return "Error! The given Hindu-Arabic numeral of $num is greater than 50.";
        }

        // If the input is less than 0, return an error message.
        else if ($num < 0) {
            return "Error! The given Hindu-Arabic numeral of $num is negative.";
        }

        // Otherwise, the input is valid.
        else {
            return "The given Hindu-Arabic numeral is valid.";
        }
    }

    /**
     * Given a string of Hindu-Arabic numerals, the function converts them to Roman numerals.
     */
    public static function IntegerToRoman($num) {
        $invalid = self::isValidHinduArabicNum($num); // Stores the output from isValidHinduArabicNum().

        // If the output from isValidHinduArabicNum() is an error message, return it.
        if (str_contains($invalid, "Error!")) {
            return $invalid;
        }

        else {
            $romanNum = ""; // Stores the Roman numeral.

            // Iterates through the associative array where the key is the Roman numeral and value is its equivalent Hindu-Arabic numeral.
            foreach (self::CONVERSIONS as $key => $value) {
                // Executes while the input is greater than or equal to the key's value.
                while ($num >= $value) {
                    $num = $num - $value; // Subtracts the key's value from the input, and updates the input.
                    $romanNum = $romanNum . $key; // Adds the key to the final Roman numeral result.
                }
            }

            return $romanNum;
        } 
    }

    /**
     * Given a specific subset of inputs, the function tests RomanToInteger() and IntegerToRoman().
     */
    public static function test() {
        // Correct input test: input is a valid Roman numeral.
        // Will output its equivalent Hindu-Arabic numeral.
        $test1 = self::RomanToInteger('XXIV');

        if ($test1 == 24) {
            echo "Test passed for RomanToInteger('XXIV').<br>";
        }

        else {
            echo "Test failed for RomanToInteger('XXIV').<br>";
        }

        // Correct input test: input is a valid Roman numeral.
        // Will output its equivalent Hindu-Arabic numeral.
        $test2 = self::RomanToInteger('XLVII');

        if ($test2 == 47) {
            echo "Test passed for RomanToInteger('XLVII').<br>";
        }

        else {
            echo "Test failed for RomanToInteger('XLVII').<br>";
        }

        // Invalid input test: input is an empty string.
        // Will output an error message.
        $test3 = self::RomanToInteger('');

        if (strcmp($test3, "Error! The numeral is empty.") == 0) {
            echo "Test passed for RomanToInteger('').<br>";
        }

        else {
            echo "Test failed for RomanToInteger('').<br>";
        }

        // Invalid input test: input is a bunch of random characters.
        // Will output an error message.
        $test4 = self::RomanToInteger('ft5*@./ju');

        if (strcmp($test4, "Error! ft5*@./ju is not a valid numeral.") == 0) {
            echo "Test passed for RomanToInteger('ft5*@./ju').<br>";
        }

        else {
            echo "Test failed for RomanToInteger('ft5*@./ju').<br>";
        }

        // Invalid input test: input is a string of Roman numerals, but their order is wrong.
        // Will output an error message.
        $test5 = self::RomanToInteger('VIIV');

        if (strcmp($test5, "Error! VIIV is not a valid numeral.") == 0) {
            echo "Test passed for RomanToInteger('VIIV').<br>";
        }

        else {
            echo "Test failed for RomanToInteger('VIIV').<br>";
        }

        // Invalid input test: input is a valid Roman numeral, but the equivalent Hindu-Arabic numeral exceeds the maximum value of 50.
        // Will output an error message.
        $test6 = self::RomanToInteger('LIX');

        if (strcmp($test6, "Error! The given Roman numeral of LIX has an equivalent Hindu-Arabic numeral that is greater than 50.") == 0) {
            echo "Test passed for RomanToInteger('LIX').<br>";
        }

        else {
            echo "Test failed for RomanToInteger('LIX').<br>";
        }

        // Correct input test: input is a valid Hindu-Arabic numeral.
        // Will output its equivalent Roman numeral.
        $test7 = self::IntegerToRoman('36');

        if (strcmp($test7, "XXXVI") == 0) {
            echo "Test passed for IntegerToRoman('36').<br>";
        }

        else {
            echo "Test failed for IntegerToRoman('36').<br>";
        }

        // Correct input test: input is a valid Hindu-Arabic numeral.
        // Will output its equivalent Roman numeral.
        $test8 = self::IntegerToRoman('15');

        if (strcmp($test8, "XV") == 0) {
            echo "Test passed for IntegerToRoman('15').<br>";
        }

        else {
            echo "Test failed for IntegerToRoman('15').<br>";
        }

        // Invalid input test: input is a bunch of random characters.
        // Will output an error message.
        $test9 = self::IntegerToRoman('bd023rt^');

        if (strcmp($test9, "Error! bd023rt^ is not a valid numeral.") == 0) {
            echo "Test passed for IntegerToRoman('bd023rt^').<br>";
        }

        else {
            echo "Test failed for IntegerToRoman('bd023rt^').<br>";
        }

        // Invalid input test: input is a decimal.
        // Will output an error message.
        $test10 = self::IntegerToRoman('5.93');

        if (strcmp($test10, "Error! 5.93 is not a valid numeral.") == 0) {
            echo "Test passed for IntegerToRoman('5.93').<br>";
        }

        else {
            echo "Test failed for IntegerToRoman('5.93').<br>";
        }

        // Invalid input test: input is a valid Hindu-Arabic numeral, but it exceeds the maximum value of 50.
        // Will output an error message.
        $test11 = self::IntegerToRoman('78');

        if (strcmp($test11, "Error! The given Hindu-Arabic numeral of 78 is greater than 50.") == 0) {
            echo "Test passed for IntegerToRoman('78').<br>";
        }

        else {
            echo "Test failed for IntegerToRoman('78').<br>";
        }

        // Invalid input test: input is a negative number.
        // Will output an error message.
        $test12 = self::IntegerToRoman('-8');

        if (strcmp($test12, "Error! The given Hindu-Arabic numeral of -8 is negative.") == 0) {
            echo "Test passed for IntegerToRoman('-8').<br>";
        }

        else {
            echo "Test failed for IntegerToRoman('-8').<br>";
        }

        // Invalid input test: input is 0, which can't be converted to a Roman numeral.
        // Will output an error message.
        $test13 = self::IntegerToRoman('0');

        if (strcmp($test13, "Error! 0 is not a valid numeral.") == 0) {
            echo "Test passed for IntegerToRoman('0').<br>";
        }

        else {
            echo "Test failed for IntegerToRoman('0').<br>";
        }
    }

    /**
     * The function reads the file from the user and interprets its contents.
     */
    public static function interpretFile() {
        if ($_FILES) {
            $name = htmlentities($_FILES['filename']['name']); // Gets the actual name of the uploaded file.
            
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
        
                // If the file contains spaces, output an error message.
                if (str_contains($file, " ")) {
                    echo "Error! $name contains spaces.<br>";
                }
        
                else {
                    // If the file is empty, or if the file contains either "L", "X", "V", or "I", we can assume the user intended their input to be a Roman numeral.
                    if ((strlen($file) == 0) || str_contains($file, "L") || str_contains($file, "X") || str_contains($file, "V") || str_contains($file, "I")) {
                        $hinduArabicNum = self::RomanToInteger($file); // Stores the output of RomanToInteger() when the file is passed in as input.

                        // If the output is an error message, display it.
                        if (str_contains($hinduArabicNum, "Error!")) {
                            echo $hinduArabicNum . "<br>";
                        }

                        // Otherwise, display the equivalent Hindu-Arabic numeral.
                        else {
                            echo "The equivalent Hindu-Arabic numeral of $file is $hinduArabicNum.<br>";
                        }
                    }

                    // Otherwise, we can assume the user intended their input to be a Hindu-Arabic numeral.
                    else {
                        $romanNum = self::IntegerToRoman($file); // Stores the output of IntegerToRoman() when the file is passed in as input.
                        
                        // If the output is an error message, display it.
                        if (str_contains($romanNum, "Error!")) {
                            echo $romanNum . "<br>";
                        }

                        // Otherwise, display the equivalent Roman numeral.
                        else {
                            echo "The equivalent Roman numeral of $file is $romanNum.<br>";
                        }
                    }
                }
            }
        
            // If the file extension is empty, the file does not have the correct extension, so an error message is displayed.
            else {
                echo "Error! $name is not an accepted text file.<br>";
            }
        }
    }
}

// HTML code for uploading a file.
echo <<<_END
    <html><head><title>Text File Upload</title></head><body>
    <form method='post' action='midterm.php' enctype='multipart/form-data'>
    Select a TXT File:
    <input type='file' name='filename' size='10'>
    <input type='submit' value='Upload'></form>
_END;

RomanNumerals::interpretFile(); // Calls the RomanNumerals class along with the static interpretFile() method.

echo "</body></html>";
echo "<br>----------TESTER----------<br>";

RomanNumerals::test(); // Calls the RomanNumerals class along with the static test() method.

?>