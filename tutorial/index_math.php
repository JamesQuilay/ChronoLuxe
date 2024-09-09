<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <form action="index_math.php" method="post">
        <label>x: </label>
        <input type="text" name="x"><br>
        <label>y: </label>
        <input type="text" name="y"><br>
        <label>z: </label>
        <input type="text" name="z"><br>
        <input type="submit" value="total">
    </form>
    
</body>
</html>

<?php 
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $x = isset($_POST["x"]) ? $_POST["x"] : 0;
        $y = isset($_POST["y"]) ? $_POST["y"] : 0;
        $z = isset($_POST["z"]) ? $_POST["z"] : 0;
        $total = null;

        if ($x !== '' || $y !== '' || $z !== '') {

            // $total = abs($x); //absolute
            // $total = round($x); //round off
            // $total = floor($x); //round down
            // $total = ceil($x); //round up

            // $total = pow($x, $y); // values raised to the power of
            // $total = sqrt($x); // square root of 
            //$total = max($x, $y, $z); // find the max number between values
            // $total = min($x, $y, $z); // find the minimum number between values

            //$total = pi(); // pie function

            $total = rand(1, 100); // random number ranging from 1 to 100

            echo $total;
        }
    

    }

?>