
$(document).ready(function() {    
	$('#example').DataTable({
		"ordering": false
	});

} );


function editar(id, nivel){
	$('#id').val(id);
	$('#nivel').val(nivel);
	$('#tituloModal').text('Editar Registro');
	var myModal = new bootstrap.Modal(document.getElementById('modalForm'), {		});
	myModal.show();

}



function limparCampos(){
	$('#id').val('');
	$('#nivel').val('');
	
}

