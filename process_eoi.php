<?php
require_once("settings.php");

if($_SERVER["REQUEST_METHOD"]!=="POST"){
header("Location: apply.php");
exit();
}

function sanitize($data){return htmlspecialchars(stripslashes(trim((string)$data)));}
function add_error(&$errors,$msg){$errors[]=$msg;}

$conn=@mysqli_connect($host,$user,$pwd,$dbname);
if(!$conn){die("<h1>Database connection error</h1><p>Please try again later.</p>");}
mysqli_set_charset($conn,"utf8mb4");

function clean($data) {
  return htmlspecialchars(trim($data));
}

$job_ref = clean($_POST["job_ref"]);
$first_name = clean($_POST["first_name"]);
$last_name = clean($_POST["last_name"]);
$email = clean($_POST["email"]);
$phone = clean($_POST["phone"]);



/*If the table doesn't exist, this'll make one*/
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
$skills=$_POST["skills"] ?? [];
$other_skills=sanitize($_POST["other_skills"] ?? "");

$allowed_job_refs=["EHK01","SEC02","THH03"];
$allowed_cities=["Doha","Al Wakra","Al Khor","Dukhan","Al Shamal","Mesaieed","Ras Laffan"];

if(!in_array($job_ref,$allowed_job_refs,true)) add_error($errors,"Invalid job reference.");
if($first_name==="" || !preg_match("/^[A-Za-z\s'-]{1,20}$/",$first_name)) add_error($errors,"Invalid first name.");
if($last_name==="" || !preg_match("/^[A-Za-z\s'-]{1,20}$/",$last_name)) add_error($errors,"Invalid last name.");

$dob_dt=DateTime::createFromFormat("d/m/Y",$dob_raw);
$dob_ok=$dob_dt && $dob_dt->format("d/m/Y")===$dob_raw;
$dob_sql=$dob_ok ? $dob_dt->format("Y-m-d") : "";
if(!$dob_ok) add_error($errors,"Invalid date of birth. Use dd/mm/yyyy.");

if($gender!=="male" && $gender!=="female") add_error($errors,"Please select a gender.");

if($street_address==="" || mb_strlen($street_address)>40) add_error($errors,"Invalid street address.");
if($suburb==="" || mb_strlen($suburb)>40) add_error($errors,"Invalid suburb.");
if($state==="" || mb_strlen($state)>10) add_error($errors,"Invalid state.");
if(!preg_match("/^\d{4}$/",$postcode)) add_error($errors,"Invalid postcode. Must be 4 digits.");
if(!in_array($city,$allowed_cities,true)) add_error($errors,"Invalid city.");

if(!preg_match("/^\d{2}$/",$zone_raw)){
add_error($errors,"Invalid zone. Must be 2 digits.");
$zone=0;
}else{$zone=(int)$zone_raw;}

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

if(count($errors)>0){
mysqli_close($conn);
echo "<!DOCTYPE html><html lang='en'><head><meta charset='utf-8'><title>EOI Errors</title></head><body>";
echo "<h1>Submission Errors</h1><ul>";
foreach($errors as $e) echo "<li>".$e."</li>";
echo "</ul><p><a href='apply.php'>Go back to the form</a></p></body></html>";
exit();
}

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

echo "<!DOCTYPE html><html lang='en'><head><meta charset='utf-8'><title>EOI Submitted</title></head><body>";
echo "<h1>Application Submitted</h1>";
echo "<p>Your application has been received.</p>";
echo "<p><strong>Your EOI Number:</strong> ".(int)$eoi_number."</p>";
echo "<p><a href='index.php'>Return to Home</a></p>";
echo "</body></html>";
?>