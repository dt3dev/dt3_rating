// Ajusta a barra de porcentagem de cada avaliacao
dt3PercentBar();	

// Dispara o listener do form
dt3SubmitRating();

// Listener of more
dt3MoreRatings();

const rating_name = document.getElementById('rating_name')
const rating_email = document.getElementById('rating_email')
const rating_title = document.getElementById('rating_title')
const rating_stars = document.getElementById('rating_stars')
const rating_recommendation = document.getElementById('rating_recommendation')
const inputs = [rating_name, rating_email, rating_title]
const radios = [rating_stars, rating_recommendation]

inputs.map(function(item){
	item.children[2].addEventListener('keyup', function(){
		if(item.children[2].value.trim() === ''){
			item.children[2].classList.add('form-error')
			item.children[0].classList.remove('hide-tooltip')
		}else{
			item.children[2].classList.remove('form-error')
			item.children[0].classList.add('hide-tooltip')
		}
	})
})
const starsRadio = document.querySelectorAll('input[name="rating"]')
const starsRadio2 = Array.prototype.slice.call(starsRadio);
starsRadio2.map(function(item){
	item.addEventListener('change', function(){
		rating_stars.children[0].classList.add('hide-tooltip')
	})
})
const recoRadio = document.querySelectorAll('input[name="recommendations-radio"]')
const recoRadio2 = Array.prototype.slice.call(recoRadio);
recoRadio2.map(function(item){
	item.addEventListener('change', function(){
		rating_recommendation.children[0].classList.add('hide-tooltip')
	})
})


// Define a URL do site atual
// Inicializada no template.ph
// const url_now = window.location.origin;
// const host_now = window.location.hostname;

// Constrói a URL para interação ajax
var url_ajax = url_now + 'wp-admin/admin-ajax.php';

// reCaptcha
var token = true;
var verifyCallback = function(response) {
	// token = response;
};
var onloadCallback = function() {
  grecaptcha.render('dt3-captcha', {
    'sitekey' : '',
    'callback' : verifyCallback,
  });
};


function validateEmail(email) {
	var re = /^(([^<>()[\]\\.,;:\s@\"]+(\.[^<>()[\]\\.,;:\s@\"]+)*)|(\".+\"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
	return re.test(email);
}

// Cria função que capta o clique no submit
function dt3SubmitRating() {
	const ratingStars2 = document.getElementsByClassName('rating-stars')
	$( '.rating-submit' ).on( 'click', function() {
		if ( token ) {
			

			let count = 0
			let rating = {};
			rating.rating_title 			= $('.rating-title').val();
			rating.rating_stars 			= $("input[name='rating']:checked"). val();
			rating.rating_name 			= $('.rating-name').val();
			rating.rating_email 			= $('.rating-email').val();
			rating.rating_positive 		= $('.rating-positive').val();
			rating.rating_negative 		= $('.rating-negative').val();
			rating.rating_confort 			= $("input[name='confort-radio']:checked").val();
			rating.rating_quality 			= $("input[name='quality-radio']:checked").val();
			rating.rating_features			= $("input[name='features-radio']:checked").val();
			rating.rating_recomendations	= $("input[name='recommendations-radio']:checked").val();
			rating.product_post_id 		= $('.button-submit').data('post');

			if(!rating.rating_name){
				count++
				rating_name.children[0].classList.remove('hide-tooltip')
			}
			console.log('email validator', validateEmail(rating.rating_email))
			if(!rating.rating_email || !validateEmail(rating.rating_email)){
				count++
				rating_email.children[0].classList.remove('hide-tooltip')
			}
			if(!rating.rating_title){
				count++
				rating_title.children[0].classList.remove('hide-tooltip')
			}
			if(!rating.rating_stars){
				count++
				rating_stars.children[0].classList.remove('hide-tooltip')
			}
			if(!rating.rating_recomendations){
				count++
				rating_recommendation.children[0].classList.remove('hide-tooltip')
			}
			if(count === 0){
				dt3SendRating( rating );
			}

		} else {
			// debugger
			jQuery("#dt3-captcha").append('<div><br>Clique em "Não sou um robô"</div>');
		}
	});
}

// Junta as variaveis em um array e prepara para envio
function dt3SendRating( rating ) {
    // if ( 'number' === typeof( product_id ) ) {
    var my_data = {
	    action: 'dt3_save_rating', // This is required so WordPress knows which func to use
        rating_title: rating[ 'rating_title' ],
       	rating_stars: rating[ 'rating_stars' ],
		rating_name: rating[ 'rating_name' ],
		rating_email: rating[ 'rating_email' ],
		rating_positive: rating[ 'rating_positive' ],
		rating_negative: rating[ 'rating_negative' ],
		rating_confort: rating[ 'rating_confort' ],
		rating_quality: rating[ 'rating_quality' ],
		rating_features: rating[ 'rating_features' ],
		rating_recomendations: rating[ 'rating_recomendations' ],
		product_post_id: rating[ 'product_post_id' ],
    };
      // }

	// Dispara o envio ajax
    dt3RatingAjax( my_data );
}

// Cria funcção para envio via ajax
function dt3RatingAjax( data ) {
	$.ajax({
        url: url_ajax,
        type: 'POST',
        data: data,
        success: function (response) {
            // console.log(response);
            // Transforma o response em objeto
            resp = JSON.parse(response);
            // // console.log(resp);
            // $('.success-message').show();
            var response_message 	= ' <section class="success-message" style="display:block;">';
            	response_message 	+= '<div class="success-message-wrapper">';
            	response_message 	+= '<h3>A DT3 agradece a sua avaliação!</h3>';
            	response_message 	+= '<p>Sua opinião é muito importante para nós.</p>';
            	response_message 	+= '<p>A avaliação será publicada em breve.</p>';
            	response_message 	+= '</div>';
        		response_message 	+=	'</section>';
            $('.action-form').html( response_message );
        },
        beforeSend: function () {
            $('.action-form').html('<center style="height:32vh; background-color:white;"><img style="max-height:8vh;margin-top: 11vh;" src="' + url_now + '/wp-content/themes/storefront/assets/images/onix-red.gif"></center>');
        }
    });
}

// Ajusta o tamanho das barras de porcentagem
// .percent-card-5
// .percent-out
// .percent-in
// Pego o valor da porcentagem em .percent-number
// Adiciona esse a porcentagem ao width da classe .percent-in

function dt3PercentBar() {

	var percent_width_5 = $('.percent-card-5>div>p.percent-number').html();
	$('.percent-in-5').css( 'width', percent_width_5 );

	var percent_width_4 = $('.percent-card-4>div>p.percent-number').html();
	$('.percent-in-4').css( 'width', percent_width_4 );

	var percent_width_3 = $('.percent-card-3>div>p.percent-number').html();
	$('.percent-in-3').css( 'width', percent_width_3 );

	var percent_width_2 = $('.percent-card-2>div>p.percent-number').html();
	$('.percent-in-2').css( 'width', percent_width_2 );

	var percent_width_1 = $('.percent-card-1>div>p.percent-number').html();
	$('.percent-in-1').css( 'width', percent_width_1 );

	// // console.log( percent_width_1 );

	// $('.percent-number').parent().find('.percent-out>div').css('width','50%');
	/*$('.percent-number').on('hover', function(){
		percent_width = $(this).html();
		$(this).parent().find('.percent-out>div').css('width', percent_width);
		// console.log( percent_width );
	});*/

}

// Identifica o numero do ultimo item carregado.
// Executa uma função no Ajax passando o data-load
// Função Ajax retorna os proximos 4 posts
// que executa a query com o offset = data-load + 1

function dt3MoreRatings() {

	var loaded = 0;
	var loadData;
	
	$('.more-link').on( 'click', function() {
		loaded 			= $('.comment-item').last().data('load');
		product_post_id = $('.button-submit').data('post');
		// console.log( loaded );

		loadData = {
	    	action: 'dt3_load_rating', // This is required so WordPress knows which func to use
        	rating_loaded: loaded,
        	product_post_id: product_post_id,
    	};

		dt3AjaxLoadMore( loadData );

	});

}

// Ocultar botão ver mais
function dt3HideMore() {
	$('a.more-link').html('');
}

function dt3AjaxLoadMore( loadData ) {
	$.ajax({
        url: url_ajax,
        type: 'POST',
        data: loadData,
        success: function (response) {
            // // console.log(response);
            // Transforma o response em objeto
            resp = JSON.parse(response);
            // console.log(resp);
            if ( '' == resp ) {
            	// console.log('Vazio');
            	// $('more-link').html('');
            	dt3HideMore();
            }
            // $('.success-message').show();
            $('.comments').append( resp );
            // Deactive loading animation
            $('.rating-loading').html('');
        },
        beforeSend: function () {
            $('.rating-loading').append('<center style="height:10vh; background-color:white;"><img style="max-height:8vh;margin-top: 5vh;" src="https://dt3sports.com.br/wp-content/themes/storefront/assets/images/onix-red.gif"></center>');
        }
    });
}

