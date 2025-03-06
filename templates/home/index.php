<?php

use App\Config\Requests;

Requests::getConnection();
$restaurants = Requests::getRestaurants(5);

?>

<div class="header__main">
    <img src="/assets/images/burger.png" alt="Burger et frites" class="header__img-left">
    <div class="search-container">
        <h2>Où allez vous manger ?</h2>
        <div class="search-bar">
            <input type="text" placeholder="Mots clés" value="">
            <input type="text" placeholder="Où ?" value="">
            <select>
                <option>Restaurant</option>
                <option>Fast-Food</option>
                <option>Bar</option>
                <option>Café</option>
                <option>Pub</option>
                <option>Glaces</option>
            </select>
            <div>
                <button class="search-btn">
                    <span class="material-symbols-rounded">search</span>
                </button>
            </div>
        </div>
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

            <?php if ($restaurant->isCurrentlyOpen()): ?>
                <p><span style="color: green;">Ouvert</span> • Ferme à</p>
            <?php else: ?>
                <p><span style="color: red;">Fermé</span> • Ouvre à</p>
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