<?php
require_once("settings.php");

$body_id="ManagePage";
include "header.inc";

function sanitize($data){
    return htmlspecialchars(stripslashes(trim((string)$data)));
}

function add_error(&$errors,$msg){
    $errors[]=$msg;
}

$conn=@mysqli_connect($host,$user,$pwd,$dbname);
if(!$conn){
    die("<h1>Database connection error</h1><p>Please try again later.</p>");
}
mysqli_set_charset($conn,"utf8mb4");

$errors=[];
$success="";
$results=[];
$allowed_status=["New","Current","Final"];

if($_SERVER["REQUEST_METHOD"]==="POST" && isset($_POST["action"]) && $_POST["action"]==="update_status"){
    $eoi_number_raw=sanitize($_POST["EOInumber"] ?? "");
    $new_status=sanitize($_POST["status"] ?? "");

    if(!preg_match("/^\d+$/",$eoi_number_raw)) add_error($errors,"Invalid EOI number.");
    if(!in_array($new_status,$allowed_status,true)) add_error($errors,"Invalid status.");

    if(count($errors)===0){
        $eoi_number=(int)$eoi_number_raw;

        $stmt=mysqli_prepare($conn,"UPDATE eoi SET status=? WHERE EOInumber=?");
        if(!$stmt){
            add_error($errors,"Database error. Could not prepare update.");
        }else{
            mysqli_stmt_bind_param($stmt,"si",$new_status,$eoi_number);
            $ok=mysqli_stmt_execute($stmt);
            $affected=mysqli_stmt_affected_rows($stmt);
            mysqli_stmt_close($stmt);

            if(!$ok){
                add_error($errors,"Database error. Could not update status.");
            }elseif($affected===0){
                add_error($errors,"No record updated. Check the EOI number.");
            }else{
                $success="Status updated successfully.";
            }
        }
    }
}

$search_eoi=sanitize($_GET["eoi"] ?? "");
$search_job=sanitize($_GET["job_ref"] ?? "");
$search_last=sanitize($_GET["last_name"] ?? "");
$search_status=sanitize($_GET["status"] ?? "");

$where=[];
$params=[];
$types="";

if($search_eoi!==""){
    if(!preg_match("/^\d+$/",$search_eoi)){
        add_error($errors,"EOI number must be digits only.");
    }else{
        $where[]="EOInumber=?";
        $params[]=(int)$search_eoi;
        $types.="i";
    }
}

if($search_job!==""){
    if(!preg_match("/^(EHK01|SEC02|THH03)$/",$search_job)){
        add_error($errors,"Job reference must be EHK01, SEC02, or THH03.");
    }else{
        $where[]="job_ref=?";
        $params[]=$search_job;
        $types.="s";
    }
}

if($search_last!==""){
    if(mb_strlen($search_last)>20){
        add_error($errors,"Last name must be 20 characters or less.");
    }else{
        $where[]="last_name LIKE ?";
        $params[]=$search_last."%";
        $types.="s";
    }
}

if($search_status!=="" && $search_status!=="Any"){
    if(!in_array($search_status,$allowed_status,true)){
        add_error($errors,"Invalid status filter.");
    }else{
        $where[]="status=?";
        $params[]=$search_status;
        $types.="s";
    }
}

if(count($errors)===0 && ($search_eoi!=="" || $search_job!=="" || $search_last!=="" || $search_status!=="")){
    $sql="SELECT EOInumber,job_ref,first_name,last_name,date_of_birth,gender,street_address,suburb,state,postcode,city,zone,email,phone,skill1,skill2,skill3,other_skills,status FROM eoi";
    if(count($where)>0){
        $sql.=" WHERE ".implode(" AND ",$where);
    }
    $sql.=" ORDER BY EOInumber DESC";

    $stmt=mysqli_prepare($conn,$sql);
    if(!$stmt){
        add_error($errors,"Database error. Could not prepare search.");
    }else{
        if(count($params)>0){
            mysqli_stmt_bind_param($stmt,$types,...$params);
        }
        mysqli_stmt_execute($stmt);
        $res=mysqli_stmt_get_result($stmt);
        if($res){
            while($row=mysqli_fetch_assoc($res)){
                $results[]=$row;
            }
        }
        mysqli_stmt_close($stmt);
    }
}

mysqli_close($conn);
?>

<main>
<section>
  <h1>Manage EOIs</h1>

  <?php if(count($errors)>0): ?>
  <div class="message error">
    <ul>
      <?php foreach($errors as $e): ?>
        <li><?php echo $e; ?></li>
      <?php endforeach; ?>
    </ul>
  </div>
  <?php endif; ?>

  <?php if($success!==""): ?>
  <div class="message success">
    <?php echo $success; ?>
  </div>
  <?php endif; ?>


  <h2>Search</h2>
  <form method="get" action="manage.php" novalidate="novalidate">
    <fieldset>
      <legend>Search Filters</legend>

      <label for="eoi">EOI Number</label>
      <input type="text" id="eoi" name="eoi" value="<?php echo sanitize($_GET["eoi"] ?? ""); ?>">

      <label for="job_ref">Job Reference</label>
      <select id="job_ref" name="job_ref">
        <option value="">Any</option>
        <option value="EHK01" <?php echo (sanitize($_GET["job_ref"] ?? "")==="EHK01") ? "selected" : ""; ?>>EHK01</option>
        <option value="SEC02" <?php echo (sanitize($_GET["job_ref"] ?? "")==="SEC02") ? "selected" : ""; ?>>SEC02</option>
        <option value="THH03" <?php echo (sanitize($_GET["job_ref"] ?? "")==="THH03") ? "selected" : ""; ?>>THH03</option>
      </select>

      <label for="last_name">Last Name (starts with)</label>
      <input type="text" id="last_name" name="last_name" maxlength="20" value="<?php echo sanitize($_GET["last_name"] ?? ""); ?>">

      <label for="status">Status</label>
      <select id="status" name="status">
        <option value="Any" <?php echo (sanitize($_GET["status"] ?? "")==="Any" || sanitize($_GET["status"] ?? "")==="") ? "selected" : ""; ?>>Any</option>
        <option value="New" <?php echo (sanitize($_GET["status"] ?? "")==="New") ? "selected" : ""; ?>>New</option>
        <option value="Current" <?php echo (sanitize($_GET["status"] ?? "")==="Current") ? "selected" : ""; ?>>Current</option>
        <option value="Final" <?php echo (sanitize($_GET["status"] ?? "")==="Final") ? "selected" : ""; ?>>Final</option>
      </select>

      <button type="submit">Search</button>
    </fieldset>
  </form>

  <h2>Results</h2>

  <?php if(($search_eoi!=="" || $search_job!=="" || $search_last!=="" || $search_status!=="") && count($results)===0 && count($errors)===0): ?>
    <p>No results found.</p>
  <?php endif; ?>

  <?php if(count($results)>0): ?>
    <table>
      <thead>
        <tr>
          <th>EOI #</th>
          <th>Job Ref</th>
          <th>Name</th>
          <th>DOB</th>
          <th>Gender</th>
          <th>City</th>
          <th>Zone</th>
          <th>Email</th>
          <th>Phone</th>
          <th>Skills</th>
          <th>Status</th>
          <th>Update Status</th>
        </tr>
      </thead>
      <tbody>
        <?php foreach($results as $r): ?>
          <tr>
            <td><?php echo (int)$r["EOInumber"]; ?></td>
            <td><?php echo sanitize($r["job_ref"]); ?></td>
            <td><?php echo sanitize($r["first_name"]." ".$r["last_name"]); ?></td>
            <td><?php echo sanitize($r["date_of_birth"]); ?></td>
            <td><?php echo sanitize($r["gender"]); ?></td>
            <td><?php echo sanitize($r["city"]); ?></td>
            <td><?php echo (int)$r["zone"]; ?></td>
            <td><?php echo sanitize($r["email"]); ?></td>
            <td><?php echo sanitize($r["phone"]); ?></td>
            <td>
              <?php
                $skills_out=[];
                if(!empty($r["skill1"])) $skills_out[]=$r["skill1"];
                if(!empty($r["skill2"])) $skills_out[]=$r["skill2"];
                if(!empty($r["skill3"])) $skills_out[]=$r["skill3"];
                if(!empty($r["other_skills"])) $skills_out[]="Other";
                echo sanitize(implode(", ",$skills_out));
              ?>
            </td>
            <td><?php echo sanitize($r["status"]); ?></td>
            <td>
              <form method="post" action="manage.php?<?php echo sanitize($_SERVER["QUERY_STRING"] ?? ""); ?>">
                <input type="hidden" name="action" value="update_status">
                <input type="hidden" name="EOInumber" value="<?php echo (int)$r["EOInumber"]; ?>">
                <select name="status" required>
                  <option value="New" <?php echo ($r["status"]==="New") ? "selected" : ""; ?>>New</option>
                  <option value="Current" <?php echo ($r["status"]==="Current") ? "selected" : ""; ?>>Current</option>
                  <option value="Final" <?php echo ($r["status"]==="Final") ? "selected" : ""; ?>>Final</option>
                </select>
                <button type="submit">Update</button>
              </form>
            </td>
          </tr>
        <?php endforeach; ?>
      </tbody>
    </table>
  <?php endif; ?>
</section>
</main>

<?php include "footer.inc"; ?>
