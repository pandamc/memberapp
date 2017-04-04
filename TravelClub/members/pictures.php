<?php
require_once("../configFiles/common.inc.php");
checkLogin();
displayNavBar();
displayPageHeader( "Upload pictures here", true );
?>
    <form action="../upload.php" method="post" enctype="multipart/form-data">
        <div class="tableStyle">
        Select image to upload:
            <br>
        <input type="file" name="fileToUpload" id="fileToUpload">
        <input type="submit" value="Upload Image" name="submit">
            </div>
    </form>
<?php displayPageFooter(); ?>