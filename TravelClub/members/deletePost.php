<?php
require_once("../common.inc.php");

$postid = isset( $_GET["postid"] ) ? (string)$_GET["postid"] : 0;
$list = Blog::deleteBlogPost( $postid );


displayPageHeader( "Your post has been deleted", true );
?>
<p><a href="myposts.php">Go back to your posts</a>.</p>
<?php
displayPageFooter();
?>