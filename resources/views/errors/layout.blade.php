<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>@yield('title')</title>

    <!-- Styles -->
    <style>
            html, body {
              background-color: #fff;
              color: #636b6f;
              font-family: ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, "Noto Sans", sans-serif, "Apple Color Emoji", "Segoe UI Emoji", "Segoe UI Symbol", "Noto Color Emoji";
              font-weight: 100;
              height: 100vh;
              margin: 0;
            }

            .full-height {
              height: 100vh;
            }

            .flex-center {
              align-items: center;
              display: flex;
              justify-content: center;
            }

            .position-ref {
              position: relative;
            }

            .content {
              text-align: center;
            }

            .title {
              font-size: 36px;
            }
            .code {
              font-size: 10rem;
              font-weight: bold;
            }
            .btn-back {
              margin-top: 10px;
              color: white;
              background-color: #636b6f;
              padding: 10px 20px;
              border: 1px solid #636b6f;
              transition: background-color 0.3s, color 0.3s;
            }
            a {
              text-decoration: none;
            }
            .btn-back:hover {
              cursor: pointer;
              background-color: white;
              color: #636b6f;
            }
    </style>
  </head>
  <body>
    <div class="flex-center position-ref full-height">
      <div class="content">
        <div class="code">
        @yield('code')
        </div>
        <div class="title">
          @yield('message')
        </div>
        <a href="/">
          <div class="btn-back">
            @lang('Go home')
          </div>
        </a>
      </div>
    </div>
  </body>
</html>
