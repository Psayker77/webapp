<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $page_title; ?> - Мустафин М.Ф. 241-352</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            color: #333;
            min-height: 100vh;
            display: flex;
            flex-direction: column;
        }
        
        header {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            height: 80px;
            background-color: #1a472a;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 0 40px;
            z-index: 1000;
            box-shadow: 0 2px 10px rgba(0,0,0,0.3);
        }
        
        .header-info h1 {
            font-size: 18px;
            font-weight: 600;
            margin-bottom: 4px;
        }
        
        .header-info p {
            font-size: 14px;
            opacity: 0.9;
        }
        
        nav {
            display: flex;
            gap: 25px;
        }
        
        nav a {
            color: white;
            text-decoration: none;
            padding: 8px 16px;
            border-radius: 4px;
            transition: all 0.3s ease;
            font-size: 14px;
            font-weight: 500;
        }
        
        nav a:hover {
            background-color: rgba(255,255,255,0.15);
            transform: translateY(-2px);
        }
        
        nav a.selected_menu {
            background-color: rgba(255,255,255,0.25);
            font-weight: 600;
            box-shadow: 0 2px 8px rgba(0,0,0,0.2);
        }
        
        main {
            margin-top: 80px;
            margin-bottom: 60px;
            flex: 1;
            padding: 40px;
            max-width: 1200px;
            width: 100%;
            align-self: center;
        }
        
        h2 {
            color: #1a472a;
            margin-bottom: 20px;
            font-size: 28px;
            border-bottom: 3px solid #1a472a;
            padding-bottom: 10px;
        }
        
        h3 {
            color: #2d5a3d;
            margin: 25px 0 15px 0;
            font-size: 22px;
        }
        
        p {
            margin-bottom: 15px;
            text-align: justify;
            font-size: 16px;
            line-height: 1.8;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 25px 0;
            box-shadow: 0 2px 15px rgba(0,0,0,0.1);
            border-radius: 8px;
            overflow: hidden;
        }
        
        th {
            background-color: #1a472a;
            color: white;
            padding: 15px;
            text-align: left;
            font-weight: 600;
        }
        
        td {
            padding: 15px;
            border-bottom: 1px solid #e0e0e0;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tr:hover {
            background-color: #e8f5e9;
        }
        
        .photo-gallery {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
            gap: 25px;
            margin: 30px 0;
        }
        
        .photo-card {
            background: white;
            border-radius: 12px;
            overflow: hidden;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
            transition: transform 0.3s ease;
        }
        
        .photo-card:hover {
            transform: translateY(-5px);
        }
        
        .photo-card img {
            width: 100%;
            height: 250px;
            object-fit: cover;
            display: block;
            background-color: #ddd;
        }
        
        .photo-card p {
            padding: 15px;
            margin: 0;
            text-align: center;
            color: #666;
            font-size: 14px;
        }
        
        footer {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            height: 60px;
            background-color: #2c3e50;
            color: #bdc3c7;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 14px;
            box-shadow: 0 -2px 10px rgba(0,0,0,0.2);
        }
        
        footer strong {
            color: #ecf0f1;
            margin-left: 10px;
        }
        
        .content-section {
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 2px 20px rgba(0,0,0,0.05);
            margin-bottom: 30px;
        }
    </style>
</head>
<body>
    <header>
        <div class="header-info">
            <h1>Мустафин Марат Фаритович</h1>
            <p>Группа 241-352 | Лабораторная работа № А-1</p>
        </div>
        
        <nav>
            <!-- ПУНКТ МЕНЮ 1: Главная -->
            <a href="<?php
                $name='Главная';
                $link='index.php';
                $current_page=$page_id=='index';
                echo $link;
            ?>"<?php
                if( $current_page )
                echo ' class="selected_menu"';
            ?>><?php echo $name; ?></a>
            
            <!-- ПУНКТ МЕНЮ 2: О PHP -->
            <a href="<?php
                $name='О PHP';
                $link='about.php';
                $current_page=$page_id=='about';
                echo $link;
            ?>"<?php
                if( $current_page )
                echo ' class="selected_menu"';
            ?>><?php echo $name; ?></a>
            
            <!-- ПУНКТ МЕНЮ 3: Контакты -->
            <a href="<?php
                $name='Контакты';
                $link='contact.php';
                $current_page=$page_id=='contact';
                echo $link;
            ?>"<?php
                if( $current_page )
                echo ' class="selected_menu"';
            ?>><?php echo $name; ?></a>
        </nav>
    </header>

    <main>
        <div class="content-section">
            <h2><?php echo $page_heading; ?></h2>
            
            <?php echo $page_content; ?>
            
            <h3>Информация о выполнении задания</h3>
            <table>
                <tr>
                    <th>Элемент</th>
                    <th>Тип реализации</th>
                    <th>Статус</th>
                </tr>
                <tr>
                    <td><?php echo $table_row[0]; ?></td>
                    <td><?php echo $table_row[1]; ?></td>
                    <td><?php echo $table_row[2]; ?></td>
                </tr>
            </table>
            
            <h3>Галерея изображений</h3>
            <div class="photo-gallery">
                <div class="photo-card">
                    <?php
                    $second = date('s');
                    $photo_num = ($second % 2 == 0) ? '1' : '2';
                    $photo_path = 'fotos/foto' . $photo_num . '.jpg';
                    ?>
                    <img src="<?php echo $photo_path; ?>" alt="Динамическое изображение 1">
                    <p>Фотография меняется в зависимости от секунды (<?php echo $second; ?>)</p>
                </div>
                
                <div class="photo-card">
                    <?php
                    $photo_num2 = ($second % 2 == 0) ? '2' : '1';
                    $photo_path2 = 'fotos/foto' . $photo_num2 . '.jpg';
                    ?>
                    <img src="<?php echo $photo_path2; ?>" alt="Динамическое изображение 2">
                    <p>Альтернативное изображение (секунда: <?php echo $second; ?>)</p>
                </div>
            </div>
        </div>
    </main>

    <footer>
         Сформировано <?php echo date_default_timezone_set('Europe/Moscow'); ?> в <?php echo date('d.m.Y H:i:s'); ?>
        <strong>| PHP Lab A-1</strong>
    </footer>
</body>
</html>