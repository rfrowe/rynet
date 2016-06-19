<header>
    <a href="/"><h1 id="logo">rynet login</h1></a>
    <?php if($userutils->loggedIn()) { ?>
    <form action="/logout" id="logout_form">
        <button id="logout">log out</button>
    </form>
    <?php } ?>
</header>
