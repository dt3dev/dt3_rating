/** 
 * Busca dados de avaliaçãoes a partir de fontes externas
 * Inicialmente busca os dados da API aberta do Mercado Livre
 * https://developers.mercadolivre.com.br/pt_br/produto-autenticacao-autorizacao/
 */


 Boilerplate
 // Define o Endpoint da API para ser acessada
  const PRODUCT_ENDPOINT = 'https://api.mercadolibre.com/reviews/item/MLB1080602222';

/* 
 *	Get reviews
 *	https://api.mercadolibre.com/reviews/item/MLB1080602222
 *  https://api.mercadolibre.com/reviews/item/MLB1080602222/?offset=0
 *  Response: "reviews": [],
 *  Os dados serão obtidos a cada acesso à pagina
 *  Definir estratégias para persistir os dados sem poluir a base da DT3
 * 	Dados relevantes:
 *	- total
 *	reviews:
 *	- title
 *	- content
 *	- rate
 *	rating_average
 *	rating_levels
 *	- one_star
 *	- two_star
 *	- three_star
 *	- four_star
 *	- five_star
 *	TODO:
 *	Paginar com base na API
 */
    function makeApiCall() {
    	// const PRODUCT_ENDPOINT = 'https://api.mercadolibre.com/reviews/item/MLB1080602222';
       	fetch(PRODUCT_ENDPOINT).then( function(response){
      			response.json().then( 
      				function(data){
      					console.log(data);
      					var results = document.createElement('div');
      					var relevantData = '<ul>';
               relevantData = relevantData + '<li>Total: '   + data.paging.total;
      					relevantData = relevantData + '<li>Pagina: '   + data.paging.offset;
      					var allReviews = data.reviews;
      					var nReviews = allReviews.length;
      					
      					relevantData = relevantData + '<li>Numero de reviews : '   + nReviews;
      					i = 0;
      					
      					for ( i == 0; i < nReviews; i++  ){ 
      						console.log('Review'); 
                 relevantData = relevantData + '<li>ID: '   + data.reviews[i].id;
      						relevantData = relevantData + '<li>Title: '   + data.reviews[i].title;
      						relevantData = relevantData + '<li>Content: ' + data.reviews[i].content;
      						relevantData = relevantData + '<li>Rate: ' 	  + data.reviews[i].rate;
      					}
      					relevantData = relevantData + '<li>Average: ' + data.rating_average;
      					relevantData = relevantData + '<li>Levels: ';
      					relevantData = relevantData + '<li>One Star: '    + data.rating_levels.one_star;
      					relevantData = relevantData + '<li>Two Star: '    + data.rating_levels.two_star;
      					relevantData = relevantData + '<li>Three Star: '  + data.rating_levels.three_star;
      					relevantData = relevantData + '<li>Four Star: '   + data.rating_levels.four_star;
      					relevantData = relevantData + '<li>Five Star: '   + data.rating_levels.five_star;
      					relevantData = relevantData + '</ul>: ';
      					// var text = document.createTextNode( relevantData );
    				// results.appendChild( text );
    				// document.getElementById( 'results' ).appendChild( results );
    				// document.getElementById('results').innerHTML = '<ol><li>oi<li>oi</ol>';
    				document.getElementById('results').innerHTML = relevantData;
       	 		    });
      			}).catch(
      				function(err){
      				console.error('Failed retrieving information', err);
    			});
    			
    }

	// makeApiCall();

// Executa uma chamada de API e retorna um objeto JSON

function dt3MakeApiCall( endpoint ) {
    fetch( endpoint ).then( function(response) {
        response.json().then(
            function( data ) {
                console.log( data );
                var results = document.createElement('div');
                var relevantData = '<ul>';
                relevantData = relevantData + '<li>Total: '   + data.paging.total;
                relevantData = relevantData + '<li>Pagina: '   + data.paging.offset;
                relevantData = relevantData + '</ul>: ';
                document.getElementById('header-rating').innerHTML = relevantData;
            });
    }).catch(
        function(err) {
            console.error('Failed retrieving information', err);
    });
}

dt3MakeApiCall( PRODUCT_ENDPOINT );


// Recebe o Objeto JSON e recalcula a média

// Recebe o Objeto JSON e recalcula o total de avaliações

// Recebe os Objeto JSON e recalcula a quantidade de cada estrela

// Redesenha as estrelas

// Redesenhas as porcentagens

// Adiciona os comentários dos usuários externos

// Recalcula os numero de usuários recomendados.