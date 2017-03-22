<?php
require_once( "../common.inc.php" );


checkLogin();

$user_id = $_SESSION["member"]->getValue( "id" );

$username = $_SESSION["member"]->getValue( "username" );


// if the form action is register add user or handle errors
if ( isset( $_POST["action"] ) and $_POST["action"] == "insertBlogPost" ) {
    $user_id = $_SESSION["member"]->getValue( "id" );
    processForm();
} else {
    displayForm( array(), array(), new Blog( array() ) );
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
        <p>Add a blog post.</p>
    <?php } ?>

    <form action="newBlogPost.php" method="post" style="margin-bottom: 50px;">
        <div style="width: 30em;">
            <input name="action" type="hidden" value="insertBlogPost">
            <!--<label for="user_id"<?php validateField( "user_id", $missingFields ) ?>>user id</label>-->
            <input type="hidden" id="user_id" cols="50" name="user_id" rows="4" value="<?php echo $_SESSION["member"]->getValue( "id" ); ?>"><?php echo $blog->getValueEncoded( "user_id" ) ?>
            <div style="clear: both;">
            <label for="body"<?php validateField( "body", $missingFields ) ?>>Add a Post</label>
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


    $requiredFields = array( "user_id", "body" );
    $missingFields = array();
    $errorMessages = array();
    $blog = new blog( array(
        "user_id" => isset( $_POST["user_id"] ) ? preg_replace( "/[^ \-\_a-zA-Z0-9]/", "", $_POST["user_id"] ) : "",
        "body" => isset( $_POST["body"] ) ? preg_replace( "/[^ \-\_a-zA-Z0-9]/", "", $_POST["body"] ) : "",

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
        echo "id is " . $_POST['user_id'];
        echo $_POST['body'];
    } else {
        $blog->insertBlogPost();
        displayThanks();
    }
}

// when the values are stored, display a thankyou page
function displayThanks() {

    displayPageHeader( "Thanks for blogging with us!" );
    ?>
    <p>Thank you, your blog post has been added.</p>
    <?php
    displayPageFooter();
}
?>