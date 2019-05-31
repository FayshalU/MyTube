<?php
    require_once("config.php");
    require_once("classes/Errors.php");

    $firstName=$lastName=$username=$email=$password=$conPassword=null;

    function formatString($input){
        $input = strip_tags($input);
        $input = str_replace(" ", "", $input);
        $input = strtolower($input);
        $input = ucfirst($input);
        return $input;
    }

    $errorArray = array();

    function checkFirstName($fn){
        if(strlen($fn)>25 || strlen($fn)<2){
            global $errorArray;
            array_push($errorArray, Errors::$fnChar);
        }
    }

    function checkLastName($ln){
        if(strlen($ln)>25 || strlen($ln)<2){
            global $errorArray;
            array_push($errorArray, Errors::$lnChar);
        }
    }

    function checkUserName($un, $con){

        $query = $con->prepare("SELECT username FROM users WHERE username=:un");
        $query->bindParam(":un",$un);
        $query->execute();

        if($query->rowCount() != 0){
            global $errorArray;
            array_push($errorArray, Errors::$unChar);
        }
    }

    function checkEmail($em, $con){
        global $errorArray;

        if(!filter_var($em, FILTER_VALIDATE_EMAIL)){
            array_push($errorArray, Errors::$emChar);
            return;
        }

        $query = $con->prepare("SELECT email FROM users WHERE email=:em");
        $query->bindParam(":em",$em);
        $query->execute();

        if($query->rowCount() != 0){
            array_push($errorArray, Errors::$emTaken);
        }
    }

    function checkPasswords($pw1, $pw2){
        global $errorArray;
        if($pw1 != $pw2){
            array_push($errorArray, Errors::$pwMatch);
        }
        else if(preg_match("/[^A-Za-z0-9]/",$pw1)){
            array_push($errorArray, Errors::$pwPattern);
        }
        else if(strlen($pw1)>30 || strlen($pw1)<5){
            array_push($errorArray, Errors::$pwLen);
        }
    }

    if(isset($_POST["submitbtn"])){
        global $errorArray;

        $firstName = formatString($_POST["firstName"]);
        $lastName = formatString($_POST["lastName"]);

        $username = strip_tags($_POST["username"]);
        $username = str_replace(" ", "", $username);

        $email = strip_tags($_POST["email"]);
        $email = str_replace(" ", "", $email);

        $password = strip_tags($_POST["password"]);
        $conPassword = strip_tags($_POST["conPassword"]);

        checkFirstName($firstName);
        checkLastName($lastName);
        checkUserName($username, $con);
        checkEmail($email, $con);
        checkPasswords($password, $conPassword);

        if(empty($errorArray)){
            $pa = hash("sha512", $password);
            $picture = "assets/images/profile.png";

            $query = $con->prepare("INSERT INTO users (firstName, lastName, username, email, password, picture)
                        VALUES(:firstName, :lastName, :username, :email, :password, :picture)");

            $query->bindParam(":firstName", $firstName);
            $query->bindParam(":lastName", $lastName);
            $query->bindParam(":username", $username);
            $query->bindParam(":email", $email);
            $query->bindParam(":password", $pa);
            $query->bindParam(":picture", $picture);
            $query->execute();

            if($query){
                //echo "Successfull";
                $_SESSION["user"] = $username;
                header("Location: index.php");
            }

        }
        else{
            
        }
    }

    //var_dump($errorArray);

?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title>MyTube</title>
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link rel="stylesheet" type="text/css" media="screen" href="assets/lib/css/bootstrap.min.css">
        <link rel="stylesheet" type="text/css" media="screen" href="assets/lib/css/font-awesome.css">
        <link rel="stylesheet" type="text/css" media="screen" href="assets/css/style.css">
        <link rel="stylesheet" type="text/css" media="screen" href="assets/css/signin.css">

        <script src="assets/lib/js/jquery-3.3.1.js"></script>
        <script src="assets/lib/js/popper.min.js"></script>
        <script src="assets/lib/js/bootstrap.min.js"></script>
    </head>
    <body>
        <div class="signInContainer">
            <div class="column">
                <div class="header">
                    <center><img src="assets/images/yt.png" alt="MyTube" title="Logo"></center>
                    <h3>Sign Up</h3>
                    <span>to continue to MyTube</span>
                </div>

                <div class="loginForm">
                    <form action="signup.php" method="POST">
                        <input type="text" name="firstName" placeholder="First Name" value="<?=$firstName?>" required>
                        <?php
                            if(in_array(Errors::$fnChar, $errorArray)){
                                echo "<span class='errorMessage'>".Errors::$fnChar."</span>";
                            }
                        ?>
                        <input type="text" name="lastName" placeholder="Last Name" value="<?=$lastName?>" required>
                        <?php
                            if(in_array(Errors::$lnChar, $errorArray)){
                                echo "<span class='errorMessage'>".Errors::$lnChar."</span>";
                            }
                        ?>
                        <input type="text" name="username" placeholder="Username" value="<?=$username?>" required>
                        <?php
                            if(in_array(Errors::$unChar, $errorArray)){
                                echo "<span class='errorMessage'>".Errors::$unChar."</span>";
                            }
                        ?>
                        <input type="email" name="email" placeholder="Email" value="<?=$email?>" required>
                        <?php
                            if(in_array(Errors::$emChar, $errorArray)){
                                echo "<span class='errorMessage'>".Errors::$emChar."</span>";
                            }
                            else if(in_array(Errors::$emTaken, $errorArray)){
                                echo "<span class='errorMessage'>".Errors::$emTaken."</span>";
                            }
                        ?>
                        <input type="password" name="password" placeholder="Password" required>
                        <input type="password" name="conPassword" placeholder="Confirm Password" required>
                        <?php
                            if(in_array(Errors::$pwMatch, $errorArray)){
                                echo "<span class='errorMessage'>".Errors::$pwMatch."</span>";
                            }
                            else if(in_array(Errors::$pwPattern, $errorArray)){
                                echo "<span class='errorMessage'>".Errors::$pwPattern."</span>";
                            }
                            else if(in_array(Errors::$pwLen, $errorArray)){
                                echo "<span class='errorMessage'>".Errors::$pwLen."</span>";
                            }
                        ?>
                        <input type="submit" name="submitbtn" value="SUBMIT">
                    </form>
                </div>

                <a class="signInMessage" href="signin.php">Already have an account? Go to Sign In</a>
            </div>
        </div>
    </body>
</html>