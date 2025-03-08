<div class="header__search">
    <div class="search-container">
        <h2>Résultats de la recherche</h2>
        <form action="/search" method="GET">
            <div class="search-bar">
                <input type="text" name="keywords" placeholder="Mots clés" value="<?= htmlspecialchars($_GET['keywords'] ?? '') ?>">
                <input type="text" name="city" placeholder="Où ?" value="<?= htmlspecialchars($_GET['city'] ?? 'Orléans') ?>">
                <select name="type">
                    <?php foreach ($restaurantTypes as $type): ?>
                        <option value="<?= htmlspecialchars($type) ?>" <?= (isset($_GET['type']) && $_GET['type'] == $type) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($type) ?>
                        </option>
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
</div>

<main class="restaurants">
    <?php if (!empty($restaurants)): ?>
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
    <?php else: ?>
        <div class="no-results">
            <span class="material-symbols-rounded">search_off</span>
            <h2>Aucun résultats !</h2>
        </div>
    <?php endif; ?>
</main>