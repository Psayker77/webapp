<?php
// Инициализация переменных
$formSent = false;
$computedResult = null;
$testResult = null;
$outText = '';

// Если форма была отправлена (признак – наличие поля 'A')
if (isset($_POST['A'])) {
    $formSent = true;
    
    // Получаем данные из формы
    $A = (float) str_replace(',', '.', $_POST['A']);
    $B = (float) str_replace(',', '.', $_POST['B']);
    $C = (float) str_replace(',', '.', $_POST['C']);
    $userAnswer = trim($_POST['result']);
    $task = $_POST['TASK'];
    $fio = htmlspecialchars($_POST['FIO']);
    $group = htmlspecialchars($_POST['GROUP']);
    $about = htmlspecialchars($_POST['ABOUT']);
    $view = $_POST['VIEW']; // 'browser' или 'print'
    
    // Вычисление результата в зависимости от выбранной задачи
    if ($task == 'mean') {
        $computedResult = round(($A + $B + $C) / 3, 2);
        $taskName = 'Среднее арифметическое';
    } elseif ($task == 'perimetr') {
        $computedResult = $A + $B + $C;
        $taskName = 'Периметр треугольника';
    } elseif ($task == 'area') {
        $computedResult = 0.5 * $A * $B; // площадь прямоугольного треугольника
        $taskName = 'Площадь треугольника';
    } elseif ($task == 'volume') {
        $computedResult = $A * $B * $C;
        $taskName = 'Объем параллелепипеда';
    } elseif ($task == 'max') {
        $computedResult = max($A, $B, $C);
        $taskName = 'Максимальное из трех чисел';
    } elseif ($task == 'min') {
        $computedResult = min($A, $B, $C);
        $taskName = 'Минимальное из трех чисел';
    } elseif ($task == 'square_sum') {
        $computedResult = pow($A + $B + $C, 2);
        $taskName = 'Квадрат суммы';
    } else {
        $computedResult = null;
        $taskName = 'Неизвестная задача';
    }
    
    // Проверка ответа пользователя
    if ($userAnswer === '') {
        $testResult = 'Ошибка: тест не пройден';
        $userAnswerMsg = 'Задача самостоятельно решена не была';
        $isPassed = false;
    } else {
        $userAnswerFloat = (float) str_replace(',', '.', $userAnswer);
        if (abs($computedResult - $userAnswerFloat) < 0.0001) { // сравнение с учетом погрешности
            $testResult = 'Тест пройден';
            $userAnswerMsg = $userAnswer;
            $isPassed = true;
        } else {
            $testResult = 'Ошибка: тест не пройден';
            $userAnswerMsg = $userAnswer;
            $isPassed = false;
        }
    }
    
    // Формирование отчета
    $outText = "ФИО: $fio<br>";
    $outText .= "Группа: $group<br>";
    if (!empty($about)) $outText .= "<br>$about<br>";
    $outText .= "Решаемая задача: $taskName<br>";
    $outText .= "Входные данные: A=$A, B=$B, C=$C<br>";
    $outText .= "Вычисленный результат: $computedResult<br>";
    $outText .= "Ваш ответ: $userAnswerMsg<br>";
    $outText .= "<b>$testResult</b><br>";
    
    // Отправка письма, если отмечен флажок и указан e-mail
    $mailSent = false;
    if (isset($_POST['send_mail']) && !empty($_POST['MAIL'])) {
        $to = $_POST['MAIL'];
        $subject = 'Результат тестирования';
        // Сначала заменяем <br> на \r\n, потом удаляем оставшиеся HTML-теги
        $plainText = strip_tags(str_replace('<br>', "\r\n", $outText));
        $headers = "From: auto@mami.ru\r\n";
        $headers .= "Content-Type: text/plain; charset=utf-8\r\n";
        if (mail($to, $subject, $plainText, $headers)) {
            $mailSent = true;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа №6</title>
    <style>
        /* Базовые стили для всех версий */
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
            padding: 0;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        /* Стили для браузерной версии */
        body.browser {
            background-color: #f4f4f9;
        }
        body.browser .container {
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
        }
        body.browser h2 {
            text-align: center;
            color: #333;
        }
        body.browser .form-row {
            margin-bottom: 15px;
            display: flex;
            align-items: center;
        }
        body.browser label {
            width: 180px;
            font-weight: bold;
            color: #555;
        }
        body.browser input[type="text"],
        body.browser input[type="email"],
        body.browser select,
        body.browser textarea {
            flex: 1;
            padding: 8px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 14px;
        }
        body.browser input[type="checkbox"] {
            margin-left: 180px;
        }
        body.browser .submit-row {
            margin-left: 180px;
        }
        body.browser input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            padding: 10px 20px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        body.browser input[type="submit"]:hover {
            background-color: #45a049;
        }
        body.browser .result {
            margin-top: 20px;
            background-color: #e8f4e8;
            padding: 15px;
            border: 1px solid #4CAF50;
            border-radius: 5px;
        }
        body.browser .error {
            background-color: #f8d7da;
            border-color: #f5c6cb;
            color: #721c24;
        }
        body.browser .repeat-link {
            display: inline-block;
            margin-top: 20px;
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 5px;
            border: 1px solid #0056b3;
        }
        body.browser .repeat-link:hover {
            background-color: #0056b3;
            cursor: pointer;
        }
        /* Стили для печатной версии */
        body.print {
            background-color: white;
            font-family: 'Times New Roman', serif;
        }
        body.print .container {
            max-width: 100%;
            margin: 0;
            padding: 10px;
        }
        body.print .form-row {
            display: flex;
            align-items: center;
            margin-bottom: 10px;
        }
        body.print label {
            width: 180px;
            font-weight: bold;
        }
        body.print input, body.print select, body.print textarea {
            border: 1px solid #000;
            background: none;
        }
        body.print input[type="submit"] {
            border: 1px solid #000;
            background: none;
        }
        body.print .repeat-link {
            display: none; /* ссылка не выводится в печатной версии */
        }
        /* Общие стили для скрытия поля email */
        .hidden-field {
            display: none;
        }
        .mail-row {
            display: flex;
            align-items: center;
        }
        .mail-row label {
            width: 180px;
        }
        .mail-row input {
            flex: 1;
        }
    </style>
    <script>
        function toggleMailField() {
            var checkbox = document.getElementById('send_mail');
            var mailRow = document.getElementById('mail_row');
            if (checkbox.checked) {
                mailRow.style.display = 'flex';
            } else {
                mailRow.style.display = 'none';
            }
        }
    </script>
</head>
<body class="<?php 
    // Определяем класс для body в зависимости от выбранной версии
    if ($formSent) {
        echo $view === 'print' ? 'print' : 'browser';
    } else {
        // По умолчанию браузерная версия
        echo 'browser';
    }
?>">
<div class="container">
    <?php if ($formSent): ?>
        <!-- Блок отчета -->
        <h2>Результаты тестирования</h2>
        <div class="result">
            <?php echo $outText; ?>
        </div>
        <?php if (isset($_POST['send_mail']) && !empty($_POST['MAIL'])): ?>
            <?php if ($mailSent): ?>
                <p>Результаты теста были автоматически отправлены на e-mail <?php echo htmlspecialchars($_POST['MAIL']); ?></p>
            <?php else: ?>
                <p class="error">Не удалось отправить письмо на <?php echo htmlspecialchars($_POST['MAIL']); ?></p>
            <?php endif; ?>
        <?php elseif (isset($_POST['send_mail']) && empty($_POST['MAIL'])): ?>
            <p class="error">Для отправки результата укажите e-mail.</p>
        <?php endif; ?>
        
        <?php if ($view === 'browser'): ?>
            <!-- Ссылка "Повторить тест" только для браузерной версии -->
            <a href="?FIO=<?php echo urlencode($fio); ?>&GROUP=<?php echo urlencode($group); ?>" class="repeat-link">Повторить тест</a>
        <?php endif; ?>
        
    <?php else: ?>
        <!-- Форма -->
        <h2>Проверка математических знаний</h2>
        <form method="post" action="">
            <!-- ФИО -->
            <div class="form-row">
                <label for="FIO">ФИО:</label>
                <input type="text" name="FIO" id="FIO" value="<?php echo isset($_GET['FIO']) ? htmlspecialchars($_GET['FIO']) : ''; ?>">
            </div>
            <!-- Группа -->
            <div class="form-row">
                <label for="GROUP">Номер группы:</label>
                <input type="text" name="GROUP" id="GROUP" value="<?php echo isset($_GET['GROUP']) ? htmlspecialchars($_GET['GROUP']) : ''; ?>">
            </div>
            <!-- Значение A -->
            <div class="form-row">
                <label for="A">Значение A:</label>
                <input type="text" name="A" id="A" value="<?php echo mt_rand(0, 100); ?>">
            </div>
            <!-- Значение B -->
            <div class="form-row">
                <label for="B">Значение B:</label>
                <input type="text" name="B" id="B" value="<?php echo mt_rand(0, 100); ?>">
            </div>
            <!-- Значение C -->
            <div class="form-row">
                <label for="C">Значение C:</label>
                <input type="text" name="C" id="C" value="<?php echo mt_rand(0, 100); ?>">
            </div>
            <!-- Ваш ответ -->
            <div class="form-row">
                <label for="result">Ваш ответ:</label>
                <input type="text" name="result" id="result">
            </div>
            <!-- Немного о себе -->
            <div class="form-row">
                <label for="ABOUT">Немного о себе:</label>
                <textarea name="ABOUT" id="ABOUT" rows="3"></textarea>
            </div>
            <!-- Задача -->
            <div class="form-row">
                <label for="TASK">Задача:</label>
                <select name="TASK" id="TASK">
                    <option value="mean">Среднее арифметическое (A+B+C)/3</option>
                    <option value="perimetr">Периметр треугольника (A+B+C)</option>
                    <option value="area">Площадь треугольника (0.5*A*B)</option>
                    <option value="volume">Объем параллелепипеда (A*B*C)</option>
                    <option value="max">Максимальное из трех чисел</option>
                    <option value="min">Минимальное из трех чисел</option>
                    <option value="square_sum">Квадрат суммы (A+B+C)^2</option>
                </select>
            </div>
            <!-- Флажок отправки на e-mail -->
            <div class="form-row">
                <label for="send_mail">Отправить результат на e-mail:</label>
                <input type="checkbox" name="send_mail" id="send_mail" onclick="toggleMailField()">
            </div>
            <!-- Поле e-mail (скрыто по умолчанию) -->
            <div id="mail_row" class="mail-row" style="display: none;">
                <label for="MAIL">Ваш e-mail:</label>
                <input type="email" name="MAIL" id="MAIL">
            </div>
            <!-- Версия отображения -->
            <div class="form-row">
                <label for="VIEW">Версия отображения:</label>
                <select name="VIEW" id="VIEW">
                    <option value="browser">Для просмотра в браузере</option>
                    <option value="print">Для печати</option>
                </select>
            </div>
            <!-- Кнопка отправки -->
            <div class="submit-row">
                <input type="submit" value="Проверить">
            </div>
        </form>
        
        <script>
            // При загрузке страницы проверяем состояние чекбокса (если форма уже была отправлена, но мы её не показываем)
            // В данном случае форма всегда новая, поэтому чекбокс по умолчанию не отмечен.
            // Инициализируем скрытие поля e-mail
            toggleMailField();
        </script>
    <?php endif; ?>
</div>
</body>
</html>
