<?php
require_once( "../common.inc.php" );
checkLogin();
displayPageHeader( "Welcome to the Members' Area", true );
?>
    <p>Welcome, <?php echo $_SESSION["member"]->getValue( "firstName" ) ?>! Please choose an option below:</p>
    <ul>
        <li><a href="blog.php">Blog Posts</a></li>
        <li><a href="pictures.php">Some nice travel pics</a></li>
        <li><a href="contact.php">Contact the travel club</a></li>
        <li><a href="logout.php">Logout</a></li>
    </ul>
<?php displayPageFooter(); ?>