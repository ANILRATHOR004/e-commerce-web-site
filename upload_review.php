<?php
include('db.php');
if(isset($_FILES['review_img'])) {
    $p_id = $_POST['p_id'];
    $img_name = $_FILES['review_img']['name'];
    move_uploaded_file($_FILES['review_img']['tmp_name'], "uploads/reviews/".$img_name);
    // Yahan database mein image save karne ki query likho
    echo "<script>alert('Thank you for your feedback!'); window.history.back();</script>";
}
?>