<?php 
    
   //this is restriction for normal user to access admin panel
   session_start();
   // var_dump($_SESSION['customer_role']);
   // if(isset($_SESSION['customer_role']) === 'admin'){
   //    var_dump($_SESSION['customer_role']);
      
   //  }else{
   //    header("Location:../index.php");

   //  }
      include 'includes/config.php';
    $id = $_SESSION['id'];
    $customersql ="SELECT * FROM  customer WHERE customer_id=". $id;
    $result = $conn->query($customersql);
    
    if($result->num_rows==1){ //if any one data found go inside it
        $row = $result->fetch_assoc();
        if($row['customer_role'] == 'admin'){
            // var_dump($row['customer_role']);

        }else{
            // var_dump($row['customer_role']);
            header("Location:../index.php");
        }
         

    }
  ?>