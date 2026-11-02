<?php
include 'db.php';

if(isset($_POST['upload'])){
    $p_name = $_POST['p_name'];
    $p_price = $_POST['p_price'];
    
    // Image ki details lena
    $image_name = $_FILES['p_image']['name'];
    $image_tmp = $_FILES['p_image']['tmp_name'];
    $folder = "uploads/" . $image_name;

    // 1. Image ko 'uploads' folder mein bhejna
    if(move_uploaded_file($image_tmp, $folder)){
        // 2. Database mein details save karna
        $query = "INSERT INTO products (product_name, product_price, product_image) VALUES ('$p_name', '$p_price', '$image_name')";
        if(mysqli_query($conn, $query)){
            echo "<script>alert('Product aur Image dono upload ho gaye!');</script>";
        }
    } else {
        echo "Bhai, image upload nahi ho payi!";
    }
}
?>

<!DOCTYPE html>
<html>
<head><title>Add School Products</title></head>
<body style="font-family: sans-serif; background: #f4f4f4; padding: 50px;">
    <div style="background: #fff; padding: 20px; width: 400px; margin: auto; border-radius: 8px;">
        <h2 style="text-align: center;">Upload School Product</h2>
        <form action="" method="POST" enctype="multipart/form-data">
            <input type="text" name="p_name" placeholder="Product Name (e.g. Bag, Bottle)" style="width: 100%; padding: 10px; margin-bottom: 10px;" required>
            <input type="number" name="p_price" placeholder="Price" style="width: 100%; padding: 10px; margin-bottom: 10px;" required>
            <input type="file" name="p_image" accept="image/*" style="margin-bottom: 20px;" required>
            <button type="submit" name="upload" style="width: 100%; padding: 10px; background: #2874f0; color: white; border: none; cursor: pointer;">UPLOAD NOW</button>
        </form>
    </div>
</body>
</html>