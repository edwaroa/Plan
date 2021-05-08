// fechas
var indicador = document.getElementById('id_indicador');
var fecha_inicio = document.getElementById('fecha_inicio');
var tiempo_entrega = document.getElementById('tiempo_entrega');
var fechap_inicio = document.getElementById('fechap_inicio');
var tiempop_entrega = document.getElementById('tiempop_entrega');

var peso_total = document.getElementById('peso_total');

indicador.addEventListener('change', function () {
    var params = {
        'id_indicador': this.value
    }
    axios.post('/fecha', params)
        .then(function(res){
            // Agregando valores a los atributos max y min según corresponda
            fecha_inicio.setAttribute('min', res.data.fecha_plan[0].fecha_inicio);
            fecha_inicio.setAttribute('max', res.data.fecha_plan[0].fecha_final);
            tiempo_entrega.setAttribute('max', res.data.fecha_plan[0].fecha_final);
            tiempo_entrega.setAttribute('min', res.data.fecha_plan[0].fecha_inicio);

            // Añadiendo valores a los span
            fechap_inicio.innerHTML = "-> Minimo desde " + res.data.fecha_plan[0].fecha_inicio;
            tiempop_entrega.innerHTML = "-> Máximo hasta " + res.data.fecha_plan[0].fecha_final;

            // Añadiendo valor al span del peso
            peso_total.innerHTML = "Puede agregar " + res.data.peso_total;

            console.log("peso: " + res.data.cont_peso);
        })
        .catch(error => {
            console.log(error);
        })
});
