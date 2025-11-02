<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>@yield('title', 'TNGマーケット')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <header class="d-flex flex-wrap justify-content-center py-3 px-4 mb-4 bg-white border-bottom shadow-sm">
        <a href="{{ route('product.home') }}" class="d-flex align-items-center me-md-auto text-decoration-none ms-4">
            <h3 class="my-0 text-dark fw-bold">Cytech EC</h3>
        </a>

        <div class="d-flex align-items-center me-4">
            @auth
                <a class="text-decoration-none me-3 text-dark" href="{{ route('product.home') }}">Home</a>
                <a class="text-decoration-none me-3 text-dark" href="{{ route('mypage') }}">マイページ</a>

                <div class="me-3 text-dark small">
                    ログインユーザー: <span class="fw-bold">{{ Auth::user()->name }}</span>
                </div>

                <a class="btn btn-danger btn-sm" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    ログアウト
                </a>

                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            @else
                <a class="btn btn-outline-secondary btn-sm me-2" href="{{ route('login') }}">ログイン</a>
                @if (Route::has('register'))
                    <a class="btn btn-primary btn-sm" href="{{ route('register') }}">新規登録</a>
                @endif
            @endauth
        </div>
    </header>

    <div class="container">
        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="row">
            <div class="col-10 mx-auto">
                @yield('content')
            </div>
        </div>
    </div>

    <footer class="text-center py-3 mt-5 bg-light border-top">
        <div class="mb-3">
            <a class="btn btn-primary" href="{{ route('contact.form') }}">お問い合わせ</a>
        </div>

        <a class="text-decoration-none small text-dark me-3" href="{{ route('product.home') }}">Home</a>
        <a class="text-decoration-none small text-dark" href="{{ route('mypage') }}">マイページ</a>

        <p class="text-muted small mt-3 mb-0">&copy; 2024 Company, Inc</p>
    </footer>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
            integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>
