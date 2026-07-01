<?php
/**
 * @var string $title    page title
 * @var string $content  the rendered view, already HTML-safe
 */
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title><?= htmlspecialchars($title) ?></title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: system-ui, sans-serif;
            line-height: 1.5;
            color: #1a1a1a;
        }
        header {
            background: #1a1a1a;
            padding: 1rem;
        }
        header nav a {
            color: #fff;
            text-decoration: none;
            margin-right: 1rem;
        }
        header nav a:hover { text-decoration: underline; }
        main {
            max-width: 800px;
            margin: 0 auto;
            padding: 1.5rem 1rem;
        }
        label {
            display: block;
            margin: 0.75rem 0 0.25rem;
            font-weight: 600;
        }
        input {
            display: block;
            width: 100%;
            max-width: 320px;
            padding: 0.4rem;
        }
        button {
            margin-top: 1rem;
            padding: 0.5rem 1rem;
            cursor: pointer;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        th, td {
            text-align: left;
            padding: 0.5rem;
            border-bottom: 1px solid #ddd;
        }
        .error {
            color: #c0392b;
            margin: 0.25rem 0;
            font-size: 0.9rem;
        }
        footer {
            max-width: 800px;
            margin: 2rem auto 1rem;
            padding: 1rem;
            border-top: 1px solid #ddd;
            color: #777;
            font-size: 0.9rem;
        }
    </style>
</head>
<body>
    <header>
        <nav>
            <a href="/">Home</a>
            <a href="/users">Users</a>
            <a href="/register">Register</a>
        </nav>
    </header>

    <main>
        <?= $content ?>
    </main>

    <footer>
        <p>Mini PHP CMS</p>
    </footer>
</body>
</html>
