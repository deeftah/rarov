<?php
/**
 * Основные параметры WordPress.
 *
 * Скрипт для создания wp-config.php использует этот файл в процессе
 * установки. Необязательно использовать веб-интерфейс, можно
 * скопировать файл в "wp-config.php" и заполнить значения вручную.
 *
 * Этот файл содержит следующие параметры:
 *
 * * Настройки MySQL
 * * Секретные ключи
 * * Префикс таблиц базы данных
 * * ABSPATH
 *
 * @link https://codex.wordpress.org/Editing_wp-config.php
 *
 * @package WordPress
 */

// ** Параметры MySQL: Эту информацию можно получить у вашего хостинг-провайдера ** //
/** Имя базы данных для WordPress */
define('DB_NAME', 'rr_rarov');

/** Имя пользователя MySQL */
define('DB_USER', 'root');

/** Пароль к базе данных MySQL */
define('DB_PASSWORD', '');

/** Имя сервера MySQL */
define('DB_HOST', 'localhost');

/** Кодировка базы данных для создания таблиц. */
define('DB_CHARSET', 'utf8mb4');

/** Схема сопоставления. Не меняйте, если не уверены. */
define('DB_COLLATE', '');

/**#@+
 * Уникальные ключи и соли для аутентификации.
 *
 * Смените значение каждой константы на уникальную фразу.
 * Можно сгенерировать их с помощью {@link https://api.wordpress.org/secret-key/1.1/salt/ сервиса ключей на WordPress.org}
 * Можно изменить их, чтобы сделать существующие файлы cookies недействительными. Пользователям потребуется авторизоваться снова.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '//]_#wsQl=l/D**weH#`bF: +Wd0K:PjA_YS7s#sS[p4on1.((Cwj6#Bi}]fLXiS');
define('SECURE_AUTH_KEY',  'XNImlK6`P]XvZj!-ty;{Bw|Nz+*AEr73^ZVi[!C}<`LhYU5_cYoMgY`-nj/-mv%k');
define('LOGGED_IN_KEY',    'Hu%7GURui1O-Ic/-+{MPkuaLk%uhdy^lxsP4e}KIym2E$Xu:$93Vi7Dwg;gaf2Wd');
define('NONCE_KEY',        'Ryii|_o)i^>:(D>;$:bX8*%VA8GSZA |PAAllnQA5dr?IZipaGzR,ngRy/0D=0dY');
define('AUTH_SALT',        'n8i(m<o-&_}dC{p.zu(J}/]MI?gkaw%$(%u1>q8{CVZUr7z.#h<M[Bc)kj!o?LT@');
define('SECURE_AUTH_SALT', 'n]_OG~1na0X9~O68}l#YUXtN)`+6qU}tzVRx>%3mX-/XD5CBlFtU7?&%y@<aplF6');
define('LOGGED_IN_SALT',   '/j!DIEx>y&122Jf[}cW-sx{W-bjqT+{73$AF_,W/+Ty:SAx;NXu]eEJ<QwAL1 v9');
define('NONCE_SALT',       '81ki;gWglMadcyUdsn P|i6`>3whl*2J11:SYe,9Kq@V59LX%EV]}w7tbKz<VI`!');

/**#@-*/

/**
 * Префикс таблиц в базе данных WordPress.
 *
 * Можно установить несколько сайтов в одну базу данных, если использовать
 * разные префиксы. Пожалуйста, указывайте только цифры, буквы и знак подчеркивания.
 */
$table_prefix  = 'rr_';

/**
 * Для разработчиков: Режим отладки WordPress.
 *
 * Измените это значение на true, чтобы включить отображение уведомлений при разработке.
 * Разработчикам плагинов и тем настоятельно рекомендуется использовать WP_DEBUG
 * в своём рабочем окружении.
 * 
 * Информацию о других отладочных константах можно найти в Кодексе.
 *
 * @link https://codex.wordpress.org/Debugging_in_WordPress
 */
define('WP_DEBUG', false);

/* Это всё, дальше не редактируем. Успехов! */

/** Абсолютный путь к директории WordPress. */
if ( !defined('ABSPATH') )
	define('ABSPATH', dirname(__FILE__) . '/');

/*define( 'siteurl', 'http://rarov.zzz.com.ua/' );
define( 'home', 'http://rarov.zzz.com.ua/' );*/


define('FTP_HOST', 'localhost');
define('FTP_USER', 'daemon');
define('FTP_PASS', 'xampp');


/** Инициализирует переменные WordPress и подключает файлы. */
require_once(ABSPATH . 'wp-settings.php');

if(is_admin()) { add_filter('filesystem_method', create_function('$a', 'return "direct";' )); define( 'FS_CHMOD_DIR', 0751 ); }
