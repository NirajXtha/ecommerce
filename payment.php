<?php
session_start();
$_SESSION['paystat']='1';
   if(!(isset($_SESSION['id']))){
      header("location:signup.php?error=!loggedin");
     }
     ?>
<?php
   include_once('./includes/headerNav.php');
   // include_once('./stripeConfig.php');
     ?>
     <head>
        <style>
           .proceed-pay{
              font-size:medium;
              height:400px;
              display:flex;
              justify-content:center;
              align-items:center;
              border:none;
              background:aliceblue;
              box-shadow: 2px 2px 2px 1px rgba(0, 0, 0, 0.2);
           }
 @media only screen and (max-width: 768px){
.proceed-pay{
             height:450px;
            flex-direction:column;
            gap:20px;
}
}
           .thumbnail{
              cursor:pointer;
           }
           .ship{
               margin-right:5%;
               color:lime
           }
           .order{
               margin-left:5%;
               color:violet
           }
           h4{
              text-decoration:underline;
              color:black
           }
           .btn-pay{
              height:30px;
              color:white
           }
           .btn-pay:hover{
              border-radius:0px;
              color:wheat;
           }
           button{
              font-family:monospace
           }
        </style>

     </head>

     <?php

$sql ="SELECT * FROM  products WHERE product_id='{$_GET['id']}';";
$result = $conn->query($sql);
$row = $result->fetch_assoc();
$conn->close();
           $amt = $row['product_price']+50;
   $amount = "$amt";
   $transaction_uuid = bin2hex(random_bytes(20));
   $product_code = "EPAYTEST";
   $secret_key = '8gBm/:&EnhH.1/q';
   $message = 'total_amount=' . $amount . ',transaction_uuid=' . $transaction_uuid . ',product_code=' . $product_code;
   $signature = base64_encode(hash_hmac('sha256', $message, $secret_key, true));
?>

<h4 style="text-align:center;"><p><a style="color:grey" href="./product.php?id=<?php echo $_GET['id']?>"> Product:<?php echo $row['product_title'] ?></a></p></h4>


     <div class="proceed-pay">
        <div class="ship">
        <h4>Shipping address</h4>
        <p>Name:<?Php echo $_SESSION['customer_name']?></p>
        <p>Address:<?Php echo $_SESSION['customer_address']?></p>
        <p>Number:<?Php echo $_SESSION['customer_phone']?></p>
        <p>Email:<?Php echo $_SESSION['customer_email']?></p>
        <a href="./profile.php"><h5 style="text-decoration:underline;">Edit profile</h5></a>
        </div>
       
  
<div class="order">
<h4>Order summary</h4>
        <p>subtotal( item)</p>
        <p>shipping_fees: Rs 50</p>
        <p>Total: Rs <?php echo $row['product_price']?> + 50 = <?php echo $row['product_price']+50 ?> </p>


<button type="button" class="btn btn-info" onclick="esewaLoad()">Pay with Esewa</button>

</div>


     </div>
     <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST" id="esewa" hidden>
                            <input type="text" id="amount" name="amount" value="<?php echo ($amount) ?>" required>
                            <input type="text" id="tax_amount" name="tax_amount" value="0" required>
                            <input type="text" id="total_amount" name="total_amount" value="<?php echo ($amount) ?>" required>
                            <input type="text" id="transaction_uuid" name="transaction_uuid" value=<?php echo ($transaction_uuid) ?> required>
                            <input type="text" id="product_code" name="product_code" value="EPAYTEST" required>
                            <input type="text" id="product_service_charge" name="product_service_charge" value="0" required>
                            <input type="text" id="product_delivery_charge" name="product_delivery_charge" value="0" required>
                            <input type="text" id="success_url" name="success_url"
                                value="http://localhost/ecommerce/esewaSuccess.php?id=<?=$_GET['id']?>&" required>
                            <input type="text" id="failure_url" name="failure_url" value="https://google.com" required>
                            <input type="text" id="signed_field_names" name="signed_field_names"
                                value="total_amount,transaction_uuid,product_code" required>
                            <input type="text" id="signature" name="signature" value=<?php echo ($signature) ?> required>
                            <input value=" Submit" type="submit">
                        </form>
     	<div class="thumbnail">
				<img src="images/esewa.png" title="Payment Methods" alt="Payments Methods | Esewa">
				<div class="caption">
				  <h5>Payment Methods</h5>
				</div>
			  </div>

        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
        <script>
         function esewaLoad(){
            alert("Are you sure?");
            document.getElementById("esewa").submit();
         }
        </script>
