<?php
$body_id="ApplyPage";
include 'header.inc';
?>

<main>
<section>
    <h1>Apply to Join Our Cybersecurity Team!</h1>
    <p>
        We are looking for Cybersecurity Specialists to fill roles in 
        <strong>Ethical Hacking</strong>, <strong>Security Analysis</strong>, or 
        <strong>Threat Hunting</strong>.
    </p>
</section>

<section>
<h2>Application Form</h2>

<form action="process_eoi.php" method="post" novalidate="novalidate">

<fieldset>
<legend>Position Details</legend>
<label for="JobRef">Job Reference Number</label>
<select id="JobRef" name="job_reference" required>
    <option value="">Select a job reference</option>
    <option value="EHK01">EHK01 - Ethical Hacker</option>
    <option value="SEC02">SEC02 - Security Analyst</option>
    <option value="THH03">THH03 - Threat Hunter</option>
</select>
</fieldset>

<fieldset>
<legend>Personal Information</legend>

<label for="firstname">First Name</label>
<input type="text" id="firstname" name="first_name" maxlength="20" required>

<label for="lastname">Last Name</label>
<input type="text" id="lastname" name="last_name" maxlength="20" required>

<label for="dob">Date of Birth</label>
<input type="text" id="dob" name="date_of_birth" placeholder="dd/mm/yyyy" required>
</fieldset>

<fieldset>
<legend>Gender</legend>
<input type="radio" id="male" name="gender" value="male" required>
<label for="male">Male</label>

<input type="radio" id="female" name="gender" value="female">
<label for="female">Female</label>
</fieldset>

<fieldset>
<legend>Address Details</legend>

<label for="street">Street Address</label>
<input type="text" id="street" name="street" maxlength="40" required>

<label for="suburb">Suburb</label>
<input type="text" id="suburb" name="suburb" maxlength="40" required>

<label for="state">State</label>
<input type="text" id="state" name="state" maxlength="10" required>

<label for="postcode">Postcode</label>
<input type="text" id="postcode" name="postcode" pattern="^\d{4}$" required>

<label for="city">City</label>
<select id="city" name="city" required>
    <option value="">Select City</option>
    <option value="Doha">Doha</option>
    <option value="Al Wakra">Al Wakra</option>
    <option value="Al Khor">Al Khor</option>
    <option value="Dukhan">Dukhan</option>
    <option value="Al Shamal">Al Shamal</option>
    <option value="Mesaieed">Mesaieed</option>
    <option value="Ras Laffan">Ras Laffan</option>
</select>

<label for="zone">Zone</label>
<input type="text" id="zone" name="zone" pattern="^\d{2}$" required>
</fieldset>

<fieldset>
<legend>Contact Information</legend>

<label for="email">Email</label>
<input type="email" id="email" name="email" required>

<label for="phone">Phone Number</label>
<input type="tel" id="phone" name="phone" pattern="^\d{8}$" required>
</fieldset>

<fieldset>
<legend>Technical Skills</legend>

<input type="checkbox" id="skill1" name="skills[]" value="Ethical Hacking">
<label for="skill1">Ethical Hacking</label>

<input type="checkbox" id="skill2" name="skills[]" value="Security Analysis">
<label for="skill2">Security Analysis</label>

<input type="checkbox" id="skill3" name="skills[]" value="Threat Hunting">
<label for="skill3">Threat Hunting</label>

<label for="otherSkills">Other Skills</label>
<textarea id="otherSkills" name="other_skills" rows="4"></textarea>
</fieldset>

<button type="submit">Apply</button>

</form>
</section>
</main>
<?php include 'footer.inc'; ?>
