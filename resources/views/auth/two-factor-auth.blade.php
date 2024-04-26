{{-- 1. rész --}}
<h1>Két faktoros hitelesítés részetei</h1>
<h2>Itt frissítheti a bejelentkezési beállításait.</h2>
@if ( session('status') == 'two-factor-authentication-enabled' )
    <div>
        A két faktoros hitelesítés (2FA) engedélyezve van.
    </div>
@elseif ( session('status') == 'two-factor-authentication-disabled' )
    <div>
        A két faktoros hitelesítés (2FA) nincs engedélyezve.
    </div>
@endif

{{-- 2. rész --}}
@if ( ! auth()->user()->two_factor_secret )
  <form method="post" action="{{ route('two-factor.enable') }}">
    @csrf
    <div>
      <button>2FA: Engedélyez</button>
    </div>
  </form>
@endif

{{-- 3. rész --}}
@if ( auth()->user()->two_factor_secret )
  {{-- QR kód a hitelesítési mobil alkalmazásnak --}}
  <div>
    {!! auth()->user()->twoFactorQrCodeSvg() !!}
  </div>

  {{-- Helyreállítási kódok és újragenerálási lehetőségük --}}
  <ul>
    @foreach ( auth()->user()->recoveryCodes() as $code)
      <li>{{ $code }}</li>
    @endforeach
  </ul>
  <form method="post" action="/user/two-factor-recovery-codes">
    @csrf
    <div>
      <button>2FA: Helyreállítási kódokat újragenerál</button>
    </div>
  </form>

  {{-- Letiltás --}}
  <form method="post" action="{{ route('two-factor.disable') }}">
    @csrf
    @method('delete')
    <div>
      <button>2FA: Letilt</button>
    </div>
  </form>
@endif
