<?php
session_start();
require_once __DIR__ . '/../config/database.php';

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = $_POST['email'] ?? '';
    $password = $_POST['password'] ?? '';
    
    try {
        $database = new Database();
        $db = $database->getConnection();
        
        $query = "SELECT * FROM users WHERE email = :email";
        $stmt = $db->prepare($query);
        $stmt->execute([':email' => $email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($user && password_verify($password, $user['password_hash'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            
            if ($user['role'] == 'admin') {
                header('Location: /WTech_Spring25-26/Project_4_Task_04/Controller/AdminOrderController.php?action=manage');
            } else {
                header('Location: /WTech_Spring25-26/Project_4_Task_04/Controller/OrderController.php?action=my-orders');
            }
            exit();
        } else {
            $error = 'Invalid email or password';
        }
    } catch (PDOException $e) {
        $error = 'Database error: ' . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Login - Task 4</title>
    <style>
        body { font-family: Arial; display: flex; justify-content: center; align-items: center; height: 100vh; background: #f5f5f5; margin: 0; }
        .login-box { background: white; padding: 2rem; border-radius: 8px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); width: 350px; }
        h2 { margin-bottom: 1.5rem; color: #333; text-align: center; }
        input { width: 100%; padding: 0.5rem; margin: 0.5rem 0 1rem; border: 1px solid #ddd; border-radius: 4px; box-sizing: border-box; }
        button { width: 100%; padding: 0.75rem; background: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; font-size: 1rem; }
        button:hover { background: #0056b3; }
        .error { color: #dc3545; margin-bottom: 1rem; padding: 0.5rem; background: #f8d7da; border-radius: 4px; text-align: center; }
        .info { background: #e7f3ff; padding: 1rem; margin-bottom: 1rem; border-radius: 4px; font-size: 0.9rem; text-align: center; }
        label { font-weight: bold; display: block; margin-top: 0.5rem; }
    </style>
</head>
<body>
    <div class="login-box">
        <h2>Task 4 - Login</h2>
        <div class="info">
            <strong>Test Accounts:</strong><br>
            Customer: samiha@gmail.com / password123<br>
            Admin: admin@test.com / password123
        </div>
        <?php if ($error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <form method="POST">
            <label>Email:</label>
            <input type="email" name="email" placeholder="Enter your email" required>
            <label>Password:</label>
            <input type="password" name="password" placeholder="Enter your password" required>
            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>