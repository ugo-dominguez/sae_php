<main class="profile-page">
    <div class="profile">
        <img src="/assets/images/user.png" alt="User icon" class="main-image">
        <div>
            <h1><?= $user->username ?></h1>
        </div>
    </div>

    <div class="profile-content">
        <h2>Avis récents : </h2>

        <div class="reviews">
        <?php foreach ($reviews as $review): ?>
                <div class="review">
                    <div class="review-header">
                        <a href=""><?= $review->restaurant->name ?></a>
                        <div class="location">
                            <span class="material-symbols-rounded">location_on</span>
                            <p><?= $review->restaurant->getAddress() ?></p>
                        </div>
                    </div>
                    <div class="review-content">
                        <p><?= $review->comment ?></p>
                        <p><?= $review->getStars() ?></p>
                    </div>
                </div>

                <p class="separator">--------------------------------------------------------------------------------------------------</p>
            <?php endforeach; ?>
        </div>
    </div>
</main>