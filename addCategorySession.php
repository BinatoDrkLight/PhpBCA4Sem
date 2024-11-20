<?php
session_start();
// Initialize variables
$product_id = $category = $title = $detail = $price = $created_at = "";
$image_name = "";

// Handle form submission
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $product_id = $_POST['id'];
    $category = $_POST['category'];
    $title = $_POST['title'];
    if (!isset($_SESSION['titles'])) {
        $_SESSION['titles'] = [];
    }
    $detail = $_POST['detail'];
    $price = $_POST['price'];
    $created_at = date('Y-m-d H:i:s');

    // Handle the image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
        $image_name = basename($_FILES['image']['name']);
        $target_dir = "uploads/";
        $target_file = $target_dir . $image_name;

        // Move the uploaded file to the uploads directory
        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            echo "The file " . htmlspecialchars($image_name) . " has been uploaded.<br>";
        } else {
            echo "Sorry, there was an error uploading your file.<br>";
        }
    }
    $con = mysqli_connect('localhost','root','','jsoneproduct');
    if(!$con)
        {
            echo "connection to db failed".mysqli_connect_error();
        }

    $sql = "INSERT INTO product(category,title,details,price,created_at,image_add)VALUES('$category','$title','$detail','$price','$created_at','$image_name')";
    $res= mysqli_query($con,$sql);

    if(!$res){
        echo "failed to insert";
    }


    // Here you would typically save the product data to a database
    // For this example, we'll just print it to the screen
    echo "<h2>Product Added:</h2>";
    echo "Product ID: $product_id<br>";
    echo "Category: $category<br>";
    echo "Title: $title<br>";
    echo "Detail: $detail<br>";
    echo "Price: $price<br>";
    echo "Created At: $created_at<br>";
    echo "Image: $image_name<br>";
}

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Product</title>
</head>
<body>
    <h1>Add Product</h1>
    <form action="" method="post" enctype="multipart/form-data">
        <label for="id">ID:</label><br>
        <input type="text" id="id" name="id" required><br><br>
        
        <label for="category">Category:</label><br>
        <input type="text" id="category" name="category" required><br><br>
        
        <label for="title">Title:</label> <?php

            $isSubmitted = isset($_POST['submit']);
            if($isSubmitted){
                $_SESSION['titles'][] = $title;
                echo "<select>";

                foreach($_SESSION['titles'] as $optTitle){
                    echo "<option>". htmlspecialchars($optTitle) ."</option>";
                } 
                
                echo "</select>";
            }
        ?><br>
        <input type="text" id="title" name="title" required><br><br>
        
        <label for="detail">Detail:</label><br>
        <textarea id="detail" name="detail" required></textarea><br><br>
        
        <label for="price">Price:</label><br>
        <input type="number" id="price" name="price" step="0.01" required><br><br>
        
        <label for="created_at">Created At:</label><br>
        <input type="datetime-local" id="created_at" name="created_at" value="<?php echo date('Y-m-d\TH:i'); ?>" readonly><br><br>
        
        <label for="image">Image:</label><br>
        <input type="file" id="image" name="image_add" accept="image/*" required><br><br>
        
        <input type="submit" name="submit" value="Add Product">
    </form>
</body>
</html>