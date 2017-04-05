<?php
require_once("../configFiles/common.inc.php");



checkLogin();

$userid = $_SESSION["member"]->getValue( "id" );


$username = $_SESSION["member"]->getValue( "username" );
$postid = isset( $_REQUEST["postid"] ) ? (int)$_REQUEST["postid"] : 0;


// show nav bar for members area
displayNavBar();
echo $userid;
echo "<br>";
echo $postid;
// if the form action is register add user or handle errors
if ( isset( $_POST["action"] ) and $_POST["action"] == "addComment" ) {
    $userid = $_SESSION["member"]->getValue( "id" );
    processForm();
} else {
    displayForm( array(), array(), new Comment( array() ) );
}


// if there are no errors display the form
function displayForm( $errorMessages, $missingFields, $blog ) {
    displayPageHeader( "HI, Blog about your latest adventure" );

    if ( $errorMessages ) {
        foreach ( $errorMessages as $errorMessage ) {
            echo $errorMessage;
        }
    } else {
        ?>
        <p>Hi <?php echo $_SESSION["member"]->getValue( "username" ); ?> add a comment to this post.</p>
    <?php } ?>



    <form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post" style="margin-bottom: 50px;">
        <div style="width: 30em;">
            <input name="action" type="hidden" value="addComment">
            <!--<label for="userid"<?php validateField( "userid", $missingFields ) ?>>user id</label>-->
            <input type="hidden" id="userid" cols="50" name="userid" rows="4" value="<?php echo $_SESSION["member"]->getValue( "id" ); ?>"><?php echo $blog->getValueEncoded( "userid" ) ?>
            <input type="hidden" id="postid" cols="50" name="postid" rows="4" value="<?php echo $_REQUEST['postid'] ?>;"<?php echo $blog->getValueEncoded( "postid" ) ?>
            <div style="clear: both;">
                <label for="body"<?php validateField( "body", $missingFields ) ?>>Add a Comment</label>
                <textarea id="body" cols="50" name="body" rows="4"><?php echo $blog->getValueEncoded( "body" ) ?></textarea>
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


    $requiredFields = array( "userid", "postid" ,"body" );
    $missingFields = array();
    $errorMessages = array();
    $comment = new Comment( array(
        "userid" => isset( $_POST["userid"] ) ? preg_replace( "/[^ \-\_a-zA-Z0-9]/", "", $_POST["userid"] ) : "",
        "postid" => isset( $_POST["postid"] ) ? (int) $_POST["postid"] : "",
        "body" => isset( $_POST["body"] ) ? preg_replace( "/[^ \-\_a-zA-Z0-9]/", "", $_POST["body"] ) : "",
        "postdate" =>  date( "Y-m-d H:i:s" )

    ) );
    foreach ( $requiredFields as $requiredField ) {
        if ( !$comment->getValue( $requiredField ) ) {
            $missingFields[] = $requiredField;
        }
    }
    // deal with errors
    if ( $missingFields ) {
        $errorMessages[] = '<p class="error">There were some missing fields in the form you submitted. Please complete the fields highlighted below and click Send Details to resend the form.</p>';
    }

    if ( $errorMessages ) {
        displayForm( $errorMessages, $missingFields, $comment );
    } else {
        $comment->addComment();
        displayThanks();
    }
}

// when the values are stored, display a thankyou page
function displayThanks() {

    displayPageHeader( "Thanks for your comment!" );
    ?>
    <p>Thank you, your comment has been added.</p>
    <a href="blog.php/">go to blog page</a>
    <?php
//sleep(5);
//header("Location: blog.php");
//exit();
    displayPageFooter();
}
?>