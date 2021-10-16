<?php
    include_once("include/connection.php");
    include_once("include/function.php");
    session_start();
    update_balance($_SESSION['user_id']);
?>
<html>
<head>
<link rel="stylesheet" href="css/bootstrap.min.css">
<link rel="stylesheet" href="css/style.css">
<link rel="stylesheet" href="css/home.css">
</head>


<body>
<div class="container rounded bg-white mt-5 mb-5">
    <div class="row">
        <div class="col-md-3 border-right">
            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"><span class="font-weight-bold"><?php echo $_SESSION['user_name'];?></span><span class="text-black-50"><?php echo $_SESSION['user_email'];?></span><span><a href="logout.php"><button class="btn btn-primary profile-button">Logout</button></a></span></div>
        </div>
        <div class="col-md-5 border-right">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h4 class="text-right">Balance</h4>
                </div>
                <h1><?php echo $_SESSION['user_balance'];?></h1>
                <div class="mt-5 text-center"><button class="btn btn-primary profile-button" id="transfer" type="button">Transfer Money</button></div>
                <div id="transfer-form" style ="display:none" class="mt-5 text-center">
                    <form method="POST">
                        <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon1">Sender</span>
                            <input name="sender" type="text" readonly class="form-control" value="<?php echo $_SESSION['user_name']; ?> (You)" aria-label="Username" aria-describedby="basic-addon1" required>
                            </div>

                            <div class="input-group mb-3">
                            <span class="input-group-text" id="basic-addon2">Recipient</span>
                            <select name="recipient" class="form-control" aria-label="Default select example" required>
                            <option value="" disabled selected>Click to select</option>
                            <?php
                                $user = $_SESSION['user_id'];
                                $sql = "SELECT * FROM user WHERE NOT (user_id = '$user') ORDER BY user_name";
                                $result = mysqli_query($conn, $sql);
                                $count = 1;
                                while($row = mysqli_fetch_assoc($result)){
                                    echo "<option value='" . $count .'|'.$row['user_id']. "'>" . $row['user_email'] .'| '.$row['user_name']."</option>";
                                    $count += 1;
                                }
                            ?>
                            </select>
                            </div>

                            <div class="input-group mb-3">
                            <span class="input-group-text">&#x20b9</span>
                            <input name="amount" type="number" min="0" class="form-control" aria-label="Amount (to the nearest dollar)" required>
                            </div>


                            <div class="input-group mb-3">
                            <span name="comment" class="input-group-text">Comments</span>
                            <textarea class="form-control" aria-label="With textarea"></textarea>

                            <div class="input-group mb-3">
                            <div class="mt-5 text-center">
                            <button name="send" class="btn btn-primary profile-button form-control" id="send" type="submit">Send</button>
                            </div>
                            </div>
                        </div>
                    </form>
                    <?php
                        if(isset($_POST['send'])){
                            if(isset($_SESSION['user_id'])){
                                $sender = $_SESSION['user_id'];
                                $recipient = mysqli_escape_string($conn, $_POST['recipient']);
                                $recipient_id = explode('|', $recipient)[1];
                                
                                $amount = mysqli_escape_string($conn, $_POST['amount']);
                                $comment = mysqli_escape_string($conn, $_POST['comment']);
                                if(empty($sender) OR empty($recipient) OR empty($amount)){
                                    header("Location: home.php?message=empty+fields");
                                    exit();
                                }else{
                                    if($amount > $_SESSION['user_balance']){
                                        header("Location: home.php?message=insufficient+amount");
                                        exit();
                                         
                                    }else{
                                        $sql = "SELECT * FROM user WHERE (user_id = '$recipient_id')";
                                        $result = mysqli_query($conn, $sql);
                                        if(mysqli_num_rows($result) <= 0){
                                            header("Location: home.php?message=recipient+not+found");
                                            exit();
                                        }else{
                                            while($row = mysqli_fetch_assoc($result)){
                                                $recipient_old_balance = $row['user_balance'];
                                            }
                                            $sender_balance = $_SESSION['user_balance'] - $amount;
                                            $recipient_balance = $recipient_old_balance + $amount;
                                            $sql_sender = "UPDATE user SET user_balance='$sender_balance' WHERE user_id='$sender'";
                                            $sql_recipient = "UPDATE user SET user_balance='$recipient_balance' WHERE user_id='$recipient_id'";
                                            if(mysqli_query($conn, $sql_sender) AND mysqli_query($conn, $sql_recipient)){
                                                header("Location: home.php?message=transfered");
                                                update_balance($_SESSION['user_id']);
                                                $sql_trans = "INSERT INTO `transaction` (sender, recipient, amount, comment) VALUES ('$sender', '$recipient_id', '$amount', '$comment')";
                                                mysqli_query($conn, $sql_trans);
                                                exit();
                                            }else{
                                                header("Location: home.php?message=transfered+failed");
                                                exit();
                                            }

                                        }
                                    }
                                    
                                }
                            }
                        }
                    ?>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="p-3 py-5">
                <div class="d-flex justify-content-between align-items-center experience"><span><h4>Transaction History<h4></span></div><br>
                <div class="mt-5 text-center">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                        <th scope="col">#</th>
                        <th scope="col">Sender</th>
                        <th scope="col">Recipient</th>
                        <th scope="col">Amount</th>
                        <th scope="col">Comment</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $sql_get_trans = "SELECT * FROM `transaction`";
                        $result = mysqli_query($conn, $sql_get_trans);
                        while($row = mysqli_fetch_assoc($result)){
                            $sender = get_username($row['sender']);
                            $recipient = get_username($row['recipient']);
                            echo "<tr><th scope='row'>".$row['transc_id']."</th><td>".$sender."</td><td>".$recipient."</td><td>".$row['amount']."</td><td>".$row['comment']."</td></tr>";
                        }
                        
                        ?>
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
</div>
</div>
</div>
<script src="js/jquery.js"></script>
<script>
        $(document).ready(function(){
            $('#transfer').click(function(){
                $('#transfer-form').slideToggle();
            });
        });
</script>
</body>
</html>