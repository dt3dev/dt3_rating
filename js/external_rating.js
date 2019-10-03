/** 
 * Busca dados de avaliaçãoes a partir de fontes externas
 * Inicialmente busca os dados da API aberta do Mercado Livre
 * https://developers.mercadolivre.com.br/pt_br/produto-autenticacao-autorizacao/
 */


 // Boilerplate
 // Define o Endpoint da API para ser acessada
 // 'https://api.mercadolibre.com/sites/MLB/search?nickname=DT3SPORTS&q=%C3%94nix%20Diamond'
 // 'https://api.mercadolibre.com/sites/MLB/search?nickname=DT3+OFICIAL&q=Ônix%20Diamond'
 // Para montar a URl de busca:
 // Usar 'https://api.mercadolibre.com/sites/MLB/search?nickname=DT3+OFICIAL'
 // Use como referência o DT3 Oficial:
 // https://www.mercadolivre.com.br/perfil/DT3+OFICIAL
 // Adicionar nome do produto.
 // Ônix Diamond
 // Formatar como URL
 // Buscar o ID 
 // "results": [ { "id": "MLB1230252313",
 // https://api.mercadolibre.com/reviews/item/MLB1230252313
 // variation=36150943406

 // Itens não retornados:


// Inicializa a URL da Busca
let dt3_product_name = document.querySelector( '#description-wrap>h1:first-child' ).textContent;
console.log( 'Nome do produto: ' + dt3_product_name );

let search = 'https://api.mercadolibre.com/sites/MLB/search?nickname=DT3+OFICIAL&q=' + dt3_product_name;
console.log( 'URL de Busca: ' + search );


// https://api.mercadolibre.com/reviews/item/MLB1230252313
// const PRODUCT_ENDPOINT = 'https://api.mercadolibre.com/reviews/item/' + ml_id;
const PRODUCT_ENDPOINT = 'https://api.mercadolibre.com/reviews/item/MLB1080602222';

// console.log( PRODUCT_ENDPOINT );

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
    // dt3GetMLId( endpoint );
    fetch( endpoint ).then( function(response){
        response.json().then( function( data ) {
            console.log( 'Primeira etapa' );
            console.log( data );
            let new_endpoint = 'https://api.mercadolibre.com/reviews/item/' + data.results[0].id;

            fetch( new_endpoint ).then( function(response) {
                console.log( 'Segunda Etapa: ');
                console.log( new_endpoint );
                response.json().then(
                    function( data ) {
                        console.log( data );
                        // Incializa o total de avaliações
                        const total_interno = parseInt( document.querySelector('.total-avaliations').innerText );
                        let ex_new_average = dt3ExAverage( data.rating_average, data.paging.total );
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
                        relevantData = relevantData + '<li>Nova média: '  + ex_new_average;
                        relevantData = relevantData + '<li>Estrela 5: '  + dt3ExStar( data.paging.total, data.rating_levels.five_star, 5, total_interno );
                        relevantData = relevantData + '<li>Estrela 4: '  + dt3ExStar( data.paging.total, data.rating_levels.four_star, 4, total_interno );
                        relevantData = relevantData + '<li>Estrela 3: '  + dt3ExStar( data.paging.total, data.rating_levels.three_star, 3, total_interno );
                        relevantData = relevantData + '<li>Estrela 2: '  + dt3ExStar( data.paging.total, data.rating_levels.two_star, 2, total_interno );
                        relevantData = relevantData + '<li>Estrela 1: '  + dt3ExStar( data.paging.total, data.rating_levels.one_star, 1, total_interno );
                        relevantData = relevantData + '<li>Estrela 1: '  + dt3ExRecomendations( data.rating_levels.five_star, data.rating_levels.four_star );
                        dt3RedrawStars ( ex_new_average );
                        dt3ExReviews( data.reviews, data.paging.offset );
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

        });

    });
    
}

// dt3MakeApiCall( PRODUCT_ENDPOINT );
dt3MakeApiCall( search );


// Recebe nova média externa, o total de avaliações externas
// Calcula a nova média
// Exibe o valor na pagina do produto
function dt3ExAverage( exaverage, extotal ) {

    let average = parseFloat( document.querySelector('.average-number').innerText );
    let total = parseFloat( document.querySelector('.total-avaliations').innerText );

    console.log( 'average' + average );
    console.log( 'total' + total );

    var new_total = dt3ExSum( extotal );
    var new_average;

    // A nova média é igual média ponderada das médias interna e externa
    // usando o total de avaliações como peso.
    if ( 0 == new_total ) {
        // Evita divisão por zero
        new_average = 0;
    } else {
        new_average = (( exaverage*extotal ) + ( average*total )) / ( new_total );
    }

    // Arredonda para 1 casa decimal
    new_average = new_average.toFixed(1);
    
    // Exibe o novo total
    document.querySelector('.total-avaliations').innerText = String( new_total );
    document.querySelector('.total-avaliations-top').innerText = String( new_total );

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
function dt3ExStar( extotal=0, exstar=0, star, total ) {
    // Se não houver avaliação atribua 0% para cada estrela.
    let perc_star = parseInt( document.querySelector('.percent-card-'+ star +'>div>p.percent-number').innerText );
    console.log( 'perc_star' + perc_star  );
    let new_total = parseInt( document.querySelector('.total-avaliations').innerText );
    console.log( 'new_total' + new_total  );

    if ( 0 == new_total ) {
        // Evita divisão por zero
        new_perc_star = 0;    
    } else {
        new_perc_star = ( ( perc_star * total ) + exstar * 100 ) / new_total;
        new_perc_star = new_perc_star.toFixed();
    }

    document.querySelector('.percent-card-'+ star +'>div>p.percent-number').innerText = String( new_perc_star ) + '%';

    return new_perc_star;

}

// Redesenha as estrelas
// Recebe a nova média e 
// Exibe a quantidade de estrelas correspondente
/*
<div class="clipboard-wrapper">
    <img src="https://localhost/dt3/wp-content/plugins/dt3-rating/images/red-star.svg'" alt="">
    <img src="https://localhost/dt3/wp-content/plugins/dt3-rating/images/red-star.svg'" alt="">
    <img src="https://localhost/dt3/wp-content/plugins/dt3-rating/images/red-star.svg'" alt="">
    <img src="https://localhost/dt3/wp-content/plugins/dt3-rating/images/white-star.svg'" alt="">
    <img src="https://localhost/dt3/wp-content/plugins/dt3-rating/images/white-star.svg'" alt="">                           
</div>

// Percorre os imgs e troca apenas os nomes dos arquivos
// document.querySelector( '.clipboard-wrapper>img:nth-child(5)' );

*/
function dt3RedrawStars ( new_average ) {

    let url = document.querySelector( '.clipboard-wrapper>img' ).src;
    let url_array = url.split( '/' );
    url = url_array.slice( 0, url_array.length - 1 );
    url = url.join( '/' );

    let n = 1;
  
    while ( n <= 5 ) {
        if ( n <= new_average ) {
            // console.log( url + '/red-star.svg' );
            document.querySelector( '.clipboard-wrapper>img:nth-child('+ n +')' ).src = url + '/red-star.svg';
        } else {
            // console.log( url + '/white-star.svg' );
            document.querySelector( '.clipboard-wrapper>img:nth-child('+ n +')' ).src = url + '/white-star.svg';
        }
        n++;
    }
}

// Retorna uma string com as estrelas da avaliação
function dt3DrawStars( rating, url ) {

    let n = 1;
    let stars_string = '<div class="comment-stars">';
  
    while ( n <= 5 ) {

        if ( n <= rating ) {
            // console.log( url + '/red-star.svg' );
            stars_string = stars_string + '<img src="' + url   + '/red-star.svg " alt="">';
        } else {
            // console.log( url + '/white-star.svg' );
            stars_string = stars_string + '<img src="' + url   + '/white-star.svg " alt="">';
        }

        n++;
    }

    stars_string = stars_string + '</div>';

    return stars_string;

}

// Calcula o novo número de recomendações
// Pontuações acima de 3 pontos serão consideradas recomendações
// Exibe o valor na pagina
// Retorna o total de recomendações
function dt3ExRecomendations( fivestar, fourstar ) {

    const internal_recomend = parseInt( document.querySelector('.recomendation-number').innerText );
    let ex_recomend = parseInt ( fivestar + fourstar );
    let all_recomend = internal_recomend + ex_recomend;

    document.querySelector('.recomendation-number').innerText = String( all_recomend );

    return all_recomend;

}

// Adiciona os comentários dos usuários externos
// Recebe o objeto review e o offset
// Percorre o as opiniões adcionando abaixo dos comentários existentes
// document.querySelector('.comment-item:first-child').dataset.load

function dt3ExReviews( reviews, offset ) {

    // Quantidade de reviews
    let n_reviews = reviews.length;
    let i = 0;
    let date;
    let json_date;
    let dia, mes, ano;
    let comment_item;
    let comment_stars;
    let comment_title;
    let comment_user_date;
    let comment_positive_point;
    let comment_recomended;
    let last_comment = 0;

    if ( document.querySelector('.comment-item:last-child') ) {
        last_comment = document.querySelector('.comment-item:last-child').dataset.load;
    }

    // Percorre os reviews
    for( i = 0; i < n_reviews ; i++) {

        console.log( 'Via Mercado Livre' );
        // console.log( reviews[i].date_created );
        // console.log( reviews[i].title );
        // console.log( reviews[i].content );

        var node = document.createElement("div");
        var textnode = document.createTextNode("Carregando commentarios externos...");
        node.appendChild(textnode);  
        document.getElementsByClassName("comments")[0].appendChild(node); 

        // Formata a data
        json_date = reviews[i].date_created;
        date = new Date( json_date );
        // console.log( 'Data do comentário: ' + date );
        // console.log( 'DIA: ' + date.getDate() );
        // console.log( 'MÊS: ' + ( date.getMonth() + 1 )  );
        // console.log( 'ANO: ' + date.getFullYear() );

        dia = date.getDate();
        mes = ( date.getMonth() + 1 );
        ano = date.getFullYear();

        comment_item = '<div class="comment-item" data-load="'+ last_comment +'">';
        
        comment_stars = dt3DrawStars( reviews[i].rate, 'https://dt3sports.com.br/wp-content/plugins/dt3-rating/images' );

        comment_title = '<div class="comment-title"> ' +
                '<h3> ' +
                    reviews[i].title +
                '</h3> ' +
            '</div>';

        comment_user_date = '<div class="comment-user-date">' +
                '<p><i>via Mercado Livre</i> em ' + dia + '/' + mes + '/' + ano + '</p>' +
            '</div>';
        comment_positive_point = ' <div class="comment-positive-point">' +
                '<p>' +
                    reviews[i].content +
                '</p>' +
            '</div>';
        comment_recomended = '<div class="comment-recommended">' +
                '<img src="https://sports.dt3.com.br/wp-content/plugins//dt3-rating/images/circle-with-check-symbol.svg" alt="">' +
                '<div class="recommended-text">' +
                    'Recomendaria para um amigo' +
                '</div>' +
            '</div>';

        // Montando o comentário completo
        comment_item = comment_item + comment_stars;
        comment_item = comment_item + comment_title;
        comment_item = comment_item + comment_user_date;
        comment_item = comment_item + comment_positive_point;
        if ( reviews[i].rate > 3 ) {
            comment_item = comment_item + comment_recomended;
        } 
        comment_item = comment_item + '</div>';
        
        /// document.querySelector('.comments>div:last-child').innerHTML= '<div class="comment-item">Esse é um comentario de verdade</div>';
        document.querySelector('.comments>div:last-child').innerHTML= comment_item;

    }

}


