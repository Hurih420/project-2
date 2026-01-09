<?php
$body_id = "EnhancementsPage";
include "header.inc";
?>

<main>
<section>
  <h1>Enhancements</h1>

  <h2>Improvement 1: Manager's Secure Login Details are Stored</h2>

  <p>
    Login module for managers is designed by using a MySQL table named
    <strong>"managers"</strong>.
    Password storage is done in a safe manner using the code
    <strong>password_hash()</strong>, and
    only authorized managers have access to <strong>manage.php</strong>
    (verified through session).
  </p>

  <ul>
    <li>The manager's credentials are stored within the database instead of being hardcoded.</li>
    <li>Passwords are hashed and not stored as plaintext.</li>
    <li>Session is used for protecting the manage page.</li>
  </ul>

  <h2>Enhancement 2: Account Lockout After Multiple Invalid Login Attempts</h2>

  <p>
    There is also a lockout system that prevents brute-force logins.
    If the user has failed his/her login attempts, the account will temporarily lock.
  </p>

  <ul>
    <li>Failed attempts are recorded in the database.</li>
    <li>Lockout after 5 unsuccessful password attempts for 10 minutes.</li>
    <li>Failed login attempts are reset after successful login.</li>
  </ul>

  <h2>Extra Improvement: Sorting in Manage Page</h2>

  <p>
    The manage page allows for the selection of fields by which the EOIs can then be sorted,
    for example, by EOI number, job reference, name, and direction of asc/desc.
  </p>

</section>
</main>

<?php include "footer.inc"; ?>