<div class="register-page">
  <h1 class="register-title">Inscription</h1>
  <div class="register-wrapper">
    <form action="/register" method="POST" class="register-form">
      <label for="username">Identifiant</label>
      <input 
        type="text" 
        id="username" 
        name="username" 
        placeholder="Perticoz" 
        required
      />

      <label for="password">Mot de passe</label>
      <input 
        type="password" 
        id="password" 
        name="password" 
        placeholder="************" 
        required
      />

      <label for="confirm_password">Confirmation du mot de passe</label>
      <input 
        type="password" 
        id="confirm_password" 
        name="confirm_password" 
        placeholder="************" 
        required
      />

      <button type="submit">S'inscrire</button>
    </form>
  </div>
</div>
