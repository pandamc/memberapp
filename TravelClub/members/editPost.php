<?php
require_once( "../common.inc.php" );



checkLogin();
// show nav bar for members area
displayNavBar();

$postid = isset( $_GET["postid"] ) ? (string)$_GET["postid"] : 0;
$body = isset( $_GET["body"]) ? (string)$_GET["body"] :0;
//$post = Blog::viewBlogPost($postid );
//$body = Blog::viewBlogPost($body );

echo "<br>";
echo "the post id is " .$postid;
echo "<br>";
echo "the blog text is " . $body;


// if the form action is register add user or handle errors
if ( isset( $_POST["action"] ) and $_POST["action"] == "updateBlogPost" ) {
    $postid = isset( $_GET["postid"] ) ? (int)$_GET["postid"] : 0;
    $body = isset( $_GET["body"] ) ? (int)$_GET["body"] : 0;
    processForm();
} else {
    displayForm( array(), array(), new Blog( array() ) );
}


// if there are no errors display the form
function displayForm( $errorMessages, $missingFields, $blog ) {
    displayPageHeader( "Edit your blog post" );

    if ( $errorMessages ) {
        foreach ( $errorMessages as $errorMessage ) {
            echo $errorMessage;
        }
    } else {
        ?>
    <?php } ?>


    <form action="editPost.php" method="post" style="margin-bottom: 50px;">
        <div style="width: 30em;">
            <input name="action" type="hidden" value="updateBlogPost">
            <!--<label for="userid"<?php validateField( "postid", $missingFields ) ?>>user id</label>-->
            <input type="hidden" id="postid" cols="50" name="postid" rows="4" value="<?php echo $blog->getValueEncoded( "postid" ) ?>; ?>"><?php echo $blog->getValueEncoded( "postid" ) ?>
            <input type="hidden" id="body" cols="50" name="body" rows="4" value="<?php echo $blog->getValueEncoded( "body" ) ?>; ?>"><?php echo $blog->getValueEncoded( "body" ) ?>
           <input type="text" value="<?php echo htmlspecialchars($_GET['postid']); ?>">
            <div style="clear: both;">
                <label for="body"<?php validateField( "body", $missingFields ) ?>><?php echo $blog->getValueEncoded( "body" ) ?></label>

                <textarea id="body" cols="50" name="body" rows="4" value="<?php echo htmlspecialchars($_GET['body']); ?>"><?php echo htmlspecialchars($_GET['body']); ?></textarea>
                <div style="clear: both;">
                    <input id="submitButton" name="submitButton" type="submit" value="Send Details">
                    <input id="resetButton" name="resetButton" style="margin-right: 20px;" type="reset" value="Reset Form">
                </div>
            </div>
    </form>
    <?php
    displayPageFooter();
}

//  process the form data and store it in the database
function processForm() {


    $requiredFields = array( "postid", "body" );
    $missingFields = array();
    $errorMessages = array();
    $blog = new blog( array(
        "postid" => isset( $_POST["postid"] ) ? preg_replace( "/[^ \-\_a-zA-Z0-9]/", "", $_POST["postid"] ) : "",
        "body" => isset( $_POST["body"] ) ? preg_replace( "/[^ \-\_a-zA-Z0-9]/", "", $_POST["body"] ) : "",
        "postdate" =>  date( "Y-m-d H:i:s" )

    ) );
    foreach ( $requiredFields as $requiredField ) {
        if ( !$blog->getValue( $requiredField ) ) {
            $missingFields[] = $requiredField;
        }
    }
    // deal with errors
    if ( $missingFields ) {
        $errorMessages[] = '<p class="error">There were some missing fields in the form you submitted. Please complete the fields highlighted below and click Send Details to resend the form.</p>';
    }

    if ( $errorMessages ) {
        displayForm( $errorMessages, $missingFields, $blog );
    } else {
        $blog->updateBlogPost($postid, $body);
        displayThanks();
    }
}

// when the values are stored, display a thankyou page
function displayThanks() {

    displayPageHeader( "Your post has been updated" );
    ?>
    <p>Thank you, your blog post has been updated.</p>

    <a href="blog.php">go to blog page</a>
    <?php
    displayPageFooter();
}
?>