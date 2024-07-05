<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Photos</title>
    <link rel="stylesheet" href="styles.css">

</head>
<style>

</style>
<body>
<header>
    <a href="/">
        <h1>Gallery</h1>
    </a>

    <?php use app\core\Application;

    if (Application::isGuest()): ?>
        <ul>
            <li>
                <a href="/wishlist">Wishlist</a>
            </li>
            <li>
                <a href="/login">Login</a>
            </li>
            <li>
                <a href="/register">Register</a>
            </li>
        </ul>
    <?php else: ?>
        <ul>
            <li>
                <a href="/wishlist">Wishlist</a>
            </li>
            <li>
                <a href="/upload">Add image</a>
            </li>
            <li>
                <a href="/my-photos">My photos</a>
            </li>


            <li>
                <a href="/logout">
                    <strong>Welcome <?php echo Application::$app->user->getDisplayName() ?> (Logout)</strong>
                </a>
            </li>
        </ul>

    <?php endif; ?>
</header>

<main>
    {{content}}
</main>

<footer>
    <p>&copy; Since 2024 |</p>
    <p>This website was designed with<b>php |</b></p>
    <?php if (Application::isGuest()): ?>
        <a href="/login">Login</a>
        <a href="/register">Registration</a>
    <?php else: ?>
        <a href="/upload">Add image</a>
        <a href="/my-photos">My photos</a>
        <a href="/wishlist">Wishlist</a>
    <?php endif; ?>

</footer>

<script src="scripts.js"></script>

</body>
</html>
