<?php
//This script will handle login
session_start();

// check if the user is already logged in
require_once "config.php";

$username = $password = "";
$err = "";

// if request method is post
if ($_SERVER['REQUEST_METHOD'] == "POST"){
    if(empty(trim($_POST['username'])) || empty(trim($_POST['password'])))
    {
        $err = "Please enter username + password";
    }
    else{
        $username = trim($_POST['username']);
        $password = trim($_POST['password']);
    }


if(empty($err))
{
    $sql = "SELECT id, username, password FROM sysusers WHERE username = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "s", $param_username);
    $param_username = $username;
    
    
    // Try to execute this statement
    if(mysqli_stmt_execute($stmt)){
        mysqli_stmt_store_result($stmt);
        if(mysqli_stmt_num_rows($stmt) == 1)
                {
                    mysqli_stmt_bind_result($stmt, $id, $username, $hashed_password);
                    if(mysqli_stmt_fetch($stmt))
                    {
                        if(password_verify($password, $hashed_password))
                        {
                            // this means the password is corrct. Allow user to login
                            session_start();
                            $_SESSION["username"] = $username;
                            $_SESSION["id"] = $id;
                            $_SESSION["loggedin"] = true;

                            //Redirect user to welcome page
                            header("location: index.html");
                            
                        }
                    }

                }

    }
}    


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
background: url(jobs.jpg);
background-size: cover;
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
<img class="usrimg" src="usr.png" >
<h2>Log In</h2>
<form id="theForm" action="" method="post">
<label>Username</label><br />
<input  name="username" placeholder="Enter Name" type="text" />
<label>Password</label><br />
<input name="password" placeholder="Enter Password" type="Password" />
<input name="" onclick="hello()" type="submit" value="Log In" />
<br/> 
&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <br />
<br />
<a href="https://www.facebook.com">Facebook</a>&nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp &nbsp <a href="register.php">Sign up</a>
</form>
</div>
<script type="text/javascript">
    var h="Welcome to Naukri.com";
   function hello() {
       alert(h);
   }
</script>
</body>
</html>