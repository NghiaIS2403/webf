<?=template_header('Login')?>
    <div class="login-container">
      <form action="loginhanding.php" method="post">
        <h2>Login</h2>
        <div class="input-group">
          <label for="username">Username</label>
          <input type="text" id="username" name="username" required />
        </div>
        <div class="input-group">
          <label for="password">Password</label>
          <input type="password" id="password" name="password" required />
        </div>
        <div class="error-messege">
        <?php
        if (isset($_GET['error']) && $_GET['error'] == '1') {
          echo '<p class="error-message">Tên người dùng hoặc mật khẩu không chính xác.</p>';
        }
        ?>
        </div>
        <button type="submit" name ="submit">Login</button>
      </form>
    </div>
<?=template_footer('Login')?>