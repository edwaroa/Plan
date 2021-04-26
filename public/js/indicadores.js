var aspecto = document.getElementById('id_aspecto');
var peso_total = document.getElementById('peso_total');

aspecto.addEventListener('change', function () {
    var params = {
        'id_aspecto': this.value
    }
    axios.post('/pesoind', params)
        .then(function(res){
            var data = res.data;
            peso_total.innerHTML = "Puede agregar " + data.peso_total;
        })
});
