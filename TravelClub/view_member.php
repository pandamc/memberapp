<?php
require_once("common.inc.php");

$memberId = isset( $_GET["memberId"] ) ? (int)$_GET["memberId"] : 0;
if ( !$member = Member::getMember( $memberId ) ) {
    displayPageHeader( "Error" );
    echo "<div>Member not found.</div>";
    displayPageFooter();
    exit;
}
$logEntries = LogEntry::getLogEntries( $memberId );
displayPageHeader( "View member: " . $member->getValueEncoded( "firstName" ) . " " . $member->getValueEncoded( "lastName" ) );
?>

<!--  boilerplate html for displaying member record details -->
<table style="width: 30em; border: 1px solid #666;">
    <tr><td>Username</td>
    <td><?php echo $member->getValueEncoded( "username" ) ?></td></tr>
    <tr><td>First name
    <td><?php echo $member->getValueEncoded( "firstName" ) ?></td></tr>
    <tr><td>Last name</td>
    <td><?php echo $member->getValueEncoded( "lastName" ) ?></td></tr>
    <tr><td>Joined on</td>
    <td><?php echo $member->getValueEncoded( "joinDate" ) ?></td></tr>
    <tr><td>Gender</td>
    <td><?php echo $member->getGenderString() ?></td></tr>
    <tr><td>Favorite Activity</td>
    <td><?php echo $member->getFavoriteActivityString() ?></td></tr>
    <tr><td>Email address</td>
    <td><?php echo $member->getValueEncoded( "emailAddress" ) ?></td></tr>
    <tr><td>Other interests</td>
    <td><?php echo $member->getValueEncoded( "otherInterests" ) ?></td></tr>
</table>
<h2>Access log</h2>
<table style="width: 30em; border: 1px solid #666;">
    <tr>
        <th>Web page</th>
        <th>Number of visits</th>
        <th>Last visit</th>
    </tr>
    <?php
    $rowCount = 0;
    foreach ( $logEntries as $logEntry ) {
        $rowCount++;
        ?>

        <tr<?php if ( $rowCount % 2 == 0 ) echo ' class="alt"' ?>>
            <td><?php echo $logEntry->getValueEncoded( "pageUrl" ) ?></td>
            <td><?php echo $logEntry->getValueEncoded( "numVisits" ) ?></td>
            <td><?php echo $logEntry->getValueEncoded( "lastAccess" ) ?></td>
        </tr>
        <?php

    }
    ?>
</table>
<!-- create back link to referring page -->
<div style="width: 30em; margin-top: 20px; text-align: center;">
    <a href="javascript:history.go(-1)">Back</a>
</div>
<?php
displayPageFooter();
?>
