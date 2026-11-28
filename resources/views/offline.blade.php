@php
    $code = '503';
    $title = __('You are offline');
    $status = 'Lakasir cannot reach the network. Reconnect and the page will reload itself.';
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex">

  <title>{{ $code }} — {{ $title }}</title>

  <link rel="shortcut icon" type="image/x-icon" href="/assets/logo/image.png">

  {{-- Served by the service worker, so it must not depend on the Vite manifest:
       an offline build is exactly when that manifest may be unreachable. --}}
  <style>
    :root {
      color-scheme: light dark;

      --ui-primary: #ff6600;
      --ui-surface: #ffffff;
      --ui-surface-2: #f6f4f1;
      --ui-rule: #ece8e2;
      --ui-rule-soft: #f6f4f1;
      --ui-text: #1c1917;
      --ui-text-muted: #78716c;
      --ui-blueprint-line: rgb(23 23 23 / 0.05);
      --ui-corner-mark: #ddd7ce;
      --ui-corner-mark-size: 12px;
    }

    @media (prefers-color-scheme: dark) {
      :root {
        --ui-surface: #2a2521;
        --ui-surface-2: #14120f;
        --ui-rule: #332e28;
        --ui-rule-soft: #3d3730;
        --ui-text: #f5f3f0;
        --ui-text-muted: #a8a29e;
        --ui-blueprint-line: rgb(255 255 255 / 0.05);
        --ui-corner-mark: rgb(255 255 255 / 0.2);
      }
    }

    *,
    *::before,
    *::after {
      box-sizing: border-box;
    }

    html,
    body {
      height: 100%;
      margin: 0;
    }

    body {
      display: grid;
      place-items: center;
      padding: 1.5rem;
      background-color: var(--ui-surface-2);
      color: var(--ui-text);
      font-family: Figtree, ui-sans-serif, system-ui, -apple-system, "Segoe UI", Roboto, sans-serif;
      -webkit-font-smoothing: antialiased;
    }

    .grid {
      position: fixed;
      inset: 0;
      z-index: -1;
      pointer-events: none;
      background:
        linear-gradient(to right, transparent 11.5px, var(--ui-blueprint-line) 11.5px 12.5px, transparent 12.5px) center / 24px 24px repeat,
        linear-gradient(to bottom, transparent 11.5px, var(--ui-blueprint-line) 11.5px 12.5px, transparent 12.5px) center / 24px 24px repeat;
    }

    .card {
      position: relative;
      width: 100%;
      max-width: 30rem;
      padding: 2.5rem 2rem;
      background-color: var(--ui-surface);
      border: 1px solid var(--ui-rule);
      text-align: center;
    }

    @supports (mask-composite: intersect) {
      .card::after {
        content: '';
        position: absolute;
        inset: 0;
        pointer-events: none;
        border: 1px solid var(--ui-corner-mark);
        -webkit-mask-image:
          linear-gradient(to right, #000 0 var(--ui-corner-mark-size), transparent var(--ui-corner-mark-size) calc(100% - var(--ui-corner-mark-size)), #000 calc(100% - var(--ui-corner-mark-size)) 100%),
          linear-gradient(to bottom, #000 0 var(--ui-corner-mark-size), transparent var(--ui-corner-mark-size) calc(100% - var(--ui-corner-mark-size)), #000 calc(100% - var(--ui-corner-mark-size)) 100%);
        mask-image:
          linear-gradient(to right, #000 0 var(--ui-corner-mark-size), transparent var(--ui-corner-mark-size) calc(100% - var(--ui-corner-mark-size)), #000 calc(100% - var(--ui-corner-mark-size)) 100%),
          linear-gradient(to bottom, #000 0 var(--ui-corner-mark-size), transparent var(--ui-corner-mark-size) calc(100% - var(--ui-corner-mark-size)), #000 calc(100% - var(--ui-corner-mark-size)) 100%);
        -webkit-mask-composite: source-in;
        mask-composite: intersect;
      }
    }

    .brand {
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      margin-bottom: 2rem;
      font-size: 0.9375rem;
      font-weight: 700;
      color: var(--ui-text);
      text-decoration: none;
    }

    .brand img {
      width: 1.75rem;
      height: 1.75rem;
      object-fit: contain;
    }

    .icon {
      display: grid;
      place-items: center;
      width: 3rem;
      height: 3rem;
      margin: 0 auto 1.25rem;
      color: var(--ui-primary);
      border: 1px solid var(--ui-rule);
    }

    .icon svg {
      width: 1.5rem;
      height: 1.5rem;
    }

    .title {
      margin: 0;
      font-size: 1.25rem;
      font-weight: 600;
    }

    .message {
      margin: 0.5rem 0 0;
      font-size: 0.875rem;
      line-height: 1.6;
      color: var(--ui-text-muted);
    }

    .rule {
      height: 1px;
      margin: 1.75rem 0;
      background-color: var(--ui-rule-soft);
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      padding: 0.5rem 1rem;
      border: 1px solid transparent;
      font: inherit;
      font-size: 0.875rem;
      font-weight: 600;
      cursor: pointer;
      background-color: var(--ui-primary);
      color: #fff;
    }

    .btn:hover {
      background-color: #e65c00;
    }

    .state {
      margin-top: 1rem;
      font-size: 0.75rem;
      color: var(--ui-text-muted);
    }
  </style>
</head>

<body>
  <div class="grid"></div>

  <main class="card">
    <a href="/" class="brand">
      <img src="/assets/logo/image.png" alt="">
      <span>{{ config('app.name', 'Lakasir') }}</span>
    </a>

    <div class="icon">
      <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" aria-hidden="true">
        <path stroke-linecap="round" stroke-linejoin="round" d="M12 18.75a6 6 0 0 0 6-6v-1.5m-6 7.5a6 6 0 0 1-6-6v-1.5m6 7.5v3.75m-3.75 0h7.5M3 3l18 18" />
      </svg>
    </div>

    <h1 class="title">{{ $title }}</h1>

    <p class="message">{{ $status }}</p>

    <div class="rule"></div>

    <button type="button" class="btn" onclick="location.reload()">{{ __('Try again') }}</button>

    <p class="state" id="state">{{ __('Waiting for a connection…') }}</p>
  </main>

  <script>
    // Only reload on a real offline -> online transition. Reloading whenever
    // `navigator.onLine` is true would spin forever for a visitor who reached
    // this page while online (cached fallback, or a manual visit).
    window.addEventListener('online', () => location.reload());
  </script>
</body>

</html>
