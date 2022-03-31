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
    <title>Document</title>
    <style>
        .submit{
            display: none;
        }
    </style>
</head>
<body>
<?php
    require "connection.php";
    $cart = $_SESSION['cart'];
    print_r($cart);
    echo "<table><thead><tr><th>Pizza</th><th>Size</th><th>Price</th><th>Amount</th><th>Total</th></tr></thead>";
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
        echo "<td class='button'><button onclick=submit(".$k.")></td>";
        echo "</tr>";
    }
    echo "</table>";

    function setAdmount($pizzaID, $amount){
        if ($amount <= 0){
            unset($_SESSION['cart'][$pizzaID]);
        }
        $_SESSION['cart'][$pizzaID] = $amount;
    }
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        var_dump($_POST);
        var_dump($_SESSION['cart']);
        if (!empty($_POST['amount']) && !empty($_POST['id'])){
            if (intval($_POST['amount']) == 0){
                unset($_SESSION['cart'][intval($_POST['id'])]);
            }else{
                
            }
        }
    }
    ?>
<script>    
    function submit(id){
        document.getElementById("form"+id).submit()
        if (document.getElementById('amount' + id).value == 0){
            document.getElementById("row" + id).remove()
        }
    }
</script>
<div id="result"></div>
</body>
</html> 