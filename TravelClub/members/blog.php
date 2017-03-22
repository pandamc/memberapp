<?php
require_once("../common.inc.php");
require_once("../config.php");
require_once("../Member.class.php");
require_once("../LogEntry.class.php");
require_once("../Blog.Class.php");
checkLogin();
$start = isset($_GET["start"]) ? (int)$_GET["start"] : 0;
// get column to order results by
$order = isset($_GET["order"]) ? preg_replace("/[^a-zA-Z]/", "", $_GET["order"]) : "user_id";

list($posts, $totalRows) = Blog::getPosts($start, PAGE_SIZE, $order);
displayPageHeader("View recent blog posts", true);



?>
<!-- display all blog posts -->
    <table style="width: 30em; border: 1px solid #666;">
        <tr>
            <th>User id</th>
            <th>Blog Post ID</th>
            <th>Blog text</th>
        </tr>
        <?php
        $rowCount = 0;
        foreach ($posts as $post) {
            $rowCount++;
            ?>

            <tr<?php if ($rowCount % 2 == 0) echo ' class="alt"' ?>>
                <td><?php echo $post->getValueEncoded("user_id") ?></td>
                <td><?php echo $post->getValueEncoded("post_id") ?></td>
                <td><?php echo $post->getValueEncoded("body") ?></td>
            </tr>
            <?php

        }
        ?>
    </table>





<?php displayPageFooter(); ?>