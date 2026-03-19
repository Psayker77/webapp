<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Таблица умножения</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }
        header {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: center;
        }
        .main-menu a {
            margin: 0 10px;
            padding: 5px 10px;
            text-decoration: none;
            color: #333;
            border: 1px solid transparent;
        }
        .main-menu a.selected {
            border: 1px solid #333;
            background-color: #ddd;
        }
        .container {
            display: flex;
            flex: 1;
        }
        aside {
            width: 200px;
            background-color: #e9e9e9;
            padding: 10px;
        }
        .side-menu a {
            display: block;
            margin: 5px 0;
            padding: 5px;
            text-decoration: none;
            color: #333;
            border: 1px solid transparent;
        }
        .side-menu a.selected {
            border: 1px solid #333;
            background-color: #ccc;
        }
        main {
            flex: 1;
            padding: 10px;
        }
        footer {
            background-color: #f0f0f0;
            padding: 10px;
            text-align: center;
        }
        /* Общие стили для таблицы и блоков */
        .multiplication-table {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }
        .column, .single-column {
            border: 1px solid #999;
            padding: 5px;
            background-color: #fff;
            min-width: 100px;
        }
        table {
            border-collapse: collapse;
            width: 100%;
        }
        table td {
            border: 1px solid #999;
            padding: 5px;
            vertical-align: top;
        }
        /* Чтобы таблица и блоки выглядели одинаково */
        .multiplication-table, table {
            width: 100%;
        }
        .column, table td {
            background-color: #fff;
        }
        a {
            text-decoration: none;
            color: #0066cc;
        }
        a:hover {
            text-decoration: underline;
        }
    </style>
</head>
<body>
<?php
// ==================== ПОЛЬЗОВАТЕЛЬСКИЕ ФУНКЦИИ ====================

/**
 * Преобразует число в ссылку на соответствующую таблицу умножения,
 * если число от 2 до 9. Иначе возвращает число без ссылки.
 * Ссылка всегда сбрасывает тип верстки (не передаёт html_type).
 *
 * @param int $x Число
 * @return string HTML-код (число или ссылка)
 */
function outNumAsLink($x) {
    if ($x >= 2 && $x <= 9) {
        return '<a href="?content=' . $x . '">' . $x . '</a>';
    } else {
        return (string)$x;
    }
}

/**
 * Возвращает HTML-код столбца таблицы умножения для заданного числа $n.
 * Строки имеют формат: множимое x множитель = произведение,
 * где каждое число от 2 до 9 (кроме результатов >9) обёрнуто в ссылку.
 *
 * @param int $n Число, для которого строится столбец (от 2 до 9)
 * @return string HTML-код столбца (набор строк с <br>)
 */
function outRow($n) {
    $html = '';
    for ($i = 2; $i <= 9; $i++) {
        $html .= outNumAsLink($n) . ' x ' . outNumAsLink($i) . ' = ' . outNumAsLink($i * $n) . '<br>';
    }
    return $html;
}

// ==================== ПОЛУЧЕНИЕ ПАРАМЕТРОВ ====================

// Тип вёрстки: по умолчанию 'table', но для выделения пунктов главного меню
// важно, что если параметр не передан, то пункты не выделены.
$html_type = isset($_GET['html_type']) ? $_GET['html_type'] : 'table';

// Содержимое: если параметр content передан и является числом от 2 до 9, используем его.
$content = null;
if (isset($_GET['content']) && is_numeric($_GET['content'])) {
    $c = (int)$_GET['content'];
    if ($c >= 2 && $c <= 9) {
        $content = $c;
    }
}

// ==================== ГЛАВНОЕ МЕНЮ ====================
?>
<header>
    <div class="main-menu">
        <?php
        // Функция для построения ссылки главного меню с сохранением content
        function buildMainMenuItem($type, $label, $currentType, $currentContent) {
            $href = '?html_type=' . $type;
            if ($currentContent !== null) {
                $href .= '&content=' . $currentContent;
            }
            $selected = (isset($_GET['html_type']) && $_GET['html_type'] === $type) ? ' class="selected"' : '';
            return '<a href="' . $href . '"' . $selected . '>' . $label . '</a>';
        }

        echo buildMainMenuItem('table', 'Табличная вёрстка', $html_type, $content);
        echo buildMainMenuItem('div', 'Блочная вёрстка', $html_type, $content);
        ?>
    </div>
</header>

<div class="container">
    <!-- ==================== ОСНОВНОЕ МЕНЮ (ЛЕВОЕ) ==================== -->
    <aside>
        <div class="side-menu">
            <?php
            // Пункт "Всё" (без content)
            $hrefAll = '?';
            if (isset($_GET['html_type'])) {
                $hrefAll .= 'html_type=' . $_GET['html_type'];
            }
            $selectedAll = !isset($_GET['content']) ? ' class="selected"' : '';
            echo '<a href="' . $hrefAll . '"' . $selectedAll . '>Всё</a>';

            // Пункты 2-9
            for ($i = 2; $i <= 9; $i++) {
                $href = '?content=' . $i;
                if (isset($_GET['html_type'])) {
                    $href .= '&html_type=' . $_GET['html_type'];
                }
                $selected = (isset($_GET['content']) && (int)$_GET['content'] === $i) ? ' class="selected"' : '';
                echo '<a href="' . $href . '"' . $selected . '>' . $i . '</a>';
            }
            ?>
        </div>
    </aside>

    <!-- ==================== ОСНОВНОЙ КОНТЕНТ ==================== -->
    <main>
        <?php
        // Определяем, что выводить: полную таблицу или один столбец
        if ($content === null) {
            // Полная таблица (восемь колонок)
            if ($html_type === 'div') {
                // Блочная вёрстка
                echo '<div class="multiplication-table">';
                for ($n = 2; $n <= 9; $n++) {
                    echo '<div class="column">' . outRow($n) . '</div>';
                }
                echo '</div>';
            } else {
                // Табличная вёрстка (по умолчанию)
                echo '<table>';
                echo '<tr>';
                for ($n = 2; $n <= 9; $n++) {
                    echo '<td>' . outRow($n) . '</td>';
                }
                echo '</tr>';
                echo '</table>';
            }
        } else {
            // Один столбец
            if ($html_type === 'div') {
                echo '<div class="multiplication-table">';
                echo '<div class="single-column">' . outRow($content) . '</div>';
                echo '</div>';
            } else {
                echo '<table><tr><td>' . outRow($content) . '</td></tr></table>';
            }
        }
        ?>
    </main>
</div>

<!-- ==================== ПОДВАЛ ==================== -->
<footer>
    <?php
    // Тип вёрстки
    if ($html_type === 'div') {
        $typeStr = 'Блочная вёрстка. ';
    } else {
        $typeStr = 'Табличная вёрстка. ';
    }

    // Содержание таблицы
    if ($content === null) {
        $contentStr = 'Таблица умножения полностью. ';
    } else {
        $contentStr = 'Столбец таблицы умножения на ' . $content . '. ';
    }

    // Дата и время
    $dateTime = date('d.m.Y H:i:s');

    echo $typeStr . $contentStr . $dateTime;
    ?>
</footer>
</body>
</html>