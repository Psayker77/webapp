<?php
$mysqli = new mysqli('MySQL-8.0', 'root', '', 'friends');
if ($mysqli->connect_error) die('Ошибка БД');

// Удаление, если передан id
if (isset($_GET['delete'])) {
    $id = (int)$_GET['delete'];
    $res = $mysqli->query("SELECT last_name FROM friends WHERE id=$id");
    if ($res && $row = $res->fetch_assoc()) {
        $lastName = $row['last_name'];
        $mysqli->query("DELETE FROM friends WHERE id=$id");
        echo "<p class='ok'>Запись с фамилией {$lastName} удалена</p>";
    } else {
        echo "<p class='error'>Запись не найдена</p>";
    }
}

// Список ссылок (Фамилия И.О.)
$res = $mysqli->query("SELECT id, last_name, first_name, patronymic FROM friends ORDER BY last_name, first_name");
if ($res && $res->num_rows) {
    echo '<div class="delete-links">';
    while ($row = $res->fetch_assoc()) {
        $initials = mb_substr($row['first_name'], 0, 1) . '.' . ($row['patronymic'] ? mb_substr($row['patronymic'], 0, 1) . '.' : '');
        $name = htmlspecialchars($row['last_name'] . ' ' . $initials);
        echo "<a href='?p=delete&delete={$row['id']}'>{$name}</a><br>";
    }
    echo '</div>';
} else {
    echo '<p>Нет записей для удаления.</p>';
}
?>