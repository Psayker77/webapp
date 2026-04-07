<!-- form.php -->
<!DOCTYPE html>
<html><!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Лабораторная №7: Ввод массива</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 40px; }
        table { border-collapse: collapse; margin-bottom: 15px; }
        td { padding: 5px 10px; border: 1px solid #ccc; }
        .element_row { font-weight: bold; background: #f5f5f5; text-align: right; width: 30px; }
        select, button { padding: 8px 12px; margin-right: 10px; cursor: pointer; }
        input[type="text"] { padding: 6px; width: 100px; }
    </style>
</head>
<body>
    <h2>Ввод массива для сортировки</h2>
    
    <form method="POST" action="process.php" target="_blank">
        <table id="elements">
            <tr>
                <td class="element_row">0:</td>
                <td><input type="text" name="element0"></td>
            </tr>
        </table>
        
        <input type="hidden" id="arrLength" name="arrLength" value="1">
        
        <select name="algoritm" required>
            <option value="0">Сортировка выбором</option>
            <option value="1">Пузырьковый алгоритм</option>
            <option value="2">Алгоритм Шелла</option>
            <option value="3">Алгоритм садового гнома</option>
            <option value="4">Быстрая сортировка</option>
            <option value="5">Встроенная функция РНР для сортировки списков по значению</option>
        </select>
        
        <br><br>
        <input type="button" value="Добавить еще один элемент" onClick="addElement('elements', 1);">
        <input type="submit" value="Сортировать массив">
    </form>

    <script>
        function addElement(table_name, amount) // функция добавляет еще один элемент
        {
            var t = document.getElementById(table_name); // объект таблицы
            for(var i=1; i<amount; i++)
            {
                var index = t.rows.length; // индекс новой строки
                var row = t.insertRow(index); // добавляем новую строку
                var cel = row.insertCell(0); // добавляем в строку ячейку
                cel.className = 'element_row'; // определяем css-класс ячейки
                
                // формируем html-код содержимого ячейки с номером элемента и полем elementX
                var celcontent = index + ':<input type="text" name="element' + index + '">';
                
                // добавляем контент в ячейку таблицы
                setHTML(cel, celcontent);
            }
            // в скрытом поле записываем количество полей (строк таблицы)
            document.getElementById('arrLength').value = t.rows.length;
        }
        
        function setHTML(element, txt)
        {
            if(element.innerHTML)
                element.innerHTML = txt;
            else
            {
                var range = document.createRange();
                range.selectNodeContents(element);
                range.deleteContents();
                var fragment = range.createContextualFragment(txt);
                element.appendChild(fragment);
            }
        }
    </script>
</body>
</html>
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
