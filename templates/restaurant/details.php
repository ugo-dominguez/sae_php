<main class="restaurant-details">
    <div class="top-section">
        <div class="hero">
            <img src="/assets/images/baratie.jpg" alt="<?= htmlspecialchars($restaurant->name) ?>" class="main-image">
        </div>

        <div class="restaurant-info">
            <h1><?= htmlspecialchars($restaurant->name) ?></h1>
            <p class="address">
                <span class="material-symbols-rounded">location_on</span> 
                <?= htmlspecialchars($restaurant->getAddress()) ?>
            </p>
            
            <?php if (isset($restaurant->schedule)): ?>
                <div class="hours-info">
                    <p class="status">
                        <?php if ($restaurant->isCurrentlyOpen()): ?>
                            <span style="color: green;">Ouvert</span> • Ferme à <?= htmlspecialchars($restaurant->whenWillClose()) ?>
                        <?php else: ?>
                            <span style="color: red;">Fermé</span> • Ouvre à <?= htmlspecialchars($restaurant->whenWillOpen()) ?>
                        <?php endif; ?>
                    </p>
                    
                    <div class="schedule">
                        <?php foreach ($restaurant->schedule as $day => $hours): ?>
                            <p><?= htmlspecialchars($day) ?>: 
                                <?php foreach ($hours as $time): ?>
                                    <?= htmlspecialchars($time) ?> 
                                <?php endforeach; ?>
                            </p>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>
            
            <div class="contact-info">
                <?php if (isset($restaurant->website)): ?>
                    <p class="website">
                        <span class="material-symbols-rounded">language</span> 
                        <a href="<?= htmlspecialchars($restaurant->website) ?>" target="_blank"><?= htmlspecialchars($restaurant->website) ?></a>
                    </p>
                <?php endif; ?>
                
                <?php if (isset($restaurant->phone)): ?>
                    <p class="phone">
                        <span class="material-symbols-rounded">call</span> 
                        <?= htmlspecialchars($restaurant->phone) ?>
                    </p>
                <?php endif; ?>
            </div>

            <?php if ($restaurant->accessible): ?>
                <span class="badge green">Accessible</span>
            <?php endif; ?>
            
            <?php if ($restaurant->delivery): ?>
                <span class="badge">Livraison</span>
            <?php endif; ?>
        </div>
    </div>

    <hr class="divider">

    <div class="reviews">
        <h2>Avis</h2>
        
    </div>
</main>