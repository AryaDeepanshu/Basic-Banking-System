<?php
include_once("include/connection.php");
include_once("include/function.php")
?>
<html>
    <head>
        <title>Money transfer</title>
        <link rel="stylesheet" href="css/bootstrap.min.css">
        <link rel="stylesheet" href="css/style.css">
    </head>
    <body>
    <table class="table table-striped">
    <thead>
        <tr>
            <th scope="col">#</th>
            <th scope="col">Name</th>
            <th scope="col">Balance</th>
            <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody>
        <?php
            $sql = "SELECT * FROM user";
            $count =1;
            $result = mysqli_query($conn, $sql);
            if(mysqli_num_rows($result) > 0){
                while($row = mysqli_fetch_assoc($result)){
                    
                  echo '<tr>
                  <th scope="row">'.$count.'</th>
                  <td>'.$row['user_name'].'</td>
                  <td>'.$row['user_balance'].'</td>
                  <td><a href="customer.php"><button type="button" name="transfer"class="btn btn-primary">Transfer</button></a></td>
                </tr>';
                $count+=1;  
                  
                    
                }
            }
        ?>
        </tbody>
        </table>
    </body>
</html>