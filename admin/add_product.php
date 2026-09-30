<?php
include('../db.php'); 
session_start();

if(!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $item_name = mysqli_real_escape_string($conn, $_POST['item_name']);
    $price = $_POST['price'];
    $stock = $_POST['stock'];
    $category = $_POST['category'];

    $target_dir = "../uploads/"; 
    $file_name = time() . "_" . basename($_FILES["image"]["name"]); 
    $target_file = $target_dir . $file_name;
    $upload_ok = true;

    $check = getimagesize($_FILES["image"]["tmp_name"]);
    if($check === false) {
        echo "<script>alert('Error: File image nahi hai.');</script>";
        $upload_ok = false;
    }

    if ($upload_ok) {
        if (move_uploaded_file($_FILES["image"]["tmp_name"], $target_file)) {
            $query = "INSERT INTO products (item_name, price, stock, category, image) 
                      VALUES ('$item_name', '$price', '$stock', '$category', '$file_name')";
            
            if (mysqli_query($conn, $query)) {
                echo "<script>alert('Badhai ho! Product GwaliorStore par live ho gaya.'); window.location.href='admin_dashboard.php';</script>";
            } else {
                echo "Database Error: " . mysqli_error($conn);
            }
        } else {
            echo "<script>alert('Error: Image upload karne mein panga ho gaya.');</script>";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product | Admin Panel</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; background: #f1f3f6; margin: 0; padding: 20px; }
        .admin-card { background: white; max-width: 500px; margin: 50px auto; padding: 30px; border-radius: 15px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); }
        h2 { color: #2874f0; text-align: center; margin-bottom: 25px; }
        .input-group { margin-bottom: 15px; }
        label { display: block; font-size: 13px; font-weight: 700; color: #666; margin-bottom: 5px; }
        input, select { width: 100%; padding: 12px; border: 1px solid #ddd; border-radius: 8px; box-sizing: border-box; outline: none; }
        .upload-btn { width: 100%; padding: 15px; background: #fb641b; color: white; border: none; border-radius: 8px; font-weight: 700; cursor: pointer; margin-top: 10px; transition: 0.3s; }
        .back-link { display: block; text-align: center; margin-top: 20px; color: #2874f0; text-decoration: none; font-size: 14px; }
    </style>
</head>
<body>
    <div class="admin-card">
        <h2>Add New Product</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <div class="input-group"><label>Product Name</label><input type="text" name="item_name" required></div>
            <div class="input-group"><label>Price (₹)</label><input type="number" name="price" required></div>
            <div class="input-group"><label>Stock Quantity</label><input type="number" name="stock" required></div>
            <div class="input-group">
                <label>Category</label>
                <select name="category">
                    <option value="Uniform">Uniform</option>
                    <option value="Bags">Bags</option>
                    <option value="Stationery">Stationery</option>
                    <option value="Shoes">Shoes</option>
                </select>
            </div>
            <div class="input-group"><label>Product Image</label><input type="file" name="image" required></div>
            <button type="submit" class="upload-btn">UPLOAD TO STORE 🚀</button>
        </form>
        <a href="admin_dashboard.php" class="back-link">← Back to Dashboard</a>
    </div>
</body>
</html>