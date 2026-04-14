<?php
session_start();

if (!isset($_SESSION['history'])) {
    $_SESSION['history'] = array();
    $_SESSION['iteration'] = 0;
}
$_SESSION['iteration']++;

function isnum($x) {
    // Приводим к строке, если это число
    if (is_numeric($x)) $x = (string)$x;
    if (!is_string($x)) return false;
    if ($x === '' || $x === false || $x === null) return false;
    // Разрешаем "0" и "0."
    if ($x === "0" || $x === "0.") return true;
    // Запрещаем начинаться с точки
    if ($x[0] == '.') return false;
    // Если начинается с нуля, то следующий символ ДОЛЖЕН быть точкой
    if ($x[0] == '0' && isset($x[1]) && $x[1] != '.') return false;
    // Запрещаем заканчиваться точкой
    if (substr($x, -1) == '.') return false;
    
    $has_dot = false;
    for ($i = 0; $i < strlen($x); $i++) {
        $ch = $x[$i];
        if ($ch >= '0' && $ch <= '9') continue;
        if ($ch == '.') {
            if ($has_dot) return false;
            $has_dot = true;
        } else {
            return false;
        }
    }
    return true;
}

function calculate($val) {
    if ($val === '') return 'Выражение не задано!';
    if (isnum($val)) return (float)$val;
    
    // Сложение
    if (strpos($val, '+') !== false) {
        $args = explode('+', $val);
        $sum = 0;
        foreach ($args as $arg) {
            $res = calculate($arg);
            if (!is_numeric($res)) return $res;
            $sum += $res;
        }
        return $sum;
    }
    
    // Вычитание (с поддержкой унарного минуса)
    if (strpos($val, '-') !== false) {
        // Если первый символ '-', добавляем 0 в начало
        if ($val[0] == '-') $val = '0' . $val;
        $args = explode('-', $val);
        $result = calculate($args[0]);
        if (!is_numeric($result)) return $result;
        for ($i = 1; $i < count($args); $i++) {
            $res = calculate($args[$i]);
            if (!is_numeric($res)) return $res;
            $result -= $res;
        }
        return $result;
    }
    
    // Умножение
    if (strpos($val, '*') !== false) {
        $args = explode('*', $val);
        $prod = 1;
        foreach ($args as $arg) {
            $res = calculate($arg);
            if (!is_numeric($res)) return $res;
            $prod *= $res;
        }
        return $prod;
    }
    
    // Деление /
    if (strpos($val, '/') !== false) {
        $args = explode('/', $val);
        $result = calculate($args[0]);
        if (!is_numeric($result)) return $result;
        for ($i = 1; $i < count($args); $i++) {
            $res = calculate($args[$i]);
            if (!is_numeric($res)) return $res;
            if ($res == 0) return 'Деление на ноль!';
            $result /= $res;
        }
        return $result;
    }
    
    // Деление :
    if (strpos($val, ':') !== false) {
        $args = explode(':', $val);
        $result = calculate($args[0]);
        if (!is_numeric($result)) return $result;
        for ($i = 1; $i < count($args); $i++) {
            $res = calculate($args[$i]);
            if (!is_numeric($res)) return $res;
            if ($res == 0) return 'Деление на ноль!';
            $result /= $res;
        }
        return $result;
    }
    
    return 'Недопустимые символы в выражении';
}

function SqValidator($val) {
    $open = 0;
    for ($i = 0; $i < strlen($val); $i++) {
        if ($val[$i] == '(') $open++;
        elseif ($val[$i] == ')') {
            $open--;
            if ($open < 0) return false;
        }
    }
    return $open == 0;
}

function calculateSq($val) {
    if (!SqValidator($val)) return 'Неправильная расстановка скобок';
    $start = strpos($val, '(');
    if ($start === false) return calculate($val);
    
    $end = $start + 1;
    $open = 1;
    while ($open > 0 && $end < strlen($val)) {
        if ($val[$end] == '(') $open++;
        elseif ($val[$end] == ')') $open--;
        $end++;
    }
    
    $inner = substr($val, $start + 1, $end - $start - 2);
    $inner_res = calculateSq($inner);
    if (!is_numeric($inner_res)) return $inner_res;
    
    $new_val = substr($val, 0, $start) . $inner_res . substr($val, $end);
    return calculateSq($new_val);
}

// Обработка формы
$res = '';
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['val'])) {
    $expr = trim($_POST['val']);
    if ($expr == '') {
        $res = 'Выражение не задано!';
    } else {
        // Проверка на недопустимые символы (разрешены цифры, + - * / : . ( ) )
        if (preg_match('/[^0-9+\-*\/:\.\(\)]/', $expr)) {
            $res = 'Недопустимые символы в выражении';
        } else {
            $res = calculateSq($expr);
        }
        
        // Сохраняем в историю (если $res число, то успех, иначе ошибка)
        if (is_numeric($res)) {
            $_SESSION['history'][] = $expr . ' = ' . $res;
        } else {
            $_SESSION['history'][] = $expr . ' = Ошибка: ' . $res;
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Калькулятор</title>
    <style>
        body { font-family: Arial; max-width: 600px; margin: 50px auto; }
        .result { margin: 20px 0; padding: 15px; border-radius: 5px; }
        .history-item { padding: 8px; margin: 5px 0; border-left: 3px solid; background: #f9f9f9; }
    </style>
</head>
<body>
    <h2>Арифметический калькулятор</h2>
    <form method="POST">
        <input type="text" name="val" style="width: 300px; padding: 5px;" 
               value="<?php echo isset($_POST['val']) ? htmlspecialchars($_POST['val']) : ''; ?>">
        <button type="submit">Вычислить</button>
    </form>

    <?php if ($res !== ''): ?>
        <div class="result" style="background: <?php echo is_numeric($res) ? '#d4edda' : '#f8d7da'; ?>;">
            <?php if (is_numeric($res)): ?>
                Значение выражения: <?php echo $res; ?>
            <?php else: ?>
                Ошибка вычисления выражения: <?php echo $res; ?>
            <?php endif; ?>
        </div>
    <?php endif; ?>

    <div style="margin-top: 30px; border-top: 2px solid #ccc; padding-top: 20px;">
        <h3>История вычислений:</h3>
        <?php 
        // Показываем последние записи сверху
        $history = array_reverse($_SESSION['history']);
        foreach ($history as $item) {
            $is_err = (strpos($item, 'Ошибка') !== false);
            $color = $is_err ? '#dc3545' : '#28a745';
            echo "<div class='history-item' style='border-left-color: $color;'>";
            echo htmlspecialchars($item);
            echo "</div>";
        }
        ?>
    </div>
</body>
</html>