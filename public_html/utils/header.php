<header>
    <h1 id="logo">rynet login</h1>
    <?php if($userutils->loggedIn()) { ?>
    <form action="/logout">
        <button>log out</button>
    </form>
    <?php } ?>
</header>
