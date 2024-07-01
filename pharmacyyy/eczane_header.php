<?php
@include 'config.php';

session_start();

$user_id = $_SESSION['eczane_id'];

if (!isset($user_id)) {
    header('location:login.php');
}

// Fetch cart count for the logged-in user
$select_cart_count = mysqli_query($conn, "SELECT COUNT(quantity) AS cart_count FROM `cart` WHERE user_id = '$user_id'") or die('query failed');
$cart_count = mysqli_fetch_assoc($select_cart_count)['cart_count'];

if (isset($message)) {
    foreach ($message as $msg) {
        echo '
        <div class="message">
            <span>' . $msg . '</span>
            <i class="fas fa-times" onclick="this.parentElement.remove();"></i>
        </div>
        ';
    }
}
?>

<header class="header">
    <div class="flex">
        <a href="eczane_page.php" class="logo">Eczane<span>Paneli</span></a>
        <nav class="navbar">
            <a href="eczane_page.php">Anasayfa</a>
            <a href="eczane_shop.php">Ürünler</a>
            <a href="eczane_orders.php">Siparişler</a>
            <a href="eczane_my_orders.php">Siparişlerim</a>
            <a href="eczane_contacts.php">Mesajlar</a>
        </nav>
        <div class="icons">
            <div id="menu-btn" class="fas fa-bars"></div>
            <a href="eczane_search_page.php" class="fas fa-search"></a>
            <div id="user-btn" class="fas fa-user"></div>
            <a href="eczane_cart.php">
                <i class="fas fa-shopping-cart"></i>
                <span>(<?php echo $cart_count ? $cart_count : '0'; ?>)</span>
            </a>
        </div>
        <div class="account-box">
            <p>Kullanıcı Adı : <span><?php echo $_SESSION['eczane_name']; ?></span></p>
            <p>E-mail : <span><?php echo $_SESSION['eczane_email']; ?></span></p>
            <a href="logout.php" class="delete-btn">Çıkış Yap</a>
        </div>
    </div>
</header>
