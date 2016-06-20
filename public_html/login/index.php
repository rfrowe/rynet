
<?php

require($_SERVER["DOCUMENT_ROOT"]."/utils/UserUtils.php");
$userutils = new UserUtils();

$failure = false;
$login = true;

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
        <?php readfile($_SERVER["DOCUMENT_ROOT"]."/utils/imports.php") ?>
        <script>
            $(function () {
                $("#login_form").find("input").focus(toggleLabels).blur(toggleLabels).change(toggleLabels);

                function toggleLabels() {
                    var $label = $("label[for='"+$(this).attr('id')+"']");
                    if($(this).val() === "" && $label.hasClass("small")) {
                       $label.removeClass("small");
                    } else if(!$label.hasClass("small")) {
                        $label.addClass("small");
                    }
                }
            })
        </script>
    </head>
    <body>
        <?php include($_SERVER["DOCUMENT_ROOT"]."/utils/header.php") ?>
        <main>
            <form method="post" class="centered" id="login_form">
                <label for="username">Username</label>
                <input type="text" name="username" id="username" />
                <label for="password">Password</label>
                <input type="password" name="password" id="password" />
                <?php if($failure) { ?>
                <div id="failure">Those credentials were invalid.</div>
                <?php } ?>
                <button id="login">Log In</button>
            </form>
        </main>
    </body>
</html>
