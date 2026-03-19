<?php
// Лабораторная работа № А-6 (исправленная версия)
// Обработка формы с математическими задачами

// Функция для безопасного получения значения из POST
function postVal($key, $default = '') {
    return isset($_POST[$key]) ? htmlspecialchars(trim($_POST[$key]), ENT_QUOTES) : $default;
}

// Функция для преобразования строки с числом (поддержка запятой)
function parseNumber($str) {
    $str = trim(str_replace(',', '.', $str));
    return is_numeric($str) ? floatval($str) : null;
}

// Функция вычисления задачи по её типу
function solveTask($task, $a, $b, $c) {
    // Проверка, что все входные числа — корректные числовые значения
    if ($a === null || $b === null || $c === null) {
        return null;
    }

    switch ($task) {
        case 'area_triangle':
            // Проверка существования треугольника (положительные стороны и неравенство треугольника)
            if ($a > 0 && $b > 0 && $c > 0 && 
                $a + $b > $c && $a + $c > $b && $b + $c > $a) {
                $p = ($a + $b + $c) / 2;
                $area = sqrt($p * ($p - $a) * ($p - $b) * ($p - $c));
                return round($area, 2);
            } else {
                return null; // треугольник не существует
            }
        case 'perimeter_triangle':
            // Периметр может быть вычислен для любых неотрицательных чисел
            return round($a + $b + $c, 2);
        case 'volume_parallelepiped':
            // Объём может быть отрицательным, но NAN не возникает
            return round($a * $b * $c, 2);
        case 'arithmetic_mean':
            // Среднее арифметическое
            return round(($a + $b + $c) / 3, 2);
        case 'sum_squares':
            // Сумма квадратов
            return round($a*$a + $b*$b + $c*$c, 2);
        case 'max_number':
            // Максимальное число
            return round(max($a, $b, $c), 2);
        default:
            return null;
    }
}

// Функция формирования отчёта (текст с HTML-разметкой)
function buildReport($post, $computed, $taskSolved) {
    $fio = htmlspecialchars($post['fio'] ?? '', ENT_QUOTES);
    $group = htmlspecialchars($post['group'] ?? '', ENT_QUOTES);
    $about = htmlspecialchars($post['about'] ?? '', ENT_QUOTES);
    $a = htmlspecialchars($post['A'] ?? '', ENT_QUOTES);
    $b = htmlspecialchars($post['B'] ?? '', ENT_QUOTES);
    $c = htmlspecialchars($post['C'] ?? '', ENT_QUOTES);
    $userAnswer = htmlspecialchars($post['answer'] ?? '', ENT_QUOTES);
    $taskType = $post['task'] ?? '';
    $taskNames = [
        'area_triangle' => 'Площадь треугольника',
        'perimeter_triangle' => 'Периметр треугольника',
        'volume_parallelepiped' => 'Объём параллелепипеда',
        'arithmetic_mean' => 'Среднее арифметическое',
        'sum_squares' => 'Сумма квадратов',
        'max_number' => 'Максимальное число'
    ];
    $taskName = $taskNames[$taskType] ?? $taskType;

    $out = "<p><strong>ФИО:</strong> $fio</p>";
    $out .= "<p><strong>Группа:</strong> $group</p>";
    if (!empty($about)) {
        $out .= "<p><strong>О себе:</strong> $about</p>";
    }
    $out .= "<p><strong>Тип задачи:</strong> $taskName</p>";
    $out .= "<p><strong>Входные данные:</strong> A = $a, B = $b, C = $c</p>";
    
    if ($userAnswer === '') {
        $out .= "<p><strong>Ваш ответ:</strong> Задача самостоятельно решена не была</p>";
    } else {
        $out .= "<p><strong>Ваш ответ:</strong> $userAnswer</p>";
    }

    // Вывод вычисленного результата с проверкой на null (NAN/невозможно вычислить)
    if ($computed === null) {
        $out .= "<p><strong>Вычисленный результат:</strong> Невозможно вычислить (некорректные данные)</p>";
    } else {
        $out .= "<p><strong>Вычисленный результат:</strong> $computed</p>";
    }

    if ($taskSolved !== null) {
        if ($taskSolved) {
            $out .= "<p style='color:green;'><strong>Тест пройден</strong></p>";
        } else {
            $out .= "<p style='color:red;'><strong>Ошибка: тест не пройден</strong></p>";
        }
    }

    return $out;
}

// --- Основная логика ---
$showForm = true;
$report = '';
$emailSentMessage = '';
$isPrintVersion = false;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['A'])) {
    // Обработка отправленной формы
    $a = parseNumber($_POST['A'] ?? '');
    $b = parseNumber($_POST['B'] ?? '');
    $c = parseNumber($_POST['C'] ?? '');
    $userAnswer = parseNumber($_POST['answer'] ?? '');
    $task = $_POST['task'] ?? '';
    $sendEmail = isset($_POST['send_mail']);
    $email = trim($_POST['email'] ?? '');
    $version = $_POST['version'] ?? 'browser';

    $computed = solveTask($task, $a, $b, $c);
    $taskSolved = null;

    // Сравнение ответа пользователя с вычисленным значением
    if ($computed !== null) {
        if ($_POST['answer'] === '') {
            $taskSolved = false; // задача не решена пользователем
        } else {
            $userRounded = round($userAnswer, 2);
            $taskSolved = (abs($computed - $userRounded) < 0.001);
        }
    } else {
        $taskSolved = false; // если программа не смогла вычислить, тест не пройден
    }

    // Формируем отчёт
    $report = buildReport($_POST, $computed, $taskSolved);

    // Отправка email, если нужно
    if ($sendEmail && !empty($email) && $computed !== null) {
        $plainText = strip_tags(str_replace(['<br>', '</p>', '<p>'], ["\n", "\n", ''], $report));
        $plainText = html_entity_decode($plainText, ENT_QUOTES, 'UTF-8');
        $subject = 'Результат тестирования';
        $headers = "From: auto@test.ru\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
        if (mail($email, $subject, $plainText, $headers)) {
            $emailSentMessage = "<p>Результаты теста были автоматически отправлены на e-mail: $email</p>";
        } else {
            $emailSentMessage = "<p>Ошибка при отправке email.</p>";
        }
    }

    $isPrintVersion = ($version === 'print');
    $showForm = false;
}

// Определяем, нужно ли показывать форму с предзаполненными данными (при повторе)
$presetFio = '';
$presetGroup = '';
if (!$showForm && isset($_GET['F'], $_GET['G'])) {
    $presetFio = htmlspecialchars($_GET['F'], ENT_QUOTES);
    $presetGroup = htmlspecialchars($_GET['G'], ENT_QUOTES);
    $showForm = true;
    $report = '';
}

// Генерация случайных чисел для A, B, C (если не POST)
$randA = mt_rand(0, 10000) / 100;
$randB = mt_rand(0, 10000) / 100;
$randC = mt_rand(0, 10000) / 100;
?>
<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа №6</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background: #f5f5f5; }
        .container { max-width: 800px; margin: 0 auto; background: #fff; padding: 20px; border-radius: 5px; box-shadow: 0 0 10px rgba(0,0,0,0.1); }
        form { margin: 0; }
        .form-row { display: flex; align-items: center; margin-bottom: 10px; }
        .form-row label { width: 200px; text-align: right; padding-right: 10px; }
        .form-row input, .form-row select, .form-row textarea { 
            flex: 1; 
            padding: 5px; 
            border: 1px solid #ccc; 
            border-radius: 3px;
            box-sizing: border-box;
        }
        .form-row textarea { min-height: 60px; resize: vertical; }
        .form-row input[type="checkbox"] { flex: none; margin-left: 0; }
        .form-row button { margin-left: 210px; padding: 8px 20px; background: #4CAF50; color: white; border: none; border-radius: 3px; cursor: pointer; }
        .form-row button:hover { background: #45a049; }
        #email-block { display: none; }
        .report { margin-top: 20px; padding: 15px; border: 1px solid #ddd; background: #f9f9f9; }
        .print-version .report { border: none; background: white; font-size: 12pt; }
        .print-version a, .print-version button { display: none; }
        .back-link { display: inline-block; margin-top: 20px; padding: 8px 15px; background: #ddd; border: 1px solid #999; border-radius: 3px; text-decoration: none; color: #333; }
        .back-link:hover { background: #ccc; }
    </style>
    <script>
        function toggleEmail(checkbox) {
            var block = document.getElementById('email-block');
            block.style.display = checkbox.checked ? 'flex' : 'none';
        }
    </script>
</head>
<body>
<div class="container">
    <?php if ($showForm): ?>
        <!-- Вывод формы -->
        <h2>Математический тест</h2>
        <form method="post" action="">
            <div class="form-row">
                <label for="fio">ФИО:</label>
                <input type="text" id="fio" name="fio" value="<?php echo $presetFio ?: ''; ?>" required>
            </div>
            <div class="form-row">
                <label for="group">Номер группы:</label>
                <input type="text" id="group" name="group" value="<?php echo $presetGroup ?: ''; ?>" required>
            </div>
            <div class="form-row">
                <label for="A">Значение А:</label>
                <input type="text" id="A" name="A" value="<?php echo $randA; ?>" required>
            </div>
            <div class="form-row">
                <label for="B">Значение В:</label>
                <input type="text" id="B" name="B" value="<?php echo $randB; ?>" required>
            </div>
            <div class="form-row">
                <label for="C">Значение С:</label>
                <input type="text" id="C" name="C" value="<?php echo $randC; ?>" required>
            </div>
            <div class="form-row">
                <label for="answer">Ваш ответ:</label>
                <input type="text" id="answer" name="answer">
            </div>
            <div class="form-row">
                <label for="task">Выберите задачу:</label>
                <select id="task" name="task">
                    <option value="area_triangle">Площадь треугольника</option>
                    <option value="perimeter_triangle">Периметр треугольника</option>
                    <option value="volume_parallelepiped">Объём параллелепипеда</option>
                    <option value="arithmetic_mean">Среднее арифметическое</option>
                    <option value="sum_squares">Сумма квадратов</option>
                    <option value="max_number">Максимальное число</option>
                </select>
            </div>
            <div class="form-row">
                <label for="send_mail">Отправить результат по e-mail:</label>
                <input type="checkbox" id="send_mail" name="send_mail" onclick="toggleEmail(this)">
            </div>
            <div id="email-block" class="form-row">
                <label for="email">Ваш e-mail:</label>
                <input type="email" id="email" name="email">
            </div>
            <div class="form-row">
                <label for="version">Версия:</label>
                <select id="version" name="version">
                    <option value="browser" selected>версия для просмотра в браузере</option>
                    <option value="print">версия для печати</option>
                </select>
            </div>
            <div class="form-row">
                <label for="about">Немного о себе:</label>
                <textarea id="about" name="about"></textarea>
            </div>
            <div class="form-row">
                <button type="submit">Проверить</button>
            </div>
        </form>
    <?php else: ?>
        <!-- Вывод отчёта -->
        <div class="<?php echo $isPrintVersion ? 'print-version' : ''; ?>">
            <div class="report">
                <?php echo $report; ?>
            </div>
            <?php if ($emailSentMessage): ?>
                <div class="email-note"><?php echo $emailSentMessage; ?></div>
            <?php endif; ?>
            <?php if (!$isPrintVersion): ?>
                <!-- Ссылка "Повторить тест" только для браузерной версии -->
                <a class="back-link" href="?F=<?php echo urlencode($_POST['fio'] ?? ''); ?>&G=<?php echo urlencode($_POST['group'] ?? ''); ?>">Повторить тест</a>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>