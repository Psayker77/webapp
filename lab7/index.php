<!-- form.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Ввод массива для сортировки</title>
    <style>
        table { border-collapse: collapse; }
        td { padding: 5px; }
        .element_row input { width: 100px; }
    </style>
    <script>
        function addElement() {
            var table = document.getElementById('elements');
            var rowCount = table.rows.length;
            var newRow = table.insertRow(rowCount);
            var cell1 = newRow.insertCell(0);
            var cell2 = newRow.insertCell(1);
            cell1.innerHTML = rowCount + ':';
            cell2.innerHTML = '<input type="text" name="element' + rowCount + '">';
            document.getElementById('arrLength').value = rowCount + 1;
        }
    </script>
</head>
<body>
    <form method="post" action="sort.php" target="_blank">
        <table id="elements">
            <tr>
                <td>0:</td>
                <td><input type="text" name="element0"></td>
            </tr>
        </table>
        <input type="hidden" id="arrLength" name="arrLength" value="1">
        <input type="button" value="Добавить еще один элемент" onclick="addElement()">
        <br><br>
        <select name="algorithm">
            <option value="selection">Сортировка выбором</option>
            <option value="bubble">Пузырьковый алгоритм</option>
            <option value="shell">Алгоритм Шелла</option>
            <option value="gnome">Алгоритм садового гнома</option>
            <option value="quick">Быстрая сортировка</option>
            <option value="builtin">Встроенная функция PHP</option>
        </select>
        <br><br>
        <input type="submit" value="Сортировать массив">
    </form>
</body>
</html>