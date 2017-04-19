<?php

require_once("../configFiles/common.inc.php");

$postid = isset($_REQUEST["postid"]) ? (int)$_REQUEST["postid"] : 0;
//$count = isset($_REQUEST["count"]) ? (int)$_REQUEST["count"] : 0;


list($comments, $totalRows) = Comment::getCommentsPerPost($startRow, $numRows, $order,$postid);
?>
<table class="tableStyle">
    <tr>

        <!-- allow user to set order by column-->

        <th><a href="#">postid</a></th>
        <th><a href="#">count</a></th>


    </tr>
    <?php
    $rowCount = 0;
    foreach ($comments as $comment) {
        $rowCount++;
        ?>

        <tr<?php if ($rowCount % 2 == 0) echo ' class="alt"' ?>>

            <td><?php echo $comment->getValueEncoded("postid") ?></td>
            <td><?php echo $comment->getValueEncoded("body") ?> comment(s)</td>


        </tr>
        <?php

    }
    ?>
</table>


