<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Redirecting...</title>
    <link rel="stylesheet" href="res/css/redirect.css">
    <meta http-equiv="refresh" content="5;url=<?= htmlspecialchars($url)?>">
</head>
<body>
<h1>Redirecting...</h1>
<p>You will be redirected to the link below in 5 seconds:</p>
<a href="<?= htmlspecialchars($url)?>">
    <?= htmlspecialchars($url)?>
</a>
</body>
</html>
