<?=template_header( 'Sign Up')?>
<div class="register-container">
    <form action="signuphanding.php" method="POST">
      <h2>Register</h2>
      <div class="input-group">
        <label for="username">Username</label>
        <input id="username" type="text" name="username" required />
      </div>
      <div class="input-group">
        <label for="password">Password</label>
        <input id="password" type="password" name="password" required />
      </div>
      <div class="input-group">
        <label for="repeatpassword">Repeat Password</label>
        <input id="repeatpassword" type="password" name="repeatpassword" required />
      </div>
      <div class="input-group">
        <label for="email">Email</label>
        <input id="email" type="email" name="email" required />
      </div>
      <button type="submit" name="register">Register</button>
    </form>
    <div name="error-messege">
    <?php
      if(isset($_GET['error'])&& $_GET['error']==2) echo "Tên đăng nhập đã được sử dụng";
      if(isset($_GET['error'])&& $_GET['error']==3) echo "Email đã được sử dụng";
    ?>
</div>
<?=template_footer('Sign Up')?>