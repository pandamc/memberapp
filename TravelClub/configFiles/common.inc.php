<?php

// require common files used by the application

require_once("config.php");
require_once("member.class.php");
require_once("LogEntry.class.php");
require_once("Blog.Class.php");
require_once("memberNav.php");
// boiler plate for html pages to avoid code repetition
// display header
function displayPageHeader( $pageTitle, $membersArea = false ) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <link href="<?php if ( $membersArea ) echo "../" ?>../common.css" rel="stylesheet" type="text/css">
        <title>Traveller chat and message site</title>
        <style type="text/css">
            .error {
                background: #dd3333;
                color: white;
                padding: 0.2em;
            }
            th {
                text-align: left;
                background-color: #bbbbbb;
            }
            th, td {
                padding: 0.4em;
            }
            tr.alt td {
                background: #dddddd;
            }
        </style>
    </head>
    <body>
    <h1><?php echo $pageTitle?></h1>
    <?php
}

// display footer
function displayPageFooter() {
    ?>
    </body>
    </html>
    <?php
}

// if field(s) not filled out change class and background colour
function validateField( $fieldName, $missingFields ) {
    if ( in_array( $fieldName, $missingFields ) ) {
        echo ' class="error"';
    }
}

// check value appropriate
function setChecked( DataObject $obj, $fieldName, $fieldValue ) {
    if ( $obj->getValue( $fieldName ) == $fieldValue ) {
        echo ' checked="checked"';
    }
}

// select value if appropriate
function setSelected( DataObject $obj, $fieldName, $fieldValue ) {
    if ( $obj->getValue( $fieldName ) == $fieldValue ) {
        echo ' selected="selected"';
    }
}

// check to see if user is logged in, if not direct to login page, otherwise log page visit
function checkLogin() {
    session_start();
    if ( !$_SESSION["member"] or !$_SESSION["member"] = Member::getMember( $_SESSION["member"]->getValue( "id" ) ) ) {
        $_SESSION["member"] = "";
        header( "Location: login.php" );
        exit;
    } else {
        $logEntry = new LogEntry( array (
            "memberId" => $_SESSION["member"]->getValue( "id" ),
            "pageUrl" => basename( $_SERVER["PHP_SELF"] )
        ) );
        $logEntry->record();
    }
}
?>