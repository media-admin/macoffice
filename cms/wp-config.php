<?php
/**
 * Grundeinstellungen für WordPress
 *
 * Diese Datei wird zur Erstellung der wp-config.php verwendet.
 * Du musst aber dafür nicht das Installationsskript verwenden.
 * Stattdessen kannst du auch diese Datei als „wp-config.php“ mit
 * deinen Zugangsdaten für die Datenbank abspeichern.
 *
 * Diese Datei beinhaltet diese Einstellungen:
 *
 * * MySQL-Zugangsdaten,
 * * Tabellenpräfix,
 * * Sicherheitsschlüssel
 * * und ABSPATH.
 *
 * @link https://wordpress.org/support/article/editing-wp-config-php/
 *
 * @package WordPress
 */

// ** MySQL-Einstellungen - Diese Zugangsdaten bekommst du von deinem Webhoster. ** //
/**
 * Ersetze datenbankname_hier_einfuegen
 * mit dem Namen der Datenbank, die du verwenden möchtest.
 */
define( 'DB_NAME', 'macoffice-maintain' );

/**
 * Ersetze benutzername_hier_einfuegen
 * mit deinem MySQL-Datenbank-Benutzernamen.
 */
define( 'DB_USER', 'media-admin' );

/**
 * Ersetze passwort_hier_einfuegen mit deinem MySQL-Passwort.
 */
define( 'DB_PASSWORD', 'Tr1-I7ad#1n' );

/**
 * Ersetze localhost mit der MySQL-Serveradresse.
 */
define( 'DB_HOST', 'localhost' );

/**
 * Der Datenbankzeichensatz, der beim Erstellen der
 * Datenbanktabellen verwendet werden soll
 */
define( 'DB_CHARSET', 'utf8' );

/**
 * Der Collate-Type sollte nicht geändert werden.
 */
define('DB_COLLATE', '');

/**#@+
 * Sicherheitsschlüssel
 *
 * Ändere jeden untenstehenden Platzhaltertext in eine beliebige,
 * möglichst einmalig genutzte Zeichenkette.
 * Auf der Seite {@link https://api.wordpress.org/secret-key/1.1/salt/ WordPress.org secret-key service}
 * kannst du dir alle Schlüssel generieren lassen.
 *
 * Du kannst die Schlüssel jederzeit wieder ändern, alle angemeldeten
 * Benutzer müssen sich danach erneut anmelden.
 *
 * @since 2.6.0
 */
define('AUTH_KEY',         '+-JM=[`Z`^Jj!bV)veDi<|ebQW #OIxU_iwbuOqVWnk]Z|k<eL~sZl}Pz|q&*6>[');
define('SECURE_AUTH_KEY',  '{[5j!gb6IkfVM$%X e4-Uc!+^tx8E^$uUQ7dMY1E{`q,m6S6o[rkvob=AkfDB0Mo');
define('LOGGED_IN_KEY',    ':1ZFmbs( }3k/4y[E<wE,eoi%9 TPQY[rHSr*z!gmo1Xlwqb{a$set`UoLf8C4H,');
define('NONCE_KEY',        '(;mybZN2mTbeK~iB-7Nw.u^-GT$(kOHnCNqEuRG_jytO*mlGoT![xw-vGG|=6|]=');
define('AUTH_SALT',        'k>3sa wVls)dvzB^gfjsquDb}I1JzA_0,#8D24aB9*afX{,/@*1r3(@Vw{}g WnW');
define('SECURE_AUTH_SALT', ' 4pQ[+ 0-Z|G]XH--lC8sXb(d4)8{CG1Z@8dl4]|Rz3L_j:G.SP]83z|!7&h3O_3');
define('LOGGED_IN_SALT',   '9om{yyozq14v~6@BfNT$^ywLR/|g_K}d!aH?d|~n,I;[_:-ZVOYuiqmQj8/fB!Md');
define('NONCE_SALT',       '$TM0c{}`{QS#{%g]9p:jK}Nc]D%3E?2(mf`[=B0&k)Sm-%>GW3c2b|&fsn80|e|S');

/**#@-*/

/**
 * WordPress Datenbanktabellen-Präfix
 *
 * Wenn du verschiedene Präfixe benutzt, kannst du innerhalb einer Datenbank
 * verschiedene WordPress-Installationen betreiben.
 * Bitte verwende nur Zahlen, Buchstaben und Unterstriche!
 */
$table_prefix = 'moff_';

/**
 * Für Entwickler: Der WordPress-Debug-Modus.
 *
 * Setze den Wert auf „true“, um bei der Entwicklung Warnungen und Fehler-Meldungen angezeigt zu bekommen.
 * Plugin- und Theme-Entwicklern wird nachdrücklich empfohlen, WP_DEBUG
 * in ihrer Entwicklungsumgebung zu verwenden.
 *
 * Besuche den Codex, um mehr Informationen über andere Konstanten zu finden,
 * die zum Debuggen genutzt werden können.
 *
 * @link https://wordpress.org/support/article/debugging-in-wordpress/
 */
define( 'WP_DEBUG', false );

/* Füge individuelle Werte zwischen dieser Zeile und der „Schluss mit dem Bearbeiten“ Zeile ein. */



/* Das war’s, Schluss mit dem Bearbeiten! Viel Spaß. */
/* That's all, stop editing! Happy publishing. */

/** Der absolute Pfad zum WordPress-Verzeichnis. */
if ( ! defined( 'ABSPATH' ) ) {
	define( 'ABSPATH', __DIR__ . '/' );
}

/** Definiert WordPress-Variablen und fügt Dateien ein.  */
require_once ABSPATH . 'wp-settings.php';
