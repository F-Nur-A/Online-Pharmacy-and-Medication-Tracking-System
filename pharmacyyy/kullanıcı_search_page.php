<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}


if(isset($_POST['add_to_cart'])){

    $product_id = $_POST['pid'];
    $product_name = $_POST['name'];
    $product_price = $_POST['price'];
    $product_image = $_POST['image'];
    $product_quantity = $_POST['product_quantity']; 
 
     $check_cart_numbers = mysqli_query($conn, "SELECT * FROM `cart` WHERE name = '$product_name' AND user_id = '$user_id'") or die('query failed');
 
     if(mysqli_num_rows($check_cart_numbers) > 0){
         $message[] = 'Ürün sepete daha önce eklendi.';
     }else{
        mysqli_query($conn, "INSERT INTO `cart`(user_id, product_id, name, price, quantity, image) VALUES('$user_id', '$product_id', '$product_name', '$product_price', '$product_quantity', '$product_image')") or die('query failed');
        $message[] = 'Sepete eklendi';
    }
 
 }

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>search page</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

   <style>
        .image {
            max-width: 100%;
            max-height: 200px;
            height: auto;
            display: block;
            margin: 0 auto;
        }
        .show-products .box-container .box .stok{
            margin:1rem 0;
            font-size: 2rem;
            color:var(--black);
         }

    </style>
</head>
<body>
   
<?php @include 'header.php'; ?>

<section class="search-form">
    <form action="" method="POST">
        <input type="text" class="box" placeholder="ürün ara..." name="search_box">
        <input type="submit" class="btn" value="ARA" name="search_btn">
    </form>
</section>

<section class="show-products" style="padding-top: 0;">

   <div class="box-container">

   <?php
        if(isset($_POST['search_btn'])){
         $search_box = mysqli_real_escape_string($conn, $_POST['search_box']);
         $select_products = mysqli_query($conn, "SELECT * FROM `products` WHERE name LIKE '%{$search_box}%'") or die('query failed');
         if(mysqli_num_rows($select_products) > 0){
            while($fetch_products = mysqli_fetch_assoc($select_products)){
      ?>
               <form action="" method="POST" class="box">
                  <a href="kullanıcı_view_page.php?product_id=<?php echo $fetch_products['ilaç_id']; ?>" class="fas fa-eye"></a>
                  <div class="price"><?php echo $fetch_products['price']; ?> TL</div>
                  <img src="uploaded_img/<?php echo $fetch_products['image']; ?>" alt="" class="image">
                  <div class="name"><?php echo $fetch_products['name']; ?></div>
                  <input type="number" name="product_quantity" value="1" min="1" class="qty">
                  <input type="hidden" name="pid" value="<?php echo $fetch_products['ilaç_id']; ?>">
                  <input type="hidden" name="name" value="<?php echo $fetch_products['name']; ?>">
                  <input type="hidden" name="price" value="<?php echo $fetch_products['price']; ?>">
                  <input type="hidden" name="image" value="<?php echo $fetch_products['image']; ?>">
                  <input type="submit" value="Sepete Ekle" name="add_to_cart" class="btn">
               </form>
      <?php
         }
            }else{
                echo '<p class="empty">Sonuç bulunamadı!</p>';
            }
        }else{
            echo '<p class="empty">Bir şeyler ara!</p>';
        }
      ?>

   </div>

</section>

<script src="js/script.js"></script>

</body>
</html>
