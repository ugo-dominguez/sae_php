<h1>Inscription</h1>
<?php if (isset($error)): ?>
    <p style="color:red;"><?= htmlspecialchars($error) ?></p>
<?php endif; ?>
<form method="POST" action="/register">
    <input type="text" name="username" placeholder="Identifiant" required>
    <input type="password" name="password" placeholder="Mot de passe" required>
    <input type="password" name="confirm_password" placeholder="Confirmer le mot de passe" required>
    <button type="submit">S'inscrire</button>
</form>
