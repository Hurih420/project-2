<?php
require_once("settings.php");
$body_id = "ProcessPage";      
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="process_eoi.php page.">
        <meta name="author" content="Against all odds">
        <meta name="keywords" content="Cybersecurity, Ethical Hacker, Security Analyst, Threat Hunter, One Studio, Security Jobs">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="refresh" content="300">
        <meta http-equiv="Content-Security-Policy" content="default-src 'self'; style-src 'self';"> 
        <link rel="stylesheet" href="styles/styles.css">
        <title>process_eoi</title>
    </head>
    <body id="<?php echo $body_id; ?>">
        <main>
            <section>
<?php
if($_SERVER["REQUEST_METHOD"]!=="POST"){
    header("Location: apply.php");
    exit();
}

function sanitize($data){return htmlspecialchars(stripslashes(trim((string)$data))); }
function add_error(&$errors,$msg){ $errors[]=$msg; }

$conn=@mysqli_connect($host,$user,$pwd,$dbname);
if(!$conn){
    die("<h1>Database connection error</h1><p>Please try again later.</p>");
}
mysqli_set_charset($conn,"utf8mb4");

function clean($data){ return htmlspecialchars(trim($data)); }

$job_ref = clean($_POST["job_reference"] ?? "");
$first_name = clean($_POST["first_name"]);
$last_name = clean($_POST["last_name"]);
$email = clean($_POST["email"]);
$phone = clean($_POST["phone"]);

/* Create table if not exists */
mysqli_query($conn,"CREATE TABLE IF NOT EXISTS eoi(
EOInumber INT NOT NULL AUTO_INCREMENT,
job_ref VARCHAR(5) NOT NULL,
first_name VARCHAR(20) NOT NULL,
last_name VARCHAR(20) NOT NULL,
date_of_birth DATE NOT NULL,
gender ENUM('male','female') NOT NULL,
street_address VARCHAR(40) NOT NULL,
suburb VARCHAR(40) NOT NULL,
state VARCHAR(10) NOT NULL,
postcode VARCHAR(4) NOT NULL,
city VARCHAR(20) NOT NULL,
zone INT NOT NULL,
email VARCHAR(255) NOT NULL,
phone VARCHAR(8) NOT NULL,
skill1 VARCHAR(50) DEFAULT NULL,
skill2 VARCHAR(50) DEFAULT NULL,
skill3 VARCHAR(50) DEFAULT NULL,
other_skills TEXT DEFAULT NULL,
status ENUM('New','Current','Final') NOT NULL DEFAULT 'New',
PRIMARY KEY(EOInumber)
)ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci");

$errors=[];

$job_ref=sanitize($_POST["job_reference"] ?? "");
$first_name=sanitize($_POST["first_name"] ?? "");
$last_name=sanitize($_POST["last_name"] ?? "");
$dob_raw=sanitize($_POST["date_of_birth"] ?? "");
$gender=sanitize($_POST["gender"] ?? "");
$street_address=sanitize($_POST["street"] ?? "");
$suburb=sanitize($_POST["suburb"] ?? "");
$state=sanitize($_POST["state"] ?? "");
$postcode=sanitize($_POST["postcode"] ?? "");
$city=sanitize($_POST["city"] ?? "");
$zone_raw=sanitize($_POST["zone"] ?? "");
$email=sanitize($_POST["email"] ?? "");
$phone=sanitize($_POST["phone"] ?? "");
$phone = preg_replace('/\D/', '', $phone);
$skills=$_POST["skills"] ?? [];
$other_skills=sanitize($_POST["other_skills"] ?? "");

$allowed_job_refs=["EHK01","SEC02","THH03"];
$allowed_cities=["Doha","Al Wakra","Al Khor","Dukhan","Al Shamal","Mesaieed","Ras Laffan"];

if(!in_array($job_ref,$allowed_job_refs,true)) add_error($errors,"Invalid job reference.");
if($first_name==="" || !preg_match("/^[A-Za-z\s'-]{1,20}$/",$first_name)) add_error($errors,"Invalid first name.");
if($last_name==="" || !preg_match("/^[A-Za-z\s'-]{1,20}$/",$last_name)) add_error($errors,"Invalid last name.");

$dob_dt = DateTime::createFromFormat("Y-m-d", $dob_raw);

if ($dob_dt && $dob_dt->format("Y-m-d") === $dob_raw) {
    $today = new DateTime();
    if ($dob_dt > $today) add_error($errors, "Date of birth cannot be in the future.");
    $dob_sql = $dob_dt->format("Y-m-d"); 
} else {
    add_error($errors, "Invalid date of birth.");
    $dob_sql = "";
}

if($gender!=="male" && $gender!=="female") add_error($errors,"Please select a gender.");
if($street_address==="" || mb_strlen($street_address)>40) add_error($errors,"Invalid street address.");
if($suburb==="" || mb_strlen($suburb)>40) add_error($errors,"Invalid suburb.");
if($state==="" || mb_strlen($state)>10) add_error($errors,"Invalid state.");
if(!preg_match("/^\d{4}$/",$postcode)) add_error($errors,"Invalid postcode. Must be 4 digits.");
if(!in_array($city,$allowed_cities,true)) add_error($errors,"Invalid city.");

if(!preg_match("/^\d{2}$/",$zone_raw)){
    add_error($errors,"Invalid zone. Must be 2 digits.");
    $zone=0;
}else{ $zone=(int)$zone_raw; }

$email = strtolower($email);
if($email==="" || !filter_var($email,FILTER_VALIDATE_EMAIL)) add_error($errors,"Invalid email address.");
if(!preg_match("/^\d{8}$/",$phone)) add_error($errors,"Invalid phone number. Must be 8 digits.");

if(!is_array($skills)) $skills=[];
$skills=array_values(array_unique(array_map("sanitize",$skills)));
if(count($skills)<1) add_error($errors,"Select at least one technical skill.");
if(count($skills)>3) add_error($errors,"Select up to three technical skills.");

if(in_array("Other",$skills,true) && $other_skills==="") add_error($errors,"Please describe your other skills.");

$skill1=$skills[0] ?? null;
$skill2=$skills[1] ?? null;
$skill3=$skills[2] ?? null;

if($other_skills!=="" && mb_strlen($other_skills)>2000) add_error($errors,"Other skills is too long.");

/* Display errors in styled card if any */
if(count($errors)>0){
    mysqli_close($conn);
    echo '<div class="message error"><h2>Submission Errors</h2><ul>';
    foreach($errors as $e) echo '<li>'.$e.'</li>';
    echo '</ul><p><a href="apply.php" class="button">Go back to the form</a></p></div>';
    exit();
}

/* Prevent duplicate submission */
$check = mysqli_prepare($conn, "SELECT EOInumber FROM eoi WHERE email=? AND job_ref=?");
mysqli_stmt_bind_param($check, "ss", $email, $job_ref);
mysqli_stmt_execute($check);
mysqli_stmt_store_result($check);
if(mysqli_stmt_num_rows($check) > 0){
    add_error($errors, "You have already applied for this job with this email.");
}
mysqli_stmt_close($check);

$last_submission_stmt = mysqli_prepare($conn, "SELECT EOInumber, DATE_SUB(NOW(), INTERVAL 1 MINUTE) as last_time FROM eoi WHERE email=? ORDER BY EOInumber DESC LIMIT 1");
mysqli_stmt_bind_param($last_submission_stmt, "s", $email);
mysqli_stmt_execute($last_submission_stmt);
mysqli_stmt_bind_result($last_submission_stmt, $last_eoi, $last_time);
mysqli_stmt_fetch($last_submission_stmt);
if($last_eoi && strtotime($last_time) > time() - 60){
    add_error($errors, "You must wait at least 1 minute before submitting another application.");
}
mysqli_stmt_close($last_submission_stmt);

/* Insert new EOI */
$stmt=mysqli_prepare($conn,"
INSERT INTO eoi
(job_ref,first_name,last_name,date_of_birth,gender,street_address,suburb,state,postcode,city,zone,email,phone,skill1,skill2,skill3,other_skills,status)
VALUES
(?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,'New')
");

if(!$stmt){
    mysqli_close($conn);
    die("<h1>Database error</h1><p>Could not prepare statement.</p>");
}

mysqli_stmt_bind_param(
    $stmt,
    "ssssssssssissssss",
    $job_ref,
    $first_name,
    $last_name,
    $dob_sql,
    $gender,
    $street_address,
    $suburb,
    $state,
    $postcode,
    $city,
    $zone,
    $email,
    $phone,
    $skill1,
    $skill2,
    $skill3,
    $other_skills
);

$ok=mysqli_stmt_execute($stmt);
if(!$ok){
    mysqli_stmt_close($stmt);
    mysqli_close($conn);
    die("<h1>Database error</h1><p>Could not save your application.</p>");
}

$eoi_number=mysqli_insert_id($conn);

mysqli_stmt_close($stmt);
mysqli_close($conn);


?>
<div class="message success">
    <h2>Application Submitted</h2>
    <p>Thank you, <?php echo htmlspecialchars($first_name); ?>, for your application.</p>
    <p>Job applied for: <span class="highlight-hover ref"><?php echo htmlspecialchars($job_ref); ?></span></p>
    <p>Confirmation email has been sent to: <span class="highlight-hover email"><?php echo htmlspecialchars($email); ?></span></p>
    <p>We will review your application and get back to you soon.</p><p><strong>Your EOI Number:</strong> <span class="highlight-hover eoi"><?php echo (int)$eoi_number; ?></span></p>
    <p><strong>Submission date:</strong> <span class="highlight-hover date"><?php echo date('d/m/Y H:i'); ?></span></p>
    <p><a href='apply.php' class="button">Submit the application again</a></p>
    <p><a href='index.php' class="button">Return to Home</a></p>
</div>
  </section>
</main>

