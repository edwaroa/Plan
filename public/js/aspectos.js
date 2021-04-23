var caracteristica = document.getElementById('id_caracteristica');
var peso_total = document.getElementById('peso_total');

caracteristica.addEventListener('change', function () {
    var params = {
        'id_caracteristica': this.value
    }
    axios.post('/pesopro', params)
        .then(function(res){
            var data = res.data;
            peso_total.innerHTML = "Puede agregar " + data.peso_total;
        })
});
