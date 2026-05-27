$(document).ready(function() {
    listar();
    
  
} );



function excluir(id, nome){
    $('#id-excluir').val(id);
    $('#nome-excluido').text(nome);
    var myModal = new bootstrap.Modal(document.getElementById('modalExcluir'), {       });
    myModal.show();
    $('#mensagem-excluir').text('');
}



function inserir(){
    
    $('#mensagem').text('');
    $('#tituloModal').text('Inserir Registro');
    var myModal = new bootstrap.Modal(document.getElementById('modalForm'), {
        backdrop: 'static',
    });
    myModal.show();
    limparCampos();
}






$("#form").submit(function () {
	event.preventDefault();
    var formData = new FormData(this);
    
	$.ajax({
		url: pag + "/inserir",
		type: 'POST',
		data: formData,

		success: function (mensagem) {
            $('#mensagem').text('');
            $('#mensagem').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {
                $('#mensagem').addClass('mensagem-sucesso')
                location. reload('#lista')
                    //$('#nome').val('');
                    //$('#cpf').val('');
                    $('#btn-fechar').click();
                     listar();
                    
                                        
                } else {

                	$('#mensagem').addClass('text-danger')
                    $('#mensagem').text(mensagem)
                   
                    
                   
                }


            },

            cache: false,
            contentType: false,
            processData: false,
            
        });

});

$("#formGer").submit(function () {
	event.preventDefault();
    var formData = new FormData(this);
    
	$.ajax({
		url: pag + "/geraEx",
		type: 'POST',
		data: formData,

		success: function (mensagem) {
            $('#mensagem').text('');
            $('#mensagem').removeClass()
            if (mensagem.trim() == "Salvo com Sucesso") {
                $('#mensagem').addClass('mensagem-sucesso')
                //location. reload('#lista')
                    //$('#nome').val('');
                    //$('#cpf').val('');
                    //('#btn-fechar').click();
                     //listar();
                    
                                        
                } else {

                	$('#mensagem').addClass('text-danger')
                    $('#mensagem').text(mensagem)
                   
                    
                   
                }


            },

            cache: false,
            contentType: false,
            processData: false,
            
        });

});




function listar(){
    $.ajax({
        url: pag + "/listar",
        method: 'POST',
        data: $('#form').serialize(),
        dataType: "html",
        
        success:function(result){
           
            $("#listar").html(result);
            //location. reload()
            
        }
    });
}

$("#form-excluir").submit(function () {
    event.preventDefault();
    var formData = new FormData(this);
    
    $.ajax({
        url: pag + "/excluir",
        type: 'POST',
        data: formData,

        success: function (mensagem) {
            $('#mensagem-excluir').text('');
            $('#mensagem-excluir').removeClass()
            if (["Excluído com Sucesso", "Excluido com Sucesso"].includes(mensagem.trim())) {
                $('#btn-fechar-excluir').click();
                listar();
                limparCampos();
            } else {

                $('#mensagem-excluir').addClass('text-danger')
                $('#mensagem-excluir').text(mensagem)
            }


        },

        cache: false,
        contentType: false,
        processData: false,

    });

});






function mudarStatus(id, ativar){
        $.ajax({
        url: pag + "/mudarstatus",
        method: 'POST',
        data: {id, ativar},
        dataType: "text",
       
        success: function (mensagem) {
            if (mensagem.trim() == "Alterado com Sucesso") {
                listar();
            }               
        },

    });
}


function carregarImg() {
    var target = document.getElementById('target');
    var file = document.querySelector("input[type=file]").files[0];
    var arquivo = file['name'];
    resultado = arquivo.split(".", 2);
        //console.log(resultado[1]);
        if(resultado[1] === 'pdf'){
            $('#target').attr('src', "config/img/pdf.png");
            return;
        }

        var reader = new FileReader();

        reader.onloadend = function () {
            target.src = reader.result;
        };

        if (file) {
            reader.readAsDataURL(file);

        } else {
            target.src = "";
        }
    }

    function parcelar(id, descricao, valor){
        $('#id-parcelar').val(id);
        $('#descricao-parcelar').text(descricao);
        $('#valor-parcelar').val(valor);
        $('#qtd-parcelar').val('');
    
        
        var myModal = new bootstrap.Modal(document.getElementById('modalParcelar'), {       });
        myModal.show();
        $('#mensagem-parcelar').text('');
    }
    
    
    
    
    $("#form-parcelar").submit(function () {
        event.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: pag + "/parcelar",
            type: 'POST',
            data: formData,
    
            success: function (mensagem) {
                $('#mensagem-parcelar').text('');
                $('#mensagem-parcelar').removeClass()
                if (mensagem.trim() == "Parcelado com Sucesso") {
                    $('#btn-fechar-parcelar').click();
                    listar();
                    limparCampos();
                } else {
    
                    $('#mensagem-parcelar').addClass('text-danger')
                    $('#mensagem-parcelar').text(mensagem)
                }
    
    
            },
    
            cache: false,
            contentType: false,
            processData: false,
    
        });
    
    });
    
        
    $("#form-baixar").submit(function () {
        event.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: pag + "/baixar",
            type: 'POST',
            data: formData,
    
            success: function (mensagem) {
                $('#mensagem-baixar').text('');
                $('#mensagem-baixar').removeClass()
                if (mensagem.trim() == "Baixado com Sucesso") {
                    $('#btn-fechar-baixar').click();
                    listar();
                    limparCampos();
                } else {
    
                    $('#mensagem-baixar').addClass('text-danger')
                    $('#mensagem-baixar').text(mensagem)
                }
                console.log(mensagem);
    
            },
    
            cache: false,
            contentType: false,
            processData: false,
    
        });
    
    });



    $("#form-fotos").submit(function () {
        event.preventDefault();
        var formData = new FormData(this);
        
        $.ajax({
            url: pag + "/comprovante",
            type: 'POST',
            data: formData,
    
            success: function (mensagem) {
                $('#mensagem-fotos').text('');
                $('#mensagem-fotos').removeClass()
                if (mensagem.trim() == "Baixado com Sucesso") {
                    //$('#btn-fechar-baixar').click();
                    listar();
                    limparCampos();
                } else {
    
                    $('#mensagem-fotos').addClass('text-danger')
                    $('#mensagem-fotos').text(mensagem)
                }
                console.log(mensagem);
    
            },
    
            cache: false,
            contentType: false,
            processData: false,
    
        });
    
    });


    $(window).on('load',function(){
        setTimeout(function(){ // allowing 3 secs to fade out loader
        $('.page-loader').fadeOut('slow');
        },3500);
    });

    
   
   
