<?php
// Если store не передан, инициализируем его пустой строкой
if (!isset($_GET['store'])) {
    $_GET['store'] = '';
}

// Если counter не передан, инициализируем его нулем
if (!isset($_GET['counter'])) {
    $_GET['counter'] = '0';
}

// Обработка нажатий
if (isset($_GET['key'])) {
    // Увеличиваем счетчик нажатий
    $_GET['counter'] = (int)$_GET['counter'] + 1;
    
    if ($_GET['key'] == 'reset') {
        $_GET['store'] = ''; // Сброс
    } else {
        $_GET['store'] .= $_GET['key']; // Добавляем цифру
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Виртуальная клавиатура</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 400px;
            margin: 0 auto;
            padding: 20px;
        }
        .keyboard {
            display: grid;
            grid-template-columns: repeat(5, 1fr);
            gap: 5px;
            margin-top: 20px;
        }
        .key {
            padding: 10px;
            background-color: #f0f0f0;
            border: 1px solid #ccc;
            border-radius: 4px;
            text-align: center;
            text-decoration: none;
            color: #333;
            display: block;
        }
        .key:hover {
            background-color: #e0e0e0;
        }
        .result {
            height: 50px;
            border: 1px solid #ccc;
            border-radius: 4px;
            padding: 10px;
            text-align: center;
            font-size: 24px;
            margin-top: 20px;
        }
        .footer {
            margin-top: 20px;
            text-align: center;
            font-style: italic;
        }
    </style>
</head>
<body>
    <div class="result"><?php echo htmlspecialchars($_GET['store']); ?></div>
    
    <div class="keyboard">
        <a href="?key=1&store=<?php echo urlencode($_GET['store']); ?>&counter=<?php echo $_GET['counter']; ?>" class="key">1</a>
        <a href="?key=2&store=<?php echo urlencode($_GET['store']); ?>&counter=<?php echo $_GET['counter']; ?>" class="key">2</a>
        <a href="?key=3&store=<?php echo urlencode($_GET['store']); ?>&counter=<?php echo $_GET['counter']; ?>" class="key">3</a>
        <a href="?key=4&store=<?php echo urlencode($_GET['store']); ?>&counter=<?php echo $_GET['counter']; ?>" class="key">4</a>
        <a href="?key=5&store=<?php echo urlencode($_GET['store']); ?>&counter=<?php echo $_GET['counter']; ?>" class="key">5</a>
        <a href="?key=6&store=<?php echo urlencode($_GET['store']); ?>&counter=<?php echo $_GET['counter']; ?>" class="key">6</a>
        <a href="?key=7&store=<?php echo urlencode($_GET['store']); ?>&counter=<?php echo $_GET['counter']; ?>" class="key">7</a>
        <a href="?key=8&store=<?php echo urlencode($_GET['store']); ?>&counter=<?php echo $_GET['counter']; ?>" class="key">8</a>
        <a href="?key=9&store=<?php echo urlencode($_GET['store']); ?>&counter=<?php echo $_GET['counter']; ?>" class="key">9</a>
        <a href="?key=0&store=<?php echo urlencode($_GET['store']); ?>&counter=<?php echo $_GET['counter']; ?>" class="key">0</a>
        <a href="?key=reset&store=<?php echo urlencode($_GET['store']); ?>&counter=<?php echo $_GET['counter']; ?>" class="key">СБРОС</a>
    </div>
    
    <div class="footer">Общее число нажатий: <?php echo $_GET['counter']; ?></div>
</body>
</html>