<?php
require "connection.php";
$stmt2 = $conn->prepare("INSERT INTO `order_item`(`pizza_id`, `amount`, `unit_price`, `order_id`) VALUES (:pizza_id, :amount, :unit_price, :order_id)");
                $stmt2->bindParam(':pizza_id', $id, PDO::PARAM_INT);
                $stmt2->bindParam(':amount', $amount, PDO::PARAM_INT);
                $stmt2->bindParam(':unit_price', $price, PDO::PARAM_INT);
                $stmt2->bindParam(':order_id', $order_id, PDO::PARAM_INT);
                $id = 2;
                $amount = 3;
                $price = 9.54;
                $order_id = 21;
                $stmt2->execute()
?>