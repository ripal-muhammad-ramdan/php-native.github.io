<?
session_start();

$host  = $_SERVER['HTTP_HOST'];
$uri   = rtrim(dirname($_SERVER['PHP_SELF']), '/\\');
$extra = 'login.php';

if(!isset($_SESSION["username"]) ){
    header("Location: http://$host$uri/$extra");
    exit;
}else{

    setcookie("username", "", time()-(86400 * 30)); 
    setcookie("pwd", "", time()-(86400 * 30)); 

    session_destroy();
    $_SESSION[] = [];
    session_unset();
    header("Location: http://$host$uri/$extra");

    // clear cookies
    

    exit;
}
