<main class="profile-page">
    <div class="profile">
        <img src="/assets/images/user.png" alt="User icon" class="main-image">
        <div>
            <h1><?= $user->getUsername() ?></h1>
        </div>
    </div>

    <div class="profile-content">
        <h2>Avis récents : </h2>

        <div class="reviews">
            <div class="review">
                <div class="review-header">
                    <a href="">Crêperie bretonne</a>
                    <div class="location">
                        <span class="material-symbols-rounded">location_on</span>
                        <p>244 Rue de Bourgogne, 45000 Orléans  </p>
                    </div>
                </div>
                <div class="review-content">
                    <p>Super restaurant ^^</p>
                    <p>★★★★★</p>
                </div>
            </div>

            <p class="separator">--------------------------------------------------------------------------------------------------</p>
        </div>

    </div>
</main>