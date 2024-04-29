<?php
// Database configuration
$servername = "localhost";
$username = "username";
$password = "";
$database = "Thriftify_db";

// Create connection
$conn = new mysqli($servername, $username, $password, $database);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $b_name = $_POST['b_name'];
    $b_gender = $_POST['b_gender'];
    $b_email = $_POST['b_email'];
    $b_address = $_POST['b_address'];
    $b_contact = $_POST['b_contact'];
    $b_password = password_hash($_POST['b_password'], PASSWORD_DEFAULT); // Hash password

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO users (ID, b_name, b_gender, b_email, b_address,b_contact, b_password) VALUES ('0',$b_name,$b_gender, $b_email,$b_address,$b_contact,$b_password)");
    $stmt->bind_param("ssssss", $name, $gender, $email, $address, $contact, $password);

    // Execute SQL statement
    if ($stmt->execute() === TRUE) {
        echo "New user registered successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

}
//regiter seller 
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $s_name = $_POST['s_name'];
    $s_gender = $_POST['s_gender'];
    $s_email = $_POST['s_email'];
    $s_address = $_POST['s_address'];
    $s_contact = $_POST['s_contact'];
    $s_password = password_hash($_POST['s_password'], PASSWORD_DEFAULT); // Hash password
    $S_nic = $_POST['S_nic'];
    // Handling document upload (assuming a file upload field with name 'document_upload')
    $document_upload = ''; // Initialize variable to store file path
    if ($_FILES['document_upload']['size'] > 0) {
        $upload_directory = "uploads/"; // Directory to upload documents
        $document_upload = $upload_directory . basename($_FILES["document_upload"]["name"]);
        if (move_uploaded_file($_FILES["document_upload"]["tmp_name"], $document_upload)) {
            echo "File uploaded successfully";
        } else {
            echo "Error uploading file";
        }
    }
    $s_mobile_number = $_POST['mobile_number'];
    $s_bank_information = $_POST['bank_information'];

    // Prepare and bind SQL statement
    $stmt = $conn->prepare("INSERT INTO sellers (ID,s_name, s_gender, s_email, s_address, s_contact, s_password, s_nic, s_document_upload, s_mobile_number, s_bank_information) VALUES ('0',$s_name,$s_gender,$s_email ,$s_address,$s_contact,$s_password,S_nic,$document_upload, $s_mobile_number,$s_bank_information)");
    $stmt->bind_param("ssssssssss", $name, $gender, $email, $address, $contact, $password, $nic, $document_upload, $mobile_number, $bank_information);

    // Execute SQL statement
    if ($stmt->execute() === TRUE) {
        echo "New seller registered successfully";
    } else {
        echo "Error: " . $stmt->error;
    }

// Function to authenticate a user
function authenticateUser($username, $password) {
    global $conn;
    $sql = "SELECT * FROM users WHERE username='$username'";
    $result = $conn->query($sql);

    if ($result->num_rows == 1) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            return true;
        }
    }
    return false;
}

// Product Management
// Function to add a new product
function addProduct($name, $price, $description, $category, $inventory) {
    global $conn;
    $sql = "INSERT INTO products (name, price, description, category, inventory) VALUES ('$name', $price, '$description', '$category', $inventory)";
    $result = $conn->query($sql);
    return $result ? true : false;
}

// Function to update a product
function updateProduct($id, $name, $price, $description, $category, $inventory) {
    global $conn;
    $sql = "UPDATE products SET name='$name', price=$price, description='$description', category='$category', inventory=$inventory WHERE id=$id";
    $result = $conn->query($sql);
    return $result ? true : false;
}

// Function to delete a product
function deleteProduct($id) {
    global $conn;
    $sql = "DELETE FROM products WHERE id=$id";
    $result = $conn->query($sql);
    return $result ? true : false;
}

// Order Processing
// Function to place an order
function placeOrder($user_id, $product_id, $quantity) {
    global $conn;
    $sql = "INSERT INTO orders (user_id, product_id, quantity) VALUES ($user_id, $product_id, $quantity)";
    $result = $conn->query($sql);
    return $result ? true : false;
}

// Function to process payment (dummy function)
function processPayment($order_id, $amount) {
    // Dummy implementation for processing payment
    return true;
}

// Shopping Cart
// Function to add a product to the shopping cart
function addToCart($user_id, $product_id, $quantity) {
    // Implementation for adding a product to the shopping cart
    return true;
}

// Search and Filtering
// Function to search products by keyword
function searchProducts($keyword) {
    global $conn;
    $sql = "SELECT * FROM products WHERE name LIKE '%$keyword%' OR description LIKE '%$keyword%'";
    $result = $conn->query($sql);
    return $result->fetch_all(MYSQLI_ASSOC);
}

// Close connection
$conn->close();
?>
