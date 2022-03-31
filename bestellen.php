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
    <link rel="stylesheet" href="styles/main.css">
    <title>Document</title>
</head>
<body>
    <div class="wrapper">
        <header class="black">
            <div class="logocontainer">
                Sopranos
            </div>
            <nav class="black">
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="overons.php">Over ons</a></li>
                </ul>
            </nav>
        </header>
        <div id='main'>
            <?php 
            require "connection.php";
            $sql = 'SELECT p.id, p.size, p.price, p.type_id, t.name, t.img FROM pizza p INNER JOIN type t ON p.type_id = t.id';
            $stmt = $conn->query($sql);
            $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
            foreach ($data as $row){
                echo "<div class='pizzalist'><img src=".$row['img'].">";
                echo "<table> <tr><td>" . $row['name'] . "</td></tr><tr><td>" . $row['price'] . "</td></tr><tr><td>" . $row['size'] . "</td></tr> </table>";
                echo "<form method='POST' action='" . 'bestellen.php' . "'>";
                echo "<input type='hidden' name='pizzaID' id='pizzaID' value=" . $row['id'] . ">";
                echo "<input type='number' name='amount' id='amount' value=1>";
                echo "<input type='submit' value='order'>";
                echo "</form>";
                echo "</div>";
            }                
            if ($_SERVER["REQUEST_METHOD"] == "POST") {
                echo "recieved";
                if (!empty($_POST['pizzaID']) && !empty($_POST['amount'])){
                    $pizzaID = $_POST['pizzaID'];
                    $amount = $_POST['amount'];
                    if(empty($_SESSION['cart'][$pizzaID])){
                        $_SESSION['cart'][$pizzaID] = $amount;
                    }else{
                        $_SESSION['cart'][$pizzaID] += $amount;
                        if ($_SESSION['cart'][$pizzaID] <= 0){
                            unset($_SESSION['cart'][$pizzaID]);
                        }
                    }
                    echo $pizzaID;
                    print_r($_SESSION['cart']);
                }else{
                    echo "empty fields";
                }
            }
            ?>
        </main>
    </div>
</body>
</html>