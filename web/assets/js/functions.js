/**
 * Created by Marc on 15/05/2017.
 */
/*
function listsection(){

    $.post("HomeService.php", function(data){
        $("$posts").html(data);
    });
}*/

/*
function listsection(){
    $('#div-btn1').click(function(){
        $.ajax({
            type: "POST",
            url: "hola.php",
            success: function(a) {
                $('#div-results').html(a);
            }
        });
    });
});
*/

/*
window.onload = function() {
    // Cargar el recurso solicitado cuando se pulse el botón MOSTRAR CONTENIDOS
    document.getElementById('enviar').onclick = cargaContenido;
}

function cargaContenido() {
    // Borrar datos anteriores
    document.getElementById('contenidos').innerHTML = "";

    // Instanciar objeto XMLHttpRequest
    if(window.XMLHttpRequest) {
        peticion = new XMLHttpRequest();
    }
    else {
        peticion = new ActiveXObject("Microsoft.XMLHTTP");
    }

    // Preparar función de respuesta
    peticion.onreadystatechange = muestraContenido;

    // Realizar petición
    tiempoInicial = new Date();
    var recurso = document.getElementById('recurso').value;
    peticion.open('GET', recurso+'?nocache='+Math.random(), true);
    peticion.send(null);
}

// Función de respuesta
function muestraContenido() {
    var tiempoFinal = new Date();
    var milisegundos = tiempoFinal - tiempoInicial;

    var estados = document.getElementById('estados');
    estados.innerHTML += "[" + milisegundos + " mseg.] " + estadosPosibles[peticion.readyState] + "<br/>";

    if(peticion.readyState == 4) {
        if(peticion.status == 200) {
            var contenidos = document.getElementById('contenidos');
            contenidos.innerHTML = peticion.responseText.transformaCaracteresEspeciales();
        }
        muestraCabeceras();
        muestraCodigoEstado();
    }
}
*/