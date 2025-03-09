<div class="login-page">
  <h1 class="login-title">Connexion</h1>
  <div class="login-wrapper">
    <form method="POST" action="/login" class="login-form">
      <label for="username">Identifiant</label>
      <input 
        type="text" 
        id="username" 
        name="username" 
        placeholder="Votre identifiant" 
        required
      />

      <label for="password">Mot de passe</label>
      <input 
        type="password" 
        id="password" 
        name="password" 
        placeholder="********" 
        required
      />

      <button type="submit">Se connecter</button>
    </form>
  </div>
</div>
