<?php
function buildMenu() {
    $current = isset($_GET['p']) ? $_GET['p'] : 'viewer';
    $currentSort = isset($_GET['sort']) ? $_GET['sort'] : 'byid';
    
    $html = '<div id="menu">';
    $items = [
        'viewer' => 'Просмотр',
        'add'    => 'Добавление записи',
        'edit'   => 'Редактирование записи',
        'delete' => 'Удаление записи'
    ];
    foreach ($items as $key => $title) {
        $class = ($current == $key) ? 'class="active"' : '';
        $html .= "<a href='/?p={$key}' {$class}>{$title}</a>";
    }
    $html .= '</div>';
    
    // Подменю для просмотра
    if ($current == 'viewer') {
        $html .= '<div id="submenu">';
        $sorts = [
            'byid'   => 'По умолчанию',
            'fam'    => 'По фамилии',
            'birth'  => 'По дате рождения'
        ];
        foreach ($sorts as $key => $title) {
            $class = ($currentSort == $key) ? 'class="active"' : '';
            $html .= "<a href='/?p=viewer&sort={$key}' {$class}>{$title}</a>";
        }
        $html .= '</div>';
    }
    return $html;
}
?>