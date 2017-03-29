
<?php


require_once("../common.inc.php");

checkLogin();

$userid = $_SESSION["member"]->getValue( "id" );

$start = isset($_GET["start"]) ? (int)$_GET["start"] : 0;
// get column to order results by
$order = isset($_GET["order"]) ? preg_replace("/[^a-zA-Z]/", "", $_GET["order"]) : "postid";
displayNavBar();
list($posts, $totalRows) = Blog::viewMyPosts($start, PAGE_SIZE, $order, $userid);

displayPageHeader("View your blog posts", true);



?>
<!-- display all blog posts by the logged in user -->



<h2>Displaying Blog Posts <?php echo $start + 1 ?> - <?php echo min( $start +  PAGE_SIZE, $totalRows ) ?> of <?php echo $totalRows ?></h2>
<table style="width: 30em; border: 1px solid #666;">
    <tr>

        // allow user to set order by column
        <th>User <?php if ( $order != "userid" ) { ?><a href="blog.php?order=userid&$direction=ASC"><?php } ?>^<?php if ( $order != "userid") { ?></a><?php } ?></th>
        <th><?php if ( $order != "postid" ) { ?><a href="blog.php?order=postid&DESC"><?php } ?>post id<?php if ( $order != "postid" ) { ?></a><?php } ?></th>
        <th><?php if ( $order != "body" ) { ?><a href="blog.php?order=body"><?php } ?>post<?php if ( $order != "body" ) { ?></a><?php } ?></th>
        <th><?php if ( $order != "postdate" ) { ?><a href="blog.php?order=postdate"><?php } ?>post date<?php if ( $order != "postdate" ) { ?></a><?php } ?></th>
        <th>Edit your post</th>
        <th>Delete your post</th>
    </tr>
    <?php
    $rowCount = 0;
    foreach ($posts as $post) {
        $rowCount++;
        ?>

        <tr<?php if ($rowCount % 2 == 0) echo ' class="alt"' ?>>
            <td><?php echo $post->getValueEncoded("userid") ?></td>
            <td><?php echo $post->getValueEncoded("postid") ?></td>
            <td><?php echo $post->getValueEncoded("body") ?></td>
            <td><?php echo $post->getValueEncoded("postdate") ?></td>
            <td><a href="../editPost.php?postid=<?php echo $post->getValueEncoded( "postid" ) ?>&body=<?php echo $post->getValueEncoded( "body" ) ?>"><?php echo $post->getValueEncoded( "postid" ) ?></a></td>
            <td><a href="../deletePost.php?postid=<?php echo $post->getValueEncoded( "postid" ) ?>&body=<?php echo $post->getValueEncoded( "body" ) ?>"><?php echo $post->getValueEncoded( "postid" ) ?></a></td>


        </tr>
        <?php

    }
    ?>
</table>

<!-- display forward and back links for pagination -->
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