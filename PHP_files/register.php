<?php
require_once "config.php";

$username = $password = $confirm_password = "";
$username_err = $password_err = $confirm_password_err = "";

if ($_SERVER['REQUEST_METHOD'] == "POST"){

    // Check if username is empty
    if(empty(trim($_POST["username"]))){
        $username_err = "Username cannot be blank";
    }
    else{
        $sql = "SELECT id FROM sysusers WHERE username = ?";
        $stmt = mysqli_prepare($conn, $sql);
        if($stmt)
        {
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set the value of param username
            $param_username = trim($_POST['username']);

            // Try to execute this statement
            if(mysqli_stmt_execute($stmt)){
                mysqli_stmt_store_result($stmt);
                if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    $username_err = "This username is already taken"; 
                }
                else{
                    $username = trim($_POST['username']);
                }
            }
            else{
                echo "Something went wrong";
            }
        }
    }

    mysqli_stmt_close($stmt);


// Check for password
if(empty(trim($_POST['password']))){
    $password_err = "Password cannot be blank";
}
elseif(strlen(trim($_POST['password'])) < 5){
    $password_err = "Password cannot be less than 5 characters";
}
else{
    $password = trim($_POST['password']);
}

// Check for confirm password field
if(trim($_POST['password']) !=  trim($_POST['confirm_password'])){
    $password_err = "Passwords should match";
}


// If there were no errors, go ahead and insert into the database
if(empty($username_err) && empty($password_err) && empty($confirm_password_err))
{
    $sql = "INSERT INTO sysusers (username, password) VALUES (?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    if ($stmt)
    {
        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_password);

        // Set these parameters
        $param_username = $username;
        $param_password = password_hash($password, PASSWORD_DEFAULT);

        // Try to execute the query
        if (mysqli_stmt_execute($stmt))
        {
            header("location: login.php");
        }
        else{
            echo "Something went wrong... cannot redirect!";
        }
    }
    mysqli_stmt_close($stmt);
}
mysqli_close($conn);
}

?>

<html>
<head>
<title></title>
<style type="text/css">
    body {
margin: 0;
padding: 0;
font-family: sans-serif;
background-size: cover;
background: url(jobs.jpg);
}

.log-box{
background: rgba(0,0,0,.5);
color: #fff;
width: 320px;
height: 420px;
top: 50%;
left: 50%;
transform: translate(-50%, -50%);
box-sizing: border-box;
position: absolute;
padding: 70px 30px;
}
.usrimg {
width: 100px;
height: 100px;
border-radius: 50%;
overflow: hidden;
position: absolute;
top: calc(-100px/2);
left: calc(50% - 50px);
}
h2{
margin: 0;
padding: 0 0 20px;
text-align: center;
}
.log-box p{
margin: 0;
padding: 0;
font-weight: bold;
color: #fff;
}
.log-box input{
width: 100%;
margin-bottom: 20px;
}
.log-box input[type="text"],
.log-box input[type="tel"],
.log-box input[type="email"],
.log-box input[type="password"]
{
border: none;
border-bottom: 1px solid #fff;
background: transparent;
outline: none;
height: 40px;
color: #fff;
font-size: 16px;
}
::placeholder {
color: rgba(255,255,255,.5);
}
.log-box input[type="submit"]{
border: none;
outline: none;
height: 40px;
font-size: 18px;
cursor: pointer;
border-radius: 20px;
padding-left:60px;
padding-right:60px;
}
.log-box a{
text-decoration: none;
color: #fff;
}
</style>
</head>
<body>
<div class="log-box">
<img class="usrimg" src="usr.png" />
<h2>
Sign up</h2>
<form id="theForm" action=" " method="post">
Username<br/>
<input name="username" placeholder="Enter Name" type="text" />
<label>Password</label><br />
<input name="password" placeholder="Enter Password" type="Password" />
<label>Confirm password</label><br />
<input name="confirm_password" placeholder="Enter Password" type="Password" />
<input type="submit" value="Sign Up" />
<br/> 
 <br />
</form>
</div>
</body>
</html>