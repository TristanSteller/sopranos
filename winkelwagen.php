<?php 
session_start();
if (empty($_SESSION['cart'])){
    $_SESSION['cart'] = array();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <title>Document</title>
    <style>
        .submit{
            display: none;
        }
        </style>
</head>
<body>
<script>    
function submit(id){
    document.getElementById("form"+id).submit()
    if (document.getElementById('amount' + id).value == 0){
        document.getElementById("row" + id).remove()
    }
}
</script>
<?php
    require "connection.php";
    $cart = $_SESSION['cart'];
    echo "<table class='table'><thead><tr><th>Pizza</th><th>Size</th><th>Price</th><th>Amount</th><th>Total</th></tr></thead>";
    foreach ($cart as $k => $v){
        $sql = 'SELECT p.id, p.size, p.price, p.type_id, t.name, t.img FROM pizza p INNER JOIN type t ON p.type_id = t.id WHERE p.id=' . $k;
        $stmt = $conn->query($sql);
        $data = $stmt->fetch(PDO::FETCH_ASSOC);
        echo "<tr id='row". $k ."'><td>". $data['name'] ."</td>";
        echo "<td>". $data['size'] ."</td>";
        echo "<td>". $data['price'] ."</td>";
        echo "<td><form method='POST' action='winkelwagen.php' id='form".$k."'>";
        echo "<input type='hidden' name='id' value='".$k."'>";
        echo "<input type='number' name='amount' id='amount" . $k ."' value='". $v ."' min='0'></input>";
        echo "</form>";
        echo "<td class='total'>". $v * $data['price'] ."</td>";
        echo "<td class='button'><button onclick=submit(".$k.")>Update</button></td>";
        echo "<td> <form id='delete" . $k . "' method='POST' action='winkelwagen.php'><input type='hidden' value='" . $k . "' name='delete'></input><input value='delete' type='submit'></input></form></td>";
        echo "</tr>";
    }
    echo "</table>";
    echo "<form method='POST' action='winkelwagen.php'>";
    echo "<input type='hidden' name='order' value='1'>";
    echo "<table class='table'>";
    echo "<tr><td>naam</td><td> <input type='text' name='name'></td></tr><br>";
    echo "<tr><td>postcode</td><td> <input type='text' name='postcode'></td></tr><br>";
    echo "<tr><td>straat</td><td> <input type='text' name='street'></td></tr><br>";
    echo "<tr><td>huisnummer</td><td> <input type='number' name='housenumber'></td></tr><br>";
    echo "</table>";
    echo "<input type='submit'>";
    echo "</form>";

    function order($name, $postcode, $street, $housenumber){
        require "connection.php";
        if (empty($_SESSION['cart'])){return;}
        $orderquery = $conn->prepare("INSERT INTO `order`(`name`, `postcode`, `street`, `housenumber`) 
                                VALUES (:name,:postcode,:street,:housenumber)");
        $orderquery->bindParam(':name', $name);
        $orderquery->bindParam(':postcode', $postcode);
        $orderquery->bindParam(':street', $street);
        $orderquery->bindParam(':housenumber', $housenumber);
        $orderquery->execute();
        $getid = $conn->query("SELECT MAX(id) FROM `order`;"); 
        $data = $getid->fetch(PDO::FETCH_ASSOC);
        $order_id = $data['MAX(id)'];
        foreach($_SESSION['cart'] as $id => $amount){
            $stmt = $conn->query("SELECT price FROM `pizza` where id=".$id.";"); 
            $data = $stmt->fetch(PDO::FETCH_ASSOC);
            $price = $data["price"];
            $stmt2 = $conn->prepare("INSERT INTO `order_item`(`pizza_id`, `amount`, `unit_price`, `order_id`) VALUES (:pizza_id, :amount, :unit_price, :order_id)");
            $stmt2->bindParam(':pizza_id', $id);
            $stmt2->bindParam(':amount', $amount);
            $stmt2->bindParam(':unit_price', $price);
            $stmt2->bindParam(':order_id', $order_id);
            $stmt2->execute(); 
        }
        session_destroy();
        header("Location complete.php/?Order_no=".$order_id);
    }

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if (!empty($_POST['amount']) && !empty($_POST['id'])){
            if ($_POST['amount'] == 0){
                unset($_SESSION['cart'][intval($_POST['id'])]);
            }else{
                $_SESSION['cart'][intval($_POST['id'])] = $_POST['amount'];
            }
            header("Refresh:0");
        }else if(!empty($_POST['delete'])){
            unset($_SESSION['cart'][intval($_POST['delete'])]);
            header("Refresh:0");
        }else if(!empty($_POST['order'])){
            order($_POST['name'], $_POST['postcode'], $_POST['street'], $_POST['housenumber']);
        }
    }
    ?>
<div id="result"></div>
</body>
</html> 