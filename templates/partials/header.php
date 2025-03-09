<header class="header">
    <div class="header__top">
        <a class="home-button" href="/">
            <img class="logo" src="/assets/images/logo.png" alt="Le Baratie">
        </a>
        
        <div class="auth-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- L'utilisateur est connecté -->
                <a href="/profile/<?= $_SESSION['user_id'] ?>" class="account-btn">account_circle</a>
                <a href="/logout" class="logout btn"><strong>Déconnexion</strong></a>
            <?php else: ?>
                <!-- L'utilisateur n'est pas connecté -->
                <a href="/login" class="login btn"><strong>Connexion</strong></a>
                <a href="/register" class="signin btn"><strong>Inscription</strong></a>
            <?php endif; ?>
        </div>
    </div>
</header>
