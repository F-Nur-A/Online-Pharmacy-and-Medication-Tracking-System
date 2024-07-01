<?php

@include 'config.php';

session_start();

if(isset($_POST['submit'])){

   $filter_email = filter_var($_POST['email'], FILTER_SANITIZE_STRING);
   $email = mysqli_real_escape_string($conn, $filter_email);
   $filter_pass = filter_var($_POST['pass'], FILTER_SANITIZE_STRING);
   $pass = mysqli_real_escape_string($conn, md5($filter_pass));

   $select_users = mysqli_query($conn, "SELECT * FROM `users` WHERE email = '$email'AND password = '$pass' ") or die('query failed');


   if(mysqli_num_rows($select_users) > 0){
      
      $row = mysqli_fetch_assoc($select_users);
      if($row['user_type'] == 'eczane'){

         $_SESSION['eczane_name'] = $row['name'];
         $_SESSION['eczane_email'] = $row['email'];
         $_SESSION['eczane_id'] = $row['id'];
         header('location:eczane_page.php');

      }elseif($row['user_type'] == 'kullanıcı'){

         $_SESSION['user_name'] = $row['name'];
         $_SESSION['user_email'] = $row['email'];
         $_SESSION['user_id'] = $row['id'];
         header('location:home.php');

      }elseif($row['user_type'] == 'eczane_depo'){

         $_SESSION['eczane_depo_name'] = $row['name'];
         $_SESSION['eczane_depo_email'] = $row['email'];
         $_SESSION['eczane_depo_id'] = $row['id'];
         header('location:eczane_depo_page.php');

      }elseif($row['user_type'] == 'üretici'){

         $_SESSION['üretici_name'] = $row['name'];
         $_SESSION['üretici_email'] = $row['email'];
         $_SESSION['üretici_id'] = $row['id'];
         header('location:üretici_page.php');

      }elseif($row['user_type'] == 'kargo'){

         $_SESSION['kargo_name'] = $row['name'];
         $_SESSION['kargo_email'] = $row['email'];
         $_SESSION['kargo_id'] = $row['id'];
         header('location:kargo_page.php');

      }else{
         $message[] = 'Kullanıcı Bulunamadı!';
      }

   }else{
      $message[] = 'Hatalı E-mail veya Şifre!';
   }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>login</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>

<?php
if(isset($message)){
   foreach($message as $message){
      echo '
      <div class="message">
         <span>'.$message.'</span>
         <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
      </div>
      ';
   }
}
?>
   
<section class="form-container">

   <form action="" method="post">
      <h3>OTURUM AÇ</h3>
      <input type="email" name="email" class="box" placeholder="E-mail" required>
      <input type="password" name="pass" class="box" placeholder="Şifre" required>
      <input type="submit" class="btn" name="submit" value="Oturum Aç">
      <p>Hesabınız yok mu? <a href="register.php">Kayıt Ol</a></p>
   </form>

</section>

</body>
</html>