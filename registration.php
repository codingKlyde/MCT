<?php
    include 'configuration.php';





    // DEFINE VARIABLES AND INITIALIZE WITH EMPTY VALUES

    $username = $password = $confirm_password = "";
    $username_err = $password_err = $confirm_password_err = "";
 




    // PROCESSING FORM DATA WHEN FORM IS SUBMITED

    if($_SERVER["REQUEST_METHOD"] == "POST")
        {
            // VALIDATE USERNAME
	
            if(empty(trim($_POST["username"])))
                {
                    $username_err = "Please enter a username.";
                } 
                else
                {
                    // PREPARE A SELECT STATEMENT
                    
                    $sql = "SELECT username FROM users WHERE username = ?";
        
                    if($stmt = mysqli_prepare($link, $sql))
                        {
                            // BIIND VARIABLES TO THE PREPARED STATEMENT PARAMETERS
            
                            mysqli_stmt_bind_param($stmt, "s", $param_username);
            
                            // SET PARAMETERS

                            $param_username = trim($_POST["username"]);
            
                            // ATTEMPT TO EXECUTE THE PREPARED STATEMENT
                            
                            if(mysqli_stmt_execute($stmt))
                                {
                                    // STORE RESULT
                
                                    mysqli_stmt_store_result($stmt);
                
                                    if(mysqli_stmt_num_rows($stmt) == 1)
                                        {
                                            $username_err = "This username is already taken.";
                                        } 
                                        else
                                        {
                                            $username = trim($_POST["username"]);
                                        }
                                } 
                                else
                                {
                                    echo "Oops! Something went wrong. Please try again later.";
                                }
                        }
        
                    // CLOSE STATEMENT
                 
                    mysqli_stmt_close($stmt);   
                }
    
            // VALIDATE PASSWORD
    
            if(empty(trim($_POST["password"])))
                {
                    $password_err = "Please enter a password.";     
                } 
                elseif(strlen(trim($_POST["password"])) < 6)
                    {
                        $password_err = "Password must have atleast 6 characters.";
                    } 
                    else
                        {
                            $password = trim($_POST["password"]);
                        }
    
                        // VALIDATE CONFIRM PASSWORD
    
                        if(empty(trim($_POST["confirm_password"])))
                            {
                                $confirm_password_err = "Please confirm password.";     
                            } 
                            else
                            {
                                $confirm_password = trim($_POST["confirm_password"]);
        
                                if(empty($password_err) && ($password != $confirm_password))
                                    {
                                        $confirm_password_err = "Password did not match.";
                                    }
                            }
    
                            // CHECK INPUT ERRORS BEFORE INSERTING IN DATABASE
    
                            if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
                                {
        
                                    // PREPARE AN INSERT STATEMENT
                                    
                                    $sql = "INSERT INTO users (username, password) VALUES (?, ?)";
         
                                    if($stmt = mysqli_prepare($link, $sql))
                                        {
            
                                            // BIND VARIABLES TO THE PREPARED STATEMENT AS PARAMETERS
            
                                            mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);
            
                                            // SET PARAMETERS
            
                                            $param_username = $username;
                                            $param_password = password_hash($password, PASSWORD_DEFAULT); // CREATES A PASSWORD HASH
            
                                            // ATTEMPT TO EXECUTE THE PREPARED STATEMENT
                                            
                                            if(mysqli_stmt_execute($stmt))
                                                {
                                                    // REDIRECT TO THE LOGIN PAGE
			                    
                                                    header("location: login.php");
                                                } 
                                                else
                                                {
                                                    echo "Something went wrong. Please try again later.";
                                                }
                                        }
         
                                        // CLOSE STATEMENT

                                        mysqli_stmt_close($stmt);
                                }

                                // CLOSE CONNECTION

                                mysqli_close($link);
        }
?>
 


















<!DOCTYPE html>
    <html lang="en">
        <head>
            <meta charset="UTF-8">
            <meta http-equiv="X-UA-Compatible" content="IE=edge">
            <meta name="viewport" content="width=device-width, initial-scale=1.0">
            
            <title>
                Sign Up
            </title>

            <link href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.css" rel="stylesheet">

            <link href="index.css" rel="stylesheet">
            <link href="logo.png" rel="icon">


            <!-- FONS -->
            <link href="https://fonts.googleapis.com/css2?family=Oxygen:wght@300&display=swap" rel="stylesheet">
            <link href="https://fonts.googleapis.com/css2?family=Open+Sans:wght@700&family=Original+Surfer&family=Oxygen:wght@300&family=Roboto&display=swap" rel="stylesheet">
        </head>





        <body>
            <div class="form">
                <h1 class="f-header">
                    Sign Up
                </h1>

                <p class="f-subheader">
                    Please fill this form to create an account
                </p>

                <img class="f-image" src="undraw_Sign_in_re_o58h.svg">




                
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="form-group <?php echo (!empty($username_err)) ? 'has-error' : ''; ?>">
                        <label class="char">
                            Username
                        </label>
                        <input type="text" name="username" class="form-control" value="<?php echo $username; ?>">
                        <span class="help-block"><?php echo $username_err; ?></span>
                    </div>    


                    <div class="form-group <?php echo (!empty($password_err)) ? 'has-error' : ''; ?>">
                        <label class="char">
                            Password
                        </label>
                        <input type="password" name="password" class="form-control" value="<?php echo $password; ?>">
                        <span class="help-block"><?php echo $password_err; ?></span>
                    </div>


                    <div class="form-group <?php echo (!empty($confirm_password_err)) ? 'has-error' : ''; ?>">
                        <label class="char">
                            Confirm Password
                        </label>
                        <input type="password" name="confirm_password" class="form-control" value="<?php echo $confirm_password; ?>">
                        <span class="help-block"><?php echo $confirm_password_err; ?></span>
                    </div>


                    <div class="form-group">
                        <input type="submit" class="btn btn-primary" value="Submit">
                        <input type="reset" class="btn btn-default" value="Reset">
                    </div>
                    <p>Already have an account? <a href="login.php">Login here</a>.</p>
                    <p>Admin management? <a href="admin.php">Click here</a>.</p>
                    <p><a href="index.html">Home</a></p>
                </form>
            </div> 
        </body>
    </html>