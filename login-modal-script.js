jQuery(document).ready(function($) {
    // Mostrar el modal cuando se hace clic en el botón
    $('#openLoginModal').on('click', function() {
        $('#loginModal').css('display', 'block');
    });

    // Cerrar el modal cuando se hace clic en el botón de cerrar
    $('.close').on('click', function() {
        $('#loginModal').css('display', 'none');
    });

    // Cerrar el modal si se hace clic fuera del contenido del modal
    $(window).on('click', function(event) {
        if ($(event.target).is('#loginModal')) {
            $('#loginModal').css('display', 'none');
        }
    });
});


