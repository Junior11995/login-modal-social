<?php
/*
Plugin Name: Login Modal Junior Valera
Plugin URI: https://juniorvalera.com
Description: Añade una ventana modal de inicio de sesión en tu sitio.
Version: 1.1
Author: Junior Alexis Valera
Author URI: https://juniorvalera.com
License: GPL2
*/

// Añadir el botón al menú principal (opcional)
function login_modal_add_menu_button($items, $args) {
    if ($args->theme_location == 'primary') {
        $items .= '<li class="menu-item login-modal-button"><button id="openLoginModal" class="open-login-btn">Iniciar Sesión</button></li>';
    }
    return $items;
}
add_filter('wp_nav_menu_items', 'login_modal_add_menu_button', 10, 2);

// Enqueue CSS and JavaScript
function login_modal_enqueue_scripts() {
    wp_enqueue_style('login-modal-style', plugin_dir_url(__FILE__) . 'css/login-modal-style.css');
    wp_enqueue_script('login-modal-script', plugin_dir_url(__FILE__) . 'js/login-modal-script.js', array('jquery'), null, true);
}
add_action('wp_enqueue_scripts', 'login_modal_enqueue_scripts');

// Generar el HTML del modal
function login_modal_html() {
    ob_start(); // Iniciar la captura de salida
    ?>
    <!-- Estructura del modal -->
    <div id="loginModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Iniciar Sesión</h2>

            <!-- Botones de inicio de sesión con Google y Facebook -->
            <div class="social-login-btns">
                <button class="social-login-btn google-btn">
                    <img src="https://img.icons8.com/color/24/000000/google-logo.png" alt="Google"> Google
                </button>
                <button class="social-login-btn facebook-btn">
                    <img src="https://img.icons8.com/color/24/000000/facebook.png" alt="Facebook"> Facebook
                </button>
            </div>

            <p>o utiliza tu cuenta:</p>

            <form id="loginForm" action="<?php echo wp_login_url(); ?>" method="post">
                <label for="username">Usuario:</label>
                <input type="text" id="username" name="log" required>
                <label for="password">Contraseña:</label>
                <input type="password" id="password" name="pwd" required>
                <button type="submit">Entrar</button>
            </form>
        </div>
    </div>
    <?php
    return ob_get_clean(); // Devolver el contenido capturado
}

// Registrar el shortcode
function login_modal_shortcode() {
    return login_modal_html();
}
add_shortcode('login_modal', 'login_modal_shortcode');

// Crear un menú en la administración
function login_modal_admin_menu() {
    add_menu_page(
        'Configuración de Modal', // Título de la página
        'Login Modal', // Título del menú
        'manage_options', // Capacidad
        'login-modal-settings', // Slug
        'login_modal_settings_page', // Función de la página
        'dashicons-admin-generic' // Icono
    );
}
add_action('admin_menu', 'login_modal_admin_menu');

// Función para mostrar la página de configuración
function login_modal_settings_page() {
    ?>
    <div class="wrap">
        <h1>Configuración de Login Modal</h1>
        <p>Utiliza el shortcode <code>[login_modal]</code> para mostrar el modal de inicio de sesión en cualquier parte de tu sitio.</p>
        <h2>Configuración de SDK</h2>
        <form method="post" action="options.php">
            <?php
            settings_fields('login_modal_options_group');
            do_settings_sections('login_modal_options_group');
            ?>
            <table class="form-table">
                <tr valign="top">
                    <th scope="row">SDK de Google</th>
                    <td><input type="text" name="google_sdk" value="<?php echo esc_attr(get_option('google_sdk')); ?>" /></td>
                </tr>
                <tr valign="top">
                    <th scope="row">SDK de Facebook</th>
                    <td><input type="text" name="facebook_sdk" value="<?php echo esc_attr(get_option('facebook_sdk')); ?>" /></td>
                </tr>
            </table>
            <?php submit_button(); ?>
        </form>
    </div>
    <?php
}

// Registrar los ajustes
function login_modal_register_settings() {
    register_setting('login_modal_options_group', 'google_sdk');
    register_setting('login_modal_options_group', 'facebook_sdk');
}
add_action('admin_init', 'login_modal_register_settings');
