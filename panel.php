<?php
declare(strict_types=1);
require_once(__DIR__ . '/core.php');


function is_ip_allowed(array $allowedIps): bool {
    return in_array($_SERVER['REMOTE_ADDR'], $allowedIps, true);
}

function is_logged_in(): bool {
    return isset($_SESSION['logged_in']) && $_SESSION['logged_in'] === true;
}

function kill_session(): void {
    session_unset();
    session_destroy();
    header('Location: /panel.php');
    exit;
}

?>

<!DOCTYPE html>
<html lang="ru" data-theme="dark">
<head>
    <title>Панель управления</title>
    <?= $vxConfig_head ?>
</head>
<body class="body-panel">

<?php
if (SESSION_LOG_IN) {
    session_set_cookie_params(SESSION_TIME, "/");
    session_start();

    // Если пользователь уже вошёл — продолжаем
    if (is_logged_in()) {
        // Проверяем время сессии
        if (isset($_SESSION['last_activity']) && (time() - $_SESSION['last_activity']) > SESSION_TIME) {
            kill_session();
        }
        $_SESSION['last_activity'] = time(); // обновляем время активности
    } else {
        if (isset($_POST['log_in']) && !empty($_POST['login']) && !empty($_POST['pass'])) {
            $login = trim($_POST['login']);
            $pass = trim($_POST['pass']);
            $login = strip_tags($login);
            $pass = strip_tags($pass);

            if ($login === ADMIN_LOGIN && $pass === ADMIN_PASS) {
                $_SESSION['login'] = $login;
                $_SESSION['pass'] = $pass;
                $_SESSION['logged_in'] = true;
                $_SESSION['last_activity'] = time();
                header('Location: /panel.php');
                exit;
            } else {
                session_unset();
                session_destroy();
                $_SESSION['mess'] = 'Неверный логин или пароль!';
            }
        }
        // Форма входа
        ?>
        <div class="page-auth">
            <div class="form-auth">
                <form method="post" autocomplete="off">
                    <h1>Авторизация</h1>
                    <div class="input-box">
                        <input type="text" name="login" placeholder="Логин">
                    </div>
                    <div class="input-box">
                        <input type="password" name="pass" placeholder="Пароль">
                    </div>
                    <p><?php echo isset($_SESSION['mess']) ? $_SESSION['mess'] : ''; unset($_SESSION['mess']) ?></p>
                    <button type="submit" name="log_in">Войти</button>
                    <p>Попал сюда случайно? Молча закрой страницу.</p>
                </form>
            </div>
        </div>

        </body>
        </html>
        <?php
        exit;
    }

    if ($_SESSION['login'] != ADMIN_LOGIN || $_SESSION['pass'] != ADMIN_PASS) {
        kill_session();
    }
    if (isset($_POST['logout'])) {
        kill_session();
    }

} else {
    if (!is_ip_allowed(ADMIN_IP)) {
        http_response_code(403);
        echo "<h1>Доступ запрещён!</h1>";
        echo "<p>Ваш IP-адрес ". $_SERVER['REMOTE_ADDR'] ." не имеет доступа к панели управления.</p>";
        exit;
    }
}
// ========================================
// ========================================
function load_links(): array {
    if (!file_exists(DATA_FILE)) return [];
    return json_decode(file_get_contents(DATA_FILE), true) ?: [];
}

function save_links(array $data): bool {
    return file_put_contents(DATA_FILE, json_encode($data, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)) !== false;
}

$success = '';
$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id = $_POST['id'] ?? '';
    $links = load_links();

    if ($action === 'edit' && isset($links[$id])) {
        $number = intval($_POST['number'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $desc = trim($_POST['desc'] ?? '');

        if (!filter_var($url, FILTER_VALIDATE_URL)) $errors[] = "Некорректный URL.";
        if (!$title || !$desc || $number <= 0) $errors[] = "Заполните все поля корректно.";

        if (!$errors) {
            $links[$id] = compact('number', 'title', 'url', 'desc');
            if (save_links($links)) $success = "Ссылка #$id обновлена.";
            else $errors[] = "Ошибка при сохранении.";
        }
    }

    if ($action === 'add') {
        $number = intval($_POST['number'] ?? 0);
        $title = trim($_POST['title'] ?? '');
        $url = trim($_POST['url'] ?? '');
        $desc = trim($_POST['desc'] ?? '');

        if (!filter_var($url, FILTER_VALIDATE_URL)) $errors[] = "Некорректный URL.";
        if (!$title || !$desc || $number <= 0) $errors[] = "Заполните все поля корректно.";

        if (!$errors) {
            $id = time(); // уникальный ID
            $links[$id] = compact('number', 'title', 'url', 'desc');
            if (save_links($links)) $success = "Ссылка добавлена.";
            else $errors[] = "Ошибка при добавлении.";
        }
    }

    if ($action === 'delete' && isset($links[$id])) {
        unset($links[$id]);
        if (save_links($links)) $success = "Ссылка удалена.";
        else $errors[] = "Ошибка при удалении.";
    }
}

$links = load_links();

?>
<div class="panel">
    <div class="top-bar">
        <h1>Панель управления</h1>
        <form method="post">
            <button type="submit" name="logout" onclick="return confirm('Выйти?')">Выйти</button>
        </form>
    </div>

    <?php if ($success): ?>
        <div class="message success"><?= vx_val($success) ?></div>
    <?php endif; ?>
    <?php if ($errors): ?>
        <div class="message error">
            <ul><?php foreach ($errors as $e): ?><li><?= vx_val($e) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <button onclick="openModal('add')">Добавить ссылку</button>
    
    <br><br>
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Номер</th>
                <th>Заголовок</th>
                <th>URL</th>
                <th>Описание</th>
                <th>Действия</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($links as $id => $link): ?>
            <tr>
                <td><?= $id ?></td>
                <td><?= (int)$link['number'] ?></td>
                <td><?= vx_val($link['title']) ?></td>
                <td><a href="<?= vx_val($link['url']) ?>" target="_blank"><?= vx_val($link['url']) ?></a></td>
                <td><?= vx_val($link['desc']) ?></td>
                <td class="actions">
                    <button onclick="openModal('edit', '<?= $id ?>')">Редактировать</button>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $id ?>">
                        <button onclick="return confirm('Удалить ссылку?')">Удалить</button>
                    </form>
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

    <!-- Модальное окно -->
    <div id="modal" class="modal" onclick="closeModal(event)">
        <div class="modal-content">
            <h2 id="modal-title">Добавить / Редактировать</h2>
            <form method="post" id="modal-form">
                <input type="hidden" name="action" id="modal-action" value="add">
                <input type="hidden" name="id" id="modal-id">

                <label>Номер:</label>
                <input type="number" name="number" id="form-number" required>

                <label>Заголовок:</label>
                <input type="text" name="title" id="form-title" required>

                <label>URL:</label>
                <input type="url" name="url" id="form-url" required>

                <label>Описание:</label>
                <textarea name="desc" id="form-desc" rows="3" required></textarea>

                <button type="submit">Сохранить</button>
                <button type="button" onclick="closeModal()">Отмена</button>
            </form>
        </div>
    </div>

    <?= $vxPanel_footer ?>

    <script>
        const links = <?= json_encode($links, JSON_UNESCAPED_UNICODE | JSON_HEX_TAG) ?>;

        function openModal(mode, id = '') {
            const modal = document.getElementById('modal');
            const title = document.getElementById('modal-title');
            const form = document.getElementById('modal-form');
            document.getElementById('modal-action').value = mode;

            if (mode === 'edit' && links[id]) {
                title.textContent = 'Редактировать ссылку';
                form.action.value = 'edit';
                document.getElementById('modal-id').value = id;
                document.getElementById('form-number').value = links[id].number;
                document.getElementById('form-title').value = links[id].title;
                document.getElementById('form-url').value = links[id].url;
                document.getElementById('form-desc').value = links[id].desc;
            } else {
                title.textContent = 'Добавить новую ссылку';
                document.getElementById('modal-id').value = '';
                form.reset();
            }

            modal.style.display = 'flex';
        }

        function closeModal(e) {
            if (!e || e.target === document.getElementById('modal')) {
                document.getElementById('modal').style.display = 'none';
            }
        }
    </script>
</div>
</body>
</html>