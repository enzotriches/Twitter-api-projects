 $(document).ready(function(){
      //Caso fizerem um request 
      $('#form-tweet').submit(function(e){
        //Não deixa recarregar
        e.preventDefault();

        let data; /**/
        let boxAbout = $('#about'); /*Container html 'Sobre'*/
        let boxResponse = $('#response'); /*Container html de Resposta */
        // Fazemos a requisição Ajax dos dados passados
        $.ajax({
          
          url: 'https://localhost/{NOME DA SUA PASTA}'+$('#who').val(), /*Local da request*/
          type: 'GET',
          dataType: 'json',
        
        }).done(function(data){
          
          // Verifica se esta passando json de erro
          if (typeof data.error == 'undefined') {
            // Estrutura dos container com os valores da response
            let html  = '<div class="container-fluid text-dark rounded " style="background-color: rgb(236, 237, 238,0.3); ">';
               html +=    '<small class="small text-secondary text-center justify-content-center">';
               html +=    '<p class="">Nos últimos <b>'+data.tweetsTotais+'</b> tweets o usuário está repetindo <b>'+data.repetidasTotais+'</b> palavras de um total de <b>'+data.palavrasTotais+'</b> palavras comuns</p> <p> (Foram tirados os conectivos)</p>';
               html +=    '</small></div>';
            // Limpa e apresenta os novos dados
            boxAbout.empty();
            boxAbout.append(html);
            boxResponse.empty();
           //Percorre um loop entre os valores json 
           let makeLoop = function(i,data){

              let index = parseInt(i); /*índice*/
              let size  = parseInt(Object.keys(data.data).length); /*Quantidade de objetos*/
              let inverseIndex = ((size - 1)-index); /*Reverso do índice*/
              let structure = ''; /*Onde ficará o html*/
              // Modifica a estrutura se é o mais comentado ou não
              if(index == 0){
                structure = '<div class="container bg-white border  border-primary shadow shadow-success rounded p-4 mb-2 p" style="transition: 2s;">';
              }else{
                structure = '<div class="container bg-white border rounded p-4 mb-2 p" style="transition: 2s;">';
              }
                  //Implementa valores ao html
                  structure += '<p>';
                  structure += '<b>'+(index + 1)+')</b><br>'
                  structure += 'Palavra : <b><u>'+data.data[inverseIndex]['palavra']+'</u></b>;<br> ';
                  structure += 'Frequencia: <b><u>'+data.data[inverseIndex]['frequencia']+' vezes | '+data.data[inverseIndex]['porcentagem']+'%</u></b>';
                  structure += '</p>';
                  structure += '</div>';
               // implementa
               boxResponse.append(structure);
             
            }

            //Percorre todo o json da response da api e aplica a função makeLoop para tratar os dados e implementar ao HTML
            for(var i in data.data){
              makeLoop(i,data);
            };
            
          }else{ // Caso o JSON da request retorne erro:
            let response = "<span class='alert alert-danger'>Nada pra mostrar aqui! Verifica certinho o nome... (Caso esteja correto é erro meu mesmo! Vida que segue!).</span>";
            boxAbout.empty();
            boxResponse.empty();
            boxAbout.append(response);
          }
        }).fail(function(codigo, message){
            //Caso falhe a request
            let response = "<span class='alert alert-danger'>Nada pra mostrar aqui...</span>";
            boxAbout.empty();
            boxResponse.empty();
            boxAbout.append(response);
        
        }).always(function(){
        
         console.log('concluido'); // Para melhor entendimento do que está acontecendo
        
        });
        
        return false;
      });
    });
