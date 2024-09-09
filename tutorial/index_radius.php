<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="index_radius.php" method="post">
        <label>radius</label>
        <input type="text" name="radius">
        <input type="submit" value="calculate">
    </form>
</body>
</html>

<?php 
    if($_SERVER["REQUEST_METHOD"] == "POST") {
        $radius = isset($_POST["radius"]) ? $_POST["radius"] : 0;
        $circumference = null;

        
    

    }

?>