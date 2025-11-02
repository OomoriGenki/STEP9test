<!DOCTYPE html>
<html lang="ja">
<head>
    <meta charset="UTF-8">
    <title>新規ユーザー登録</title>
    <style>
        /* 例: シンプルなセンタリングスタイル */
        body { font-family: sans-serif; display: flex; justify-content: center; align-items: center; min-height: 100vh; background-color: #f4f4f4; }
        .container { background: white; padding: 20px; border-radius: 8px; box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); width: 400px; }
        .form-group { margin-bottom: 15px; }
        label { display: block; margin-bottom: 5px; font-weight: bold; }
        input[type="text"], input[type="email"], input[type="password"] { width: 100%; padding: 8px; box-sizing: border-box; border: 1px solid #ccc; border-radius: 4px; margin-bottom: 5px;}
        .name-group input { width: 49%; display: inline-block; } /* 姓と名を横並びにする調整 */
        button { padding: 10px 15px; background-color: #007bff; color: white; border: none; border-radius: 4px; cursor: pointer; }
        .links { text-align: center; margin-top: 10px; }
        .error-message { color: red; font-size: 0.9em; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Register</h2>

        <form method="POST" action="{{ route('register.post') }}">
            @csrf <div class="form-group">
                <label for="name">Name (ユーザ名)</label>
                <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus>
                @error('name')<span class="error-message">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>名前 (漢字)</label>
                <div class="name-group">
                    <input type="text" name="last_name_kanji" value="{{ old('last_name_kanji') }}" placeholder="姓" required>
                    <input type="text" name="first_name_kanji" value="{{ old('first_name_kanji') }}" placeholder="名" required>
                </div>
                @error('last_name_kanji')<span class="error-message">{{ $message }}</span>@enderror
                @error('first_name_kanji')<span class="error-message">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label>名前 (カナ)</label>
                <div class="name-group">
                    <input type="text" name="last_name_kana" value="{{ old('last_name_kana') }}" placeholder="セイ" required>
                    <input type="text" name="first_name_kana" value="{{ old('first_name_kana') }}" placeholder="メイ" required>
                </div>
                @error('last_name_kana')<span class="error-message">{{ $message }}</span>@enderror
                @error('first_name_kana')<span class="error-message">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="email">Email Address</label>
                <input id="email" type="email" name="email" value="{{ old('email') }}" required>
                @error('email')<span class="error-message">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <input id="password" type="password" name="password" required>
                @error('password')<span class="error-message">{{ $message }}</span>@enderror
            </div>

            <div class="form-group">
                <label for="password-confirm">Confirm Password</label>
                <input id="password-confirm" type="password" name="password_confirmation" required>
            </div>

            <div class="form-group">
                <button type="submit">Register</button>
            </div>
        </form>

        <div class="links">
            <a href="{{ route('login') }}">ログイン画面に戻る</a>
        </div>
    </div>
</body>
</html>