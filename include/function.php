<?php
    include_once("connection.php");
    function get_username($user_id){
        global $conn;
        $sql = "SELECT * FROM `user` WHERE user_id = '$user_id'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            return $row['user_name'];
        }
    }

    function update_balance($user_id){
        global $conn;
        $sql = "SELECT * FROM user where user_id='$user_id'";
        $result = mysqli_query($conn, $sql);
        while($row = mysqli_fetch_assoc($result)){
            $_SESSION['user_balance'] = $row['user_balance'];
        }
    }
?>