<?php

require_once("../configFiles/common.inc.php");
checkLogin();

$commentid = isset( $_REQUEST["commentid"] ) ? (int)$_REQUEST["commentid"] : 0;

$body = isset( $_GET["body"] ) ? (string)$_GET["body"] : 0;

// show nav bar for members area
displayNavBar();



if ( !$comment = Comment::viewComment( $commentid, $body)) {
    displayPageHeader( "Error" );
    echo "<div>Comment not found.</div>";
    displayPageFooter();
}

if ( isset( $_POST["action"] ) and $_POST["action"] == "Save Changes" ) {
    saveChanges();
}
elseif ( isset( $_POST["action"] ) and $_POST["action"] == "Delete Member" ) {
    deleteMember();
} else {
    displayForm( array(), array(), $comment );
}

function displayForm( $errorMessages, $missingFields, $comment )
{
    $comment = Comment::viewComment($comment->getValue("commentid"));
    displayPageHeader("View comment: " . $comment->getValueEncoded("commentid"));

    if ($errorMessages) {
        foreach ($errorMessages as $errorMessage) {
            echo $errorMessage;
        }
    } else {
        ?>
        <p>Edit this comment</p>
    <?php } ?>






    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="margin-bottom: 50px;" xmlns="http://www.w3.org/1999/html">
        <div style="width: 30em;">
            <input type="hidden" name="commentid" id="commentid" value="<?php echo $comment->getValueEncoded( "commentid" ) ?>">
            <label for="body"<?php validateField( "body", $missingFields ) ?>>Text *</label>
            <textarea rows="4" cols="50" name="body"  value=""><?php echo $comment->getValueEncoded( "body" ) ?></textarea>
            <div style="clear: both;">
                <input type="submit" name="action" id="saveButton" value="Save Changes">

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

    $post = new Comment( array(
        "commentid" => isset( $_POST["commentid"] ) ? (int) $_POST["commentid"] : "",
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
        $post->updateComment();
        displaySuccess();
    }

}

function displaySuccess() {
    displayPageHeader( "Changes saved" );
    ?>

    <p>Your changes have been saved. <a href="blog.php">Return to blog posts</a></p>

    <?php

    displayPageFooter();

}

?>