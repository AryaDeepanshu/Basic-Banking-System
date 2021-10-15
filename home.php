<?php
    include_once("include/connection.php");
    session_start();
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
            <div class="d-flex flex-column align-items-center text-center p-3 py-5"><img class="rounded-circle mt-5" width="150px" src="https://st3.depositphotos.com/15648834/17930/v/600/depositphotos_179308454-stock-illustration-unknown-person-silhouette-glasses-profile.jpg"><span class="font-weight-bold"><?php echo $_SESSION['user_name'];?></span><span class="text-black-50"><?php echo $_SESSION['user_email'];?></span><span> </span></div>
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
                                    echo "<option value='" . $count . "'>" . $row['user_email'] .'| '.$row['user_name']."</option>";
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
                                $recipient_email = array_shift(explode('|', $recipient));
                                $amount = mysqli_escape_string($conn, $_POST['amount']);
                                $comment = mysqli_escape_string($conn, $_POST['comment']);
                                if(empty($sender) OR empty($recipient) OR empty($amount)){
                                    header("Location: home.php?message=empty+fields");
                                    exit();
                                }else{
                                    $sql = "SELECT user_id WHERE (user_email = '$recipient_email')";
                                    if(!mysqli_query($conn, $sql)){
                                        
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
                        <th scope="col">First</th>
                        <th scope="col">Last</th>
                        <th scope="col">Handle</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                        <th scope="row">1</th>
                        <td>Mark</td>
                        <td>Otto</td>
                        <td>@mdo</td>
                        </tr>
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