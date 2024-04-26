<h1>Bejelentkezés a hitelesítési alkalmazásból kapott 6 számjegyű kóddal</h1>
<form method="POST" action="/two-factor-challenge">
    @csrf
    <label for="code">Kód:</label>
    <input id="code" type="text" name="code" />
    @error('code')
      <div>{{ $message }}</div>
    @enderror
    <button>Bejelentkezés</button>
</form>

<h1>Bejelentkezés egy érvényes helyreállítási kóddal</h1>
<form method="POST" action="/two-factor-challenge">
  @csrf
  <label for="recovery_code">Kód:</label>
  <input id="recovery_code" type="text" name="recovery_code" />
  @error('recovery_code')
    <div>{{ $message }}</div>
  @enderror
  <button>Bejelentkezés</button>
</form>
