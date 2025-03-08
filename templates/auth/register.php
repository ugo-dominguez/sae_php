<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Inscription</title>
  <link rel="stylesheet" href="/assets/css/register.css">
</head>
<body>

<div class="register-wrapper">
  <h1 class="register-title">Inscription</h1>
  
  <form action="/register" method="POST" class="register-form">
    <!-- Identifiant -->
    <label for="username">Identifiant</label>
    <input 
      type="text" 
      id="username" 
      name="username" 
      placeholder="Perticoz" 
      required
    />

    <!-- Mot de passe -->
    <label for="password">Mot de passe</label>
    <input 
      type="password" 
      id="password" 
      name="password" 
      placeholder="************" 
      required
    />

    <!-- Confirmation du mot de passe -->
    <label for="confirm_password">Confirmation du mot de passe</label>
    <input 
      type="password" 
      id="confirm_password" 
      name="confirm_password" 
      placeholder="************" 
      required
    />

    <!-- Bouton s'inscrire -->
    <button type="submit">S'inscrire</button>
  </form>
</div>

</body>
</html>
