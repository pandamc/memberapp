<?php

require_once("../configFiles/common.inc.php");

checkLogin();
displayNavBar();
$userid = $_SESSION["member"]->getValue("id");
$postid = isset($_REQUEST["postid"]) ? (int)$_REQUEST["postid"] : 0;

$start = isset($_GET["start"]) ? (int)$_GET["start"] : 0;

// get column to order results by
$order = isset($_GET["order"]) ? preg_replace("/[^a-zA-Z]/", "", $_GET["order"]) : "postid";

//list($posts, $totalRows) = Blog::viewMyPosts($start, PAGE_SIZE, $order, $userid, $postid, $body, $postdate);
list($comments, $totalRows) = Comment::getCommentsPerPost($start, PAGE_SIZE, $order, $postid);


displayPageHeader("View comments for this post", true);


?>
<!-- display all comments related to a post -->


<h2>Displaying comments on blog post <?php echo $postid ?> <br> <?php echo $start + 1 ?>
    - <?php echo min($start + PAGE_SIZE, $totalRows) ?> of <?php echo $totalRows ?></h2>
<table class="tableStyle">
    <tr>


        <th><?php if ($order != "postid") { ?><a href="showcomments.php?order=postid&DESC"><?php } ?>post
                id<?php if ($order != "postid") { ?></a><?php } ?></th>
        <th><?php if ($order != "commentid") { ?><a href="showcomments.php?order=postid&DESC"><?php } ?>comment
                id<?php if ($order != "commentid") { ?></a><?php } ?></th>
        <th><?php if ($order != "body") { ?><a href="showcomments.php?order=body"><?php } ?>
                comment<?php if ($order != "body") { ?></a><?php } ?></th>
        <th><?php if ($order != "postdate") { ?><a href="showcomments.php?order=postdate"><?php } ?>comment
                date<?php if ($order != "postdate") { ?></a><?php } ?></th>
        <th>Edit your comments</th>
        <th>Delete your comments</th>
        <th>Comment user id</th>


    </tr>
    <?php
    $rowCount = 0;
    foreach ($comments as $comment) {
        $commentid = $comment->getValueEncoded("commentid");
        $rowCount++;
        ?>

        <tr<?php if ($rowCount % 2 == 0) echo ' class="alt"' ?>>

            <td><?php echo $comment->getValueEncoded("postid") ?></td>
            <td><?php echo $comment->getValueEncoded("commentid") ?></td>
            <td><?php echo $comment->getValueEncoded("body") ?></td>
            <td><?php echo $comment->getValueEncoded("postdate") ?></td>
            <!-- <td><a href="../editPost.php?postid=<?php echo $comment->getValueEncoded("postid") ?>&body=<?php echo $comment->getValueEncoded("postid") ?>"><?php echo $comment->getValueEncoded("postid") ?></a></td>-->

            <!--<td><a href="../deletePost.php?postid=<?php echo $comment->getValueEncoded("body") ?>&body=<?php echo $comment->getValueEncoded("body") ?>"><?php echo $comment->getValueEncoded("body") ?></a></td>-->
            <td><?php if ($userid == $comment->getValueEncoded("userid")) echo "<a href='editComment.php?commentid=$commentid'> edit this comment</a>";


                else echo "you can't edit another user's comment"; ?></td>

            <td><?php if ($userid == $comment->getValueEncoded("userid")) echo "<a href='deleteComment.php?commentid=$commentid'> delete this comment</a>";
                else echo "you can't delete another user's comment"; ?></td>
            <td><?php echo $comment->getValueEncoded("userid") ?></td>
        </tr>
        <?php

    }
    ?>
</table>

<!-- display forward and back links for pagination -->
<div style="width: 30em; margin-top: 20px; text-align: center;">
    <?php if ($start > 0) { ?>
        <a href="blog.php?start=<?php echo max($start - PAGE_SIZE, 0) ?>&amp;order=<?php echo $order ?>">Previous
            page</a>
    <?php } ?>
    &#160;
    <?php if ($start + PAGE_SIZE < $totalRows) { ?>
        <a href="blog.php?start=<?php echo min($start + PAGE_SIZE, $totalRows) ?>&amp;order=<?php echo $order ?>">Next
            page</a>
    <?php } ?>
</div>


<?php displayPageFooter(); ?>