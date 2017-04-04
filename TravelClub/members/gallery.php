
<?php
require_once("../configFiles/common.inc.php");

checkLogin();
displayNavBar();

?>
<div id="wrapper">
    <div id="content">

        <div id="main">
            <h3>Gallery</h3>
            <div id="targetContent">
                <?php

                $files = glob("../members/uploads/*.*");

                for ($i=0; $i<count($files); $i++)

                {

                    $image = $files[$i];
                    $supported_file = array(
                        'gif',
                        'jpg',
                        'jpeg',
                        'png'
                    );

                    $ext = strtolower(pathinfo($image, PATHINFO_EXTENSION));
                    if (in_array($ext, $supported_file)) {
                        print "<br />";
                        echo '<img src="'.$image .'" height="200" width="200" alt="Random image" />'."<br /><br />";
                        echo $image . "<br>";
                    } else {
                        echo $image . "IS NOT SUPPORTED!<br>";
                        continue;
                    }

                }


                $dirname = "your photos";
                $images = glob($dirname."*.jpg");
                foreach($images as $image) {
                    echo '<img src="'.$image.'" /><br />';
                }

                ?>
            </div>
        </div>
    </div>
</div>
<?php displayPageFooter(); ?>