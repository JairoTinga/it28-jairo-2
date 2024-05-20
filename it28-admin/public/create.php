<?php
// Include config file
require_once "../db/config.php";
 
// Define variables and initialize with empty values
$product_name = $product_details = $product_retail_price = $quantity = "";
$product_name_err = $product_details_err = $product_retail_price_err = $quantity = "";
 
// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
    // Validate name
    $input_name = trim($_POST["product_name"]);
    if(empty($input_name)){
        $product_name_err = "Please enter a name.";
    } elseif(!filter_var($input_name, FILTER_VALIDATE_REGEXP, array("options"=>array("regexp"=>"/^[a-zA-Z\s]+$/")))){
        $product_name_err = "Please enter a valid name.";
    } else{
        $product_name = $input_name;
    }
    
    // Validate address
    $input_product_details = trim($_POST["product_details"]);
    if(empty($input_product_details)){
        $product_details_err = "Please enter product details.";     
    } else{
        $product_details = $input_product_details;
    }
    
    // Validate salary
    $input_product_retail_price = trim($_POST["product_retail_price"]);
    if(empty($input_product_retail_price)){
        $product_retail_price_err = "Please enter the salary amount.";     
    } elseif(!ctype_digit($input_product_retail_price)){
        $product_retail_price_err = "Please enter a positive integer value.";
    } else{
        $product_retail_price = $input_product_retail_price;
    }

      // Validate salary
      $input_quantity = trim($_POST["quantity"]);
      if(empty($input_quantity)){
          $quantity_err = "Please enter the salary amount.";     
      } elseif(!ctype_digit($input_quantity)){
          $quantity_err = "Please enter a positive integer value.";
      } else{
          $quantity = $input_quantity;
      }
  
    
    // Check input errors before inserting in database
    if(empty($product_name_err) && empty($product_details_err) && empty($product_retail_price_err) && empty($quantity_err)){
        // Prepare an insert statement
        $sql = "INSERT INTO products (product_name, product_details, product_retail_price, quantity) VALUES (:product_name, :product_details, :product_retail_price, :quantity)";
 
        if($stmt = $pdo->prepare($sql)){
            // Bind variables to the prepared statement as parameters
            $stmt->bindParam(":product_name", $param_product_name);
            $stmt->bindParam(":product_details", $param_product_details);
            $stmt->bindParam(":product_retail_price", $param_product_retail_price);
            $stmt->bindParam(":quantity", $param_quantity);
            
            // Set parameters
            $param_product_name = $product_name;
            $param_product_details = $product_details;
            $param_product_retail_price = $product_retail_price;
            $param_quantity = $quantity;
            
            // Attempt to execute the prepared statement
            if($stmt->execute()){
                // Records created successfully. Redirect to landing page
                header("location: dashboard.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }
         
        // Close statement
        unset($stmt);
    }
    
    // Close connection
    unset($pdo);
}
?>
 
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Create Record</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .wrapper{
            width: 600px;
            margin: 0 auto;
        }
    </style>
</head>
<body>
    <div class="wrapper">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="mt-5">Create Record</h2>
                    <p>Please fill this form and submit to add employee record to the database.</p>
                    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                        <div class="form-group">
                            <label>Product Name</label>
                            <input type="text" name="product_name" class="form-control <?php echo (!empty($product_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $product_name; ?>">
                            <span class="invalid-feedback"><?php echo $product_name_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Product Details</label>
                            <textarea name="product_details" class="form-control <?php echo (!empty($product_details_err)) ? 'is-invalid' : ''; ?>"><?php echo $product_details; ?></textarea>
                            <span class="invalid-feedback"><?php echo $product_details_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Retail Price</label>
                            <input type="text" name="product_retail_price" class="form-control <?php echo (!empty($product_retail_price_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $product_retail_price; ?>">
                            <span class="invalid-feedback"><?php echo $product_retail_price_err;?></span>
                        </div>
                        <div class="form-group">
                            <label>Quantity</label>
                            <input type="text" name="quantity" class="form-control <?php echo (!empty($quantity_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $quantity; ?>">
                            <span class="invalid-feedback"><?php echo $quantity_err;?></span>
                        </div>
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <a href="dashboard.php" class="btn btn-secondary ml-2">Cancel</a>
                    </form>
                </div>
            </div>        
        </div>
    </div>
</body>
</html>