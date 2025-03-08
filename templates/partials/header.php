<header class="header">
    <div class="header__top">
        <img class="logo" src="/assets/images/logo.png" alt="Le Baratie">
        
        <div class="auth-buttons">
            <?php if (isset($_SESSION['user_id'])): ?>
                <!-- L'utilisateur est connecté -->
                <a href="/profile" class="btn"><strong>Profil</strong></a>
                <a href="/logout" class="btn"><strong>Déconnexion</strong></a>
            <?php else: ?>
                <!-- L'utilisateur n'est pas connecté -->
                <a href="/login" class="login btn"><strong>Connexion</strong></a>
                <a href="/register" class="signin btn"><strong>Inscription</strong></a>
            <?php endif; ?>
        </div>
    </div>
</header>
