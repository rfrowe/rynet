<?php

require($_SERVER["DOCUMENT_ROOT"]."/utils/UserUtils.php");
$userutils = new UserUtils();
$userutils->logOut();

?>
<html>
    <head>
        <title>rynet logout</title>
    </head>
    <body>
        <?php include($_SERVER["DOCUMENT_ROOT"]."/utils/header.php") ?>
        <div class="centered">
            <h2>You have been logged out.</h2>
        </div>
    </body>
</html>
