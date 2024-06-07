<?php
session_start();
include 'config.php';

if(isset($_POST['book_id'])) {
    $book_id = $_POST['book_id'];
    $quantity = $_POST['quantity'];
    $book_title = $_POST['book_title']; // Retrieve book title from form
    $author_name = $_POST['author_name']; // Retrieve author name from form
    $book_price = $_POST['book_price']; // Retrieve book price from form
    
    // Check if the user is logged in
    if(isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        
        // Add the book to the user's cart in the database
        $total_price = $book_price * $quantity;
        $query = "INSERT INTO cart (user_id, book_id, name, price, quantity, total) VALUES ('$user_id', '$book_id', '$book_title', '$book_price', '$quantity', '$total_price')";
        
        if(mysqli_query($conn, $query)) {
            // Book added to cart successfully
            // You can redirect the user back to the adventure page or show a success message
            header("Location: adventure.php");
            exit();
        } else {
            // Handle database error
            echo "Error: " . $query . "<br>" . mysqli_error($conn);
        }
    } else {
        // Redirect the user to the login page if they are not logged in
        header("Location: login.php");
        exit();
    }
}
?>




<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>fiction  Books</title>
    <link rel="stylesheet" href="./css/adventure.css">
    <!-- Include any additional CSS stylesheets for styling the adventure page -->
</head>
<body>



<?php
    include 'index_header.php';
    ?>
    <div class="cart_form">
    <?php
    if(isset($message)){
      foreach($message as $message){
        echo '
        <div class="message" id="messages"><span>'.$message.'</span>
        </div>
        ';
      }
    }
    ?>


    <div class="content">
        <h1>fiction</h1>
        <div class="books">
            <!-- Fetch and display adventure books -->
            <?php
            $sql = "SELECT * FROM book_info WHERE category = 'fiction'";
            $result = $conn->query($sql);

            if ($result->num_rows > 0) {
                while($row = $result->fetch_assoc()) {
                    echo "<div class='book'>";
                    echo "<img src='./added_books/{$row['image']}' alt='{$row['title']}' style='height: 100px;'>"; // Display book image
                    echo "<input type='hidden' name='book_id' value='" . $row["bid"] . "'>";
                    echo "<h2>" . $row["title"] . "</h2>";
                    echo "<p>Author: " . $row["name"] . "</p>";
                    echo "<p>Price: $" . $row["price"] . "</p>";
                    // Add to Cart form
                    echo "<form action='' method='post'>";
                    echo "<input type='hidden' name='book_image' value='" . $row["image"] . "'>"; // Add hidden input for book image

                    echo "<input type='hidden' name='book_id' value='" . $row["bid"] . "'>";
                    echo "<input type='hidden' name='book_title' value='" . $row["title"] . "'>"; // Add hidden input for book title
                    echo "<input type='hidden' name='author_name' value='" . $row["name"] . "'>"; // Add hidden input for author name
                    echo "<input type='hidden' name='book_price' value='" . $row["price"] . "'>"; // Add hidden input for book price
                    echo "<input type='number' name='quantity' value='1' min='1' max='10'>"; // Quantity input field
                    echo "<input type='submit' value='Add to Cart'>";
                    
                    echo "</form>";
                    echo "</div>";
                }
            } else {
                echo "No adventure books available.";
            }
            ?>
        </div>
    </div>
    <?php include'index_footer.php'; ?>
    <!-- Include footer if you have one -->

</body>
</html>
