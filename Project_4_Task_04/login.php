<?php
// Minimal login test
$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    // Simple hardcoded check for testing
    if ($email === 'samiha@gmail.com' && $password === 'password123') {
        echo "<h2>✅ Login Successful! (Hardcoded check)</h2>";
        echo "<p>Welcome Samiha!</p>";
        exit();
    } else {
        $error = 'Invalid credentials';
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Test Login</title>
    <style>
        body { font-family: Arial; display: flex; justify-content: center; align-items: center; height: 100vh; }
        .box { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 300px; }
        input { width: 100%; padding: 0.5rem; margin: 0.5rem 0; }
        button { width: 100%; padding: 0.5rem; background: blue; color: white; border: none; cursor: pointer; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Test Login</h2>
        <?php if ($error): ?>
            <div class="error"><?php echo $error; ?></div>
        <?php endif; ?>
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="password" placeholder="Password" required>
            <button type="submit">Login</button>
        </form>
        <p style="margin-top: 1rem; font-size: 0.8rem;">Test: samiha@gmail.com / password123</p>
    </div>
</body>
</html>