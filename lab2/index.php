<?php
// ============================
// ОБЩИЕ ПАРАМЕТРЫ (для всех циклов)
// ============================
$start_value = -10;          // начальное значение аргумента
$encounting = 50;            // количество запланированных итераций
$step = 2;                    // шаг изменения аргумента
$min_value = -10000;           // минимальная граница для остановки
$max_value = 10000;            // максимальная граница для остановки
$type = 'A';                  // тип вёрстки (A, B, C, D, E)

// Выбор типа цикла: 'for', 'while' или 'do_while'
$loop_type = 'do_while';  // измените на 'while' или 'do_while' для проверки других вариантов

// ============================
// ВЫЧИСЛЕНИЯ С ВЫБРАННЫМ ЦИКЛОМ
// ============================
$results = [];       // массив для вывода (x => f)
$values = [];        // массив числовых значений функции (без округления) для статистики
$max_func = -INF;
$min_func = INF;
$sum = 0;

if ($loop_type == 'for') {
    // ----------------------------
    // Цикл for
    // ----------------------------
    $x = $start_value;
    for ($i = 0; $i < $encounting; $i++, $x += $step) {
        // Вычисление функции (вариант 8)
        if ($x <= 10) {
            $f = 7 * $x + 18;
        } elseif ($x < 20) {
            $denom = 8 - $x * 0.5;
            $f = ($denom == 0) ? 'error' : ($x - 17) / $denom;
        } else {
            $f = ($x + 4) * ($x - 7);
        }

        // Обработка результата
        if ($f === 'error') {
            $results[] = ['x' => $x, 'f' => 'error'];
        } else {
            // Проверка выхода за допустимые пределы
            if ($f < $min_value || $f > $max_value) {
                break; // немедленная остановка, значение не сохраняется
            }
            // Сохраняем числовое значение
            $values[] = $f;
            $sum += $f;
            if ($f > $max_func) $max_func = $f;
            if ($f < $min_func) $min_func = $f;
            $results[] = ['x' => $x, 'f' => round($f, 3)];
        }
    }
} 
elseif ($loop_type == 'while') {
    // ----------------------------
    // Цикл while
    // ----------------------------
    $i = 0;
    $x = $start_value;
    while ($i < $encounting) {
        // Вычисление функции
        if ($x <= 10) {
            $f = 7 * $x + 18;
        } elseif ($x < 20) {
            $denom = 8 - $x * 0.5;
            $f = ($denom == 0) ? 'error' : ($x - 17) / $denom;
        } else {
            $f = ($x + 4) * ($x - 7);
        }

        if ($f === 'error') {
            $results[] = ['x' => $x, 'f' => 'error'];
        } else {
            if ($f < $min_value || $f > $max_value) {
                break; // остановка, не сохраняем значение
            }
            $values[] = $f;
            $sum += $f;
            if ($f > $max_func) $max_func = $f;
            if ($f < $min_func) $min_func = $f;
            $results[] = ['x' => $x, 'f' => round($f, 3)];
        }

        $i++;
        $x += $step;
    }
} 
elseif ($loop_type == 'do_while') {
    // ----------------------------
    // Цикл do-while
    // ----------------------------
    $i = 0;
    $x = $start_value;
    do {
        // Вычисление функции
        if ($x <= 10) {
            $f = 7 * $x + 18;
        } elseif ($x < 20) {
            $denom = 8 - $x * 0.5;
            $f = ($denom == 0) ? 'error' : ($x - 17) / $denom;
        } else {
            $f = ($x + 4) * ($x - 7);
        }

        if ($f === 'error') {
            $results[] = ['x' => $x, 'f' => 'error'];
        } else {
            if ($f < $min_value || $f > $max_value) {
                break; // остановка
            }
            $values[] = $f;
            $sum += $f;
            if ($f > $max_func) $max_func = $f;
            if ($f < $min_func) $min_func = $f;
            $results[] = ['x' => $x, 'f' => round($f, 3)];
        }

        $i++;
        $x += $step;
    } while ($i < $encounting);
}

// ============================
// СТАТИСТИКА
// ============================
$count_valid = count($values);
$average = $count_valid > 0 ? $sum / $count_valid : 0;
$rounded_average = round($average, 3);
$rounded_sum = round($sum, 3);
$rounded_max = $count_valid > 0 ? round($max_func, 3) : '—';
$rounded_min = $count_valid > 0 ? round($min_func, 3) : '—';

// ============================
// ВЫВОД В ЗАВИСИМОСТИ ОТ ТИПА ВЁРСТКИ
// ============================
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Мустафин Марат Фаритович, 241-352, Лабораторная работа №2, вариант 8</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <img src="logo.png" alt="Логотип университета">
        <h1>Мустафин Марат Фаритович, группа 241-352, Лабораторная работа №2, вариант 8</h1>
    </header>
    <main>
        <h2>Параметры вычислений</h2>
        <p><strong>Тип цикла:</strong> <?php echo $loop_type; ?></p>
        <p>Начальное значение аргумента: <?php echo $start_value; ?></p>
        <p>Шаг: <?php echo $step; ?></p>
        <p>Запланировано итераций: <?php echo $encounting; ?></p>
        <p>Выполнено итераций (до остановки): <?php echo count($results); ?></p>
        <p>Границы остановки: [<?php echo $min_value; ?>, <?php echo $max_value; ?>]</p>
        <p>Тип вёрстки: <?php echo $type; ?></p>

        <h2>Результаты</h2>
        <?php
        switch ($type) {
            case 'A':
                $last = count($results) - 1;
                foreach ($results as $idx => $res) {
                    echo "f({$res['x']}) = {$res['f']}";
                    if ($idx < $last) echo "<br>";
                }
                break;
            case 'B':
                echo "<ul>";
                foreach ($results as $res) {
                    echo "<li>f({$res['x']}) = {$res['f']}</li>";
                }
                echo "</ul>";
                break;
            case 'C':
                echo "<ol>";
                foreach ($results as $res) {
                    echo "<li>f({$res['x']}) = {$res['f']}</li>";
                }
                echo "</ol>";
                break;
            case 'D':
                echo "<table>";
                echo "<tr><th>№</th><th>x</th><th>f(x)</th></tr>";
                $n = 1;
                foreach ($results as $res) {
                    echo "<tr><td>$n</td><td>{$res['x']}</td><td>{$res['f']}</td></tr>";
                    $n++;
                }
                echo "</table>";
                break;
            case 'E':
                echo "<div class='blocks-container'>";
                foreach ($results as $res) {
                    echo "<div class='block-item'>f({$res['x']}) = {$res['f']}</div>";
                }
                echo "</div>";
                break;
            default:
                echo "<p>Неизвестный тип вёрстки.</p>";
        }
        ?>

        <h2>Статистика</h2>
        <?php if ($count_valid > 0): ?>
            <p>Максимум: <?php echo $rounded_max; ?></p>
            <p>Минимум: <?php echo $rounded_min; ?></p>
            <p>Среднее арифметическое: <?php echo $rounded_average; ?></p>
            <p>Сумма: <?php echo $rounded_sum; ?></p>
            <p>Количество числовых значений: <?php echo $count_valid; ?></p>
        <?php else: ?>
            <p>Нет числовых значений для расчёта статистики.</p>
        <?php endif; ?>
    </main>
    <footer>
        Тип верстки: <?php echo $type; ?>
    </footer>
</body>
</html>