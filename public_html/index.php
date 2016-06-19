<?php

require($_SERVER["DOCUMENT_ROOT"]."/utils/UserUtils.php");
$userutils = new UserUtils();

if($userutils->loggedIn()) {
    echo "Welcome, " . $userutils->name();
} else {
    redirectLogin();
}

function redirectLogin() {
    header("Location: /login/");
}

