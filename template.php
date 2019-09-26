<?php

// Identifica o post
$product_post_id = get_the_ID();

// Trate os loops para um grande numero de avaliações
// Para fins de contagem use o loop com todos os itens
$loop = new WP_Query( array( 
    'post_type'         => 'dt3-rating',
    'posts_per_page'    => -1,
    'meta_key'          => 'dt3_rating_post_id',
    'meta_value'        => $product_post_id,
));
// wp_reset_query();

// Para carregamento retorne deste loop apenas as páginas que deseja de 4 em 4 páginas.
// Carregar inicialmente apenas 4 avaliações
$partial_loop = new WP_Query( array( 
    'post_type'         => 'dt3-rating',
    'posts_per_page'    => 4,
    'meta_key'          => 'dt3_rating_post_id',
    'meta_value'        => $product_post_id,
));
// wp_reset_query();

// Cada avaliação conterá uma data-load contendo a sua posicao no loop
// Ao clicar em Ver mais avaliações
// São exibidas as próximas 4 avaliações do produto
// Contadas a partir do data-load
?>

	<link rel="stylesheet" href="<?php echo PLUGIN_URL; ?>dt3-rating/css/reset.css">
    <link rel="stylesheet" href="<?php echo PLUGIN_URL; ?>dt3-rating/css/style.css">
    <link rel="stylesheet" href="<?php echo PLUGIN_URL; ?>dt3-rating/css/media-queries-desktop.css">
    <link rel="stylesheet" href="<?php echo PLUGIN_URL; ?>dt3-rating/css/media-queries-mobile.css">
	<main>
        <section class="header-rating" id="header-rating">
            <div class="title"><h2>Avaliações</h2></div>
            <div class="notes">
                <div class="average">
                    <h4>Média de avaliações</h4>
                    <!-- <h2>4.5</h2> -->
                    <h2>
                    <?php $stars_average = dt3_rating_stars_average( $loop, $product_post_id ); ?>
                    <?php settype($stars_average, "double"); ?>
                    <?php echo sprintf("%2.1f", $stars_average); ?>
                    </h2>
                    <div class="stars">
                        <img src="<?php echo PLUGIN_URL; ?>dt3-rating/images/rate-star-button.svg" alt="">
                        <img src="<?php echo PLUGIN_URL; ?>dt3-rating/images/rate-star-button.svg" alt="">
                        <img src="<?php echo PLUGIN_URL; ?>dt3-rating/images/rate-star-button.svg" alt="">
                        <img src="<?php echo PLUGIN_URL; ?>dt3-rating/images/rate-star-button.svg" alt="">
                        <img src="<?php echo PLUGIN_URL; ?>dt3-rating/images/rate-star-button.svg" alt="">
                        <!-- <img src="<?php // echo PLUGIN_URL; ?>dt3-rating/images/expand-button.svg" alt="" style="padding-left: 10px"> -->
                    </div>
                    <section class="clipboard cut-desktop-mobile" onclick="toggleClipboard()">
                        <div class="clipboard-wrapper">
                            <?php
                                dt3_rating_the_averarge_stars( $stars_average );
                            ?>
                           
                            <img id="arrow-clipboard" src="<?php echo PLUGIN_URL; ?>dt3-rating/images/expand-button.svg" alt="" style="padding: 0 10px">
                        </div>
                        <div class="users-rating-mobile">
                            <!-- <p>(23 avaliacoes)</p> -->
                            <p>(
                                <?php
                                    $dt3_rating_total = dt3_rating_total( $loop, $product_post_id );
                                    echo esc_html( $dt3_rating_total ) ;
                                ?>
                                <?php 
                                    echo 1 == $dt3_rating_total ? 'avaliação' : 'avaliações';
                                ?>
                            )</p>
                        </div>
                        <div class="percent-card-5">
                            <div class="percent-stars">
                                <p>5</p>
                                <img src="<?php echo PLUGIN_URL; ?>dt3-rating/images/red-star.svg" alt="">
                            </div> 
                            <div class="percent-bar">
                                <div class="percent-out">
                                    <div class="percent-in-5"></div>
                                </div>
                            </div>
                            <div class="percent-avg">
                                <p class="percent-number">
                                <?php
                                    echo dt3_rating_percent( 5, $loop, $product_post_id );
                                ?>
                                </p>
                            </div>
                        </div>
                        <div class="percent-card-4">
                            <div class="percent-stars">
                                <p>4</p>
                                <img src="<?php echo PLUGIN_URL; ?>dt3-rating/images/red-star.svg" alt="">
                            </div> 
                            <div class="percent-bar">
                                <div class="percent-out">
                                    <div class="percent-in-4"></div>
                                </div>
                            </div>
                            <div class="percent-avg" style="">
                                <p class="percent-number">
                                <?php
                                    echo dt3_rating_percent( 4, $loop, $product_post_id );
                                ?>
                                </p>
                            </div>
                        </div>
                        <div class="percent-card-3">
                            <div class="percent-stars">
                                <p>3</p>
                                <img src="<?php echo PLUGIN_URL; ?>dt3-rating/images/red-star.svg" alt="">
                            </div> 
                            <div class="percent-bar">
                                <div class="percent-out">
                                    <div class="percent-in-3"></div>
                                </div>
                            </div>
                            <div class="percent-avg">
                                <p class="percent-number">
                                <?php
                                    echo dt3_rating_percent( 3, $loop, $product_post_id );
                                ?>
                                </p>
                            </div>
                        </div>
                        <div class="percent-card-2">
                            <div class="percent-stars">
                                <p>2</p>
                                <img src="<?php echo PLUGIN_URL; ?>dt3-rating/images/red-star.svg" alt="">
                            </div> 
                            <div class="percent-bar">
                                <div class="percent-out">
                                    <div class="percent-in-2"></div>
                                </div>
                            </div>
                            <div class="percent-avg">
                                <p class="percent-number">
                                <?php
                                    echo dt3_rating_percent( 2, $loop, $product_post_id );
                                ?>
                                </p>
                            </div>
                        </div>
                        <div class="percent-card-1">
                            <div class="percent-stars">
                                <p>1</p>
                                <img src="<?php echo PLUGIN_URL; ?>dt3-rating/images/red-star.svg" alt="">
                            </div> 
                            <div class="percent-bar">
                                <div class="percent-out">
                                    <div class="percent-in-1"></div>
                                </div>
                            </div>
                            <div class="percent-avg">
                                <p class="percent-number">
                                <?php
                                    echo dt3_rating_percent( 1, $loop, $product_post_id );
                                ?>
                                </p>
                            </div>
                        </div>
                    </section>
                    <div class="users-rating">
                        <!-- <p>(23 avaliacoes)</p> -->
                        <p>(
                            <?php
                                echo dt3_rating_total( $loop, $product_post_id );
                            ?>
                            avaliações
                        )</p>
                    </div>
                    <div class="recommendations">
                        <img src="<?php echo PLUGIN_URL; ?>dt3-rating/images/circle-with-check-symbol.svg" alt="">
                        <div class="recommendations-text">
                            <!-- <span>3 clientes</span> recomendariam este produto para um amigo -->
                            <span>
                                <?php  $recommended = dt3_rating_get_recommendations( $loop, $product_post_id ); ?>
                                <?php echo esc_html( $recommended ); ?>
                                clientes
                            </span> 
                            recomendariam este produto para um amigo
                        </div>
                    </div>
                </div>
                <div class="details">
                    <div class="comfort">
                        <h3>CONFORTO 
                        <?PHP
                            $confort_rate = dt3_rating_attribute_average( $loop, 'dt3_rating_confort' );
                            // echo $confort_rate;
                        ?>
                        </h3>
                        <div class="rate">
                            <?php
                                dt3_rating_the_rate ( $confort_rate );
                            ?>
                        </div>
                        <div class="rate-text">
                            <p>POUCO<br/>CONFORTÁVEL</p>
                            <p style="text-align: end">MUITO<br/>CONFORTÁVEL</p>
                        </div>
                    </div>
                    <div class="quality">
                        <h3>QUALIDADE
                        <?PHP
                            $quality_rate = dt3_rating_attribute_average( $loop, 'dt3_rating_quality' );
                            // echo $quality_rate;
                        ?>
                        </h3>
                        <div class="rate">
                            <?php
                                dt3_rating_the_rate ( $quality_rate );
                            ?>
                        </div>
                        <div class="rate-text">
                            <p>BÁSICA</p>
                            <p style="text-align: end">EXCEPCIONAL</p>
                        </div>
                    </div>
                    <div class="features">
                        <h3>CARACTERÍSTICAS
                        <?PHP
                            $features_rate = dt3_rating_attribute_average( $loop, 'dt3_rating_features' );
                            // echo $features_rate;
                        ?>
                        </h3>
                        <div class="rate">
                            <?php
                                dt3_rating_the_rate ( $features_rate );
                            ?>
                        </div>
                        <div class="rate-text">
                            <p>FRACO</p>
                            <p style="text-align: end">EXCEPCIONAL</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="button-rate">
                <a><button onclick="toggleForm(event)">Avaliar produto</button></a>
            </div>
        </section>
        <span class="default-border-1"></span>
        <section class="success-message">
            <div class="success-message-wrapper">
                <h3>A DT3 agradece a sua avaliação!</h3>
                <p>Sua opinião é muito importante para nós.</p>
                <p>A avaliação será publicada em breve.</p>
            </div>
        </section>
        <!-- <span class="default-border-2"></span> -->
        <section class="rating-form" id="form-rate">
            <div class="title-form">
                <h2>DÊ SUA OPINIÃO SOBRE O PRODUTO</h2>
            </div>
            <form class="action-form" method="post" action="">
                <div class="name-email">
                    <div id="rating_name" class="name-input">
                        <span class="name-span hide-tooltip">Insira seu nome e sobrenome.<span></span></span>
                        <label for="name">Nome <span class="add-color-asterisc">*</span></label>
                        <input class="rating-name" type="text" name="name" placeholder="Nome e sobrenome" required maxlength="100">
                    </div>
                    <div id="rating_email" class="email-input">
                        <span class="email-span hide-tooltip">Insira um e-mail válido<span></span></span>
                        <label for="email">E-mail <span class="add-color-asterisc">*</span></label>
                        <input class="rating-email" type="email" name="email" placeholder="exemplo@email.com" required maxlength="100">
                    </div>
                </div>
                <div id="rating_title" class="title-input">
                    <span class="title-span hide-tooltip">Insira o titulo da avaliação<span></span></span>
                    <label for="title">Título da avaliação <span class="add-color-asterisc">*</span></label>
                    <input class="rating-title" type="text" name="title" placeholder="Exemplo: A cadeira mais confortável do mercado" required maxlength="100">
                </div>
                <div class="positive-input">
                    <label for="positive">Pontos positivos</label>
                    <textarea class="rating-positive" id="positive-input" type="text" name="positive" placeholder="O que você gostou na DT3?" rows="4" maxlength="300"></textarea>
                    <p style="text-align: end; color: #333; padding: 5px 0 0 0"><span class="positive-value">-300</span> caracteres</p>
                </div>
                <div class="improvement-input">
                    <label for="improvement">Poderia melhorar</label>
                    <textarea class="rating-negative" id="improvement-input" type="text" name="improvement" placeholder="O que você acha que poderia melhorar no produto?" rows="4" maxlength="300"></textarea>
                    <p style="text-align: end; color: #333; padding: 5px 0 0 0"><span class="improvement-value">-300</span> caracteres</p>
                </div>
                <div class="do-your-rate">
                    <h3 style="white-space:nowrap">Faça sua avaliação <span class="add-color-asterisc">*</span></h3>
                    <span class="rating-by-stars" id="rating_stars">
                        <span class="stars-span hide-tooltip">Selecione uma avaliação de 1 a 5 estrelas<span></span></span>
                        <input class="rating-stars" id="rating5" type="radio" name="rating" value="5" required>
                        <label for="rating5">5</label>
                        <input class="rating-stars" id="rating4" type="radio" name="rating" value="4" required>
                        <label for="rating4">4</label>
                        <input class="rating-stars" id="rating3" type="radio" name="rating" value="3" required>
                        <label for="rating3">3</label>
                        <input class="rating-stars" id="rating2" type="radio" name="rating" value="2" required>
                        <label for="rating2">2</label>
                        <input class="rating-stars" id="rating1" type="radio" name="rating" value="1" required>
                        <label for="rating1">1</label>
                      </span>
                </div>
                <div class="comfort-form">
                    <h3>Conforto</h3>
                    <div class="comfort-radios">
                        <div class="comfort-radios-item">
                            <label for="comfort-1" class="container">
                                <input class="rating-confort" type="radio" name="confort-radio" value="1" id="comfort-1">
                                <span class="checkmark"></span>
                                Pouco confortável
                            </label>
                        </div>
                        <div class="comfort-radios-item">
                            <label for="comfort-2" class="container">
                                <input class="rating-confort" type="radio" name="confort-radio" value="2" id="comfort-2">
                                <span class="checkmark"></span>
                                Incômodo
                            </label>
                        </div>
                        <div class="comfort-radios-item">
                            <label for="comfort-3" class="container">
                                <input class="rating-confort" type="radio" name="confort-radio" value="3" id="comfort-3">
                                <span class="checkmark"></span>
                                Razoável
                            </label>
                        </div>
                        <div class="comfort-radios-item">
                            <label for="comfort-4" class="container">
                                <input class="rating-confort" type="radio" name="confort-radio" value="4" id="comfort-4">
                                <span class="checkmark"></span>
                                Confortável
                            </label>
                        </div>
                        <div class="comfort-radios-item">
                            <label for="comfort-5" class="container">
                                <input class="rating-confort" type="radio" name="confort-radio" value="5" id="comfort-5">
                                <span class="checkmark"></span>
                                Muito confortável
                            </label>
                        </div>
                    </div>
                </div>
                <div class="quality-form">
                    <h3>Qualidade</h3>
                    <div class="quality-radios">
                        <div class="quality-radios-item">
                            <label for="quality-1" class="container">
                                <input class="rating-quality" type="radio" name="quality-radio" value="1" id="quality-1">
                                <span class="checkmark"></span>
                                Básica
                            </label>
                        </div>
                        <div class="quality-radios-item">
                            <label for="quality-2" class="container">
                                <input class="rating-quality" type="radio" name="quality-radio" value="2" id="quality-2">
                                <span class="checkmark"></span>
                                Insatisfatória
                            </label>
                        </div>
                        <div class="quality-radios-item">
                            <label for="quality-3" class="container">
                                <input class="rating-quality" type="radio" name="quality-radio" value="3" id="quality-3">
                                <span class="checkmark"></span>
                                Razoável
                            </label>
                        </div>
                        <div class="quality-radios-item">
                            <label for="quality-4" class="container">
                                <input class="rating-quality" type="radio" name="quality-radio" value="4" id="quality-4">
                                <span class="checkmark"></span>
                                Boa
                            </label>
                        </div>
                        <div class="quality-radios-item">
                            <label for="quality-5" class="container">
                                <input class="rating-quality" type="radio" name="quality-radio" value="5" id="quality-5">
                                <span class="checkmark"></span>
                                Excepcional
                            </label>
                        </div>
                    </div>
                </div>
                <div class="features-form">
                    <h3>Características</h3>
                    <div class="features-radios">
                        <div class="features-radios-item">
                            <label for="features-1" class="container">
                                <input class="rating-features" type="radio" name="features-radio" value="1" id="features-1">
                                <span class="checkmark"></span>
                                Fraco
                            </label>
                        </div>
                        <div class="features-radios-item">
                            <label for="features-2" class="container">
                                <input class="rating-features" type="radio" name="features-radio" value="2" id="features-2">
                                <span class="checkmark"></span>
                                Insatisfatório
                            </label>
                        </div>
                        <div class="features-radios-item">
                            <label for="features-3" class="container">
                                <input class="rating-features" type="radio" name="features-radio" value="3" id="features-3">
                                <span class="checkmark"></span>
                                Razoável
                            </label>
                        </div>
                        <div class="features-radios-item">
                            <label for="features-4" class="container">
                                <input class="rating-features" type="radio" name="features-radio" value="4" id="features-4">
                                <span class="checkmark"></span>
                                Bom
                            </label>
                        </div>
                        <div class="features-radios-item">
                            <label for="features-5" class="container">
                                <input class="rating-features" type="radio" name="features-radio" value="5" id="features-5">
                                <span class="checkmark"></span>
                                Excepcional
                            </label>
                        </div>
                    </div>
                </div>
                <div class="do-your-rate" id="rating_recommendation">
                    <span class="recommendation-span hide-tooltip">Selecione a resposta para a pergunta<span></span></span>
                    <h3>Você recomendaria esse produto? <span class="add-color-asterisc">*</span></h3>
                </div>
                <div class="recommendations-form">
                    <div class="recommendations-radios">
                        <div class="recommendations-radios-item">
                            <label for="recommendations-1" class="container">
                                <input class="rating-recomendations" type="radio" name="recommendations-radio" value="yes" id="recommendations-1" required>
                                <span class="checkmark"></span>
                                Sim
                            </label>
                        </div>
                        <div class="recommendations-radios-item">
                            <label for="recommendations-2" class="container">
                                <input class="rating-recomendations" type="radio" name="recommendations-radio" value="no" id="recommendations-2" required>
                                <span class="checkmark"></span>
                                Não
                            </label>
                        </div>
                    </div>
                </div>
                <div id="dt3-captcha"></div>
                <div class="button-submit" data-post="<?php echo $product_post_id; ?>">
                    <button class="rating-submit" onclick="toggleForm(event)">Enviar avaliação</button>
                </div>
            </form>
        </section>

        <!-- <span class="default-border-3"></span> -->
        <span></span>
        <section class="comments">

            <?php

                // Retorne um partial loop que será usado apenas na primeira iteração de 4 posts
                // Para cada novo ciclo execute novos WP_Querys retornando novos dados
                // Conte cada post e armazene em data-load
                
                // Number of ratings loaded
                $ratings_loaded = 0;
                
                while ( $partial_loop->have_posts() ) : $partial_loop->the_post();

                $rating_post_id = get_the_ID();

                ?>
                    <div class="comment-item" data-load="<?php echo $ratings_loaded; ?>">
                        <div class="comment-stars">
                            <?php dt3_rating_the_stars(); ?>
                        </div>
                        <div class="comment-title">
                            <h3>
                                <?php the_title(); ?>
                            </h3>
                        </div>
                        <div class="comment-user-date">
                            <p>Por <?php dt3_rating_the_field('dt3_rating_name'); ?> em <?php the_modified_time('d/m/Y'); ?></p>
                        </div>
                        <div class="comment-positive-point">
                            <p>
                                <span>Pontos positivos:</span><?php dt3_rating_the_field( 'dt3_rating_positive' );?>
                            </p>
                        </div>
                        <div class="comment-negative-point">
                            <p>
                                <span>Poderia melhorar:</span><?php dt3_rating_the_field( 'dt3_rating_negative' ); ?>
                            </p>
                        </div>
                        <div class="comment-recommended">
                            <!-- <img src="<?php // echo PLUGIN_URL; ?>dt3-rating/images/circle-with-check-symbol.svg" alt="">
                            <div class="recommended-text"> -->
                                <?php
                                    // Exibir os icones de recomendação ou não
                                    // Exibir exibir texto de recomendação
                                    // $rating_recomendations = dt3_rating_get_field( 'dt3_rating_recomendations' ); 
                                    // echo $rating_recomendations;
                                    dt3_rating_the_recommendation ();
                                ?>
                            <!-- </div> -->
                        </div>
                    </div>
                <?php

                // Incrementa o numero de ratings carregados
                $ratings_loaded++;

                endwhile;
            ?>
            
        </section>
        <section class="rating-more">
            <div class="rating-loading"></div>
            <div class="title-more">
                <h3><a class="more-link" href="#"> Ver mais avaliações </a></h3>
            </div>
        </section>
    </main>
    <!-- Inicializa a URL no Plugin -->
    <script type="text/javascript">
        var url_now = "<?php echo esc_url( home_url( '/' ) ); ?>"
    </script>
    <!-- <script src="https://www.google.com/recaptcha/api.js?onload=onloadCallback&render=explicit" async defer></script> -->
    <script src="<?php echo PLUGIN_URL; ?>dt3-rating/js/main.js"></script>
    <script src="<?php echo PLUGIN_URL; ?>dt3-rating/js/index.js"></script>
