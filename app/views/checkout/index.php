<?php ob_start(); ?>

<div class="auth-container">
    <div class="auth-card glassmorphism" style="max-width: 500px;">
        <h2>Checkout - Mobile Money</h2>
        <p>Pay securely via MTN or Airtel Money.</p>

        <?php 
        $grandTotal = 0;
        foreach($_SESSION['cart'] as $item) {
            $grandTotal += $item['price'] * $item['quantity'];
        }
        ?>

        <h3 style="margin: 1.5rem 0;">Total to Pay: $<span style="color: var(--primary);"><?= number_format($grandTotal, 2) ?></span></h3>

        <form action="/checkout/process" method="POST">
            <div class="form-group">
                <label>Mobile Network</label>
                <select name="network" class="form-control" required>
                    <option value="MTN">MTN Uganda</option>
                    <option value="Airtel">Airtel Uganda</option>
                </select>
            </div>
            <div class="form-group">
                <label>Mobile Money Number</label>
                <input type="text" name="phone" class="form-control" placeholder="07XXXXXXXX" required>
            </div>
            <button type="submit" class="btn btn-primary btn-block">Pay Now</button>
        </form>
    </div>
</div>

<?php 
$content = ob_get_clean(); 
require_once APP . '/views/layouts/main.php'; 
?>
