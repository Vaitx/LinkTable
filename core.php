<?php
// ========================================
const VERSION = '1.0.0'; 
const DATA_FILE = __DIR__ . '/data_links.json';
// ========================================

require_once(__DIR__ . '/settings.php');


// ========================================
$json = file_get_contents('data_links.json');
$data_links = json_decode($json, true);
// ========================================


// ========================================
// Безопасная обработка входных данных с опцией разрешения HTML
function vx_val($val, $html = false, $filter = false) {
    if (!isset($val)) {
        return '';
    }
    $val = trim($val);
    if ($html) {
        $val = htmlspecialchars($val, ENT_NOQUOTES, 'UTF-8');
        $val = htmlspecialchars_decode($val);
    } else {
        if ($filter) {
            $val = strip_tags($val);
        }
        $val = htmlspecialchars($val, ENT_QUOTES, 'UTF-8');
    }
    return $val;
}
// ========================================


// ========================================
$vxDisp_siteName = vx_val(NAME_SITE);
// ========================================


// ========================================
$theme = $_COOKIE['theme'] ?? 'dark';
$theme = $theme === 'light' ? 'light' : 'dark';
// ========================================


// ========================================
if (TARGET_MODE) {
    $vxDisp_targetMode = 'target="_blank"';
} else { 
    $vxDisp_targetMode = ''; 
}
// ========================================


// ========================================
$vxConfig_head = '
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">

<link rel="stylesheet" type="text/css" href="/assets/main.css">
<link rel="stylesheet" type="text/css" href="/assets/panel.css">

<link rel="icon" type="image/png" href="'. vx_val(ICON_SITE) .'">
';

// ---

if (FOOTER_DISPLAY) {
    $footer_display = '';
} else {
    $footer_display = 'none-display';
}

// ---

$vxConfig_footer = '
<footer class="footer '. $footer_display .'">
    <div class="footer-con">
        <div class="footer-content">
            <p>© '. date('Y') .' Автор проекта: <a href="https://vaitx.ru" target="_blank">Vaitx</a>. Все права защищены.
                <a href="'. FOOTER_SITE_URL .'" target="_blank">'. FOOTER_SITE_NAME .'</a>
            </p>
        </div>
    </div>
</footer>
';

// ---

$vxPanel_footer = '
<footer class="footer">
        <div class="footer-inner">
            <div class="footer-title">LinkTable | Панель управления ссылками</div>
            <div>Автор: <strong><a href="https://vaitx.ru" target="_blank">Vaitx</a></strong> | Версия: <strong>'. VERSION .'</strong> &copy; '. date('Y') .' 
                <a href="https://github.com/Vaitx/vaitx/blob/main/DONATE.md" target="_blank">Поддержать проект</a>
            </div>
            <div class="footer-note">Благодаря вашей активности проект продолжает развиваться ❤️</div>
        </div>
    </footer>
</footer>
';
// ========================================


// ========================================
if (!empty($data_links)) {
    function compareByNumber($a, $b) {
    return $a['number'] <=> $b['number'];
    }

    usort($data_links, 'compareByNumber');

    if (SORTING_END) {
        krsort($data_links);
    }
}
// ========================================