<?php session_start(); ?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Link Minimal</title>
    <link rel="stylesheet" href="res/css/home.css">
</head>
<body>
    <h1>Link Minimal</h1>

    <form action="/link" method="POST">
        <div>
            <label for="url">URL: </label>
            <input type="text" name="url" id="url" required/>
        </div>

        <div>
            <input type="submit" value="Generate" />
        </div>
    </form>

    <?php if (isset($_SESSION['success'])): ?>
        <div class="alert-success">
            <p><?php echo $_SESSION['success']; ?></p>
            <?php if (isset($_SESSION['shortenedUrl'])): ?>
                <?php
                    $protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off' ? 'https' : 'http';
                    $baseUrl = $protocol . '://' . $_SERVER['HTTP_HOST'] . '/' . $_SESSION['shortenedUrl'];
                ?>
                <p><strong>Shortened link:</strong> <a href="<?=$baseUrl?>"><?=$baseUrl?></a></p>
            <?php endif; ?>
        </div>
        <?php unset($_SESSION['success']); unset($_SESSION['shortenedUrl']); ?>
    <?php elseif (isset($_SESSION['error'])): ?>
        <div class="alert-error">
            <p><?php echo $_SESSION['error']; ?></p>
        </div>
        <?php unset($_SESSION['error']); ?>
    <?php endif; ?>

</body>
</html>
