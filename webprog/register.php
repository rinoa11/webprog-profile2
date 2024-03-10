<?php
// Include config file
require_once "config.php";

// Define variables and initialize with empty values
$last_name = $first_name = $email = $password = $confirm_password = "";
$last_name_err = $first_name_err = $email_err = $password_err = $confirm_password_err = "";

// Define address variables
$region = $province = $city = $barangay = "";
$region_err = $province_err = $city_err = $barangay_err = "";

// Define address variables
$lot_blk = $street = $phase_subdivision = "";
$lot_blk_err = $street_err = $phase_subdivision_err = "";

// Processing form data when form is submitted
if($_SERVER["REQUEST_METHOD"] == "POST"){
 
    // Validate last name
    if(empty(trim($_POST["last_name"]))){
        $last_name_err = "Please enter your last name.";
    } else{
        $last_name = trim($_POST["last_name"]);
    }

    // Validate first name
    if(empty(trim($_POST["first_name"]))){
        $first_name_err = "Please enter your first name.";
    } else{
        $first_name = trim($_POST["first_name"]);
    }

    // Validate email
    if(empty(trim($_POST["email"]))){
        $email_err = "Please enter your email.";
    } else{
        $email = trim($_POST["email"]);
    }

    // Validate password
    if(empty(trim($_POST["password"]))){
        $password_err = "Please enter a password.";     
    } elseif(strlen(trim($_POST["password"])) < 6){
        $password_err = "Password must have at least 6 characters.";
    } else{
        $password = trim($_POST["password"]);
    }
    
    // Validate confirm password
    if(empty(trim($_POST["confirm_password"]))){
        $confirm_password_err = "Please confirm password.";     
    } else{
        $confirm_password = trim($_POST["confirm_password"]);
        if(empty($password_err) && ($password != $confirm_password)){
            $confirm_password_err = "Password did not match.";
        }
    }

    // Validate address
    if(empty(trim($_POST["lot_blk"]))){
        $lot_blk_err = "Please enter your Lot/Blk.";
    } else{
        $lot_blk = trim($_POST["lot_blk"]);
    }

    // Validate Street
    if(empty(trim($_POST["street"]))){
        $street_err = "Please enter your Street.";
    } else{
        $street = trim($_POST["street"]);
    }

    // Validate Phase/Subdivision
    if(empty(trim($_POST["phase_subdivision"]))){
        $phase_subdivision_err = "Please enter your Phase/Subdivision.";
    } else{
        $phase_subdivision = trim($_POST["phase_subdivision"]);
    }
    if(empty(trim($_POST["region"]))){
        $region_err = "Please select a region.";
    } else{
        $region = trim($_POST["region"]);
    }

    if(empty(trim($_POST["province"]))){
        $province_err = "Please select a province.";
    } else{
        $province = trim($_POST["province"]);
    }

    if(empty(trim($_POST["city"]))){
        $city_err = "Please select a city.";
    } else{
        $city = trim($_POST["city"]);
    }

    if(empty(trim($_POST["barangay"]))){
        $barangay_err = "Please select a barangay.";
    } else{
        $barangay = trim($_POST["barangay"]);
    }

    // Check input errors before inserting in database
    if(empty($last_name_err) && empty($first_name_err) && empty($email_err) && empty($password_err) && empty($confirm_password_err) && empty($region_err) && empty($province_err) && empty($city_err) && empty($barangay_err)){
        
        // Prepare an insert statement
        $sql = "INSERT INTO users (last_name, first_name, email, password, region, province, city, barangay) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
         
        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "ssssssss", $param_last_name, $param_first_name, $param_email, $param_password, $param_region, $param_province, $param_city, $param_barangay);
            
            // Set parameters
            $param_last_name = $last_name;
            $param_first_name = $first_name;
            $param_email = $email;
            $param_password = password_hash($password, PASSWORD_DEFAULT); // Creates a password hash
            $param_region = $region;
            $param_province = $province;
            $param_city = $city;
            $param_barangay = $barangay;
            
            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                // Redirect to login page
                header("location: index.php");
                exit();
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }

            // Close statement
            mysqli_stmt_close($stmt);
        }
    }
    
    // Close connection
    mysqli_close($link);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Sign Up</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.min.js"></script>
    <script src="https://f001.backblazeb2.com/file/buonzz-assets/jquery.ph-locations-v1.0.4.js"></script>
    <script src="signup.js" defer></script>
    <link rel="stylesheet" href="register.css">
</head>
<body>
    <div class="container-register">
        <div class="wrapper">
            <h2>Sign Up</h2>
            <p>Please fill this form to create an account.</p>
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="form-group">
                    <label>Last Name</label>
                    <input type="text" name="last_name" class="form-control <?php echo (!empty($last_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $last_name; ?>">
                    <span class="invalid-feedback"><?php echo $last_name_err; ?></span>
                </div>  
                <div class="form-group">
                    <label>First Name</label>
                    <input type="text" name="first_name" class="form-control <?php echo (!empty($first_name_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $first_name; ?>">
                    <span class="invalid-feedback"><?php echo $first_name_err; ?></span>
                </div>  
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" class="form-control <?php echo (!empty($email_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $email; ?>">
                    <span class="invalid-feedback"><?php echo $email_err; ?></span>
                </div>  
                <div class="form-group">
                    <label>Password</label>
                    <input type="password" name="password" class="form-control <?php echo (!empty($password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $password; ?>">
                    <span class="invalid-feedback"><?php echo $password_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Confirm Password</label>
                    <input type="password" name="confirm_password" class="form-control <?php echo (!empty($confirm_password_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $confirm_password; ?>">
                    <span class="invalid-feedback"><?php echo $confirm_password_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Lot/Blk</label>
                    <input type="text" name="lot_blk" class="form-control <?php echo (!empty($lot_blk_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $lot_blk; ?>">
                    <span class="invalid-feedback"><?php echo $lot_blk_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Street</label>
                    <input type="text" name="street" class="form-control <?php echo (!empty($street_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $street; ?>">
                    <span class="invalid-feedback"><?php echo $street_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Phase/Subdivision</label>
                    <input type="text" name="phase_subdivision" class="form-control <?php echo (!empty($phase_subdivision_err)) ? 'is-invalid' : ''; ?>" value="<?php echo $phase_subdivision; ?>">
                    <span class="invalid-feedback"><?php echo $phase_subdivision_err; ?></span>
                </div>
                <!-- Address fields -->
                <div class="form-group">
                    <label>Region</label>
                    <select name="region" id="region" class="form-control"></select>
                    <span class="invalid-feedback"><?php echo $region_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Province</label>
                    <select name="province" id="province" class="form-control"></select>
                    <span class="invalid-feedback"><?php echo $province_err; ?></span>
                </div>
                <div class="form-group">
                    <label>City</label>
                    <select name="city" id="city" class="form-control"></select>
                    <span class="invalid-feedback"><?php echo $city_err; ?></span>
                </div>
                <div class="form-group">
                    <label>Barangay</label>
                    <select name="barangay" id="barangay" class="form-control"></select>
                    <span class="invalid-feedback"><?php echo $barangay_err; ?></span>
                </div>
                <!-- End of Address fields -->
                <div class="form-group">
                    <input type="submit" class="btn btn-primary" value="Submit">
                </div>
                <p>Already have an account? <a href="index.php">Login here</a>.</p>
            </form>
        </div>
    </div>
    <script>
        
        <script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.5.0/jquery.js"></script>
        <script type="text/javascript" src="../../jquery.ph-locations.js"></script>
        <script type="text/javascript" src="https://f001.backblazeb2.com/file/buonzz-assets/jquery.ph-locations.js"></script>
        <script type="text/javascript">
            
            var my_handlers = {

                fill_provinces:  function(){

                    var region_code = $( "option:selected" , this).data('psgc-code');
                    
                    $('#province').ph_locations('fetch_list', [
                        { 
                            "filter": {
                                "region_code": region_code
                            }
                        }
                    ]);                    
                    
                },

                fill_cities: function(){

                    var province_code = $( "option:selected" , this).data('psgc-code');

                    $('#city').ph_locations('fetch_list', [
                        { 
                            "filter": {
                                "province_code": province_code
                            }
                        }
                    ]);
                },


                fill_barangays: function(){

                    var city_code = $( "option:selected" , this).data('psgc-code');
                    var province_code = $( "#province option:selected").data('psgc-code');

                    $('#barangay').ph_locations('fetch_list', [
                        { 
                            "filter": {
                                "province_code": province_code,
                                "city_code": city_code
                            }
                        }
                    ]);

                }
            };

            $(function(){
                $('#region').on('change', my_handlers.fill_provinces);
                $('#province').on('change', my_handlers.fill_cities);
                $('#city').on('change', my_handlers.fill_barangays);

                $('#region').ph_locations({'location_type': 'regions'});
                $('#province').ph_locations({'location_type': 'provinces'});
                $('#city').ph_locations({'location_type': 'cities'});
                $('#barangay').ph_locations({'location_type': 'barangays'});

                $('#region').ph_locations('fetch_list', [{'location_type': 'regions', "selected_value" : "REGION I (ILOCOS REGION)"}]);
            });
        </script>
<audio autoplay loop>
  <source src="assets/images/requiem.mp3" type="audio/mpeg">
</audio>
</body>
</html>