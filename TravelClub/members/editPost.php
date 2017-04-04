<?php

require_once("../common.inc.php");
checkLogin();

$postid = isset( $_REQUEST["postid"] ) ? (int)$_REQUEST["postid"] : 0;
//$postid = isset( $_GET["postid"] ) ? (string)$_GET["postid"] : 0;
$body = isset( $_GET["body"] ) ? (string)$_GET["body"] : 0;

// show nav bar for members area
displayNavBar();



if ( !$post = Blog::viewBlogPost( $postid, $body)) {
    displayPageHeader( "Error" );
    echo "<div>Post not found.</div>";
    displayPageFooter();
}

if ( isset( $_POST["action"] ) and $_POST["action"] == "Save Changes" ) {
    saveChanges();
}
elseif ( isset( $_POST["action"] ) and $_POST["action"] == "Delete Member" ) {
    deleteMember();
} else {
    displayForm( array(), array(), $post );
}

function displayForm( $errorMessages, $missingFields, $post )
{
    $post = Blog::viewBlogPost($post->getValue("postid"));
    displayPageHeader("View post: " . $post->getValueEncoded("postid"));

    if ($errorMessages) {
        foreach ($errorMessages as $errorMessage) {
            echo $errorMessage;
        }
    } else {
        ?>
        <p>Add a blog post.</p>
    <?php } ?>






    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="margin-bottom: 50px;" xmlns="http://www.w3.org/1999/html">
        <div style="width: 30em;">
            <input type="hidden" name="postid" id="postid" value="<?php echo $post->getValueEncoded( "postid" ) ?>">
            <label for="body"<?php validateField( "body", $missingFields ) ?>>Text *</label>
            <textarea rows="4" cols="50" name="body"  value=""><?php echo $post->getValueEncoded( "body" ) ?></textarea>
            <div style="clear: both;">
                <input type="submit" name="action" id="saveButton" value="Save Changes">
                <!-- <input type="submit" name="action" id="deleteButton" value="Delete Member" style="margin-right: 20px;">-->
            </div>
        </div>
    </form>


    <?php
    displayPageFooter();
}


function saveChanges() {

    $requiredFields = array( "body");
    $missingFields = array();
    $errorMessages = array();

    $post = new Blog( array(
        "postid" => isset( $_POST["postid"] ) ? (int) $_POST["postid"] : "",
        "body" => isset( $_POST["body"] ) ? preg_replace( "/[^ \-\_a-zA-Z0-9]/", "", $_POST["body"] ) : "",
    ) );

    foreach ( $requiredFields as $requiredField ) {
        if ( !$post->getValue( $requiredField ) ) {
            $missingFields[] = $requiredField;
        }
    }

    if ( $missingFields ) {
        $errorMessages[] = '<p class="error">There were some missing fields in the form you submitted. Please complete the fields highlighted below and click Save Changes to resend the form.</p>';
    }


    if ( $errorMessages ) {
        displayForm( $errorMessages, $missingFields, $post );
    } else {
        $post->updateBlogPost();
        displaySuccess();
    }

}

function displaySuccess() {
    displayPageHeader( "Changes saved" );
    ?>

    <p>Your changes have been saved. <a href="myposts.php?">Return to your posts</a></p>

    <?php

    displayPageFooter();

}

?>