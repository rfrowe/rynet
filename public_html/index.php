<?php

require($_SERVER["DOCUMENT_ROOT"]."/utils/UserUtils.php");
$userutils = new UserUtils();

$userutils->secure("redirectLogin");

function redirectLogin() {
    header("Location: /status/");
}
