<?php
// Лабораторная работа № А-4
// Пользовательские функции. Вывод таблиц.

// Задание массива с не менее 10 структурами таблиц 
$structures = [
    "Имя*Возраст*Город#Иван*25*Москва#Анна*30*Санкт-Петербург#Михаил*35*Казань",
    "Товар*Цена*Количество#Яблоки*1.2*10#Бананы*0.5*20#Вишня*2.0*15",
    "ID*Имя#1*Алиса#2*Борис#3*Чарли#4*Дарья",
    "Страна*Столица#Россия*Москва#Германия*Берлин#Италия*Рим#Испания*Мадрид",
    "Название*Автор*Год#Основы PHP*Иван Петров*2020#Продвинутый PHP*Анна Смирнова*2021",
    "Месяц*Продажи#Янв*100#Фев*200#Мар*150#Апр*180",
    "A*B*C*D#1*2*3*4#5*6*7*8#9*10*11*12",
    "X*Y*Z#10*20*30#40*50*60",
    "Пусто*Ячейки#* *#а**б",   // строка с пустыми ячейками и ячейкой из пробела
    "ОднаСтрокаТолько*одна*строка"   // одна строка
];

// Число колонок для всех таблиц
$numColumns = 4;

// Проверка на нулевое число колонок
if ($numColumns == 0) {
    echo "Неправильное число колонок";
    exit;
}

/**
 * Формирует HTML-код одной строки таблицы на основе переданной строки ячеек.
 * Учитывает требуемое число колонок.
 * Возвращает пустую строку, если в строке нет непустых ячеек.
 *
 * @param string $rowString Строка вида "C1*C2*C3"
 * @param int $numColumns Требуемое количество колонок
 * @return string HTML-код строки <tr>...</tr> или пустая строка
 */
function getRowHTML($rowString, $numColumns) {
    // Разбиваем строку на ячейки
    $cells = explode('*', $rowString);
    // Оставляем только первые $numColumns ячеек
    $cells = array_slice($cells, 0, $numColumns);
    
    // Проверяем, есть ли среди них хотя бы одна непустая (не состоящая только из пробелов)
    $hasNonEmpty = false;
    foreach ($cells as $cell) {
        if (trim($cell) !== '') {
            $hasNonEmpty = true;
            break;
        }
    }
    // Если нет ни одной непустой ячейки, строку не выводим
    if (!$hasNonEmpty) {
        return '';
    }
    
    // Дополняем массив до нужного количества колонок пустыми ячейками
    while (count($cells) < $numColumns) {
        $cells[] = '';
    }
    
    // Формируем HTML строки
    $html = '<tr>';
    foreach ($cells as $cell) {
        $html .= '<td>' . htmlspecialchars($cell) . '</td>';
    }
    $html .= '</tr>';
    
    return $html;
}

/**
 * Формирует HTML-код таблицы на основе структуры.
 * Проверяет наличие строк и непустых ячеек.
 * В случае ошибки заполняет переменную $error и возвращает false.
 *
 * @param string $structure Структура таблицы в формате "C1*C2*C3#C4*C5*C6..."
 * @param int $numColumns Требуемое количество колонок
 * @param string $error Ссылка на переменную для сообщения об ошибке
 * @return string|false HTML-код таблицы или false в случае ошибки
 */
function buildTable($structure, $numColumns, &$error) {
    // Разбиваем структуру на строки
    $rowStrings = explode('#', $structure);
    
    $rowsHTML = '';
    $hasAnyNonEmptyRow = false;       // есть ли хотя бы одна непустая строка (после trim)
    $hasAnyRowWithCells = false;      // есть ли хотя бы одна строка с непустыми ячейками
    
    foreach ($rowStrings as $rowString) {
        // Пропускаем полностью пустые строки (они не содержат даже символов)
        if (trim($rowString) === '') {
            continue;
        }
        $hasAnyNonEmptyRow = true;
        
        $rowHTML = getRowHTML($rowString, $numColumns);
        if ($rowHTML !== '') {
            $rowsHTML .= $rowHTML;
            $hasAnyRowWithCells = true;
        }
    }
    
    // Анализ результатов
    if (!$hasAnyNonEmptyRow) {
        $error = "В таблице нет строк";
        return false;
    }
    
    if (!$hasAnyRowWithCells) {
        $error = "В таблице нет строк с ячейками";
        return false;
    }
    
    return '<table border="1" cellpadding="5">' . $rowsHTML . '</table>';
}

// Вывод всех таблиц
foreach ($structures as $index => $structure) {
    $error = '';
    $tableHTML = buildTable($structure, $numColumns, $error);
    
    if ($tableHTML !== false) {
        // Таблица успешно сформирована – выводим заголовок и саму таблицу
        echo "<h2>Таблица №" . ($index + 1) . "</h2>\n";
        echo $tableHTML . "\n";
    } else {
        // Ошибка – выводим сообщение без заголовка
        echo "<p>$error</p>\n";
    }
}
?>