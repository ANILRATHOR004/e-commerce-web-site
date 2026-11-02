<?php
session_start();
include('db.php');

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$u_id = $_SESSION['user_id'];
$subtotal = 0;
$shipping = 8;

if (isset($_GET['id'])) {
    $p_id = intval($_GET['id']);
    $sql = "SELECT * FROM products WHERE id = $p_id";
    $res = mysqli_query($conn, $sql);
    
    if (!$res) {
        die(mysqli_error($conn));
    }
    
    $row = mysqli_fetch_assoc($res);
    $subtotal = $row['product_price'] ?? $row['price'] ?? 0;
} else {
    $sql = "SELECT SUM(p.product_price * c.quantity) as total FROM cart c JOIN products p ON c.product_id = p.id WHERE c.user_id = '$u_id'";
    $res = mysqli_query($conn, $sql);
    
    if (!$res) {
        die(mysqli_error($conn));
    }
    
    $row = mysqli_fetch_assoc($res);
    $subtotal = $row['total'] ?? 0;
}
?>

<!DOCTYPE html>
<html lang="hi">
<head>
    <meta charset="UTF-8">
    <title>Checkout | Store</title>
    <style>
        body { font-family: sans-serif; background: #f1f3f6; padding: 20px; }
        .card { background: #fff; padding: 20px; border-radius: 10px; margin-bottom: 15px; border: 1px solid #ddd; }
        input, select { width: 100%; padding: 12px; margin: 8px 0; border: 1px solid #ccc; border-radius: 5px; }
        .btn-pay { background: #fb641b; color: white; padding: 15px; width: 100%; border: none; border-radius: 8px; cursor: pointer; font-weight: bold; font-size: 16px; }
        .row { display: flex; justify-content: space-between; padding: 5px 0; }
        .hidden { display: none; }
    </style>
</head>
<body>

<form action="checkout_process.php" method="POST" id="checkoutForm">
    <div class="card">
        <h3>Delivery Details</h3>
        <input type="text" name="name" placeholder="Full Name" required>
        <input type="text" name="phone" placeholder="WhatsApp Number" required>
        <input type="text" name="address" placeholder="House/Village" required>
        <input type="text" name="district" placeholder="District" required>
        <input type="text" name="state" placeholder="State" required>
        <input type="text" name="pincode" placeholder="Pincode" required>
    </div>

    <div class="card">
        <h3>Delivery Speed</h3>
        <select name="speed" id="speed" onchange="updateTotal()">
            <option value="0">Normal Delivery (Free)</option>
            <option value="100">24 Hours (+ ₹100)</option>
            <option value="70">36 Hours (+ ₹70)</option>
            <option value="50">48 Hours (+ ₹50)</option>
        </select>
    </div>

    <div class="card">
        <h3>Payment Method</h3>
        <select name="payment_method" id="pay_method" onchange="updateTotal()">
            <option value="ONLINE">UPI / Card / QR</option>
            <option value="COD">Cash on Delivery (+ ₹7)</option>
        </select>

        <div id="online_fields" class="hidden">
            <input type="text" name="upi_id" placeholder="Enter UPI ID">
            <input type="text" name="card_no" placeholder="Card Number">
            <div style="display:flex; gap:10px;">
                <input type="text" placeholder="MM/YY">
                <input type="text" placeholder="CVV">
            </div>
            <p><strong>QR Code:</strong> [Display QR Image Here]</p>
        </div>
    </div>

    <div class="card">
        <h3>Order Summary</h3>
        <div class="row"><span>Subtotal:</span> <span>₹<?php echo $subtotal; ?></span></div>
        <div class="row"><span>Shipping:</span> <span>₹<?php echo $shipping; ?></span></div>
        <div class="row"><span>Delivery Charge:</span> <span id="delivery_val">₹0</span></div>
        <div class="row"><span>COD Charge:</span> <span id="cod_val">₹0</span></div>
        <hr>
        <div class="row"><b>Total Payable:</b> <b id="final_total">₹0</b></div>
        <input type="hidden" name="total_amount" id="total_input">
        <button type="submit" class="btn-pay">PLACE ORDER</button>
    </div>
</form>

<script>
function updateTotal() {
    let sub = <?php echo $subtotal; ?>;
    let ship = <?php echo $shipping; ?>;
    let speed = parseInt(document.getElementById('speed').value);
    let pay = document.getElementById('pay_method').value;
    
    document.getElementById('online_fields').classList.toggle('hidden', pay === 'COD');
    
    let cod = (pay === 'COD') ? 7 : 0;
    let total = sub + ship + speed + cod;

    document.getElementById('delivery_val').innerText = '₹' + speed;
    document.getElementById('cod_val').innerText = '₹' + cod;
    document.getElementById('final_total').innerText = '₹' + total;
    document.getElementById('total_input').value = total;
}
updateTotal();
</script>
</body>
</html>