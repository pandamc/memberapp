<?php
require_once("../configFiles/common.inc.php");
session_start();
$_SESSION["member"] = "";
displayPageHeader( "Logged out", true );
?>
    <p>Thank you, you are now logged out. <a href="../login.php">Login again</a>.</p>
<?php
displayPageFooter();
?>