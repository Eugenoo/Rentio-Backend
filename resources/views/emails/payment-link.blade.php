<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Dokończ płatność</title>
</head>
<body style="font-family: Arial, sans-serif; line-height:1.5;">
<h2>Dokończ płatność</h2>

<p>Cześć,</p>
<p>Kliknij w poniższy link, aby dokończyć swoją płatność:</p>

<p>
    <a href="{{ $link }}" style="background-color:#4CAF50;color:white;padding:10px 20px;text-decoration:none;border-radius:5px;">
        Zapłać teraz
    </a>
</p>

<p>Link wygasa za 24 godziny.</p>
<p>Pozdrawiamy,<br>{{ config('app.name') }}</p>
</body>
</html>
