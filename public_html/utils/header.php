<?php
if($userutils->loggedIn()) {
    $action = "/logout/";
    $text = "log out";
} else {
    $action = "/login/";
    $text = "log in";
}
?>
<header>
    <a href="/"><h1 id="logo"></h1></a>
    <form action="<?php echo $action ?>" id="logout_form" method="post">
        <button id="logout"><?php echo $text; ?></button>
    </form>
</header>
