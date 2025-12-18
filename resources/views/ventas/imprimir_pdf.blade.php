<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Imprimiendo Ticket...</title>
    <style>
        body, html { margin: 0; padding: 0; height: 100%; overflow: hidden; }
        iframe { width: 100%; height: 100%; border: none; }
    </style>
</head>
<body>

    <!-- Este iframe cargará el PDF -->
    <iframe id="pdf-frame" src="{{ $urlPdf }}"></iframe>

    <script>
        const iframe = document.getElementById('pdf-frame');
        
        // Variable para controlar que 'onafterprint' no se llame dos veces
        let hasPrinted = false;

        // Función para cerrar la ventana
        const closeWindow = () => {
            if (!hasPrinted) {
                hasPrinted = true;
                window.close();
            }
        };

        // Cuando el iframe (con el PDF) termine de cargar...
        iframe.onload = function() {
            try {
                // 1. Lanza el diálogo de impresión
                iframe.contentWindow.print();
                
                // 2. Escucha cuándo se cierra el diálogo de impresión
                iframe.contentWindow.onafterprint = closeWindow;

            } catch (e) {
                console.error("Error al imprimir:", e);
                // Si algo falla, al menos intenta cerrar la ventana
                closeWindow();
            }
        };

        // Fallback por si 'onload' no se dispara (raro, pero seguro)
        setTimeout(function() {
            try {
                if (!hasPrinted && iframe.contentWindow && iframe.contentWindow.print) {
                    iframe.contentWindow.print();
                    iframe.contentWindow.onafterprint = closeWindow;
                }
            } catch(e) {
                console.error("Error en fallback de impresión:", e);
                closeWindow();
            }
        }, 2500); // Espera 2.5 segundos
    </script>

</body>
</html>