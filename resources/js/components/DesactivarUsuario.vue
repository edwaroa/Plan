<template>
     <input type="submit" 
     class="btn btn-danger d-block w-100 mb-2" 
     value="Eliminar" @click="eliminarUsuario">
</template>

<script>
    export default {
        props: ['usuarioId'],
        methods:{
            eliminarUsuario(){
                this.$swal({
                    title: '¿Está seguro?',
                    text: "El usuario será eliminado",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Si',
                    cancelButtonText: 'No'
                    }).then((result) => {
                    if (result.value) {  //(result.isConfirmed) 
                        const params={
                            id:this.usuarioId
                        }
                        //Enviar la petición al servidor
                        axios.post(`/usuarios/${this.usuarioId}`, {params, _method:'delete'})
                            .then(respuesta=>{
                                this.$swal({
                                title: '¡Eliminado!',
                                text: 'Usuario eliminado',
                                icon: 'success'
                                })

                                //Eliminar del DOM
                                this.$el.parentNode.parentNode.parentNode.removeChild(this.$el.parentNode.parentNode);
                            })
                            .catch (error=>{
                                console.log(error)
                            })
                    }
                    })
            }
        }
    }
</script>