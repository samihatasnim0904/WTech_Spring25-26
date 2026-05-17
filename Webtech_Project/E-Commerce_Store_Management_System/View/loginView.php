
<!DOCTYPE html>
<html>
<head>
<title>Login</title>

<link rel="stylesheet" href="/Webtech_Spring_25-26-_F/Webtech_Project/E-Commerce_Store_Management_System/View/Style/loginStyle.css">
</head>

<body>

<div class="login-container">

<h2>Login</h2>

<?php if(isset($_GET['error'])){ ?>
<p class="error"><?php echo $_GET['error']; ?></p>
<?php } ?>

<form action="../Controller/loginValidation.php" method="POST">

<input type="text" name="username" placeholder="Username" required>

<div class="password-box">
<input type="password" name="password" id="password" placeholder="Password" required>
<span onclick="togglePassword()">👁️</span>
</div>

<label>
<input type="checkbox" name="remember"> Remember Me
</label>

<button type="submit" name="login">Login</button>

</form>

<p>Don't have an account? <a href="signupView.php">Sign up</a></p>

</div>

<script>
function togglePassword(){
    var pass = document.getElementById("password");
    pass.type = pass.type === "password" ? "text" : "password";
}
</script>

</body>
</html>