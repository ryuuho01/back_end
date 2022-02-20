<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

  <h1>パスワード変更を受付けました</h1>
  <br>
  <br>
  以下のURLをクリックしパスワードを変更してください。<br>
  <br>
  <a href="{{ config('app.url') }}/api/auth/reminder/<?php echo $token; ?>">パスワードを変更する</a><br>
  <br>
  クリック後、アプリからログインを行ってください。<br>
  <br>
  ※このURLは登録から30分間有効です。<br>
  <br>
  <span>Rese</span>

</body>

</html>