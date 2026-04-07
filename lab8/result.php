<?php
// result.php

// Листинг А-8.1: Проверка наличия текста
if( isset($_POST['data']) && $_POST['data'] ) {
    // Вывод исходного текста 
    echo '<div class="src_text" style="color: #0056b3; font-style: italic; margin-bottom: 20px; padding: 10px; background: #f8f9fa; border-left: 4px solid #0056b3;">';
    echo htmlspecialchars($_POST['data']);
    echo '</div>';

    // Листинг А-8.4: Перекодируем из UTF-8 в CP1251 для корректной работы стандартных функций с кириллицей
    $text_cp = iconv("utf-8", "cp1251", $_POST['data']);
    
    // Запускаем анализ
    test_it($text_cp);
} else {
    echo '<div class="src_error" style="color: red; font-weight: bold;">Нет текста для анализа</div>';
}

// Кнопка «Другой анализ» через тег <a> 
echo '<br><a href="index.html" style="display: inline-block; margin-top: 15px; padding: 8px 15px; background: #007bff; color: white; text-decoration: none; border-radius: 4px;">Другой анализ</a>';

// ========================================================================
// ФУНКЦИЯ АНАЛИЗА ТЕКСТА 
// ========================================================================
function test_it($text) {
    echo '<table border="1" cellpadding="8" cellspacing="0" style="border-collapse: collapse; margin-bottom: 20px; width: 100%; max-width: 600px;">';
    
    // 1. Количество символов в тексте
    echo '<tr><td>Количество символов:</td><td>' . strlen($text) . '</td></tr>';

    // Определение групп символов (аналогично цифрам, 
    // Включаем и английский, и русский алфавит
    $cifra   = array_flip(str_split("0123456789"));
    $lower   = array_flip(str_split("abcdefghijklmnopqrstuvwxyzабвгдеёжзийклмнопрстуфхцчшщъыьэюя"));
    $upper   = array_flip(str_split("ABCDEFGHIJKLMNOPQRSTUVWXYZАБВГДЕЁЖЗИЙКЛМНОПРСТУФХЦЧШЩЪЫЬЭЮЯ"));
    $punct   = array_flip(str_split(".,;:!?\"'()-[]{}<>@#$%^&*/\\|~`"));

    $cifra_amount  = 0;
    $lower_amount  = 0;
    $upper_amount  = 0;
    $punct_amount  = 0;
    $letter_amount = 0;
    $word_amount   = 0;
    $word          = '';
    $words         = array();

    // Перебор всех символов текста
    for($i=0; $i<strlen($text); $i++) {
        $char = $text[$i];

        // Подсчет по группам ()
        if( array_key_exists($char, $cifra) ) $cifra_amount++;
        if( array_key_exists($char, $lower) ) { $lower_amount++; $letter_amount++; }
        if( array_key_exists($char, $upper) ) { $upper_amount++; $letter_amount++; }
        if( array_key_exists($char, $punct) ) $punct_amount++;

        // Признак окончания слова: пробел, знак препинания или конец текста
        if( $char == ' ' || array_key_exists($char, $punct) || $i == strlen($text)-1 ) {
            if( $word ) {
                if( isset($words[$word]) ) $words[$word]++; // увеличиваем число повторов
                else $words[$word] = 1;                     // первый повтор
                $word = ''; // сбрасываем текущее слово
            }
        } else {
            $word .= $char; // добавляем символ к текущему слову
        }
    }

    // Вывод статистики
    echo '<tr><td>Количество букв:</td><td>' . $letter_amount . '</td></tr>';
    echo '<tr><td>Количество строчных букв:</td><td>' . $lower_amount . '</td></tr>';
    echo '<tr><td>Количество заглавных букв:</td><td>' . $upper_amount . '</td></tr>';
    echo '<tr><td>Количество знаков препинания:</td><td>' . $punct_amount . '</td></tr>';
    echo '<tr><td>Количество цифр:</td><td>' . $cifra_amount . '</td></tr>';
    echo '<tr><td>Количество слов:</td><td>' . count($words) . '</td></tr>';
    echo '</table>';

    // Сортировка массива слов по алфавиту (по ключам)
    ksort($words);

    // Вывод списка слов и количества их вхождений
    echo '<h3>Слова и частота их употребления:</h3>';
    echo '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 500px;">';
    echo '<tr><th>Слово</th><th>Вхождений</th></tr>';
    foreach($words as $w => $count) {
        // Листинг А-8.4: Перекодируем обратно в UTF-8 перед выводом
        $w_utf = iconv("cp1251", "utf-8", $w);
        echo '<tr><td>' . $w_utf . '</td><td>' . $count . '</td></tr>';
    }
    echo '</table>';

    // Вызов функции подсчета вхождений символов
    $symbs = test_symbs($text);
    
    echo '<h3>Вхождения каждого символа (без учета регистра):</h3>';
    echo '<table border="1" cellpadding="5" cellspacing="0" style="border-collapse: collapse; width: 100%; max-width: 500px;">';
    echo '<tr><th>Символ</th><th>Вхождений</th></tr>';
    foreach($symbs as $s => $count) {
        $s_utf = iconv("cp1251", "utf-8", $s);
        echo '<tr><td>' . $s_utf . '</td><td>' . $count . '</td></tr>';
    }
    echo '</table>';
}

// ========================================================================
// ФУНКЦИЯ ПОДСЧЕТА СИМВОЛОВ 
// ========================================================================
function test_symbs($text) {
    $symbs = array();
    
    // === ИСПРАВЛЕНИЕ ДЛЯ РУССКОГО ЯЗЫКА ===
    // Устанавливаем локаль, чтобы strtolower() корректно работал с кириллицей в CP1251
    setlocale(LC_ALL, 'ru_RU.CP1251', 'Russian_Russia.1251');
    
    $l_text = strtolower($text); // переводим в нижний регистр (CP1251)
    
    for($i=0; $i<strlen($l_text); $i++) {
        $char = $l_text[$i];
        // Игнорируем пробелы при подсчете символов
        if( $char == ' ' ) continue; 

        if( isset($symbs[$char]) ) $symbs[$char]++;
        else $symbs[$char] = 1;
    }
    return $symbs;
}
?>