<?php

require($_SERVER["DOCUMENT_ROOT"]."/utils/UserUtils.php");
$userutils = new UserUtils();
$userutils->logOut();

?>
<html>
    <head>
        <title>rynet logout</title>
        <?php readfile($_SERVER["DOCUMENT_ROOT"]."/utils/imports.php") ?>
    </head>
    <body>
        <?php include($_SERVER["DOCUMENT_ROOT"]."/utils/header.php") ?>
        <main>
            <h2 class="centered">You have been logged out.</h2>
        </main>
    </body>
</html>
