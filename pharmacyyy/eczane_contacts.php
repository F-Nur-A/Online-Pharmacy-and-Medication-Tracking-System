<?php

@include 'config.php';

session_start();

$eczane_id = $_SESSION['eczane_id'];
$eczane_name = $_SESSION['eczane_name'];

if(!isset($eczane_id)){
   header('location:login.php');
};

if(isset($_GET['delete'])){
   $delete_id = $_GET['delete'];
   mysqli_query($conn, "DELETE FROM `message` WHERE id = '$delete_id'") or die('query failed');
   header('location:eczane_contacts.php');
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>dashboard</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/admin_style.css">

</head>
<body>
   
<?php @include 'eczane_header.php'; ?>

<section class="messages">

   <h1 class="title">Mesajlar</h1>

   <div class="box-container">

      <?php
       $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE eczane_name = '$eczane_name'") or die('query failed');
       if(mysqli_num_rows($select_message) > 0){
          while($fetch_message = mysqli_fetch_assoc($select_message)){
      ?>
      <div class="box">
         <p>Kullanıcı id : <span><?php echo $fetch_message['user_id']; ?></span> </p>
         <p>Kullanıcı Adı : <span><?php echo $fetch_message['name']; ?></span> </p>
         <p>Telefon Numarası : <span><?php echo $fetch_message['number']; ?></span> </p>
         <p>E-mail : <span><?php echo $fetch_message['email']; ?></span> </p>
         <p>Mesaj : <span><?php echo $fetch_message['message']; ?></span> </p>
         <a href="admin_contacts.php?delete=<?php echo $fetch_message['id']; ?>" onclick="return confirm('Mesajı silmek mi istiyorsunuz?');" class="delete-btn">Sil</a>
      </div>
      <?php
         }
      }else{
         echo '<p class="empty">Mesajınız yok!</p>';
      }
      ?>
   </div>

</section>













<script src="js/admin_script.js"></script>

</body>
</html>