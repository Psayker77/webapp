<?php
$mysqli = new mysqli('MySQL-8.0', 'root', '', 'friends');
if ($mysqli->connect_error) die('Ошибка БД');

// Обработка отправки формы
if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['edit'])) {
    $stmt = $mysqli->prepare("UPDATE friends SET last_name=?, first_name=?, patronymic=?, gender=?, birth_date=?, phone=?, address=?, email=?, comment=? WHERE id=?");
    $stmt->bind_param("sssssssssi", 
        $_POST['last_name'], $_POST['first_name'], $_POST['patronymic'], 
        $_POST['gender'], $_POST['birth_date'], $_POST['phone'], 
        $_POST['address'], $_POST['email'], $_POST['comment'], $_POST['id']);
    if ($stmt->execute()) $msg = '<p class="ok">Запись изменена</p>';
    else $msg = '<p class="error">Ошибка изменения</p>';
    $stmt->close();
}

// Определяем текущую запись
$currentId = isset($_GET['id']) ? (int)$_GET['id'] : 0;
if ($currentId == 0) {
    $res = $mysqli->query("SELECT id FROM friends ORDER BY last_name, first_name LIMIT 1");
    if ($res && $res->num_rows) $currentId = $res->fetch_assoc()['id'];
}

// Получаем данные текущей записи
$current = null;
if ($currentId) {
    $stmt = $mysqli->prepare("SELECT * FROM friends WHERE id=?");
    $stmt->bind_param("i", $currentId);
    $stmt->execute();
    $current = $stmt->get_result()->fetch_assoc();
}

// Список ссылок (Фамилия Имя)
$res = $mysqli->query("SELECT id, last_name, first_name FROM friends ORDER BY last_name, first_name");
if ($res && $res->num_rows) {
    echo '<div class="edit-links">';
    while ($row = $res->fetch_assoc()) {
        $name = htmlspecialchars($row['last_name'] . ' ' . $row['first_name']);
        if ($row['id'] == $currentId) {
            echo "<div class='current'>{$name}</div>";
        } else {
            echo "<a href='?p=edit&id={$row['id']}'>{$name}</a>";
        }
    }
    echo '</div>';
} else {
    echo '<p>Нет записей для редактирования.</p>';
}

if ($current):
?>
<form method="post">
    <input type="hidden" name="id" value="<?= $current['id'] ?>">
    <input type="text" name="last_name" value="<?= htmlspecialchars($current['last_name']) ?>" required><br>
    <input type="text" name="first_name" value="<?= htmlspecialchars($current['first_name']) ?>" required><br>
    <input type="text" name="patronymic" value="<?= htmlspecialchars($current['patronymic']) ?>"><br>
    <select name="gender">
        <option value="М" <?= $current['gender'] == 'М' ? 'selected' : '' ?>>Мужской</option>
        <option value="Ж" <?= $current['gender'] == 'Ж' ? 'selected' : '' ?>>Женский</option>
    </select><br>
    <input type="date" name="birth_date" value="<?= htmlspecialchars($current['birth_date']) ?>"><br>
    <input type="text" name="phone" value="<?= htmlspecialchars($current['phone']) ?>"><br>
    <textarea name="address"><?= htmlspecialchars($current['address']) ?></textarea><br>
    <input type="email" name="email" value="<?= htmlspecialchars($current['email']) ?>"><br>
    <textarea name="comment"><?= htmlspecialchars($current['comment']) ?></textarea><br>
    <input type="submit" name="edit" value="Изменить запись">
</form>
<?php if (isset($msg)) echo $msg; ?>
<?php else: ?>
    <p>Выберите запись из списка.</p>
<?php endif; ?>