<?php
require_once(__DIR__ . '/core.php');

?>

<!DOCTYPE html>
<html lang="ru" data-theme="<?= $theme ?>">
<head>
    <title><?= $vxDisp_siteName ?></title>
    <?= $vxConfig_head ?>

    <script>
        // Функция для установки куки с темой
        const setColorSchemeCookie = (colorScheme) => {
            const expires = new Date();
            expires.setFullYear(expires.getFullYear() + 1); // Кука будет действовать 1 год
            document.cookie = `browserTheme=${colorScheme}; expires=${expires.toUTCString()}; path=/; SameSite=Lax`;
            console.log(`Кука темы установлена: ${colorScheme}`);
        };

        // Обработчик изменения темы
        window.matchMedia('(prefers-color-scheme: dark)').addEventListener('change', event => {
            const newColorScheme = event.matches ? "dark" : "light";
            setColorSchemeCookie(newColorScheme); // Устанавливаем куку с новой темой
        });

        // Проверяем текущую тему при загрузке страницы и устанавливаем куку
        const initialColorScheme = window.matchMedia('(prefers-color-scheme: dark)').matches ? "dark" : "light";
        setColorSchemeCookie(initialColorScheme);
        console.log(document.cookie);

        // Проверяем, установлена ли кука для темы, иначе перезагружаем
        if (!document.cookie.includes('browserTheme')) {
            // Кука не установлена, перезагружаем страницу
            window.location.reload();
        }
    </script>

    <?php
    // Проверка и установка куки в PHP
    if (THEME_DEFAULT != false) {
        if (!isset($_COOKIE['theme'])) {
            $d_theme = THEME_DEFAULT;
            setcookie('theme', $d_theme, time() + 31536000, '/');
            // Здесь используем JavaScript редирект для того, чтобы куки успели сохраниться
            echo "<script>window.location.href = '/';</script>";
            exit;
        }
    } else {
        ?>
        <script>
            if (!document.cookie.includes('theme')) {
                // Кука не установлена, перезагружаем страницу
                window.location.reload();
            }
        </script>
        <?php
        if (!isset($_COOKIE['theme'])) {
            $d_theme = $_COOKIE['browserTheme'];
            setcookie('theme', $d_theme, time() + 31536000, '/');
            // Здесь используем JavaScript редирект для того, чтобы куки успели сохраниться
            echo "<script>window.location.href = '/';</script>";
            exit;
        }
    }
    ?>
</head>

<body>
    <div class="main-content">
        <div class="header">
            <h1><?= $vxDisp_siteName ?></h1>
            <div class="theme-toggle" onclick="toggleTheme()" title="Сменить тему" role="button" aria-label="Переключить тему">
                <span id="theme-icon"><?= $theme === 'light' ? '☾' : '☼' ?></span>
            </div>
        </div>

        <div class="table-box">
            <table>
                <thead>
                    <tr>
                        <th>№</th>
                        <th>Сайт</th>
                        <th>Описание</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($data_links)): ?>
                        <tr>
                            <td colspan="3" style="text-align: center; font-weight: 500;">Нет доступных ссылок.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($data_links as $link): ?>
                            <tr>
                                <td><?= vx_val($link['number']) ?></td>
                                <td><a href="<?= vx_val($link['url']) ?>" <?= $vxDisp_targetMode ?> title="Перейти на сайт: <?= vx_val($link['url']) ?>" ><?= vx_val($link['title']) ?></a></td>
                                <td><?= vx_val($link['desc']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <?= $vxConfig_footer ?>

    <script>
        function toggleTheme() {
            const html = document.documentElement;
            const currentTheme = html.getAttribute('data-theme');
            const newTheme = currentTheme === 'dark' ? 'light' : 'dark';
            html.setAttribute('data-theme', newTheme);
            document.cookie = "theme=" + newTheme + "; path=/; max-age=" + (60 * 60 * 24 * 365);
            document.getElementById('theme-icon').textContent = newTheme === 'dark' ? '☼' : '☾';
        }

        document.querySelector('.theme-toggle').addEventListener('keydown', (e) => {
            if (e.key === 'Enter' || e.key === ' ') {
                e.preventDefault();
                toggleTheme();
            }
        });
    </script>
</body>
</html>