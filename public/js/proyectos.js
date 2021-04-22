var plan = document.getElementById('id_plan');
var peso_total = document.getElementById('peso_total');

plan.addEventListener('change', function () {
    var params = {
        'id_plan': this.value
    }
    axios.post('/pesopro', params)
        .then(function(res){
            var data = res.data;
            peso_total.innerHTML = "Puede agregar " + data.peso_total;
        })
});
