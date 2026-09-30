<?php
// 1. Error Reporting (Debugging ke liye)
error_reporting(E_ALL);
ini_set('display_errors', 1);

include 'db.php';

if(isset($_POST['upload'])){
    // Capture basic fields
    $p_name = mysqli_real_escape_string($conn, $_POST['p_name']);
    $s_name = mysqli_real_escape_string($conn, $_POST['school_name']);
    $category = mysqli_real_escape_string($conn, $_POST['item_category']);
    
    // Spelling mistake handling
    $p_name = str_ireplace('Shose', 'Shoes', $p_name);
    
    // URL parameters map setting
    if ($category == 'Uniform') {
        $db_category = 'dress';
    } else {
        $db_category = strtolower($category); 
    }
    
    // BACKEND SECURITY & INTERACTION SYNC
    if ($category == 'Books') {
        $board = isset($_POST['item_board']) ? mysqli_real_escape_string($conn, $_POST['item_board']) : 'N/A';
        $class = isset($_POST['item_class']) ? mysqli_real_escape_string($conn, $_POST['item_class']) : 'N/A';
        $stream = isset($_POST['stream_name']) ? mysqli_real_escape_string($conn, $_POST['stream_name']) : 'N/A';
        $type = 'N/A'; $size = 'N/A'; $color = 'N/A'; $gender = 'N/A';
    } elseif ($category == 'Uniform' || $category == 'Shoes') {
        $type = isset($_POST['item_type']) ? mysqli_real_escape_string($conn, $_POST['item_type']) : 'N/A';
        $size = isset($_POST['item_size']) ? mysqli_real_escape_string($conn, $_POST['item_size']) : 'N/A';
        $color = isset($_POST['item_color']) ? mysqli_real_escape_string($conn, $_POST['item_color']) : 'N/A';
        $gender = isset($_POST['gender']) ? mysqli_real_escape_string($conn, $_POST['gender']) : 'Unisex';
        $board = 'N/A'; $class = 'N/A'; $stream = 'N/A';
        $size = str_replace('Size ', '', $size);
    } else {
        $type = 'N/A'; $size = 'N/A'; $color = 'N/A'; $gender = 'N/A';
        $board = 'N/A'; $class = 'N/A'; $stream = 'N/A';
    }
    
    // Prices & Details Capture
    $p_price = (int)$_POST['p_price'];
    $mrp = isset($_POST['mrp']) ? (int)$_POST['mrp'] : $p_price; // Agar MRP nahi daali toh normal price
    $desc = isset($_POST['description']) ? mysqli_real_escape_string($conn, $_POST['description']) : '';
    
    // Policies Capture
    $is_returnable = isset($_POST['is_returnable']) ? 1 : 0;
    $is_replaceable = isset($_POST['is_replaceable']) ? 1 : 0;
    $policy_days = isset($_POST['policy_days']) ? (int)$_POST['policy_days'] : 7;
    
    // Images Upload
    $img1 = $_FILES['p_image']['name'];
    move_uploaded_file($_FILES['p_image']['tmp_name'], "uploads/" . $img1);

    $img2 = !empty($_FILES['p_image2']['name']) ? $_FILES['p_image2']['name'] : '';
    if($img2) move_uploaded_file($_FILES['p_image2']['tmp_name'], "uploads/" . $img2);

    $img3 = !empty($_FILES['p_image3']['name']) ? $_FILES['p_image3']['name'] : '';
    if($img3) move_uploaded_file($_FILES['p_image3']['tmp_name'], "uploads/" . $img3);

    $img4 = !empty($_FILES['p_image4']['name']) ? $_FILES['p_image4']['name'] : '';
    if($img4) move_uploaded_file($_FILES['p_image4']['tmp_name'], "uploads/" . $img4);

    // Final Database Insertion (Updated with new fields)
    $query = "INSERT INTO `products` 
              (`product_name`, `school_name`, `item_category`, `item_board`, `item_class`, `stream_name`, `item_type`, `item_size`, `item_color`, `gender`, `product_price`, `mrp`, `description`, `is_returnable`, `is_replaceable`, `policy_days`, `product_image`, `product_image2`, `product_image3`, `product_image4`) 
              VALUES 
              ('$p_name', '$s_name', '$db_category', '$board', '$class', '$stream', '$type', '$size', '$color', '$gender', '$p_price', '$mrp', '$desc', '$is_returnable', '$is_replaceable', '$policy_days', '$img1', '$img2', '$img3', '$img4')";
    
    if(mysqli_query($conn, $query)){
        echo "<script>
                alert('Bhai, zabardast! Product add ho gaya aur store par live hai.');
                window.location.href = 'add_product.php';
              </script>";
    } else {
        echo "Database Error: " . mysqli_error($conn);
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Gwalior Store - Advanced Admin Upload</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; padding: 20px; }
        .form-container { background: #fff; padding: 30px; width: 650px; margin: auto; border-radius: 8px; box-shadow: 0px 0px 15px rgba(0,0,0,0.1); }
        h2 { text-align: center; color: #2874f0; margin-bottom: 20px; }
        label { font-weight: bold; font-size: 14px; color: #333; display: block; margin-top: 12px; }
        input, select, textarea { width: 100%; padding: 10px; margin-top: 5px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; font-family: inherit; }
        .image-group { display: grid; grid-template-columns: 1fr 1fr; gap: 10px; margin-top: 5px; }
        .btn { background: #2874f0; color: white; border: none; padding: 14px; margin-top: 25px; width: 100%; font-size: 16px; font-weight: bold; border-radius: 4px; cursor: pointer; }
        .section-box { background: #fcfcfc; padding: 15px; border: 1px dashed #2874f0; margin-top: 15px; border-radius: 4px; display: none; }
        .price-group { display: flex; gap: 15px; }
        .price-group > div { flex: 1; }
        .policy-group label { font-weight: normal; margin-top: 0; display: inline-block; margin-right: 15px; cursor: pointer; }
        .policy-group input[type="checkbox"] { width: auto; margin-right: 5px; }
    </style>
    <script>
        function setRequiredFields(category) {
            var uniformFields = ['item_type', 'item_size', 'item_color', 'gender'];
            var booksFields = ['item_board', 'item_class'];
            var streamField = ['stream_name'];

            uniformFields.concat(booksFields).concat(streamField).forEach(function(id) {
                var element = document.getElementById(id);
                if (element) element.required = false;
            });

            if (category === "Uniform" || category === "Shoes") {
                uniformFields.forEach(function(id) {
                    var element = document.getElementById(id);
                    if (element) element.required = true;
                });
            } else if (category === "Books") {
                booksFields.forEach(function(id) {
                    var element = document.getElementById(id);
                    if (element) element.required = true;
                });
            }
        }

        function updateForm() {
            var category = document.getElementById("item_category").value;
            var uniformSection = document.getElementById("uniform-shoes-section");
            var booksSection = document.getElementById("books-section");
            var streamSection = document.getElementById("stream-section");
            
            uniformSection.style.display = "none";
            booksSection.style.display = "none";
            streamSection.style.display = "none";

            setRequiredFields(category);

            document.getElementById("item_type").innerHTML = '<option value="">-- Select Option --</option>';
            document.getElementById("item_size").innerHTML = '<option value="">-- Select Option --</option>';
            document.getElementById("stream_name").innerHTML = '<option value="">-- Select Option --</option>';
            document.getElementById("item_color").value = "";
            document.getElementById("gender").value = "";
            document.getElementById("item_board").value = "";
            document.getElementById("item_class").value = "";

            if (category === "Uniform" || category === "Shoes") {
                uniformSection.style.display = "block";
                var typeSelect = document.getElementById("item_type");
                var sizeSelect = document.getElementById("item_size");

                if (category === "Uniform") {
                    typeSelect.innerHTML = `
                        <option value="">-- Select Option --</option>
                        <option value="Summer Uniform">Summer Uniform</option>
                        <option value="Winter Uniform">Winter Uniform</option>
                        <option value="Sports Dress">Sports Dress</option>
                        <option value="Other">Other Clothing</option>`;
                    
                    sizeSelect.innerHTML = `
                        <option value="">-- Select Option --</option>
                        <option value="S">S (Small)</option>
                        <option value="M">M (Medium)</option>
                        <option value="L">L (Large)</option>
                        <option value="XL">XL (Extra Large)</option>
                        <option value="XXL">XXL (Double Extra Large)</option>
                        <option value="22">Size 22 (5T)</option>
                        <option value="24">Size 24 (6T)</option>
                        <option value="26">Size 26 (7T)</option>
                        <option value="28">Size 28 (8T)</option>
                        <option value="30">Size 30</option>
                        <option value="32">Size 32</option>
                        <option value="34">Size 34</option>
                        <option value="36">Size 36</option>
                        <option value="38">Size 38</option>
                        <option value="40">Size 40</option>
                        <option value="42">Size 42</option>
                        <option value="44">Size 44</option>`;
                } else {
                    typeSelect.innerHTML = `
                        <option value="">-- Select Option --</option>
                        <option value="Sports Shoes">Sports Shoes</option>
                        <option value="Black Shoes">Black Shoes</option>
                        <option value="White PT Shoes">White PT Shoes</option>
                        <option value="Other">Other Shoes</option>`;
                    
                    sizeSelect.innerHTML = `
                        <option value="">-- Select Option --</option>
                        <option value="1">Size 1</option>
                        <option value="2">Size 2</option>
                        <option value="3">Size 3</option>
                        <option value="4">Size 4</option>
                        <option value="5">Size 5</option>
                        <option value="6">Size 6</option>
                        <option value="7">Size 7</option>
                        <option value="8">Size 8</option>
                        <option value="9">Size 9</option>
                        <option value="10">Size 10</option>
                        <option value="7-8 Years">7-8 Years</option>
                        <option value="9-10 Years">9-10 Years</option>`;
                }
            } else if (category === "Books") {
                booksSection.style.display = "block";
                checkClass();
            }
        }

        function checkClass() {
            var board = document.getElementById("item_board").value;
            var cls = document.getElementById("item_class").value;
            var streamSection = document.getElementById("stream-section");
            var subjectSelect = document.getElementById("stream_name");

            if (cls === "11th" || cls === "12th") {
                streamSection.style.display = "block";
                subjectSelect.required = true;
                
                if (board === "NCERT" || board === "CBSE") {
                    subjectSelect.innerHTML = `
                        <option value="">-- Select Option --</option>
                        <option value="Maths">Mathematics (PCM)</option>
                        <option value="Biology">Biology (PCB)</option>
                        <option value="Commerce">Commerce</option>
                        <option value="Arts">Arts (Humanities)</option>
                        <option value="Agriculture">Agriculture</option>`;
                } else {
                    subjectSelect.innerHTML = '<option value="">-- Select Option --</option>';
                    subjectSelect.required = false;
                }
            } else {
                streamSection.style.display = "none";
                subjectSelect.innerHTML = '<option value="">-- Select Option --</option>';
                subjectSelect.required = false;
            }
        }
    </script>
</head>
<body onload="updateForm()">

<div class="form-container">
    <h2>Gwalior Store - Advanced Admin Upload</h2>
    <form action="" method="POST" enctype="multipart/form-data">
        
        <label>Main Category *</label>
        <select name="item_category" id="item_category" onchange="updateForm()" required>
            <option value="">-- Select Category --</option>
            <option value="Uniform">Uniform (Dress)</option>
            <option value="Shoes">Shoes</option>
            <option value="Books">Books & Notebooks</option>
            <option value="Stationery">Stationery</option>
            <option value="Other">Other Items</option>
        </select>

        <label>Select School *</label>
        <select name="school_name" required>
            <option value="">-- Select School --</option>
            <option value="General Open Market Store">General Open Market Store</option>
            <option value="The Scindia School">The Scindia School</option>
            <option value="Kendriya Vidyalaya (KV)">Kendriya Vidyalaya (KV)</option>
            <option value="Little Angels High School">Little Angels High School</option>
            <option value="Miss Hill School">Miss Hill School</option>
        </select>

        <label>Product Name / Title *</label>
        <input type="text" name="p_name" id="p_name" placeholder="e.g., KV Winter Blazer" required>

        <div id="uniform-shoes-section" class="section-box">
            <label>Type / Season / Style Option *</label>
            <select name="item_type" id="item_type">
                <option value="">-- Select Option --</option>
            </select>

            <label>Hybrid Size Option (Adaptive Age 5 to 18) *</label>
            <select name="item_size" id="item_size">
                <option value="">-- Select Option --</option>
            </select>

            <label>Colour Selection *</label>
            <select name="item_color" id="item_color">
                <option value="">-- Select Option --</option>
                <option value="White">White</option>
                <option value="Black">Black</option>
                <option value="Navy Blue">Navy Blue</option>
                <option value="Sky Blue">Sky Blue</option>
                <option value="Khaki">Khaki</option>
                <option value="Dark Grey">Dark Grey</option>
                <option value="Maroon">Maroon</option>
                <option value="Red">Red</option>
            </select>

            <label>Target Gender *</label>
            <select name="gender" id="gender">
                <option value="">-- Select Option --</option>
                <option value="Unisex">Unisex</option>
                <option value="Boys">Boys / Male</option>
                <option value="Girls">Girls / Female</option>
            </select>
        </div>

        <div id="books-section" class="section-box">
            <label>Select Board Column *</label>
            <select name="item_board" id="item_board" onchange="checkClass()">
                <option value="">-- Select Option --</option>
                <option value="CBSE">CBSE (English Medium Column)</option>
                <option value="NCERT">NCERT (Hindi Medium Column)</option>
            </select>

            <label>Select Class *</label>
            <select name="item_class" id="item_class" onchange="checkClass()">
                <option value="">-- Select Option --</option>
                <option value="1st">Class 1st</option><option value="2nd">Class 2nd</option>
                <option value="3rd">Class 3rd</option><option value="4th">Class 4th</option>
                <option value="5th">Class 5th</option><option value="6th">Class 6th</option>
                <option value="7th">Class 7th</option><option value="8th">Class 8th</option>
                <option value="9th">Class 9th</option><option value="10th">Class 10th</option>
                <option value="11th">Class 11th</option><option value="12th">Class 12th</option>
            </select>
        </div>

        <div id="stream-section" class="section-box">
            <label>Select Streams & Subjects Catalogue *</label>
            <select name="stream_name" id="stream_name">
                <option value="">-- Select Option --</option>
            </select>
        </div>

        <div class="price-group">
            <div>
                <label>Selling Price (₹) *</label>
                <input type="number" name="p_price" id="p_price" placeholder="Actual Customer Price" required>
            </div>
            <div>
                <label>MRP (₹) *</label>
                <input type="number" name="mrp" placeholder="Maximum Retail Price" required>
            </div>
        </div>

        <label>Product Highlights & Details (Separate by comma)</label>
        <textarea name="description" rows="3" placeholder="e.g., 100% Cotton, Easy Wash, Premium Quality..."></textarea>

        <div class="section-box" style="display: block; background: #f0f7ff; border-color: #a0c4ff;">
            <label style="margin-top:0; color:#0056b3;">Trust & Return Policies</label>
            <div class="policy-group" style="margin-top:10px;">
                <label><input type="checkbox" name="is_returnable" value="1" checked> Return Available</label>
                <label><input type="checkbox" name="is_replaceable" value="1" checked> Replacement Available</label>
                <label style="margin-right:0;">Valid for (Days): 
                    <input type="number" name="policy_days" value="7" style="width:60px; padding:5px; margin-left:5px; display:inline-block;">
                </label>
            </div>
        </div>

        <label>Product Images Gallery (Main view + Multi-angle views):</label>
        <div class="image-group">
            <div><span style="font-size:12px; color:#555;">Main Image *</span><input type="file" name="p_image" accept="image/*" required></div>
            <div><span style="font-size:12px; color:#555;">Image 2 (Optional)</span><input type="file" name="p_image2" accept="image/*"></div>
            <div><span style="font-size:12px; color:#555;">Image 3 (Optional)</span><input type="file" name="p_image3" accept="image/*"></div>
            <div><span style="font-size:12px; color:#555;">Image 4 (Optional)</span><input type="file" name="p_image4" accept="image/*"></div>
        </div>

        <button type="submit" name="upload" class="btn">UPLOAD ITEM NOW</button>
    </form>
</div>

</body>
</html>