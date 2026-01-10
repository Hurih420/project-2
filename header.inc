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
        <meta http-equiv="Content-Security-Policy" content="default-src 'self'; style-src 'self';"> 
        <link rel="stylesheet" href="styles/styles.css">
        <title>One Studio Cybersecurity Specialists</title>
    </head>

    <body id="<?php echo htmlspecialchars($body_id ?? ''); ?>">
        <header>
            <img src="images/GROUPLOGO.png" alt="One Studio logo" title="One Studio" loading="lazy" width="100" class="logo">
            <h2><strong>One Studio</strong></h2>
            <h1 class="page-banner">Explore Interesting Cyber Security Careers</h1>
            <?php include "nav.inc"; ?>
        </header>