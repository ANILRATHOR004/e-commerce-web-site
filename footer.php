<nav class="bottom-nav">
        <a href="index.php" class="nav-link"><i class="fa fa-home"></i><span>HOME</span></a>
        <a href="my_orders.php" class="nav-link"><i class="fa fa-box"></i><span>ORDERS</span></a>
        <a href="cart.php" class="nav-link"><i class="fa fa-shopping-cart"></i><span>CART</span></a>
        <a href="account.php" class="nav-link"><i class="fa fa-user-circle"></i><span>ACCOUNT</span></a>
    </nav>

    <script>
    function toggleSidebar() {
        const sidebar = document.getElementById('sidebar');
        const overlay = document.getElementById('overlay');
        const isActive = sidebar.classList.contains('active');

        if (isActive) {
            sidebar.classList.remove('active');
            overlay.style.display = 'none';
        } else {
            sidebar.classList.add('active');
            overlay.style.display = 'block';
        }
    }
    </script>
</body>
</html>