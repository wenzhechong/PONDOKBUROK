

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Order List</title>
    <link rel="stylesheet" href="database.css">
</head>
<body>
    <img src="../assets/brand/pondokburoklogo.png">
    <h1>Order List</h1>
    <?php
// Add a random query parameter to the URL
$refresh_url = $_SERVER['PHP_SELF'] . '?' . uniqid();
?>
 <a href="<?php echo $refresh_url; ?>"  >Refresh</a>
    <?php
// Database credentials

$host = "localhost";
$db_name = "pondokburok";
$username = "root";
$password = "";
$conn = mysqli_connect($host, $username, $password, $db_name);
if (!$conn) {
 die("Connection failed: " . mysqli_connect_error());
}


// SQL query to retrieve merged table data
$sql = "SELECT orders.order_id, orders.order_date, customer.customer_name,customer.customer_phone,customer.customer_email,
customer.customer_address,product.product_name, orders.quantity,orders.total_cost,orders.order_status
        FROM orders
        INNER JOIN product ON orders.product_id = product.product_id
        INNER JOIN customer ON orders.customer_id = customer.customer_id";

$result = $conn->query($sql);

if ($_SERVER['REQUEST_METHOD'] === 'POST'){

    if (!empty($_POST)) {
        //get ID from Select Box
        $id = key($_POST);
        //get value from Select Box's option
        $status = $_POST[$id];
        //create query to alter data based on ID and new status value
        $query = "USE $db_name; UPDATE orders SET order_status = '$status' WHERE order_id = '$id'";
        // Execute the SQL queries
        $conn->multi_query($query);
        while(mysqli_next_result($conn)){;}
        echo"<script>
        alert('Succesful Update!')
        </script>"; 
    }
  }
  

// Check if any rows are returned
if ($result->num_rows > 0) {
    // Start generating HTML table
    echo "<table>";
    echo "<tr>";
    echo "<th>Order ID</th>";
    echo "<th>Order Date</th>";
    echo "<th>Customer Name</th>";
    echo "<th>Customer Phone Number</th>";
    echo "<th>Customer Email</th>";
    echo "<th>Customer Address</th>";
    echo "<th>Product Name</th>";
    echo "<th>Quantity Order</th>";
    echo "<th>Total Cost (RM)</th>";
    echo "<th>Status</th>";
    echo "</tr>";
   
        
    // Print table rows
    while ($row = $result->fetch_assoc()) {
        if ($row['order_status']==0){
            $status = "Not Completed";
        }else if ($row['order_status']==1){
            $status = "Completed";}
        
      
        echo "<tr>";
        echo "<td>" . $row["order_id"] . "</td>";
        echo "<td>" . $row["order_date"] . "</td>";
        echo "<td>" . $row["customer_name"] . "</td>";
        echo "<td>0" . $row["customer_phone"] . "</td>";
        echo "<td>" . $row["customer_email"] . "</td>";
        echo "<td>" . $row["customer_address"] . "</td>";
        echo "<td>" . $row["product_name"] . "</td>";
        echo "<td>" . $row["quantity"] . "</td>";
        echo "<td>" . $row["total_cost"] . "</td>";
      
        echo "<td> <form method='post'>
        <select name=".$row['order_id'].">
            <option value=".$row['order_status']." disabled selected>".$status."</option>
            <option value='0'>Not Completed</option>
            <option value='1'>Completed</option>
        </select>
        <input type='submit' value='Apply Change'>
        </form>"  . "</td>";
        echo "</tr>";
    }

    // End table
    echo "</table>";
} 
else {
    echo "No results found.";
}

// Close the database connection
$conn->close();
?>
</body>
</html>