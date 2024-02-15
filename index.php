<?php
session_start(); // Start the session

$servername = "localhost";
$username = "root";
$password = "";
$dbname = "form";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Handle File Upload
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['add_product'])) {
    $product_name = $_POST['product_name'];
    $product_price = $_POST['product_price'];

    $target_dir = "uploads/";
    $target_file = $target_dir . basename($_FILES["product_image"]["name"]);
    $uploadOk = 1;
    $imageFileType = strtolower(pathinfo($target_file,PATHINFO_EXTENSION));

    $check = getimagesize($_FILES["product_image"]["tmp_name"]);
    if($check !== false) {
        $uploadOk = 1;
    } else {
        echo "File is not an image.";
        $uploadOk = 0;
    }

    if ($_FILES["product_image"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    if($imageFileType != "jpg" && $imageFileType != "png" && $imageFileType != "jpeg"
    && $imageFileType != "gif" ) {
        echo "Sorry, only JPG, JPEG, PNG & GIF files are allowed.";
        $uploadOk = 0;
    }

    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        if (move_uploaded_file($_FILES["product_image"]["tmp_name"], $target_file)) {
            $product_image = $target_file;

            $sql = "INSERT INTO products (product_name, product_price, product_image) VALUES ('$product_name', '$product_price', '$product_image')";
            if ($conn->query($sql) === TRUE) {
                header("Location: index.php");
            } else {
                echo "Error: " . $sql . "<br>" . $conn->error;
            }
        } else {
            echo "Sorry, there was an error uploading your file.";
        }
    }
}
// Handle Add to Cart
if (isset($_GET['add_to_cart']) && isset($_GET['quantity'])) {
    $id = $_GET['add_to_cart'];
    $quantity = $_GET['quantity'];

    // Fetch the product details based on the ID
    $sql = "SELECT * FROM products WHERE id = $id";
    $result = $conn->query($sql);
    $product = $result->fetch_assoc();

    // Add the product to the cart with the specified quantity
    if (!isset($_SESSION['cart'])) {
        $_SESSION['cart'] = array();
    }
    $product['quantity'] = $quantity;
    $_SESSION['cart'][] = $product;

    // Show alert message and redirect to cart
    echo '<script>alert("Product has been added to your cart."); window.location.href = "cart.php";</script>';
}

// Delete Product
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sql = "DELETE FROM products WHERE id = $id";
    if ($conn->query($sql) === TRUE) {
        header("Location: index.php");
    } else {
        echo "Error deleting record: " . $conn->error;
    }
}

// Fetch Products
$sql = "SELECT * FROM products";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Product Management</title>
</head>
<body>
    <h2>Add Product</h2>
    <form method="post" action="" enctype="multipart/form-data">
        <label>Product Name:</label>
        <input type="text" name="product_name" required><br><br>
        <label>Product Price:</label>
        <input type="number" name="product_price" step="0.01" required><br><br>
        <label>Product Image:</label>
        <input type="file" name="product_image" required><br><br>
        <input type="submit" name="add_product" value="Add Product">
    </form>

    
    <h2>Product List</h2>
<table border="1">
    <tr>
        <th>ID</th>
        <th>Product Name</th>
        <th>Product Price</th>
        <th>Product Image</th>
        <th>Quantity</th>
        <th>Action</th>
    </tr>
    <?php while($row = $result->fetch_assoc()): ?>
        <tr>
                <td><?php echo $row['id']; ?></td>
                <td><?php echo $row['product_name']; ?></td>
                <td><?php echo $row['product_price']; ?></td>
                <td><img src="<?php echo $row['product_image']; ?>" height="50"></td>
                <td>
                    <input type="number" id="quantity_<?php echo $row['id']; ?>" value="1" min="1">
                </td>
                <td>
                    <button onclick="addToCart(<?php echo $row['id']; ?>)">Add to Cart</button>
                    <button onclick="deleteProduct(<?php echo $row['id']; ?>)">Delete</button>
                </td>
            </tr>
    <?php endwhile; ?>
</table>
<!-- Add JavaScript functions -->
<script>
        function addToCart(id) {
            var quantity = document.getElementById('quantity_' + id).value;
            window.location.href = "index.php?add_to_cart=" + id + "&quantity=" + quantity;
        }
        

        function deleteProduct(id) {
            if (confirm('Are you sure you want to delete this product?')) {
                window.location.href = "index.php?delete=" + id;
            }
        }
    </script>
</body>
</html>

<?php
$conn->close();
?>
