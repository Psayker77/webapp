<?php
// Функция для красивого вывода массива
function printArray($arr) {
    return implode(', ', $arr);
}

// Проверка наличия данных
if (!isset($_POST['arrLength']) || !is_numeric($_POST['arrLength']) || $_POST['arrLength'] < 1) {
    die('<h2>Ошибка</h2><p>Массив не задан, сортировка невозможна.</p>');
}

$length = (int)$_POST['arrLength'];
$arr = [];
for ($i = 0; $i < $length; $i++) {
    $key = 'element' . $i;
    if (!isset($_POST[$key])) {
        die('<h2>Ошибка</h2><p>Отсутствует элемент с индексом ' . $i . '</p>');
    }
    $val = trim($_POST[$key]);
    if (!is_numeric($val)) {
        die('<h2>Ошибка валидации</h2><p>Элемент "' . htmlspecialchars($val) . '" не является числом.</p>');
    }
    // Преобразуем в число (с плавающей точкой)
    $arr[] = floatval($val);
}

$algorithm = isset($_POST['algorithm']) ? $_POST['algorithm'] : 'selection';

// Начинаем вывод HTML
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Результат сортировки</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .iter-step { margin-left: 20px; font-family: monospace; }
        .info { background-color: #f0f0f0; padding: 10px; border-radius: 5px; }
        h2 { color: #333; }
    </style>
</head>
<body>
    <h2>Алгоритм: 
    <?php
    switch ($algorithm) {
        case 'selection': echo "Сортировка выбором"; break;
        case 'bubble': echo "Пузырьковый алгоритм"; break;
        case 'shell': echo "Алгоритм Шелла"; break;
        case 'gnome': echo "Алгоритм садового гнома"; break;
        case 'quick': echo "Быстрая сортировка"; break;
        case 'builtin': echo "Встроенная функция PHP (sort)"; break;
        default: echo "Неизвестный алгоритм";
    }
    ?>
    </h2>

    <div class="info">
        <p><strong>Исходный массив:</strong> <?php echo printArray($arr); ?></p>
        <p><strong>Валидация:</strong> все элементы являются числами, сортировка возможна.</p>
    </div>

    <h3>Процесс сортировки:</h3>
    <div class="iter-steps">
<?php
$start = microtime(true);
$iterations = 0;

// Вызов соответствующей функции сортировки
switch ($algorithm) {
    case 'selection':
        selectionSort($arr, $iterations);
        break;
    case 'bubble':
        bubbleSort($arr, $iterations);
        break;
    case 'shell':
        shellSort($arr, $iterations);
        break;
    case 'gnome':
        gnomeSort($arr, $iterations);
        break;
    case 'quick':
        quickSort($arr, 0, count($arr)-1, $iterations);
        break;
    case 'builtin':
        // Встроенная функция – просто сортируем и выводим результат
        sort($arr);
        echo "<p>Использована встроенная функция sort(). Промежуточные шаги не отображаются.</p>";
        echo "<p><strong>Отсортированный массив:</strong> " . printArray($arr) . "</p>";
        $iterations = 0; // нет итераций для отображения
        break;
}

$time = microtime(true) - $start;
?>
    </div>

    <div class="info" style="margin-top:20px;">
        <p><strong>Сортировка завершена, проведено <?php echo $iterations; ?> итераций.</strong></p>
        <p><strong>Время выполнения:</strong> <?php echo round($time, 6); ?> секунд.</p>
    </div>
</body>
</html>

<?php
// ==================== РЕАЛИЗАЦИИ АЛГОРИТМОВ ====================

function selectionSort(&$arr, &$iter) {
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        $min = $i;
        for ($j = $i + 1; $j < $n; $j++) {
            $iter++;
            if ($arr[$j] < $arr[$min]) {
                $min = $j;
            }
        }
        if ($min != $i) {
            // Обмен
            $temp = $arr[$i];
            $arr[$i] = $arr[$min];
            $arr[$min] = $temp;
            $iter++; // учитываем обмен как отдельную операцию
            echo "<div class='iter-step'>Итерация $iter: " . printArray($arr) . "</div>";
        }
    }
}

function bubbleSort(&$arr, &$iter) {
    $n = count($arr);
    for ($i = 0; $i < $n - 1; $i++) {
        $swapped = false;
        for ($j = 0; $j < $n - $i - 1; $j++) {
            $iter++;
            if ($arr[$j] > $arr[$j+1]) {
                $temp = $arr[$j];
                $arr[$j] = $arr[$j+1];
                $arr[$j+1] = $temp;
                $swapped = true;
                $iter++; // обмен
                echo "<div class='iter-step'>Итерация $iter: " . printArray($arr) . "</div>";
            }
        }
        if (!$swapped) break;
    }
}

function shellSort(&$arr, &$iter) {
    $n = count($arr);
    for ($k = (int)ceil($n / 2); $k >= 1; $k = (int)ceil($k / 2)) {
        for ($i = $k; $i < $n; $i++) {
            $val = $arr[$i];
            $j = $i - $k;
            // сдвиг элементов
            while ($j >= 0 && $arr[$j] > $val) {
                $iter++;
                $arr[$j + $k] = $arr[$j];
                $j -= $k;
                echo "<div class='iter-step'>Итерация $iter: " . printArray($arr) . "</div>";
            }
            if ($j + $k != $i) {
                $arr[$j + $k] = $val;
                $iter++; // вставка
                echo "<div class='iter-step'>Итерация $iter: " . printArray($arr) . "</div>";
            }
        }
    }
}

function gnomeSort(&$arr, &$iter) {
    $n = count($arr);
    $i = 1;
    while ($i < $n) {
        $iter++;
        if ($i == 0 || $arr[$i-1] <= $arr[$i]) {
            $i++;
        } else {
            $temp = $arr[$i];
            $arr[$i] = $arr[$i-1];
            $arr[$i-1] = $temp;
            $i--;
            $iter++; // обмен
            echo "<div class='iter-step'>Итерация $iter: " . printArray($arr) . "</div>";
        }
    }
}

function quickSort(&$arr, $left, $right, &$iter) {
    if ($left >= $right) return;
    $i = $left;
    $j = $right;
    $pivot = $arr[(int)(($left + $right) / 2)];
    while ($i <= $j) {
        while ($arr[$i] < $pivot) {
            $iter++;
            $i++;
        }
        while ($arr[$j] > $pivot) {
            $iter++;
            $j--;
        }
        if ($i <= $j) {
            $temp = $arr[$i];
            $arr[$i] = $arr[$j];
            $arr[$j] = $temp;
            $iter++; // обмен
            echo "<div class='iter-step'>Итерация $iter: " . printArray($arr) . "</div>";
            $i++;
            $j--;
        }
    }
    if ($left < $j) quickSort($arr, $left, $j, $iter);
    if ($i < $right) quickSort($arr, $i, $right, $iter);
}
?>