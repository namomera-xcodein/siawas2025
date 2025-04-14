<!DOCTYPE html>
<html>

<head>
    <title>Test Page</title>
</head>

<body>
    <div class="container">
        <h1>Test Page</h1>
        <p>This is a test page for development purposes.</p>

        <?php
        // Test PHP functionality
        $test_var = "Hello World!";
        echo "<div>$test_var</div>";
        
        // Test database connection if needed
        /*
        try {
            $conn = new PDO("mysql:host=localhost;dbname=your_database", "username", "password");
            $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            echo "<div class='success'>Database connection successful</div>";
        } catch(PDOException $e) {
            echo "<div class='error'>Connection failed: " . $e->getMessage() . "</div>";
        }
        */
        ?>
        <?php
session_start();

// Mengecek apakah user sudah login dan menampilkan session terkait
var_dump($_SESSION['user_id']); // Menampilkan user_id
var_dump($_SESSION['role']); // Menampilkan role

// Jika Anda ingin memeriksa nilai user_id dan role dari session
?>


        <div class="test-section">
            <h2>Test Section</h2>
            <ul>
                <li>Test Item 1</li>
                <li>Test Item 2</li>
                <li>Test Item 3</li>
            </ul>
        </div>
    </div>

    <style>
    .container {
        width: 80%;
        margin: 0 auto;
        padding: 20px;
    }

    .success {
        color: green;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid green;
    }

    .error {
        color: red;
        padding: 10px;
        margin: 10px 0;
        border: 1px solid red;
    }

    .test-section {
        margin-top: 20px;
        padding: 15px;
        border: 1px solid #ccc;
    }
    </style>
</body>

<div class="test-section">
    <img src="https://www.google.com/url?sa=i&url=https%3A%2F%2Fgratisography.com%2F&psig=AOvVaw06R7eM1rr7ve36hU9Z5xQ7&ust=1742110756439000&source=images&cd=vfe&opi=89978449&ved=0CBEQjRxqFwoTCMiswbfKi4wDFQAAAAAdAAAAABAE"
        alt="Gratisography Image" style="max-width: 100%; height: auto;">
</div>

</html>