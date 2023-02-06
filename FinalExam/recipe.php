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

// Checks if $_SESSION['loginUsername] has been set.
if (isset($_SESSION['loginUsername'])) {
    // If the stored IP address doesn't match the current one, different_user() is called.
    // Provides security against session hijacking.
    if ($_SESSION['ip'] != $_SERVER['REMOTE_ADDR']) {
        different_user();
    }

    // Provides more security against session hijacking if users are on the same proxy server, share the same IP address on a home/business network, or have the same IP address.
    if ($_SESSION['check'] != hash('ripemd128', $_SERVER['REMOTE_ADDR'] . $_SERVER['HTTP_USER_AGENT'])) {
        different_user();
    }

    $username = $_SESSION['loginUsername']; // Stores $_SESSION['loginUsername'].

    // If the 'Logout' button is clicked, the session is ended and the user is directed to 'authenticate.php'.
    if (isset($_POST['logout'])) {
        destroy_session_and_data();
        header('Location: authenticate.php');
        exit();
    }

    // Form for the user to select ingredients.
    echo <<<_END
    <html><head><title>Cookbook</title></head>
    <style>
    h1 {
        text-align: center;
    }
    </style>

    <body style="background-color:blanchedalmond;">
    <h1>Dessert Cucinare</h1>

    <p align="right">
    <form method='post' action='recipe.php'>
    <input type='submit' name='logout' value='Logout' style="float: right;">
    </form>
    </p>

    <h2>Ingredients</h2>

    <form method='post' action='recipe.php'>
    <hr>
    <input type="checkbox" name="ingredient1" value="Cocoa Powder">
    <label>Cocoa Powder</label><br>

    <input type="checkbox" name="ingredient2" value="Walnuts">
    <label>Walnuts</label><br>

    <input type="checkbox" name="ingredient3" value="Chocolate Chips">
    <label>Chocolate Chips</label><br>

    <input type="checkbox" name="ingredient4" value="Mascarpone">
    <label>Mascarpone</label><br>

    <input type="checkbox" name="ingredient5" value="Coffee">
    <label>Coffee</label><br>

    <input type="checkbox" name="ingredient6" value="Ladyfingers">
    <label>Ladyfingers</label><br>

    <input type="checkbox" name="ingredient7" value="Apples">
    <label>Apples</label><br>

    <input type="checkbox" name="ingredient8" value="Cinnamon">
    <label>Cinnamon</label><br>

    <input type="checkbox" name="ingredient9" value="Nutmeg">
    <label>Nutmeg</label><br>

    <input type="checkbox" name="ingredient10" value="Carrots">
    <label>Carrots</label><br>

    <input type="checkbox" name="ingredient11" value="Graham Crackers">
    <label>Graham Crackers</label><br>

    <input type="checkbox" name="ingredient12" value="Marshmallows">
    <label>Marshmallows</label><br>

    <input type="checkbox" name="ingredient13" value="Pumpkins">
    <label>Pumpkins</label><br>

    <input type="checkbox" name="ingredient14" value="Whipped Cream">
    <label>Whipped Cream</label><br>

    <input type="checkbox" name="ingredient15" value="Peaches">
    <label>Peaches</label><br>

    <input type="checkbox" name="ingredient16" value="Bananas">
    <label>Bananas</label><br>

    <input type="checkbox" name="ingredient17" value="Vanilla Wafers">
    <label>Vanilla Wafers</label><br>

    <hr><br>

    <input type='submit' name='Submit' value='Find Recipes'>
    </form>
    <br>
    </body>
    </html>
    _END;

    $compatibleRecipes = array(); // Array to store the recipes that contain the selected ingredients.
    
    // Boolean flags to check if an ingredient has been selected.
    $checkedIngredient1 = false;
    $checkedIngredient2 = false;
    $checkedIngredient3 = false;
    $checkedIngredient4 = false;
    $checkedIngredient5 = false;
    $checkedIngredient6 = false;
    $checkedIngredient7 = false;
    $checkedIngredient8 = false;
    $checkedIngredient9 = false;
    $checkedIngredient10 = false;
    $checkedIngredient11 = false;
    $checkedIngredient12 = false;
    $checkedIngredient13 = false;
    $checkedIngredient14 = false;
    $checkedIngredient15 = false;
    $checkedIngredient16 = false;
    $checkedIngredient17 = false;

    // Calls the check_recipes() function for each ingredient.
    check_recipes($conn, 'ingredient1', $checkedIngredient1, $compatibleRecipes);
    check_recipes($conn, 'ingredient2', $checkedIngredient2, $compatibleRecipes);
    check_recipes($conn, 'ingredient3', $checkedIngredient3, $compatibleRecipes);
    check_recipes($conn, 'ingredient4', $checkedIngredient4, $compatibleRecipes);
    check_recipes($conn, 'ingredient5', $checkedIngredient5, $compatibleRecipes);
    check_recipes($conn, 'ingredient6', $checkedIngredient6, $compatibleRecipes);
    check_recipes($conn, 'ingredient7', $checkedIngredient7, $compatibleRecipes);
    check_recipes($conn, 'ingredient8', $checkedIngredient8, $compatibleRecipes);
    check_recipes($conn, 'ingredient9', $checkedIngredient9, $compatibleRecipes);
    check_recipes($conn, 'ingredient10', $checkedIngredient10, $compatibleRecipes);
    check_recipes($conn, 'ingredient11', $checkedIngredient11, $compatibleRecipes);
    check_recipes($conn, 'ingredient12', $checkedIngredient12, $compatibleRecipes);
    check_recipes($conn, 'ingredient13', $checkedIngredient13, $compatibleRecipes);
    check_recipes($conn, 'ingredient14', $checkedIngredient14, $compatibleRecipes);
    check_recipes($conn, 'ingredient15', $checkedIngredient15, $compatibleRecipes);
    check_recipes($conn, 'ingredient16', $checkedIngredient16, $compatibleRecipes);
    check_recipes($conn, 'ingredient17', $checkedIngredient17, $compatibleRecipes);

    // If the user clicks the 'Find Recipes' button, display 'Compatible Recipes'.
    if (isset($_POST["Submit"])) {
        echo "<u>Compatible Recipes</u><br><br>";
    }

    // Displays all of the compatible recipes along with a button for the user to save their favorite recipes.
    for ($i = 0; $i < sizeof($compatibleRecipes); $i++) {
        echo "<form method='post' action='recipe.php'>";

        if ($checkedIngredient1) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient1\" value=\"Cocoa Powder\">";
        }

        if ($checkedIngredient2) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient2\" value=\"Walnuts\">";
        }

        if ($checkedIngredient3) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient3\" value=\"Chocolate Chips\">";
        }

        if ($checkedIngredient4) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient4\" value=\"Mascarpone\">";
        }

        if ($checkedIngredient5) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient5\" value=\"Coffee\">";
        }

        if ($checkedIngredient6) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient6\" value=\"Ladyfingers\">";
        }

        if ($checkedIngredient7) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient7\" value=\"Apples\">";
        }

        if ($checkedIngredient8) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient8\" value=\"Cinnamon\">";
        }

        if ($checkedIngredient9) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient9\" value=\"Nutmeg\">";
        }

        if ($checkedIngredient10) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient10\" value=\"Carrots\">";
        }

        if ($checkedIngredient11) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient11\" value=\"Graham Crackers\">";
        }

        if ($checkedIngredient12) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient12\" value=\"Marshmallows\">";
        }

        if ($checkedIngredient13) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient13\" value=\"Pumpkins\">";
        }

        if ($checkedIngredient14) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient14\" value=\"Whipped Cream\">";
        }

        if ($checkedIngredient15) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient15\" value=\"Peaches\">";
        }

        if ($checkedIngredient16) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient16\" value=\"Bananas\">";
        }

        if ($checkedIngredient17) {
            echo "<input type=\"hidden\" name=\"Submit\" value=\"Find Recipes\">";
            echo "<input type=\"hidden\" name=\"ingredient17\" value=\"Vanilla Wafers\">";
        }
        
        echo "$compatibleRecipes[$i]: <input type=\"submit\" name=$i value=\"Save Recipe\" size=\"10\">";

        // If the 'Save Recipe' button is clicked, the recipe is added to the user's cookbook.
        if (isset($_POST[$i])) {
            echo "<font color=green><i>&nbsp&nbsp&nbspRecipe saved!</i></font>";
            add_recipe($conn, $username, $compatibleRecipes[$i]);
        }

        echo "</form>";
    }
}

// The user's session has expired, and they will need to login again.
else {
    echo <<<_END
    <html><head><title>Credentials</title></head>
    <style>
    h1 {
        text-align: center;
    }
    </style>

    <body style="background-color:blanchedalmond;">
    <h1>Dessert Cucinare</h1>

    <form method='post' action='authenticate.php'>
    Click on the button to login again: <input type='submit' value='Login'>
    </form>
    </body>
    </html>
    _END;
}

// Helper function that checks if any of the recipes stored in the 'recipes' table contain the selected ingredient.
function check_recipes($connection, $ingredientName, &$checked, &$recipes) {
    // Checks if the ingredient has been selected by the user.
    if (isset($_POST[$ingredientName])) {
        $checked = true;
        $ingredient = mysql_entities_fix_string($connection, $_POST[$ingredientName]); // Stores the ingredient the user selected.
        $query = "SELECT * FROM recipes"; // Query to select all data in the 'recipes' table.
        $result = $connection->query($query);

        if (!$result) mysql_fatal_error(); // Error checking.
        $rows = $result->num_rows;

        // Iterates through all of the records in the 'recipes' table.
        for ($i = 0; $i < $rows; $i++) {
            $result->data_seek($i); // Seeks the ith row.
            $row = $result->fetch_array(MYSQLI_NUM); // Fetches the item of data in the ith row.

            // Checks if any of the recipes contain the selected ingredient.
            if (str_contains($row[2], $ingredient)) {
                // If the array already contains the recipe due to common ingredient(s) among the recipes, then go to the next record in the table.
                if (in_array($row[1], $recipes)) {
                    continue;
                }

                array_push($recipes, $row[1]); // Adds the recipe to the array.
            } 
        }

        $result->close();
    }
}

// Helper function to sanitize a string.
function mysql_entities_fix_string($connection, $string) {
    return htmlentities(mysql_fix_string($connection, $string));
}

// Helper function to sanitize a string.
function mysql_fix_string($connection, $string) {
    return $connection->real_escape_string($string);
}

// Helper function to add a recipe into the 'cookbook' table.
// Note: escape characters are used since 1 of the recipes contains a quote.
function add_recipe($connection, $un, $rec) {
    $query = "INSERT INTO cookbook VALUES(NULL, \"$un\", \"$rec\")";
    $result = $connection->query($query);
    if (!$result) mysql_fatal_error();
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
    <form method='post' action='authenticate.php'>
    Sorry! Technical error! Please click on the button to login again: <input type='submit' value='Login'>
    </form>
    </body>
    </html>
    _END;
}

$conn->close();

?>