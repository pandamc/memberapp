<?php
require_once( "../common.inc.php" );
require_once( "../Blog.Class.php" );

checkLogin();

$user_id = $_SESSION["member"]->getValue( "id" );
$username = $_SESSION["member"]->getValue( "username" );
print_r($_SESSION);
echo "<br>";
echo $user_id;
echo "<br>";
echo $username;
// if the form action is register add user or handle errors
if ( isset( $_POST["action"] ) and $_POST["action"] == "newBlogPost" ) {
    processForm();
} else {
    displayForm( array(), array(), new Blog( array() ) );
}

// if there are no errors display the form
function displayForm( $errorMessages, $missingFields, $blog ) {
    displayPageHeader( "HI, tell us about your latest trip!" );

    if ( $errorMessages ) {
        foreach ( $errorMessages as $errorMessage ) {
            echo $errorMessage;
        }
    } else {
        ?>
        <p>Add a new blog post</p>
        <p>Fields marked with an asterisk (*) are required.</p>
    <?php } ?>
    <form action="newBlogPost.php" method="post" style="margin-bottom: 50px;">
        <div style="width: 30em;">
            <input name="action" type="hidden" value="newBlogPost">

            <label for="body">Enter your post here</label>
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

    $requiredFields = array( "body" );
    $missingFields = array();
    $errorMessages = array();
    $blog = new blog( array(
        "user_id" =>  $_SESSION["blog"]->getValue( "id" ),
        "body" => isset( $_POST["body"] ) ? preg_replace( "/[^ \-\_a-zA-Z0-9]/", "", $_POST["body"] ) : ""

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
        $blog->insert();
        displayThanks();
    }
}

// when the values are stored, display a thankyou page
function displayThanks() {

    displayPageHeader( "Thanks for registering!" );
    ?>
    <p>Thank you for the new post, happy travels</p>
    <?php
    displayPageFooter();
}
?>