<header>
    <div class="header-container">
        <div class="logo">
            <a href="/"><img src="/assets/images/logo-jouetopia.png" alt="Logo JoueTopia"></a>
        </div>
        <div class="search-bar">
            <form action="/search.php" method="post" class="header-form">
                <label for="__name"></label><input type="text" placeholder="Rechercher" class="rounded-input  search-bar-input" id="__name" name="__name">
                <button type="submit" class="button-search">
                    <img src="/assets/images/chercher.png" alt="Rechercher">
                </button>
            </form>
        </div>
        <a href="/products.php" class="button-connect">Nos produits</a>
        <div class="connexion">
            <?php
            echo array_key_exists('auth', $_SESSION) ?
            $_SESSION['auth']->role == 'admin'? '<a href="/admin/index.php" class="button-connect">Administration</a><a href="/account/account.php" class="button-connect">Mon compte</a>' :
            '<a href="/account/account.php" class="button-connect">Mon compte</a>' :
            '<a href="/account/login.php" class="button-connect">Se connecter</a>';
            ?>
        </div>
        <div class="panier">
            <a href="/achat/panier.php">
                <img src="/assets/images/panier.png" alt="Panier">
            </a>
        </div>
    </div>
</header>
<div id="__body">