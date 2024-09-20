<?php
if(isset($_POST["register"])){
    include('conn.php');
          
    //Lấy dữ liệu từ file dangky.php
    $username   = $_POST['username'];
    $password   = $_POST['password'];
    $email      = $_POST['email'];
    $repeatpassword=$_POST["repeatpassword"];
          
    if (!$username || !$password || !$email )
    {
        echo "Vui lòng nhập đầy đủ thông tin. ";
        exit;
    }
          
        
    
    if($password!=$repeatpassword){
        header("location:index.php?page=signup");
        exit();
    }
    else{
        // Mã hóa mật khẩu
        $password = md5($password);
    }

    
    //Kiểm tra tên đăng nhập này đã có người dùng chưa
    $sql_1="SELECT username FROM information WHERE username='$username'";
    $result_1=mysqli_query($conn,$sql_1);
    if (mysqli_num_rows($result_1) > 0){
        header("Location: index.php?page=signup&error=2");
        exit;
    }
    
    if (!filter_var($email, FILTER_VALIDATE_EMAIL))
    {
        echo "Email này không hợp lệ. Vui long nhập email khác.";
        exit;
    }
          
    $sql_2="SELECT email FROM information WHERE email='$email'";
    $result_2=mysqli_query($conn,$sql_2);
    if (mysqli_num_rows($result_2) > 0)
    {
        header("Location: index.php?page=signup&error=3");
        exit;
    }
   
    $sql = "INSERT INTO information (username, passwordmd, email ) VALUES ('$username', '$password', '$email')";
    $result= mysqli_query($conn,$sql);
                          
    if ($result){
        header("Location:index.php?page=login");
      }
      else
        echo "Có lỗi xảy ra trong quá trình đăng ký.";
}
?>