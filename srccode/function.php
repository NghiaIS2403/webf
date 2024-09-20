<?php
$servername = "localhost";
$user = "root";
$pass = "";
$database = "shoppingcart";

$conn = mysqli_connect($servername, $user, $pass, $database);
if (!$conn) {
  die("Kết nối thất bại: " . mysqli_connect_error());
}
// Template header, feel free to customize this
// Template header, có thể tùy chỉnh
if (!function_exists('template_header')) {
    function template_header($_title) {
        echo <<<EOT
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<title>$_title</title>
		<link href="style1.css" rel="stylesheet" type="text/css">
        <link href="style2.css" rel="stylesheet" type="text/css">
		<link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.7.1/css/all.css">
	</head>
	<body>
        <header>
            <div class="content-wrapper">
                <h1>MeanK5 Shop</h1>
                <nav>
                    <a href="index.php">Home</a>
                    <a href="index.php?page=products">Sản phẩm</a>
                    <a href="index.php?page=login">Đăng nhập</a>
                    <a href="index.php?page=signup">Đăng kí</a> 
    <button
      type="button"
      onclick="document.body.style.cssText='color:white; background-color:black'"
    > Dark
    </button>
    <button
      type="button"
      onclick="document.body.style.cssText='color:black; background-color:white'"
    >
          Light
    </button>                                                               
                </nav>
                <div class="link-icons">
                    <a href="index.php?page=cart">
						<i class="fas fa-shopping-cart"></i>
					</a>
                </div>
            </div>
        </header>
        <main>
EOT;
    }
}

// Template footer
if (!function_exists('template_footer')) {
    function template_footer() {
        $year = date('Y');
        echo <<<EOT
        </main>
        <footer>
            <div class="content-wrapper">
                <p>&copy; $year, Shopping Cart System</p>
            </div>
        </footer>
    </body>
</html>
EOT;
    }
}
