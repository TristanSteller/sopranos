<?php
    require "connection.php";
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        if($_POST['func'] == 'updatetype'){
            $stmt = $conn->prepare("UPDATE `type` SET`name`= :name,`img`= :img WHERE :id ;");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':img', $img);
            $stmt->bindParam(':id', $id);
            $name = $_POST['name'];
            $img = $_POST['img'];
            $id = $_POST['id'];
            $stmt->execute();
        }else if($_POST['func'] == 'updatepizza'){
            $stmt = $conn->prepare("UPDATE `pizza` SET`size`= :size,`price`= :price WHERE `pizza`.`id` = :id ;");
            $stmt->bindParam(':size', $size);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':id', $id);
            $size = $_POST['size'];
            $price = $_POST['price'];
            $id = $_POST['id'];
            $stmt->execute();
        }else if($_POST['func'] == 'deletetype'){
            echo "recieved";
            $stmt = $conn->prepare("DELETE FROM `type` WHERE `type`.`id` = :id");
            $stmt->bindParam(':id', $id);
            $id = $_POST['id'];
            $stmt->execute();
        }else if($_POST['func'] == 'deletepizza'){
            echo "recieved";
            $stmt = $conn->prepare("DELETE FROM `pizza` WHERE `pizza`.`id` = :id");
            $stmt->bindParam(':id', $id);
            $id = $_POST['id'];
            $stmt->execute();
        }else if($_POST['func'] == 'addtype'){
            $stmt = $conn->prepare("INSERT INTO `type` (`name`, `img`)
            VALUES (:name, :img)");
            $stmt->bindParam(':name', $name);
            $stmt->bindParam(':img', $img);
            $name = $_POST['name'];
            $img = $_POST['img'];
            $stmt->execute();
        }else if($_POST['func'] == 'addpizza'){
            $stmt = $conn->prepare("INSERT INTO `pizza`(`size`, `price`, `type_id`) VALUES (:size, :price, :typeId)");
            $stmt->bindParam(':size', $size);
            $stmt->bindParam(':price', $price);
            $stmt->bindParam(':typeId', $typeId);
            $size = $_POST['size'];
            $price = $_POST['price'];
            $typeId = $_POST['id'];
            $stmt->execute();
        }
    }
?>
<DOCTYPE html>
    <html>
    <head>
        <style>
            
        </style>
        <link rel="stylesheet" href="style.css">
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-1BmE4kWBq78iYhFldvKuhfTAU6auU8tT94WrHftjDbrCEXSU1oBoqyl2QvZ6jIW3" crossorigin="anonymous">
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    </head>
    <body>
        <div>
            <?php
                require "connection.php";
                $sql = 'SELECT * FROM type';
                $stmt = $conn->query($sql);
                $data = $stmt->fetchAll(PDO::FETCH_ASSOC);
                echo "<table>";
                echo "<tr><th>id</th><th>name</th><th>image link</th></tr>";
                foreach($data as $row){
                    $sql1 = 'SELECT * FROM pizza WHERE type_id ='.$row['id'].'';
                    $stmt1 = $conn->query($sql1);
                    $data1 = $stmt1->fetchAll(PDO::FETCH_ASSOC);
                    echo "<tr><form method='POST' action='admin.php'><input type='hidden' name='func' value='updatetype'>
                        <td>".$row['id']."<input type='hidden' name='id' value='".$row['id']."'></td>
                        <td><input type='text' name='name' value='".$row['name']."'></td>
                        <td><input type='text' name='img' value='".$row['img']."'></td>
                        <td><input type='submit' value='Update'></td></form>
                        <form method='POST' action='admin.php'><td><input type='hidden' name='func' value='deletetype'><input type='hidden' name='id' value='".$row['id']."'></td>
                        <td><input type='submit' value='verwijderen'></td></form></tr>
                        <table>";
                    echo "<tr><th></th><th>id</th><th>size</th><th>price</th></tr>";
                    foreach($data1 as $row1){
                        echo "<tr><form method='POST' action='admin.php'><input type='hidden' name='func' value='updatepizza'>
                        <input type='hidden' name='id' value=".$row1['id'].">
                        <td width='50px'></td><td>".$row1['id']."</td>
                        <td><input type='text' name='size' value='".$row1['size']."'></td>
                        <td><input type='number' step='0.01' min='0' name='price' value='".$row1['price']."'></td>
                        <td><input type='submit' value='Update'></input></td></form>
                        <form method='POST' action='admin.php'><td><input type='hidden' name='func' value='deletepizza'><input type='hidden' name='id' value='".$row1['id']."'>
                        <input type='submit' value='verwijderen'></td></form></tr>";
                        
                    }
                    echo "<tr><form method='POST' action='admin.php'><input type='hidden' name='func' value='addpizza'>
                    <td width='50px'><input type='hidden' name='id' value='".$row['id']."'></td><td></td><td><input type='text' name='size'></td>
                    <td><input type='number' step='0.01' min='0' name='price'></td><td><input type='submit' value='add'></td></form></tr>";
                    echo "</table>";
                }
                echo "<tr><form method='POST' action='admin.php'><input type='hidden' name='func' value='addtype'>
                <td width='50px'></td><td>".$row1['id']."</td><td><input type='text' name='name'></td>
                <td><input type='text' name='img'></td><td><input type='submit' value='add'></td></form></tr>";
                echo "</table>";
            ?>
        </div>
    </body>
    </html>