<?php

// boiler plate for html pages to avoid code repetition
// display header
function displayPageHeader( $pageTitle ) {
    ?>
    <!DOCTYPE html>
    <html>
    <head>
        <meta charset="utf-8">
        <link href="../common.css" rel="stylesheet" type="text/css">
        <title>Dynamic Web Applications</title>
        <style type="text/css">
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
?>