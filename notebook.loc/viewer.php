<?php
function getFriendsList($sort, $page) {
    $mysqli = new mysqli('MySQL-8.0', 'root', '', 'friends');
    if ($mysqli->connect_error) return 'Ошибка БД: ' . $mysqli->connect_error;
    
    // Общее количество записей
    $res = $mysqli->query("SELECT COUNT(*) FROM friends");
    $total = $res->fetch_row()[0];
    if ($total == 0) return '<p>Нет записей.</p>';
    
    $perPage = 10;
    $pages = ceil($total / $perPage);
    if ($page < 0) $page = 0;
    if ($page >= $pages) $page = $pages - 1;
    $offset = $page * $perPage;
    
    // Сортировка
    switch ($sort) {
        case 'fam':   $order = "last_name ASC, first_name ASC"; break;
        case 'birth': $order = "birth_date ASC"; break;
        default:      $order = "id ASC";
    }
    
    $sql = "SELECT last_name, first_name, patronymic, gender, birth_date, phone, address, email, comment 
            FROM friends ORDER BY $order LIMIT $offset, $perPage";
    $res = $mysqli->query($sql);
    if (!$res) return 'Ошибка запроса: ' . $mysqli->error;
    
    $html = '<table border="1">';
    $html .= '<tr><th>Фамилия</th><th>Имя</th><th>Отчество</th><th>Пол</th><th>Дата рожд.</th><th>Телефон</th><th>Адрес</th><th>E-mail</th><th>Комментарий</th></tr>';
    while ($row = $res->fetch_assoc()) {
        $html .= '<tr>';
        $html .= '<td>' . htmlspecialchars($row['last_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['first_name']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['patronymic']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['gender']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['birth_date']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['phone']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['address']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['email']) . '</td>';
        $html .= '<td>' . htmlspecialchars($row['comment']) . '</td>';
        $html .= '</tr>';
    }
    $html .= '</table>';
    
    // Пагинация
    if ($pages > 1) {
        $html .= '<div class="pagination">';
        for ($i = 0; $i < $pages; $i++) {
            if ($i == $page) {
                $html .= '<span>' . ($i+1) . '</span>';
            } else {
                $html .= '<a href="?p=viewer&sort=' . urlencode($sort) . '&page=' . $i . '">' . ($i+1) . '</a>';
            }
        }
        $html .= '</div>';
    }
    return $html;
}
?>