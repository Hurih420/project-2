         <footer>
            <p>&copy; 2025 One Studio Cybersecurity Specialists.</p>
            <p class="footer-links">
                <?php if (empty($_SESSION['is_admin'])): ?>
                    <a href="simple_login.php">Manage EOIs</a>
                <span> | </span>
                <?php endif; ?>
                    <a href="enhancements.php">Enhancements</a>
            </p>
        </footer>
    </body>
</html>