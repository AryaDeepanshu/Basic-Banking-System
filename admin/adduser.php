<?php
    include_once("../include/connection.php");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add user</title>
    <link rel="stylesheet" href="../css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/style.css">
    <link href="../css/signup.css" rel="stylesheet">
</head>
<body>
    
    <main class="form-signin">
        <form method="POST" enctype="multipart/form-data">
        <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Name</label>
                <input type="text" name="name" class="form-control" id="exampleInputEmail1">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Email address</label>
                <input type="email" name="email" class="form-control" id="exampleInputEmail1">
            </div>
            <div class="mb-3">
                <label for="exampleInputEmail1" class="form-label">Balance</label>
                <input type="number" name="balance" class="form-control" id="exampleInputEmail1">
            </div>
            <button name="submitbtn" type="submit" class="btn btn-primary">Add</button>
        </form>
        <?php
            if(isset($_POST['submitbtn'])){
                $user_name = mysqli_escape_string($conn, $_POST['name']);
                $user_email = mysqli_escape_string($conn, $_POST['email']);
                $user_balance = mysqli_escape_string($conn, $_POST['balance']);
                if(empty($user_name) OR empty($user_email) OR empty($user_balance)){
                    header("Location:adduser.php?message=Empty+fields");
                    exit();
                }else{
                    $sql = "INSERT INTO user (user_photo, user_name, user_email, user_balance) VALUES('avatar', '$user_name', '$user_email', '$user_balance');";
                    if($conn->query($sql) === TRUE){
                        header("Location: adduser.php?message=useradded");
                        exit();
                    }else{
                        echo "Error: " . $sql . "<br>" . $conn->error;
                        
                    }
                }
            }else{
                echo "FUCK!";
            }
        ?>
    </main>
    
</body>
</html>