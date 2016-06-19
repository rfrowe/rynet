
<?php

require($_SERVER["DOCUMENT_ROOT"]."/utils/UserUtils.php");
$userutils = new UserUtils();

$failure = false;

if(isset($_POST["username"]) && isset($_POST["password"])) {
    if($userutils->logIn($_POST["username"], $_POST["password"])) {
        header("Location: /");
    } else {
        $failure = true;
    }
} else if($userutils->loggedIn()) {
    header("Location: /");
}

?>
<html>
    <head>
        <title>rynet login</title>
    </head>
    <body>
        <?php include($_SERVER["DOCUMENT_ROOT"]."/utils/header.php") ?>
        <div class="centered">
            <form method="post">
                <input type="text" name="username" />
                <input type="password" name="password" />
                <?php if($failure) { ?>
                <div id="failure">Those credentials were invalid.</div>
                <?php } ?>
                <button>Log In</button>
            </form>
        </div>
    </body>
</html>
