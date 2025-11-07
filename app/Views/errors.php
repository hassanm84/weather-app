<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Error <?= htmlspecialchars($errorCode) ?></title>
    <link rel="stylesheet" href="/css/error.css">
</head>

<body>
    <h1>Error <?= htmlspecialchars($errorCode) ?></h1>
    <p><?= htmlspecialchars($errorMessage) ?></p>
    <a href="/">Return to Home Page</a>
</body>

</html>