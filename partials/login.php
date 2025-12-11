<h1>Admin Login</h1>
<?php if (!empty($admin_login_error)): ?>
  <p class="error"><?= htmlspecialchars($admin_login_error) ?></p>
<?php endif; ?>
<form method="post" action="">
  <input type="hidden" name="action" value="login">
  <div><label>Username <input name="username" required></label></div>
  <div><label>Password <input name="password" type="password" required></label></div>
  <div><button type="submit">Login</button></div>
</form>
