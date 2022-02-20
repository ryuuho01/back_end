<!DOCTYPE html>
<html>

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
</head>

<body>

  <h1>ご利用ありがとうございます。</h1>
  <br>
  登録を受け付けました。<br>
  <br>
  以下のURLをクリックすると登録が完了します。<br>
  <br>
  <a href="{{ config('app.url') }}/api/auth/verify/<?php echo $token; ?>">登録を完了する</a><br>
  <br>
  クリック後、アプリからログインを行ってください。<br>
  <br>
  ※このURLは登録から30分間有効です。<br>
  <br>
  <span>Rese</span>

</body>

</html>