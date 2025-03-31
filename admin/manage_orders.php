<?php
session_start();
include '../config/config.php';

// Redirect non-admins
if (!isset($_SESSION["user"]) || $_SESSION["account_type"] != 1) {
    header("Location: ../login.php");
    exit();
}

// ✅ Handle AJAX request to update order status
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['orderId'], $_POST['status'])) {
    $orderId = intval($_POST['orderId']);
    $status = trim($_POST['status']);

    // Valid status options
    $valid_statuses = ["Pending", "Complete", "Canceled"];
    if (!in_array($status, $valid_statuses)) {
        echo "invalid_status";
        exit();
    }

    // Update the order status in the database
    $stmt = $conn->prepare("UPDATE orders SET status = ? WHERE id = ?");
    $stmt->bind_param("si", $status, $orderId);

    if ($stmt->execute()) {
        echo "success";
    } else {
        echo "error";
    }

    $stmt->close();
    exit(); // Stop further execution
}

// ✅ Fetch Orders
$order_query = "SELECT orders.id, orders.quantity, orders.total_price, orders.payment_method, 
                       orders.shipping_address, orders.full_name, orders.email, orders.phone, orders.order_date, 
                       products.name AS product_name, products.image, orders.status 
                FROM orders 
                JOIN products ON orders.product_id = products.id 
                ORDER BY orders.order_date DESC";

$order_result = $conn->query($order_query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Orders - Admin</title>
    <link rel="stylesheet" href="styles/manage_orders_style.css">
</head>
<body>

    <?php include 'admin_navbar.php'; ?>

    <div class="admin-container">
        <a href="index.php" class="back-btn">⬅</a>
        <h1>Manage Orders</h1>

        <!-- ✅ Success Message for Deletion -->
        <?php
        if (isset($_SESSION['message'])) {
            echo "<div class='message success'>{$_SESSION['message']}</div>";
            unset($_SESSION['message']); // Remove message after displaying
        }
        ?>

        <!-- Orders Table -->
        <table>
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Product</th>
                    <th>Quantity</th>
                    <th>Total Price</th>
                    <th>Payment Method</th>
                    <th>Customer</th>
                    <th>Order Date</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php
                if ($order_result->num_rows > 0) {
                    while ($order = $order_result->fetch_assoc()) {
                        echo "<tr>
                            <td>#{$order['id']}</td>
                            <td>
                                <img src='../uploads/{$order['image']}' alt='{$order['product_name']}' class='order-img'>
                                <br>{$order['product_name']}
                            </td>
                            <td>{$order['quantity']}</td>
                            <td>$" . number_format($order['total_price'], 2) . "</td>
                            <td>{$order['payment_method']}</td>
                            <td>{$order['full_name']}<br>{$order['email']}<br>{$order['phone']}</td>
                            <td>" . date("F j, Y", strtotime($order['order_date'])) . "</td>
                            <td>
                                <select name='status' class='status-dropdown' data-id='{$order['id']}'>
                                    <option value='Pending' ". ($order['status'] == 'Pending' ? 'selected' : '') .">Pending</option>
                                    <option value='Complete' ". ($order['status'] == 'Complete' ? 'selected' : '') .">Complete</option>
                                    <option value='Canceled' ". ($order['status'] == 'Canceled' ? 'selected' : '') .">Canceled</option>
                                </select>
                            </td>
                            <td>
                                <a href='manage_order_delete.php?id={$order['id']}' class='delete-btn' onclick='return confirm(\"Are you sure you want to delete this order?\");'>Delete</a>
                            </td>
                        </tr>";
                    }
                } else {
                    echo "<tr><td colspan='9' class='no-orders'>No orders found.</td></tr>";
                }
                ?>
            </tbody>
        </table>
    </div>

    <script>
        document.querySelectorAll('.status-dropdown').forEach(select => {
            select.addEventListener('change', function() {
                const orderId = this.getAttribute('data-id');
                const status = this.value;

                fetch('manage_orders.php', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                    body: `orderId=${orderId}&status=${status}`
                })
                .then(response => response.text())
                .then(message => {
                    console.log("Server response:", message);
                    if (message === 'success') {
                        alert('Order status updated successfully!');
                    } else {
                        alert('Error updating order status: ' + message);
                    }
                });
            });
        });
    </script>

</body>
</html>
