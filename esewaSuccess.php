<?php
$conn = mysqli_connect('localhost','root', '', 'electric-shop');
if(!$conn){
    die("ErrorConnecting To database");
}
session_start();
$user = $_SESSION['id'];
    if(!$_GET['id']){
        alert("something went Wrong! Contact us for support!");
    }else{
        $id = $_GET['id'];
        var_dump($id);

        $row = mysqli_query($conn,'SELECT * FROM `products` WHERE product_id = '.$id);
        if(!$row){
            die("Connection error ");
        }else{
            $result = $row->fetch_assoc();
            $qty = 1;
            $price = $result['product_price'];
            $date = date('Y-m-d');

            $sql = $conn->query("INSERT INTO soldproducts (`uid`,`pid`,`quantity`,`price`,`date`) 
            Values($user,$id,$qty,$price,'$date')");
            if(!$sql){
            die("Connection error");

            }else{
                echo "<script>alert('Payment successfull');</script>";
                header('location:index.php');
            }
        }
    }
?>