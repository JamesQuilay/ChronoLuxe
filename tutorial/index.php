<?php
    echo "I love ph <br>";
    echo "Its really good";

    // this is comment
    /* Thuss
    is
    a
    multi
    comment*/

    // variables

    $name = "James";
    $email = "fake@gmail.com";
    $age = 21;
    $users = 2;
    $quantity = 10;
    $food = "apple";

    // float variables
    $amount = 2.43;
    $price = 544.423;

    //bool variables
    $is_true = false;
    $total = null;



    

    // string literal

    echo " <br> Hello {$name}";
    echo " <br> Email: {$email}";
    echo " <br> Age: {$age} ";
    echo "<br Users online: {$users} ";
    echo "<br> you order: {$quantity} piiza";
    echo " <br> your grade is: {$amount} ";
    echo " <br> your pizza price is {$price} ";
    echo "<br> the values is {$is_true} ";
    echo "<br> you have orders {$quantity} x {$food}s ";
    $total = $quantity * $price;
    echo "<br> your total is: \${$total} ";

    // arithmetic operators
    // + - * / ** %

    // increment/decrement operators
    // ++, --

    // operator precedence
    // ()
    // **
    // * / %
    // + -

    $x = 10;
    $y = 20;
    $z = null; //for later

    
    // $z = $x + $y;
    // $z = $x - $y;
    // $z = $x * $y;
    // $z = $x / $y;
    // $z = $x ** $y; raise to power is $y

    
    echo "<br>Total : {$z}";


    // increment/decrement operator
    $counter = 10; //+ 1 


    $counter--; // -1

    echo "<br> counter: {$counter}";

    $total = 1 + -3 * 4 / 5355;

    echo "<br> the total: {$total}";



    //$_GET, $_POST = special varriables to collect data from html form data is sent to the file in the section attribute of <form> like <form action="somefile.php" method="get">


    // $_GET = data is appendd to the url 
    // $_POST = data is packaged inside the body of he http request

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
    <br>
    <button>order pizza</button>
    
</body>
</html>