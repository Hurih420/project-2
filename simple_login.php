<?php
/*Prevent login page from being cached*/
header("Cache-Control: no-store, no-cache, must-revalidate");
header("Pragma: no-cache");

session_set_cookie_params([
  "httponly" => true,               /*Malicious JavasScript cannot access cookie*/
  "secure"   => isset($_SERVER['HTTPS']), /*Cookie sent only over HTTPS(protected),not HTTP*/
  "samesite" => "Strict"            /*Cookies are sent ONLY when user is directly on your site*/ 
]);
session_start();
require_once("settings.php");

function sanitize($data){return htmlspecialchars(stripslashes(trim((string)$data)));}

$conn=@mysqli_connect($host,$user,$pwd,$dbname);
if(!$conn){die("<h1>Database connection error</h1><p>Please try again later.</p>");}
mysqli_set_charset($conn,"utf8mb4");

mysqli_query($conn,"CREATE TABLE IF NOT EXISTS managers(
manager_id INT NOT NULL AUTO_INCREMENT,
username VARCHAR(50) NOT NULL,
password_hash VARCHAR(255) NOT NULL,
failed_attempts INT NOT NULL DEFAULT 0,
lockout_until DATETIME DEFAULT NULL,
created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
PRIMARY KEY(manager_id),
UNIQUE(username)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

$error="";

if($_SERVER["REQUEST_METHOD"]==="POST"){
  $username=sanitize($_POST["username"] ?? "");
  $password=(string)($_POST["password"] ?? "");

  if($username==="" || $password===""){
    $error="Please enter username and password.";
  }
  elseif (strlen($password) < 8) {
    $error = "Invalid login."; /*Minimum password length=8 is applied*/
}else{
    $stmt=mysqli_prepare($conn,"SELECT manager_id,username,password_hash,failed_attempts,lockout_until FROM managers WHERE username=?");
    mysqli_stmt_bind_param($stmt,"s",$username);
    mysqli_stmt_execute($stmt);
    $res=mysqli_stmt_get_result($stmt);
    $row=$res ? mysqli_fetch_assoc($res) : null;
    mysqli_stmt_close($stmt);

    if(!$row){
    $fakeHash = '$2y$10$usesomesillystringfore7hnbRJHxXVLeakoG8K30oukPsA.ztMG';
    password_verify($password, $fakeHash); /*constant-time check*/
      $error="Invalid login.";
    }else{
     $locked = (
    $row["lockout_until"] !== null &&
    strtotime($row["lockout_until"]) > time()
);


      if($locked){
        $error="Account locked. Try again later.";
      }else{
        if(password_verify($password,$row["password_hash"])){

          session_regenerate_id(true);

          $stmt=mysqli_prepare($conn,"UPDATE managers SET failed_attempts=0,lockout_until=NULL WHERE manager_id=?");
          mysqli_stmt_bind_param($stmt,"i",$row["manager_id"]);
          mysqli_stmt_execute($stmt);
          mysqli_stmt_close($stmt);

          $_SESSION["is_admin"]=true;
          $_SESSION["admin_user"]=$row["username"];
          header("Location: manage.php");
          mysqli_close($conn);
          exit();
        }else{
          $fails=(int)$row["failed_attempts"]+1;

          if($fails>=5){
            $stmt=mysqli_prepare($conn,"UPDATE managers SET failed_attempts=0,lockout_until=DATE_ADD(NOW(),INTERVAL 10 MINUTE) WHERE manager_id=?");
            mysqli_stmt_bind_param($stmt,"i",$row["manager_id"]);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $error="Too many attempts. Account locked for 10 minutes.";
          }else{
            $stmt=mysqli_prepare($conn,"UPDATE managers SET failed_attempts=? WHERE manager_id=?");
            mysqli_stmt_bind_param($stmt,"ii",$fails,$row["manager_id"]);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_close($stmt);
            $error="Invalid login.";
          }
        }
      }
    }
  }
}

mysqli_close($conn);
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="utf-8">
  <title>Manager Login</title>
  <link rel="stylesheet" href="styles/styles.css">
</head>
<body id="LoginPage">
<main>
<section>
  <h1>Manager Login</h1>

  <?php if($error!==""): ?>
    <div class="message error"><?php echo $error; ?></div>
  <?php endif; ?>

  <form method="post" action="simple_login.php" novalidate="novalidate">
    <fieldset>
      <legend>Login</legend>

      <label for="username">Username</label>
      <input type="text" id="username" name="username" maxlength="50"
       value="<?php echo htmlspecialchars($username ?? ''); ?>" required>


      <label for="password">Password</label>
      <input type="password" id="password" name="password" autocomplete="off" required>

      <button type="submit">Login</button>
    </fieldset>
  </form>

  <p><a href="index.php">Back to Home</a></p>
</section>
</main>
</body>
</html>