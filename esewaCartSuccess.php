<?php
$conn = mysqli_connect('localhost','root', '', 'electric-shop');
if(!$conn){
    die("ErrorConnecting To database");
}
session_start();
if(!$_SESSION['id']){
    header('location:index.php');
}
$user = $_SESSION['id'];
if($_GET['data']){
    var_dump($user);
    $check = mysqli_query($conn,'SELECT * FROM customer WHERE customer_id = '.$user);
    if(mysqli_num_rows($check) == 0){
     header('location:index.php');
    }else{
        $sql = mysqli_query($conn,'UPDATE carts SET `status` = "purchased" WHERE `uid` = '.$user);
        if(!$sql){
            die("COnnection Error");
        }else{
            echo "<script>alert('Payment successfull');</script>";
            header('location:index.php');
        }
    }
}else{
    header('location:index.php');

}
    

?>