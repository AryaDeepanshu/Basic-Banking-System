<?php
    include_once("include/connection.php");
  
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer</title>
    <link rel="stylesheet" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/style.css">
</head>
<body class="text-center">
    
    <main class="form-signin">
    <form method="POST">
        <img class="mb-4" src="/docs/5.1/assets/brand/bootstrap-logo.svg" alt="" width="72" height="57">
        <h1 class="h3 mb-3 fw-normal">Please sign in</h1>

        <div class="form-floating">
        <input name="email" type="email" class="form-control" id="floatingInput" placeholder="name@example.com">
        <label for="floatingInput">Email address</label>
        </div>
        <div class="form-floating">
        <input name ="password" type="password" class="form-control" id="floatingPassword" placeholder="Password">
        <label for="floatingPassword">Password</label>
        </div>
        <button name="post" class="w-100 btn btn-lg btn-primary" type="submit">Sign in</button>
        <p class="mt-5 mb-3 text-muted">&copy; 2017–2021</p>
    </form>
    </main>
    <?php
        if(isset($_POST['post'])){
            $email = mysqli_escape_string($conn, $_POST['email']);
            $password = mysqli_real_escape_string($conn, $_POST['password']);
            if(empty($email) OR empty($password)){
                header("Location:customer.php?message=empty+fields");
                exit();
            }else{
                $sql = "SELECT * FROM user WHERE user_email='$email'";
                $result = mysqli_query($conn,$sql);
                if(mysqli_num_rows($result) <= 0){
                    header("Location:customer.php?message=login+failed");
                    exit();
                }else{
                    while($row = mysqli_fetch_assoc($result)){
                        if(!password_verify($password, $row['user_password'])){
                            header("Location:customer.php?message=Login+Error");
                            exit(); 
                        }elseif(password_verify($password, $row['user_password'])){
                            session_start();
                            $_SESSION['user_name'] = $row['user_name'];
                            $_SESSION['user_balance'] = $row['user_balance'];
                            $_SESSION['user_email'] = $row['user_email'];
                            $_SESSION['user_id'] = $row['user_id'];
                            header("Location: home.php");
                            exit();
                        }
                    }
                }
            }
        }
    ?>
    
</body>
</html>