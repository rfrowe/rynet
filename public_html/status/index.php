<?php

require($_SERVER["DOCUMENT_ROOT"]."/utils/UserUtils.php");
$userutils = new UserUtils();
$userutils->secure();

?>
<html>
    <head>
        <title>rynet status</title>
        <?php readfile($_SERVER["DOCUMENT_ROOT"]."/utils/imports.php") ?>
    </head>
    <body>
        <?php include($_SERVER["DOCUMENT_ROOT"]."/utils/header.php") ?>
        <main>
            <h2>Welcome, <?php echo $userutils->username() ?></h2>
        </main>
    </body>
</html>
