/** 
 * Busca dados de avaliaçãoes a partir de fontes externas
 * Inicialmente busca os dados da API aberta do Mercado Livre
 * https://developers.mercadolivre.com.br/pt_br/produto-autenticacao-autorizacao/
 */


 // Boilerplate
 // Define o Endpoint da API para ser acessada
  const PRODUCT_ENDPOINT = 'https://api.mercadolibre.com/reviews/item/MLB1080602222';

/* 
 *  Get reviews
 *  https://api.mercadolibre.com/reviews/item/MLB1080602222
 *  https://api.mercadolibre.com/reviews/item/MLB1080602222/?offset=0
 *  Response: "reviews": [],
 *  Os dados serão obtidos a cada acesso à pagina
 *  Definir estratégias para persistir os dados sem poluir a base da DT3
 *  Dados relevantes:
 *  - total
 *  reviews:
 *  - title
 *  - content
 *  - rate
 *  rating_average
 *  rating_levels
 *  - one_star
 *  - two_star
 *  - three_star
 *  - four_star
 *  - five_star
 *  TODO:
 *  Paginar com base na API
 */

// Executa uma chamada de API e retorna um objeto JSON

function dt3MakeApiCall( endpoint ) {
    fetch( endpoint ).then( function(response) {
        response.json().then(
            function( data ) {
                console.log( data );
                // Incializa o total de avaliações
                const total_interno = parseInt( document.querySelector('.total-avaliations').innerText );
                var results = document.createElement('div');
                var relevantData = '<ul>';
                relevantData = relevantData + '<li>Total: '   + data.paging.total;
                relevantData = relevantData + '<li>Pagina: '   + data.paging.offset;
                relevantData = relevantData + '<li>Average: ' + data.rating_average;
                relevantData = relevantData + '<li>Levels: ';
                relevantData = relevantData + '<li>Five Star: '   + data.rating_levels.five_star;
                relevantData = relevantData + '<li>Four Star: '   + data.rating_levels.four_star;
                relevantData = relevantData + '<li>Three Star: '  + data.rating_levels.three_star;
                relevantData = relevantData + '<li>Two Star: '    + data.rating_levels.two_star;
                relevantData = relevantData + '<li>One Star: '    + data.rating_levels.one_star;
                relevantData = relevantData + '<li>Nova média: '  + dt3ExAverage( data.rating_average, data.paging.total );
                relevantData = relevantData + '<li>Estrela 5: '  + dt3ExStar( data.paging.total, data.rating_levels.five_star, 5, total_interno );
                relevantData = relevantData + '<li>Estrela 4: '  + dt3ExStar( data.paging.total, data.rating_levels.four_star, 4, total_interno );
                relevantData = relevantData + '<li>Estrela 3: '  + dt3ExStar( data.paging.total, data.rating_levels.three_star, 3, total_interno );
                relevantData = relevantData + '<li>Estrela 2: '  + dt3ExStar( data.paging.total, data.rating_levels.two_star, 2, total_interno );
                relevantData = relevantData + '<li>Estrela 1: '  + dt3ExStar( data.paging.total, data.rating_levels.one_star, 1, total_interno );
                relevantData = relevantData + '</ul> ';
                // document.getElementById('header-rating').innerHTML = relevantData;
                document.querySelector('.title').innerHTML = relevantData;
                // Função herdado do index.js para recalcular as barras 
                dt3PercentBar();
            });
    }).catch(
        function(err) {
            console.error('Failed retrieving information', err);
    });
}

dt3MakeApiCall( PRODUCT_ENDPOINT );


// Recebe nova média externa, o total de avaliações externas
// Calcula a nova média
// Exibe o valor na pagina do produto
function dt3ExAverage( exaverage, extotal ) {

    let average = parseFloat( document.querySelector('.average-number').innerText );
    let total = parseFloat( document.querySelector('.total-avaliations').innerText );

    var new_total = dt3ExSum( extotal );
    var new_average;

    // A nova média é igual média ponderada das médias interna e externa
    // usando o total de avaliações como peso.
    new_average = (( exaverage*extotal ) + ( average*total )) / ( new_total );

    // Arredonda para 1 casa decimal
    new_average = new_average.toFixed(1);
    
    // Exibe o novo total
    document.querySelector('.total-avaliations').innerText = String( new_total );

    // Exibe a nova média
    document.querySelector('.average-number').innerText = String( new_average );

    // Retorna o valor
    return new_average;

}

// Recebe o Objeto JSON e recalcula o total de avaliações
function dt3ExSum( extotal ) {

    let total = parseFloat( document.querySelector('.total-avaliations').innerText );
    var new_total = extotal + total;

    return new_total;

}

// Recebe o extotal, a quantidade de cada estrela
// o numero da estrela e o total de avaliaçoes internas.
// Recalcula a porcentagem de cada estrela
// ( star(%) * total + exstar * 100 ) / new_total
function dt3ExStar( extotal, exstar, star, total ) {

    let perc_star = parseInt( document.querySelector('.percent-card-'+ star +'>div>p.percent-number').innerText );
    let new_total = parseInt( document.querySelector('.total-avaliations').innerText );

    new_perc_star = ( ( perc_star * total ) + exstar * 100 ) / new_total;
    new_perc_star = new_perc_star.toFixed();

    document.querySelector('.percent-card-'+ star +'>div>p.percent-number').innerText = String( new_perc_star ) + '%';

    return new_perc_star;

}


// Redesenha as estrelas

// Redesenhas as porcentagens - OK
// dt3PercentBar();

// Adiciona os comentários dos usuários externos

// Recalcula os numero de usuários recomendados.