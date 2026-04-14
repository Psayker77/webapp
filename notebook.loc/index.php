<?php
require 'menu.php';
$menuHTML = buildMenu();
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Записная книжка</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <?php echo $menuHTML; ?>
    <div id="content">
        <?php
        $p = isset($_GET['p']) ? $_GET['p'] : 'viewer';
        $allowed = ['viewer', 'add', 'edit', 'delete'];
        if (!in_array($p, $allowed)) $p = 'viewer';
        
        if ($p == 'viewer') {
            // Подключаем модуль с функцией
            require 'viewer.php';
            $sort = isset($_GET['sort']) ? $_GET['sort'] : 'byid';
            $page = isset($_GET['page']) ? (int)$_GET['page'] : 0;
            echo getFriendsList($sort, $page);
        } else {
            include $p . '.php';
        }
        ?>
    </div>
</body>
</html>