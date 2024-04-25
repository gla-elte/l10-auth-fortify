<form action="{{ route('login') }}" method="post">
  @csrf
  <div>
    <label for="email">E-mail:</label>
    <input type="text" name="email" id="email">
  </div>
  <div>
    <label for="password">Jelszó:</label>
    <input type="password" name="password" id="password">
  </div>
  <div>
    <input type="submit" value="Bejelentkezés">
  </div>
</form>
