// Peso Total
var factor = document.getElementById('id_factor');
var peso_total = document.getElementById('peso_total');

factor.addEventListener('change', function () {
    var params = {
        'id_factor': this.value
    }
    axios.post('/pesocar', params)
        .then(function(res){
            var data = res.data;
            peso_total.innerHTML = "puede agregar " + data.peso_total;
        })
});
