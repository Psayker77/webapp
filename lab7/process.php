<?php
// process.php

// 1. Если данные не переданы в обработчик – вывести сообщение, прекратить работу
if( !isset($_POST['element0']) )
{
    echo 'Массив не задан, сортировка невозможна';
    exit();
}

// 2. Если среди переданных элементов есть не число – вывести сообщение, прекратить работу
// Функция arg_is_not_Num возвращает true, если аргумент НЕ число
function arg_is_not_Num( $arg )
{
    if( $arg !== '' ) return true; // передана пустая строка
    for( $i = 0; $i < strlen($arg); $i++ ) // цикл по всем символам аргумента
        if( $arg[$i] !== '0' && $arg[$i] !== '1' && $arg[$i] !== '2' &&
            $arg[$i] !== '3' && $arg[$i] !== '4' && $arg[$i] !== '5' &&
            $arg[$i] !== '6' && $arg[$i] !== '7' && $arg[$i] !== '8' &&
            $arg[$i] !== '9' ) // если встретилась не цифра
            return true; // возвращаем true
    // строка состоит из цифр - возвращаем false (это число)
    return false;
}

// Валидация всех элементов массива
for( $i = 0; $i < $_POST['arrLength']; $i++ )
{
    $val = $_POST['element'.$i];
    // Разрешаем отрицательные числа и числа с точкой
    $check = $val;
    if( $check[0] === '-' ) $check = substr($check, 1);
    $parts = explode('.', $check);
    $valid = true;
    foreach( $parts as $part ) {
        for( $j = 0; $j < strlen($part); $j++ ) {
            if( $part[$j] < '0' || $part[$j] > '9' ) { $valid = false; break 2; }
        }
    }
    if( !$valid || $val === '' )
    {
        echo 'Элемент массива "'.$_POST['element'.$i].'" – не число';
        exit();
    }
}

// 3. Выбрать алгоритм сортировки и вывести его название
$algoNames = array(
    '0' => 'Сортировка выбором',
    '1' => 'Пузырьковый алгоритм',
    '2' => 'Алгоритм Шелла',
    '3' => 'Алгоритм садового гнома',
    '4' => 'Быстрая сортировка',
    '5' => 'Встроенная функция РНР для сортировки списков по значению'
);

echo '<h1>'.$algoNames[$_POST['algoritm']].'</h1>';

// 4. Вывести входные данные
$arr = array();
echo 'Исходный массив<br>----------------------------<br>';
for( $i = 0; $i < $_POST['arrLength']; $i++ )
{
    echo '<div class="arr_element">'.$i.': '.$_POST['element'.$i].'</div>';
    $arr[] = $_POST['element'.$i] + 0; // преобразуем в число
}

// Сообщение об успешной валидации
echo '<br>----------------------------<br>Массив проверен, сортировка возможна';

// 5. Сохранить текущее время
$time = microtime(true);

// Переменная для подсчёта итераций (сквозная нумерация)
$iterations = 0;

// Вспомогательная функция вывода состояния массива на каждой итерации
function printState( &$arr, &$iter )
{
    $iter++;
    echo '<div style="margin:4px 0; padding:6px; background:#f9f9f9; border-left:3px solid #007BFF; font-family:monospace;">';
    echo 'Итерация '.$iter.': ['.implode(', ', $arr).']';
    echo '</div>';
}

// === АЛГОРИТМЫ СОРТИРОВКИ ===

// Алгоритм сортировки выбором
function sorting_by_choice( &$arr, &$iter )
{
    for( $i = 0; $i < count($arr) - 1; $i++ )
    {
        $min = $i;
        for( $j = $i + 1; $j < count($arr); $j++ )
        {
            printState($arr, $iter); // вывод на каждом сравнении
            if( $arr[$j] < $arr[$min] ) $min = $j;
        }
        if( $min != $i )
        {
            $element = $arr[$i];
            $arr[$i] = $arr[$min];
            $arr[$min] = $element;
        }
    }
}

// Пузырьковый алгоритм
function BubbleSort( &$arr, &$iter )
{
    for( $j = 0; $j < count($arr) - 1; $j++ )
    {
        for( $i = 0; $i < count($arr) - 1 - $j; $i++ )
        {
            printState($arr, $iter);
            if( $arr[$i] > $arr[$i+1] )
            {
                $temp = $arr[$i];
                $arr[$i] = $arr[$i+1];
                $arr[$i+1] = $temp;
            }
        }
    }
}

// Алгоритм Шелла
function ShellsSort( &$arr, &$iter )
{
    for( $k = ceil(count($arr)/2); $k >= 1; $k = ceil($k/2) )
    {
        for( $i = $k; $i < count($arr); $i++ )
        {
            $val = $arr[$i];
            $j = $i - $k;
            while( $j >= 0 && $arr[$j] > $val )
            {
                printState($arr, $iter);
                $arr[$j+$k] = $arr[$j];
                $j -= $k;
            }
            $arr[$j+$k] = $val;
            printState($arr, $iter);
        }
    }
}

// Алгоритм садового гнома
function gnomeSort( &$arr, &$iter )
{
    $i = 1;
    while( $i < count($arr) )
    {
        printState($arr, $iter);
        if( !$i || $arr[$i-1] <= $arr[$i] )
            $i++;
        else
        {
            $temp = $arr[$i];
            $arr[$i] = $arr[$i-1];
            $arr[$i-1] = $temp;
            $i--;
        }
    }
}

// Быстрая сортировка (рекурсивная)
function quickSort( &$arr, $left, $right, &$iter )
{
    $l = $left;
    $r = $right;
    $point = $arr[ floor(($left+$right)/2) ];
    
    do
    {
        while( $arr[$l] < $point ) $l++;
        while( $arr[$r] > $point ) $r--;
        
        if( $l <= $r )
        {
            $temp = $arr[$l]; $arr[$l] = $arr[$r]; $arr[$r] = $temp;
            $l++; $r--;
            printState($arr, $iter);
        }
    } while( $l <= $r );
    
    if( $r > $left ) quickSort($arr, $left, $r, $iter);
    if( $l < $right ) quickSort($arr, $l, $right, $iter);
}

// Функция-обёртка для быстрой сортировки (без передачи границ)
function quickSortWrapper( &$arr, &$iter )
{
    quickSort($arr, 0, count($arr)-1, $iter);
}

// 6. Провести сортировку выбранным алгоритмом
switch( $_POST['algoritm'] )
{
    case '0': sorting_by_choice($arr, $iterations); break;
    case '1': BubbleSort($arr, $iterations); break;
    case '2': ShellsSort($arr, $iterations); break;
    case '3': gnomeSort($arr, $iterations); break;
    case '4': quickSortWrapper($arr, $iterations); break;
    case '5':
        // Встроенная функция — итерации не считаем
        sort($arr);
        echo '<div style="margin:4px 0; padding:6px; background:#e9ecef;">';
        echo 'Использован встроенный sort(). Промежуточные итерации недоступны.';
        echo '</div>';
        break;
}

// 7. Засечь разницу времени
$elapsed = microtime(true) - $time;

// 8. Вывести сообщение о завершении в ТОЧНОМ формате из задания
echo '<hr>';
echo 'Сортировка завершена, проведено '.$iterations.' итераций. ';
echo 'Сортировка заняла '.$elapsed.' секунд.';

// Вывод результата
echo '<p><strong>Результат:</strong> ['.implode(', ', $arr).']</p>';
?>
