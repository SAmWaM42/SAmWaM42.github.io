<?php 
session_start(); 
require_once 'DatabaseConnection/Database.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = $_POST['email'];
    $password = $_POST['password'];
    
    $database = new Database();
    $conn = $database->getConnection();
    
    $sql = "SELECT employee_id, password FROM Employees WHERE email = :email";
    $stmt = $conn->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($user && $password === $user['password']) {
        $_SESSION['employee_id'] = $user['employee_id'];
        header("Location: Display3.php");
        exit();
    } else {
        $error = "Invalid email or password.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TimeOff Login</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: Arial, sans-serif;
        }

        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }

        .navbar {
            background-color: #1a3a1a;
            padding: 1rem 2rem;
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
        }

        .logo-container {
            display: flex;
            align-items: center;
            gap: 1rem;
        }

        .logo {
            width: 40px;
            height: 40px;
        }

        .brand-name {
            color: white;
            font-size: 1.5rem;
            font-weight: bold;
        }

        .login-container {
            max-width: 400px;
            margin: 4rem auto;
            padding: 2rem;
            background: linear-gradient(135deg, #a8c9a1 0%, #86a886 100%);
            backdrop-filter: blur(10px);
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }

        .login-container h2 {
            color: ;
            text-align: center;
            margin-bottom: 2rem;
            font-size: 1.8rem;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        .form-group label {
            display: block;
            margin-bottom: 0.5rem;
            color: #1a3a1a;
            font-weight: bold;
        }

        .form-group input {
            width: 100%;
            padding: 0.8rem;
            border: none;
            border-radius: 5px;
            background-color: white;
            font-size: 1rem;
            outline: none;
            transition: box-shadow 0.3s ease;
        }

        .form-group input:focus {
            box-shadow: 0 0 0 2px #1a3a1a;
        }

        .error-message {
            color: #d32f2f;
            background-color: rgba(211, 47, 47, 0.1);
            padding: 0.8rem;
            border-radius: 5px;
            margin-bottom: 1.5rem;
            text-align: center;
        }

        .submit-btn {
            width: 100%;
            padding: 1rem;
            background-color: #1a3a1a;
            color: white;
            border: none;
            border-radius: 5px;
            font-size: 1rem;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }

        .submit-btn:hover {
            background-color: #2d5a2d;
        }

        @media (max-width: 768px) {
            .login-container {
                margin: 2rem 1rem;
            }
        }
    </style>
</head>
<body>
    <nav class="navbar">
        <div class="logo-container">
            <img src="Timeoff[1].jpg" alt="TimeOff Logo" class="logo">
            <span class="brand-name">TimeOff</span>
        </div>
    </nav>

    <div class="login-container">
        <h2>Employee Login</h2>
        
        <?php if (isset($error)): ?>
            <div class="error-message">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="login.php">
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" required>
            </div>
            
            <div class="form-group">
                <label for="password">Password</label>
                <input type="password" id="password" name="password" required>
            </div>
            
            <button type="submit" class="submit-btn">Login</button>
        </form>
    </div>
</body>
</html>