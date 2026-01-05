<?php
// Start session once for admin protection
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="utf-8">
        <meta name="description" content="Information about the roles, responsibilities, and key skills required by cybersecurity specialists at One Studio.">
        <meta name="author" content="Against all odds">
        <meta name="keywords" content="Cybersecurity, Ethical Hacker, Security Analyst, Threat Hunter, One Studio, Security Jobs">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta http-equiv="refresh" content="300">
        <link rel="stylesheet" href="styles/styles.css">
        <title>One Studio Cybersecurity Specialists</title>
    </head>

    <body id="<?php echo htmlspecialchars($body_id ?? ''); ?>">
        <header>
            <img src="images/GROUPLOGO.png" alt="One Studio logo" title="One Studio" loading="lazy" width="100" class="logo">
            <h2><strong>One Studio</strong></h2>
            <h1 class="page-banner">Explore Interesting Cyber Security Careers</h1>
            <nav>
                <ul>
                    <li><a href="index.php">Home</a></li>
                    <li><a href="apply.php">Apply</a></li>
                    <li><a href="jobs.php">Job Opportunities</a></li>
                    <li><a href="about.php">About</a></li>
                    <li><a href="manage.php"Manage EOIs</ai></li>
                    <li><a href="mailto:info@onestudio.com.au">Contact Us</a></li>
                 </ul>
            </nav>
        </header>