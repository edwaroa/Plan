var proyecto = document.getElementById('id_proyecto');
var peso_total = document.getElementById('peso_total');

proyecto.addEventListener('change', function () {
    var params = {
        'id_proyecto': this.value
    }
    axios.post('/pesofac', params)
        .then(function(res){
            var data = res.data;
            peso_total.innerHTML = "Puede agregar " + data.peso_total;
        })
});
