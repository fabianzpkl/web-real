$(document).ready(function () {
    // Obtiene el enlace de cambio de idioma por su ID
    var toggleLanguageButton = $("#toggle-language");

    // Agrega un controlador de eventos para el clic en el enlace
    toggleLanguageButton.click(function (event) {
        // Evita que el enlace realice una acción predeterminada (navegar a una URL)
        event.preventDefault();

        // Utiliza la función de Polylang para cambiar el idioma
        if (typeof pll_switch_language === 'function') {
            var currentLanguage = pll_current_language(); // Obtiene el idioma actual
            var newLanguage = (currentLanguage === 'es') ? 'en' : 'es'; // Alterna entre español ('es') e inglés ('en')

            pll_switch_language(newLanguage); // Cambia el idioma al nuevo idioma
        }
    });
});