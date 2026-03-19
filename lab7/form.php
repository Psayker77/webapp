<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная работа №7 – Ввод массива</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; margin-bottom: 10px; }
        td { padding: 5px; }
        .element-row input { width: 120px; }
        input[type="button"], input[type="submit"] { padding: 8px 15px; margin: 5px; }
        select { padding: 5px; width: 250px; }
    </style>
    <script>
        function addElement() {
            var table = document.getElementById('elements');
            var rowCount = table.rows.length;          // количество строк до добавления
            var newRow = table.insertRow(rowCount);    // вставляем новую строку
            var cell1 = newRow.insertCell(0);           // ячейка с номером
            var cell2 = newRow.insertCell(1);           // ячейка с полем ввода
            cell1.innerHTML = rowCount + ':';
            cell2.innerHTML = '<input type="text" name="element' + rowCount + '" class="element-row">';
            // обновляем скрытое поле с количеством элементов
            document.getElementById('arrLength').value = rowCount + 1;
        }
    </script>
</head>
<body>
    <h2>Введите элементы массива</h2>
    <form method="post" action="sort.php" target="_blank">
        <table id="elements">
            <tr>
                <td>0:</td>
                <td><input type="text" name="element0" class="element-row"></td>
            </tr>
        </table>
        <input type="hidden" id="arrLength" name="arrLength" value="1">
        <input type="button" value="Добавить ещё один элемент" onclick="addElement()">
        <br><br>
        <label for="algorithm">Выберите алгоритм сортировки:</label>
        <br>
        <select name="algorithm" id="algorithm">
            <option value="selection">Сортировка выбором</option>
            <option value="bubble">Пузырьковый алгоритм</option>
            <option value="shell">Алгоритм Шелла</option>
            <option value="gnome">Алгоритм садового гнома</option>
            <option value="quick">Быстрая сортировка</option>
            <option value="builtin">Встроенная функция PHP (sort)</option>
        </select>
        <br><br>
        <input type="submit" value="Сортировать массив">
    </form>
</body>
</html>