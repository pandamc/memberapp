<?php
require_once("../configFiles/common.inc.php");

checkLogin();

$start = isset($_GET["start"]) ? (int)$_GET["start"] : 0;
// get column to order results by
$order = isset($_GET["order"]) ? preg_replace("/[^a-zA-Z]/", "", $_GET["order"]) : "postid";

displayNavBar();
list($posts, $totalRows) = Blog::getPosts($start, PAGE_SIZE, $order);
//list($username) = Blog::getUserName($username);
displayPageHeader("View recent blog posts", true);




?>
<!-- display all blog posts -->

    <h2>Displaying Blog Posts <?php echo $start + 1 ?> - <?php echo min( $start +  PAGE_SIZE, $totalRows ) ?> of <?php echo $totalRows ?></h2>
    <table class="tableStyle">
        <tr>

            <!-- allow user to set order by column-->
            <th><?php if ( $order != "username" ) { ?><a href="blog.php?order=userid"><?php } ?>User<?php if ( $order != "username" ) { ?></a><?php } ?></th>
            <th><?php if ( $order != "postid" ) { ?><a href="blog.php?order=postid"><?php } ?>post id<?php if ( $order != "postid" ) { ?></a><?php } ?></th>
            <th><?php if ( $order != "body" ) { ?><a href="blog.php?order=body"><?php } ?>post<?php if ( $order != "body" ) { ?></a><?php } ?></th>
            <th><?php if ( $order != "postdate" ) { ?><a href="blog.php?order=postdate"><?php } ?>post date<?php if ( $order != "postdate" ) { ?></a><?php } ?></th>
        </tr>
        <?php
        $rowCount = 0;
        foreach ($posts as $post) {
            $rowCount++;
            ?>

            <tr<?php if ($rowCount % 2 == 0) echo ' class="alt"' ?>>
                <td><?php echo $post->getValueEncoded("username") ?></td>
                <td><?php echo $post->getValueEncoded("postid") ?></td>
                <td><?php echo $post->getValueEncoded("body") ?> <br> <br> <a href="/TravelClub/members/addComment.php">add a comment</a> </td>
                <td><?php echo $post->getValueEncoded("postdate") ?></td>
            </tr>
            <?php

        }
        ?>
    </table>


    <div style="width: 30em; margin-top: 20px; text-align: center;">
<?php if ( $start > 0 ) { ?>
    <a href="blog.php?start=<?php echo max( $start - PAGE_SIZE, 0 ) ?>&amp;order=<?php echo $order ?>">Previous page</a>
<?php } ?>
    &#160;
<?php if ( $start + PAGE_SIZE < $totalRows ) { ?>
    <a href="blog.php?start=<?php echo min( $start + PAGE_SIZE, $totalRows ) ?>&amp;order=<?php echo $order ?>">Next page</a>
<?php } ?>
    </div>


<?php displayPageFooter(); ?>