/**
 * Funcao que abre e adiciona efeito ao formulario de satisfacao do cliente
 */
function toggleForm(event){
    event.preventDefault();
    var formId = document.getElementById('form-rate');
    window.scroll({
        top: formId.offsetTop - 50,
        behavior: 'smooth'
    });
    var comments = document.querySelector('.comments');
    var form = document.querySelector('.rating-form');
    form.style.transform = 'rotateX(0deg)';
    comments.style.margin = '0 auto 0 auto';
}

/**
 * Funcao que abre ou fecha a prancheta de porcentagens
 */
function toggleClipboard(){
    var arrow = document.getElementById('arrow-clipboard');
    var clipboard = document.querySelector('.clipboard');
    var recommendation = document.querySelector('.recommendations'); 

    if(arrow.style.transform === 'rotate(-180deg)'){
        arrow.style.transform = 'rotate(0deg)';
        clipboard.classList.remove('cut-desktop-mobile-default');
        recommendation.classList.remove('recommendations-open');
        return;
    }else{
        arrow.style.transform = 'rotate(-180deg)';
        clipboard.classList.add('cut-desktop-mobile-default');
        recommendation.classList.add('recommendations-open');
    }
}

/**
 * Adiciona eventListener ao textarea de pontos positivos
 */
var positive = document.getElementById('positive-input');
positive.addEventListener('keyup', function (){
    var positiveValue = document.querySelector('.positive-value');
    positiveValue.innerHTML = positive.value.length - 300;
    if(positiveValue.innerHTML == 0){
        document.querySelector('.positive-input p').style.color = '#f00';
    }else{
        document.querySelector('.positive-input p').style.color = '#333';
    }
});


/**
 * Adiciona eventListener ao textarea de sugestao de melhorias
 */
var improvement = document.getElementById('improvement-input');
improvement.addEventListener('keyup', function (){
    var improvementValue = document.querySelector('.improvement-value');
    improvementValue.innerHTML = improvement.value.length - 300;
    if(improvementValue.innerHTML == 0){
        document.querySelector('.improvement-input p').style.color = '#f00';
    }else{
        document.querySelector('.improvement-input p').style.color = '#333';
    }
});

/**
 * Funcao que adiciona scroll automático para a tela de avaliações
 */
function smoothScrollAverage(event){
    event.preventDefault();
    var headerRating = document.getElementById('header-rating');
    if(screen.width <= 1024){        
        window.scroll({
            top: headerRating.offsetTop,
            behavior: 'smooth'
        });
    }else{        
        window.scroll({
            top: headerRating.offsetTop - 90,
            behavior: 'smooth'
        });
    }
}
