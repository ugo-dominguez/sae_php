<div class="header__main">
    <img src="/assets/images/burger.png" alt="Burger et frites" class="header__img-left">
    <div class="search-container">
        <h2>Où allez vous manger ?</h2>
        <form action="/search" method="GET">
            <div class="search-bar">
                <input type="text" name="keywords" placeholder="Mots clés">
                <input type="text" name="city" placeholder="Où ?">
                <select name="type">
                    <?php foreach ($restaurantTypes as $type): ?>
                        <option value="<?= htmlspecialchars($type) ?>"><?= htmlspecialchars($type) ?></option>
                    <?php endforeach; ?>
                </select>
                <div>
                    <button type="submit" class="search-btn">
                        <span class="material-symbols-rounded">search</span>
                    </button>
                </div>
            </div>
        </form>
    </div>
    <img src="/assets/images/plat.png" alt="Brochettes et salade" class="header__img-right">
</div>

<main class="restaurants">
    <h2 class="hline">Restaurants à la une</h2>
    <div class="restaurant-list">
        <?php foreach ($restaurants as $restaurant): ?>
            <div class="restaurant-card">
                <img src="/assets/images/baratie.jpg" alt="<?= htmlspecialchars($restaurant->name) ?>">
                <h3><?= htmlspecialchars($restaurant->name) ?></h3>
                <p><?= htmlspecialchars($restaurant->getAddress()) ?></p>
                
                <?php if (isset($restaurant->schedule)): ?>
                    <?php if ($restaurant->isCurrentlyOpen()): ?>
                        <p><span style="color: green;">Ouvert</span> • Ferme à <?= htmlspecialchars($restaurant->whenWillClose()) ?></p>
                    <?php else: ?>
                        <p><span style="color: red;">Fermé</span> • Ouvre à <?= htmlspecialchars($restaurant->whenWillOpen()) ?></p>
                    <?php endif; ?>
                <?php endif; ?>

                <?php if ($restaurant->accessible): ?>
                    <span class="badge green">Accessible</span>
                <?php endif; ?>
                
                <?php if ($restaurant->delivery): ?>
                    <span class="badge">Livraison</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>
</main>