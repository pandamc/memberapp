<?php
require_once("../configFiles/common.inc.php");

$commentid = isset( $_GET["commentid"] ) ? (string)$_GET["commentid"] : 0;
$list = Comment::deleteComment( $commentid );


displayPageHeader( "Your comment has been deleted", true );
?>
    <p><a href="blog.php/">Go back the blog posts</a>.</p>
<?php
displayPageFooter();
?>