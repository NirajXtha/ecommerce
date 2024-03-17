<?php
if(isset($_POST['delete'])){
  include_once 'includes/config.php';
$sql = "DELETE FROM carts where pid={$_GET['pid']} AND quantity={$_GET['q']} LIMIT 1"; //sql query for deleting
$conn->query($sql); //executing sql query

header("Location:cart.php?itemRemovedSuccessfully");
}
?>
<?php
   include_once('./includes/headerNav.php');
      //this restriction will secure the pages path injection
      if(!(isset($_SESSION['id']))){
        header("location:index.php?UnathorizedUser");
       }
      //  include_once('./stripeConfig.php');

?>

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Ecommerce Cart</title>

</head>
<body>
<div class='cart'>

  <div class="container">
    <h1 style='float:left'>Cart</h1>
    <div style='width:100%;height:100%;overflow:hidden' class='tableBtm'>

<?php
       $total=0;
       $pidArray = [];
       $quantArray = [];
  $sql = "SELECT * FROM carts where uid={$_SESSION['id']} and status='active'";
$result = $conn->query($sql) or die("Query Failed.");
if ($result->num_rows > 0) {
?>

    <table>
<thead>
<thead >
        <tr>
          <th>Sn</th>
          <th>Product</th>
          <th>Price</th>
          <th>Quantity</th>
          <th>Total</th>
          <th>Action</th>
        </tr>
      </thead>
</thead>
<tbody>
     <?php
     $sn=0;
while($row = $result->fetch_assoc()) { 
  $sn = $sn+1;
  //to save all the product_id of card list
  $pidArray[$sn-1] = $row['pid'];
  $quantArray[$sn-1] = $row['quantity'];
  $encodedPidData = urlencode(serialize($pidArray));
  $encodedQuantityData = urlencode(serialize($quantArray));

  $total = $total+ $row["price"] * $row["quantity"];
?>
<tr>
    <td><?php echo $sn?></td>
    <td><?php echo $row["product"] ?></td>
          <td><?php echo $row["price"] ?></td>
          <td>
          <p><?php echo $row["quantity"] ?></p>
          </td>
          <td><?php echo $row["price"]*$row["quantity"] ?></td>
          <td>
          <form action="<?php echo $_SERVER['PHP_SELF']?>?pid=<?php echo $row['pid']?>&q=<?php echo $row['quantity']?>" method="post">
<button name='delete' type='submit' class="btn btn-danger">Remove</button>
</form>
        </td>
</tr>

<?php }?>
</tbody>
<?php
  $amount = "$total";
  $transaction_uuid = bin2hex(random_bytes(20));
  $product_code = "EPAYTEST";
  $secret_key = '8gBm/:&EnhH.1/q';
  $message = 'total_amount=' . $amount . ',transaction_uuid=' . $transaction_uuid . ',product_code=' . $product_code;
  $signature = base64_encode(hash_hmac('sha256', $message, $secret_key, true));
  // var_dump($amount);
?>
</table>
<div class="text-end">
    <h5>Total:<?php echo  $total?> </h5>
  </div>
  <div class="text-end ">
    <button type="button" class="btn btn-info" onclick="esewaLoad()">Pay with Esewa</button>
  </div>
<?php }else { echo "0 Results <br> No items in a cart"; }
             ?>


   

      </div>
  </div>
  </div>
  <br>
  <br>
  <form action="https://rc-epay.esewa.com.np/api/epay/main/v2/form" method="POST" id="esewa" hidden>
                            <input type="text" id="amount" name="amount" value="<?php echo ($amount) ?>" required>
                            <input type="text" id="tax_amount" name="tax_amount" value="0" required>
                            <input type="text" id="total_amount" name="total_amount" value="<?php echo ($amount) ?>" required>
                            <input type="text" id="transaction_uuid" name="transaction_uuid" value=<?php echo ($transaction_uuid) ?> required>
                            <input type="text" id="product_code" name="product_code" value="EPAYTEST" required>
                            <input type="text" id="product_service_charge" name="product_service_charge" value="0" required>
                            <input type="text" id="product_delivery_charge" name="product_delivery_charge" value="0" required>
                            <input type="text" id="success_url" name="success_url"
                                value="http://localhost/ecommerce/esewaCartSuccess.php" required>
                            <input type="text" id="failure_url" name="failure_url" value="https://google.com" required>
                            <input type="text" id="signed_field_names" name="signed_field_names"
                                value="total_amount,transaction_uuid,product_code" required>
                            <input type="text" id="signature" name="signature" value=<?php echo ($signature) ?> required>
                            <input value=" Submit" type="submit">
                        </form>

                        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz"
            crossorigin="anonymous"></script>
        <script>
          function esewaLoad(){
            alert("Are you sure?");
            document.getElementById("esewa").submit();
         }
        </script>
</body>





