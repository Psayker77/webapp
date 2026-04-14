<?php
$msg = '';

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['add'])) {
    $last_name   = trim($_POST['last_name'] ?? '');
    $first_name  = trim($_POST['first_name'] ?? '');
    $patronymic  = trim($_POST['patronymic'] ?? '');
    $gender      = $_POST['gender'] ?? '';
    $birth_date  = $_POST['birth_date'] ?? '';
    $phone       = trim($_POST['phone'] ?? '');
    $address     = trim($_POST['address'] ?? '');
    $email       = trim($_POST['email'] ?? '');
    $comment     = trim($_POST['comment'] ?? '');

    $errors = [];

    if ($last_name === '') {
        $errors[] = 'Фамилия обязательна для заполнения.';
    }
    if ($first_name === '') {
        $errors[] = 'Имя обязательно для заполнения.';
    }
    if (!in_array($gender, ['М', 'Ж'])) {
        $errors[] = 'Выберите корректный пол (М или Ж).';
    }
    if ($birth_date !== '') {
        $d = DateTime::createFromFormat('Y-m-d', $birth_date);
        if (!$d || $d->format('Y-m-d') !== $birth_date) {
            $errors[] = 'Дата рождения должна быть в формате ГГГГ-ММ-ДД.';
        }
    }
    if ($email !== '' && !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = 'Введите корректный email адрес.';
    }
    if ($phone !== '' && !preg_match('/^[0-9+\-\(\)\s]+$/', $phone)) {
        $errors[] = 'Телефон может содержать только цифры, +, -, пробелы и скобки.';
    }

    if (empty($errors)) {
        $mysqli = new mysqli('MySQL-8.0', 'root', '', 'friends');
        if ($mysqli->connect_error) {
            $msg = '<p class="error">Ошибка подключения к БД: ' . $mysqli->connect_error . '</p>';
        } else {
            $stmt = $mysqli->prepare("INSERT INTO friends (last_name, first_name, patronymic, gender, birth_date, phone, address, email, comment) VALUES (?,?,?,?,?,?,?,?,?)");
            $stmt->bind_param("sssssssss", $last_name, $first_name, $patronymic, $gender, $birth_date, $phone, $address, $email, $comment);
            if ($stmt->execute()) {
                $msg = '<p class="ok">Запись добавлена</p>';
                // Очищаем форму после успешного добавления (опционально)
                // $_POST = [];
            } else {
                $msg = '<p class="error">Ошибка: запись не добавлена. ' . $stmt->error . '</p>';
            }
            $stmt->close();
            $mysqli->close();
        }
    } else {
        $msg = '<div class="error"><ul>';
        foreach ($errors as $err) {
            $msg .= '<li>' . htmlspecialchars($err) . '</li>';
        }
        $msg .= '</ul></div>';
    }
}
?>
<form method="post">
    <div>
        <label for="last_name">Фамилия <span class="required">*</span></label><br>
        <input type="text" name="last_name" id="last_name" placeholder="Иванов" required value="<?= htmlspecialchars($_POST['last_name'] ?? '') ?>">
    </div>
    <div>
        <label for="first_name">Имя <span class="required">*</span></label><br>
        <input type="text" name="first_name" id="first_name" placeholder="Иван" required value="<?= htmlspecialchars($_POST['first_name'] ?? '') ?>">
    </div>
    <div>
        <label for="patronymic">Отчество</label><br>
        <input type="text" name="patronymic" id="patronymic" placeholder="Иванович" value="<?= htmlspecialchars($_POST['patronymic'] ?? '') ?>">
    </div>
    <div>
        <label>Пол <span class="required">*</span></label><br>
        <select name="gender">
            <option value="М" <?= (($_POST['gender'] ?? '') == 'М') ? 'selected' : '' ?>>Мужской</option>
            <option value="Ж" <?= (($_POST['gender'] ?? '') == 'Ж') ? 'selected' : '' ?>>Женский</option>
        </select>
    </div>
    <div>
        <label for="birth_date">Дата рождения</label><br>
        <input type="date" name="birth_date" id="birth_date" value="<?= htmlspecialchars($_POST['birth_date'] ?? '') ?>">
    </div>
    <div>
        <label for="phone">Телефон</label><br>
        <input type="text" name="phone" id="phone" placeholder="+7 (123) 456-78-90" value="<?= htmlspecialchars($_POST['phone'] ?? '') ?>">
    </div>
    <div>
        <label for="address">Адрес</label><br>
        <textarea name="address" id="address" placeholder="г. Москва, ул. Примерная, д.1"><?= htmlspecialchars($_POST['address'] ?? '') ?></textarea>
    </div>
    <div>
        <label for="email">E-mail</label><br>
        <input type="email" name="email" id="email" placeholder="ivanov@example.com" value="<?= htmlspecialchars($_POST['email'] ?? '') ?>">
    </div>
    <div>
        <label for="comment">Комментарий</label><br>
        <textarea name="comment" id="comment" placeholder="Дополнительная информация"><?= htmlspecialchars($_POST['comment'] ?? '') ?></textarea>
    </div>
    <div>
        <input type="submit" name="add" value="Добавить запись">
    </div>
</form>
<?php if (!empty($msg)) echo $msg; ?>