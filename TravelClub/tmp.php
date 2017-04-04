<?php

require_once("common.inc.php");

if ( isset( $_POST["action"] ) and $_POST["action"] == "register" ) {
    processForm();
} else {
    displayForm( array(), array(), new Member( array() ) );
}

function displayForm( $errorMessages, $missingFields, $member ) {
    displayPageHeader( "Sign up for the book club!" );

    if ( $errorMessages ) {
        foreach ( $errorMessages as $errorMessage ) {
            echo $errorMessage;
        }
    } else {

        ?>

        <p>Thanks for choosing to join our book club.</p>
        <p>To register, please fill in your details below and click Send Details.</p>
        <p>Fields marked with an asterisk (*) are required.</p>

    <?php } ?>

    <form action="register.php" method="post" style="margin-bottom: 50px;">
        <div style="width: 30em;">
            <input name="action" type="hidden" value="register">
            <label for="username"<?php validateField( "username", $missingFields ) ?>>Choose a username *</label>
            <input id="username" name="username" type="text" value="<?php echo $member->getValueEncoded( "username" ) ?>">
            <label for="password1"<?php if ( $missingFields ) echo ' class="error"' ?>>Choose a password *</label>
            <input id="password1" name="password1" type="password" value=""><label for="password2"<?php if ( $missingFields ) echo ' class="error"' ?>>Retype password *</label>
            <input id="password2" name="password2" type="password" value="">
            <label for="emailAddress"<?php validateField( "emailAddress", $missingFields ) ?>>Email address *</label>
            <input id="emailAddress" name="emailAddress" type="text" value="<?php echo $member->getValueEncoded( "emailAddress" ) ?>">
            <label for="firstName"<?php validateField( "firstName", $missingFields ) ?>>First name *</label>
            <input id="firstName" name="firstName" type="text" value="<?php echo $member->getValueEncoded( "firstName" ) ?>">
            <label for="lastName"<?php validateField( "lastName", $missingFields ) ?>>Last name *</label>
            <input id="lastName" name="lastName" type="text" value="<?php echo $member->getValueEncoded( "lastName" ) ?>">
            <label<?php validateField( "gender", $missingFields ) ?>>Your gender: *</label>
            <label for="genderMale">Male</label>
            <input type="radio" name="gender" id="genderMale" value="m"<?php setChecked( $member, "gender", "m" )?>/>
            <label for="genderFemale">Female</label>
            <input type="radio" name="gender" id="genderFemale" value="f"<?php setChecked( $member, "gender", "f" )?>>
            <label for="favoriteGenre">What's your favorite genre?</label>
            <select id="favoriteGenre" name="favoriteGenre" size="1">

                <?php foreach ( $member->getGenres() as $value => $label ) { ?>

                    <option value="<?php echo $value ?>"<?php setSelected( $member, "favoriteGenre", $value ) ?>><?php echo $label ?></option>

                <?php } ?>

            </select>
            <label for="otherInterests">What are your other interests?</label>
            <textarea id="otherInterests" cols="50" name="otherInterests" rows="4"><?php echo $member->getValueEncoded( "otherInterests" ) ?></textarea>
            <div style="clear: both;">
                <input id="submitButton" name="submitButton" type="submit" value="Send Details">
                <input id="resetButton" name="resetButton" style="margin-right: 20px;" type="reset" value="Reset Form">
            </div>
        </div>
    </form>

    <?php

    displayPageFooter();

}

function processForm() {

    $requiredFields = array( "username", "password", "emailAddress", "firstName", "lastName", "gender" );
    $missingFields = array();
    $errorMessages = array();

    $member = new Member( array(
        "username" => isset( $_POST["username"] ) ? preg_replace( "/[^ \-\_a-zA-Z0-9]/", "", $_POST["username"] ) : "",
        "password" => ( isset( $_POST["password1"] ) and isset( $_POST["password2"] ) and $_POST["password1"] == $_POST["password2"] ) ? preg_replace( "/[^ \-\_a-zA-Z0-9]/", "", $_POST["password1"] ) : "",
        "firstName" => isset( $_POST["firstName"] ) ? preg_replace( "/[^ \'\-a-zA-Z0-9]/", "", $_POST["firstName"] ) : "",
        "lastName" => isset( $_POST["lastName"] ) ? preg_replace( "/[^ \'\-a-zA-Z0-9]/", "", $_POST["lastName"] ) : "",
        "gender" => isset( $_POST["gender"] ) ? preg_replace( "/[^mf]/", "", $_POST["gender"] ) : "",
        "favoriteGenre" => isset( $_POST["favoriteGenre"] ) ? preg_replace( "/[^a-zA-Z]/", "", $_POST["favoriteGenre"] ) : "",
        "emailAddress" => isset( $_POST["emailAddress"] ) ? preg_replace( "/[^ \@\.\-\_a-zA-Z0-9]/", "", $_POST["emailAddress"] ) : "",
        "otherInterests" => isset( $_POST["otherInterests"] ) ? preg_replace( "/[^ \'\,\.\-a-zA-Z0-9]/", "", $_POST["otherInterests"] ) : "",
        "joinDate" => date( "Y-m-d" )
    ) );

    foreach ( $requiredFields as $requiredField ) {
        if ( !$member->getValue( $requiredField ) ) {
            $missingFields[] = $requiredField;
        }
    }

    if ( $missingFields ) {
        $errorMessages[] = '<p class="error">There were some missing fields in the form you submitted. Please complete the fields highlighted below and click Send Details to resend the form.</p>';
    }

    if ( !isset( $_POST["password1"] ) or !isset( $_POST["password2"] ) or !$_POST["password1"] or !$_POST["password2"] or ( $_POST["password1"] != $_POST["password2"] ) ) {
        $errorMessages[] = '<p class="error">Please make sure you enter your password correctly in both password fields.</p>';
    }

    if ( Member::getByUsername( $member->getValue( "username" ) ) ) {
        $errorMessages[] = '<p class="error">A member with that username already exists in the database. Please choose another username.</p>';
    }

    if ( Member::getByEmailAddress( $member->getValue( "emailAddress" ) ) ) {
        $errorMessages[] = '<p class="error">A member with that email address already exists in the database. Please choose another email address, or contact the webmaster to retrieve your password.</p>';
    }

    if ( $errorMessages ) {
        displayForm( $errorMessages, $missingFields, $member );
    } else {
        $member->insert();
        displayThanks();
    }

}

function displayThanks() {

    displayPageHeader( "Thanks for registering!" );

    ?>

    <p>Thank you, you are now a registered member of the book club.</p>

    <?php

    displayPageFooter();

}

?>