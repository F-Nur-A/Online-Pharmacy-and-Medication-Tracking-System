<?php

@include 'config.php';

session_start();

$user_id = $_SESSION['user_id'];

if(!isset($user_id)){
   header('location:login.php');
}; 

if(isset($_POST['send'])){

    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $number = mysqli_real_escape_string($conn, $_POST['number']);
    $msg = mysqli_real_escape_string($conn, $_POST['message']);
    $eczane_name = mysqli_real_escape_string($conn, $_POST['eczane_name']);

    $select_message = mysqli_query($conn, "SELECT * FROM `message` WHERE name = '$name' AND email = '$email' AND number = '$number' AND message = '$msg' AND user_id = '$user_id'") or die('query failed');

    if(mysqli_num_rows($select_message) > 0){
        $message[] = 'Bu Mesaj daha önce gönderilmiş!';
    }else{
        mysqli_query($conn, "INSERT INTO `message`(user_id, name, eczane_name, email, number, message) VALUES('$user_id', '$name', '$eczane_name', '$email', '$number', '$msg')") or die('query failed');
        $message[] = 'Mesaj başarıyla gönderildi!';
    }

}

?>

<!DOCTYPE html>
<html lang="en">
<head>
   <meta charset="UTF-8">
   <meta http-equiv="X-UA-Compatible" content="IE=edge">
   <meta name="viewport" content="width=device-width, initial-scale=1.0">
   <title>contact</title>

   <!-- font awesome cdn link  -->
   <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">

   <!-- custom admin css file link  -->
   <link rel="stylesheet" href="css/style.css">

</head>
<body>
   
<?php @include 'header.php'; ?>


<section class="contact">

    <form action="" method="POST">
        <h3>ECZANEYE MESAJ GÖNDERİN!</h3>
        <select name="eczane_name" class="box" required>
            <option value="" disabled selected>Eczane seçin</option>
            <?php
                $select_pharmacies = mysqli_query($conn, "SELECT * FROM `eczane`") or die('query failed');
                if(mysqli_num_rows($select_pharmacies) > 0){
                    while($row = mysqli_fetch_assoc($select_pharmacies)){
                        echo '<option value="'.$row['name'].'">'.$row['name'].'</option>';
                    }
                }else{
                    echo '<option value="" disabled>Eczane bulunamadı</option>';
                }
            ?>
        </select>
        <input type="text" name="name" placeholder="adınızı girin" class="box" required> 
        <input type="email" name="email" placeholder="e-mail adresinizi girin" class="box" required>
        <input type="number" name="number" placeholder="numaranızı girin" class="box" required>
        <textarea name="message" class="box" placeholder="mesajınızı yazın" required cols="30" rows="10"></textarea>
        <input type="submit" value="mesajı gönder" name="send" class="btn">
    </form>

</section>

<script src="js/script.js"></script>

</body>
</html>
