<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="index_post.php" method="POST">
        <label>quantity: </label><br>
        <input type="text" name="quantity">
        <input type="submit" value="total">
    </form>
    
    <?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $item = "pizza";
        $price = 10.83;
        $quantity = isset($_POST["quantity"]) ? $_POST["quantity"] : 0; // Initialize quantity to 0 if not set
        $total = null;

        if ($quantity !== '') {
            $total = $quantity * $price;
            echo "You have ordered: " . htmlspecialchars($quantity) . " x pizza/s <br>";
            echo "Your total is: \${$total}";
        } else {
            echo "Please enter a quantity.";
        }
    }
    ?>
    
</body>
</html>
