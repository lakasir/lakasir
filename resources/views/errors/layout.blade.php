@php
    $code = trim($__env->yieldContent('code'));
    $title = trim($__env->yieldContent('title'));
    $message = trim($__env->yieldContent('message'));

    $code = match ($code) {
        '' => '500',
        default => $code,
    };

    $status = match ($code) {
        '401' => 'You need to sign in to see this.',
        '402' => 'This page needs a payment on file.',
        '403' => 'You do not have access to this page.',
        '404' => 'The page you were looking for is not here.',
        '419' => 'The page sat idle too long. Reload and try again.',
        '429' => 'Too many requests. Wait a moment and try again.',
        '503' => 'Lakasir is down for maintenance and will be back shortly.',
        default => 'Something went wrong on our side.',
    };
@endphp
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="robots" content="noindex">

  <title>{{ $code }} — {{ $title ?: __('Error') }}</title>

  <link rel="shortcut icon" type="image/x-icon" href="/assets/logo/image.png">

  {{-- Self-contained on purpose: an error page must not depend on the built
       asset manifest, or a failed build hides the error behind a second one. --}}
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

    /* The same 24px hairline blueprint grid the panel and auth pages use. */
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

    /* Corner brackets, masked down to the four corners. */
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
      letter-spacing: -0.01em;
      color: var(--ui-text);
      text-decoration: none;
    }

    .brand img {
      width: 1.75rem;
      height: 1.75rem;
      object-fit: contain;
    }

    .code {
      font-size: 4.5rem;
      font-weight: 700;
      line-height: 1;
      letter-spacing: -0.04em;
      color: var(--ui-primary);
      font-variant-numeric: tabular-nums;
    }

    .title {
      margin: 1.25rem 0 0;
      font-size: 1.25rem;
      font-weight: 600;
      letter-spacing: -0.01em;
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

    .actions {
      display: flex;
      flex-wrap: wrap;
      justify-content: center;
      gap: 0.5rem;
    }

    .btn {
      display: inline-flex;
      align-items: center;
      justify-content: center;
      gap: 0.375rem;
      padding: 0.5rem 1rem;
      border: 1px solid transparent;
      font: inherit;
      font-size: 0.875rem;
      font-weight: 600;
      text-decoration: none;
      cursor: pointer;
      transition: background-color 0.12s, border-color 0.12s, color 0.12s;
    }

    .btn-primary {
      background-color: var(--ui-primary);
      color: #fff;
    }

    .btn-primary:hover {
      background-color: #e65c00;
    }

    .btn-ghost {
      border-color: var(--ui-rule);
      color: var(--ui-text);
    }

    .btn-ghost:hover {
      background-color: var(--ui-rule-soft);
    }

    .ref {
      margin-top: 1.5rem;
      font-size: 0.75rem;
      color: var(--ui-text-muted);
      font-variant-numeric: tabular-nums;
      word-break: break-all;
    }

    @media (prefers-reduced-motion: reduce) {
      .btn {
        transition: none;
      }
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

    <p class="code">{{ $code }}</p>

    <h1 class="title">{{ $title ?: __('Error') }}</h1>

    <p class="message">{{ $status }}</p>

    @if ($message !== '' && $message !== $title)
      <p class="message">{{ $message }}</p>
    @endif

    <div class="rule"></div>

    <div class="actions">
      <a href="/" class="btn btn-primary">{{ __('Go home') }}</a>
      <button type="button" class="btn btn-ghost" onclick="history.back()">{{ __('Go back') }}</button>
    </div>

    @if (config('app.debug') && isset($exception) && $exception?->getMessage())
      <p class="ref">{{ $exception->getMessage() }}</p>
    @endif
  </main>
</body>

</html>
