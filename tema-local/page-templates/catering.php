<?php
/**
 * Template Name: Catering
 */

defined( 'ABSPATH' ) || exit;

add_action( 'wp_head', function () {
	?>
	<style>
		/* ===== CATERING PAGE ===== */
		.catering-page {
			background-color: #EDEDED;
		}

		/* Hero */
		.catering-hero {
			padding: 6rem 0 4rem;
			background-color: #fff;
			border-bottom: 3px solid #000;
		}
		.catering-hero .label-caps {
			font-family: 'Space Mono', monospace;
			font-size: 0.7rem;
			letter-spacing: 0.2em;
			text-transform: uppercase;
			color: #5F6776;
			display: block;
			margin-bottom: 1rem;
		}
		.catering-hero h1 {
			font-size: clamp(2rem, 5vw, 3.5rem);
			font-weight: 800;
			line-height: 1.1;
			margin-bottom: 1.5rem;
		}
		.catering-hero h1 span {
			background-color: #FEE800;
			padding: 0 4px;
		}
		.catering-hero .lead {
			font-size: 1.1rem;
			color: #5F6776;
			max-width: 500px;
		}
		.catering-hero .hero-image-wrapper {
			position: relative;
			margin-top: 2rem;
		}
		.catering-hero .hero-image-wrapper::after {
			content: '';
			position: absolute;
			inset: 0;
			top: 12px;
			left: 12px;
			border: 3px solid #000;
			z-index: 0;
		}
		.catering-hero .hero-image-wrapper video {
			position: relative;
			z-index: 1;
			width: 100%;
			height: auto;
			aspect-ratio: 4 / 5;
			object-fit: cover;
			border: 3px solid #000;
			display: block;
		}

		/* Section base */
		.catering-section {
			padding: 5rem 0;
		}
		.catering-section-alt {
			background-color: #fff;
			border-top: 3px solid #000;
			border-bottom: 3px solid #000;
		}
		.catering-label {
			font-family: 'Space Mono', monospace;
			font-size: 0.65rem;
			letter-spacing: 0.15em;
			text-transform: uppercase;
			color: #5F6776;
			display: block;
			margin-bottom: 0.5rem;
		}
		.catering-section h2 {
			font-size: clamp(1.6rem, 3vw, 2.5rem);
			font-weight: 800;
			line-height: 1.15;
			margin-bottom: 0;
		}
		.section-heading-underline {
			display: inline-block;
			border-bottom: 4px solid #FEE800;
			padding-bottom: 4px;
		}

		/* Why cards */
		.why-card {
			background: #fff;
			border: 3px solid #000;
			box-shadow: 4px 4px 0 0 #000;
			padding: 2rem;
			height: 100%;
			transition: box-shadow 0.2s, transform 0.2s;
		}
		.why-card:hover {
			box-shadow: 6px 6px 0 0 #000;
			transform: translate(-2px, -2px);
		}
		.why-card .card-number {
			font-family: 'Space Mono', monospace;
			font-size: 3rem;
			font-weight: 700;
			color: #FEE800;
			opacity: 1;
			-webkit-text-stroke: 1.5px #000;
			line-height: 1;
			margin-bottom: 0.75rem;
		}
		.why-card h3 {
			font-size: 1.2rem;
			font-weight: 700;
			margin-bottom: 0.75rem;
		}
		.why-card p {
			font-size: 0.9rem;
			color: #5F6776;
			margin-bottom: 0;
		}

		/* Producto section */
		@keyframes locobo-float {
			0%, 100% { transform: translateY(0px); }
			50%       { transform: translateY(-17.5px); }
		}
		.producto-image-wrapper {
			display: flex;
			justify-content: center;
			align-items: center;
		}
		.producto-image-wrapper .locobo-main {
			width: 100%;
			max-width: 340px;
			height: auto;
			display: block;
			animation: locobo-float 3.5s ease-in-out infinite;
		}
		.producto-tag {
			display: inline-block;
			background-color: #000;
			color: #FEE800;
			font-family: 'Space Mono', monospace;
			font-size: 0.65rem;
			letter-spacing: 0.1em;
			text-transform: uppercase;
			padding: 0.4rem 0.8rem;
			border: 2px solid #000;
			font-weight: 700;
		}

		/* Sabores Carousel */
		.sabores-section {
			background-color: #fff;
			color: #000;
			padding: 5rem 0;
			border-top: 3px solid #000;
			border-bottom: 3px solid #000;
		}
		.sabores-section h2 {
			color: #000;
		}
		.sabores-section .catering-label {
			color: #5F6776;
		}
		.sabores-outer {
			position: relative;
			padding: 0 52px;
		}
		.sabores-carousel {
			overflow: hidden;
		}
		.sabores-track {
			display: flex;
			transition: transform 0.45s cubic-bezier(0.25, 0.46, 0.45, 0.94);
			will-change: transform;
		}
		.sabores-slide {
			flex: 0 0 25%;
			padding: 0 6px;
			box-sizing: border-box;
		}
		@media (max-width: 991px) {
			.sabores-slide { flex: 0 0 50%; }
			.sabores-outer { padding: 0 44px; }
		}
		@media (max-width: 575px) {
			.sabores-slide { flex: 0 0 100%; }
		}
		.sabores-arrow {
			position: absolute;
			top: 50%;
			transform: translateY(-50%);
			width: 44px;
			height: 44px;
			background: #FEE800;
			border: 3px solid #FEE800;
			color: #000;
			font-size: 1.5rem;
			font-weight: 700;
			cursor: pointer;
			display: flex;
			align-items: center;
			justify-content: center;
			box-shadow: 3px 3px 0 0 rgba(254,232,0,0.4);
			transition: background 0.15s, box-shadow 0.15s, transform 0.15s;
			z-index: 2;
		}
		#sabores-prev { left: 0; }
		#sabores-next { right: 0; }
		.sabores-arrow:hover {
			background: #000;
			border-color: #000;
			color: #fff;
			box-shadow: 3px 3px 0 0 rgba(0,0,0,0.4);
			transform: translateY(calc(-50% - 2px));
		}
		.sabores-arrow:active {
			transform: translateY(calc(-50% + 1px));
			box-shadow: 1px 1px 0 0 rgba(254,232,0,0.4);
		}
		/* Flavor card */
		.flavor-card {
			background: #fff;
			border: 3px solid #000;
			overflow: hidden;
			transition: border-color 0.2s, transform 0.2s;
			height: 100%;
		}
		.flavor-card:hover {
			border-color: #FEE800;
			transform: translateY(-4px);
		}
		.flavor-card .flavor-img-wrapper {
			height: 160px;
			background-color: #ebebea;
			display: flex;
			align-items: center;
			justify-content: center;
			overflow: hidden;
			position: relative;
		}
		.flavor-card .flavor-img-wrapper img {
			height: 135px;
			width: auto;
			object-fit: contain;
			transition: transform 0.4s;
		}
		.flavor-card:hover .flavor-img-wrapper img {
			transform: scale(1.08);
		}
		.flavor-card .flavor-badge {
			position: absolute;
			top: 8px;
			right: 8px;
			background-color: #FEE800;
			color: #000;
			font-family: 'Space Mono', monospace;
			font-size: 0.55rem;
			font-weight: 700;
			letter-spacing: 0.08em;
			padding: 3px 7px;
		}
		.flavor-card .flavor-body {
			padding: 1.25rem;
			background: #f1f1ef;
		}
		.flavor-card .flavor-category {
			font-family: 'Space Mono', monospace;
			font-size: 0.6rem;
			letter-spacing: 0.1em;
			text-transform: uppercase;
			color: #5F6776;
			display: block;
			margin-bottom: 0.4rem;
		}
		.flavor-card h4 {
			font-size: 1.05rem;
			font-weight: 700;
			margin-bottom: 0.4rem;
			color: #000;
		}
		.flavor-card p {
			font-size: 0.8rem;
			color: #5F6776;
			margin-bottom: 0;
		}

		/* Beneficios */
		.beneficios-section {
			background-color: #EDEDED;
			padding: 5rem 0;
		}
		.benefit-card {
			display: flex;
			gap: 1.25rem;
			background: #fff;
			border: 3px solid #000;
			box-shadow: 4px 4px 0 0 #000;
			padding: 1.5rem;
			height: 100%;
			transition: box-shadow 0.2s, transform 0.2s;
		}
		.benefit-card:hover {
			box-shadow: 6px 6px 0 0 #FEE800;
			transform: translate(-2px, -2px);
		}
		.benefit-icon {
			flex-shrink: 0;
			width: 44px;
			height: 44px;
			display: flex;
			align-items: center;
			justify-content: center;
		}
		.benefit-icon svg {
			width: 36px;
			height: 36px;
		}
		.benefit-card h3 {
			font-size: 1rem;
			font-weight: 700;
			margin-bottom: 0.4rem;
		}
		.benefit-card p {
			font-size: 0.85rem;
			color: #5F6776;
			margin-bottom: 0;
		}

		/* Formatos section */
		.formatos-section {
			background-color: #EDEDED;
			border-top: 3px solid #000;
			border-bottom: 3px solid #000;
			padding: 5rem 0;
		}

				/* Packaging flip cards */
		.packaging-section {
			background-color: #fff;
			border-top: 3px solid #000;
			border-bottom: 3px solid #000;
			padding: 5rem 0;
		}
		.pkg-flip {
			perspective: 1200px;
			height: 300px;
			cursor: pointer;
		}
		.pkg-flip-inner {
			position: relative;
			width: 100%;
			height: 100%;
			transform-style: preserve-3d;
			transition: transform 0.65s cubic-bezier(0.4, 0.2, 0.2, 1);
		}
		.pkg-flip.flipped .pkg-flip-inner {
			transform: rotateY(180deg);
		}
		.pkg-front,
		.pkg-back {
			position: absolute;
			inset: 0;
			backface-visibility: hidden;
			-webkit-backface-visibility: hidden;
			border: 3px solid #000;
			box-shadow: 4px 4px 0 0 #000;
			overflow: hidden;
		}
		.pkg-front {
			background: #EDEDED;
			display: flex;
			flex-direction: column;
		}
		.pkg-front .pkg-img-wrapper {
			flex: 1;
			background-color: #F1EFED;
			display: flex;
			align-items: center;
			justify-content: center;
			overflow: hidden;
			border-bottom: 3px solid #000;
			padding: 1rem;
		}
		.pkg-front .pkg-img-wrapper img {
			max-width: 100%;
			max-height: 100%;
			width: auto;
			height: auto;
			object-fit: contain;
			transform: scale(1.1);
			transition: transform 0.4s;
		}
		.pkg-front .pkg-img-wrapper img.no-zoom {
			transform: scale(1);
		}
		.pkg-flip:not(.flipped) .pkg-img-wrapper::after {
			content: '↻';
			position: absolute;
			bottom: 8px;
			right: 10px;
			font-size: 1.1rem;
			color: #000;
			opacity: 0.3;
			transition: opacity 0.2s;
			pointer-events: none;
		}
		.pkg-flip:not(.flipped):hover .pkg-img-wrapper::after {
			opacity: 0.7;
		}
		.pkg-front .pkg-label {
			padding: 1rem 1.25rem;
		}
		.pkg-front h4 {
			font-family: 'Space Mono', monospace;
			font-size: 0.7rem;
			font-weight: 700;
			letter-spacing: 0.08em;
			text-transform: uppercase;
			margin-bottom: 0;
		}
		.pkg-back {
			background: #F1EFED;
			color: #000;
			transform: rotateY(180deg);
			display: flex;
			flex-direction: column;
			justify-content: center;
			padding: 2rem 1.75rem;
			border: 3px solid #000;
		}
		.pkg-back .pkg-back-label {
			font-family: 'Space Mono', monospace;
			font-size: 0.6rem;
			letter-spacing: 0.15em;
			text-transform: uppercase;
			color: #5F6776;
			display: block;
			margin-bottom: 0.75rem;
		}
		.pkg-back h4 {
			font-size: 1.1rem;
			font-weight: 800;
			margin-bottom: 1rem;
			color: #000;
			line-height: 1.2;
		}
		.pkg-back p {
			font-size: 0.82rem;
			color: #4a4a4a;
			line-height: 1.6;
			margin-bottom: 0;
		}
		/* Testimonios */
		.testimonios-section {
			padding: 5rem 0;
			background-color: #EDEDED;
		}
		.testimonio-card {
			background: #fff;
			border: 3px solid #000;
			box-shadow: 4px 4px 0 0 #000;
			padding: 2rem;
			transition: transform 0.2s;
		}
		.testimonio-card:hover {
			transform: translateY(-4px);
		}
		.testimonio-card p {
			font-style: italic;
			font-size: 0.95rem;
			margin-bottom: 1rem;
		}
		.testimonio-card .autor {
			font-family: 'Space Mono', monospace;
			font-size: 0.6rem;
			letter-spacing: 0.1em;
			text-transform: uppercase;
			color: #105CF7;
			font-weight: 700;
		}
		.testimonio-quote-mark {
			font-size: 5rem;
			line-height: 1;
			color: #FEE800;
			font-weight: 900;
			font-family: Georgia, serif;
		}

		/* CTA final */
		.catering-cta {
			background-color: #fff;
			color: #000;
			padding: 5rem 0;
			text-align: center;
			border-top: 3px solid #000;
		}
		.catering-cta h2 {
			color: #000;
			font-size: clamp(1.8rem, 4vw, 3rem);
			font-weight: 800;
		}
		.catering-cta p {
			color: #5F6776;
		}

		/* Marquee */
		.catering-marquee {
			background-color: #FEE800;
			color: #000;
			padding: 0.6rem 0;
			overflow: hidden;
			white-space: nowrap;
			border-top: 3px solid #000;
			border-bottom: 3px solid #000;
		}
		.catering-marquee-inner {
			display: inline-block;
			animation: marquee-catering 25s linear infinite;
			font-family: 'Space Mono', monospace;
			font-size: 0.7rem;
			font-weight: 700;
			letter-spacing: 0.12em;
			text-transform: uppercase;
		}
		@keyframes marquee-catering {
			0% { transform: translateX(0); }
			100% { transform: translateX(-50%); }
		}

		@media (max-width: 767px) {
			.catering-hero {
				padding: 5rem 0 3rem;
			}
			.catering-hero .hero-image-wrapper {
				margin-top: 3rem;
			}
			.catering-hero .hero-image-wrapper video {
				aspect-ratio: 4 / 5;
			}
		}
	</style>
	<?php
}, 20 );

get_header();
$container = get_theme_mod( 'understrap_container_type' );
$theme_uri = get_template_directory_uri();
$child_uri = get_stylesheet_directory_uri();
?>

<div class="catering-page" id="catering-page-wrapper">

	<!-- ===== HERO ===== -->
	<section class="catering-hero">
		<div class="<?php echo esc_attr( $container ); ?>">
			<div class="row align-items-center">

				<div class="col-lg-6 mb-5 mb-lg-0">
					<span class="label-caps">Locopolo para caterings y eventos</span>
					<h1>El postre congelado que <span>sí funciona</span> en sala</h1>
					<p class="lead mb-4">
						¿Imaginas servir un postre congelado en un evento y olvidarte del reloj, del calor y de los imprevistos del servicio? Locobó nace para eso.
					</p>
					<div class="d-flex flex-wrap gap-3">
						<a href="#sabores" class="btn btn-dark btn-lg px-4 py-3" style="font-family:'Space Mono',monospace;font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;border:3px solid #000;box-shadow:4px 4px 0 0 #000;">
							Ver Catálogo
						</a>
						<a href="#contacto" class="btn btn-outline-dark btn-lg px-4 py-3" style="font-family:'Space Mono',monospace;font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;border:3px solid #000;box-shadow:4px 4px 0 0 #000;">
							Solicitar Muestra
						</a>
					</div>
				</div>

				<div class="col-lg-4 offset-lg-1">
					<div class="hero-image-wrapper">
						<video
							autoplay
							muted
							loop
							playsinline
							preload="auto"
						>
							<source src="<?php echo esc_url( $child_uri . '/img/catering/diario_vasco.mp4' ); ?>" type="video/mp4">
						</video>
					</div>
				</div>

			</div>
		</div>
	</section>

	<!-- ===== POR QUÉ LOCOPOLO ===== -->
	<section class="catering-section catering-section-alt">
		<div class="<?php echo esc_attr( $container ); ?>">

			<div class="mb-5">
				<span class="catering-label">Razones para elegirlo</span>
				<h2 class="section-heading-underline">Por qué Locopolo</h2>
			</div>

			<div class="row g-4">
				<div class="col-md-4">
					<div class="why-card">
						<div class="card-number">01</div>
						<h3>Para servicio profesional</h3>
						<p>Con experiencia real en retail, horeca y eventos. Formatos diferenciales pensados para la operativa real del catering.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="why-card">
						<div class="card-number">02</div>
						<h3>I+D+I orientado al servicio</h3>
						<p>Innovación en postres congelados salados y dulces para simplificar la operativa de caterings y eventos de alta exigencia.</p>
					</div>
				</div>
				<div class="col-md-4">
					<div class="why-card">
						<div class="card-number">03</div>
						<h3>Operativa sencilla</h3>
						<p>Sin pedido mínimo, entregas en 24–48h y soporte comercial cercano. Más de 100 clientes ya confían en nosotros.</p>
					</div>
				</div>
			</div>

		</div>
	</section>

	<!-- ===== EL PRODUCTO ===== -->
	<section class="catering-section">
		<div class="<?php echo esc_attr( $container ); ?>">
			<div class="row align-items-center g-5">

				<div class="col-lg-4 order-2 order-lg-1">
					<div class="producto-image-wrapper">
						<img
							class="locobo-main"
							src="<?php echo esc_url( $child_uri . '/img/catering/locobo.png' ); ?>"
							alt="Locobó — postre para catering"
						/>
					</div>
				</div>

				<div class="col-lg-7 offset-lg-1 order-1 order-lg-2">
					<span class="catering-label">El Producto</span>
					<h2 class="mb-4">Locobó: el postre que elimina el estrés en sala</h2>
					<p class="mb-4" style="color:#5F6776;font-size:1.05rem;">
						Mantiene su estructura y presencia hasta los 23°C, sin necesidad de cadena de frío estricta durante el servicio. Termo-reversible, consistente y visualmente impactante.
					</p>
					<div class="d-flex flex-wrap gap-3">
						<span class="producto-tag">Estable hasta 23°C</span>
						<span class="producto-tag">Termo-reversible</span>
						<span class="producto-tag">+100 clientes</span>
					</div>
				</div>

			</div>
		</div>
	</section>

	<!-- ===== CARTA DE SABORES ===== -->
	<section class="sabores-section" id="sabores">
		<div class="<?php echo esc_attr( $container ); ?>">

			<div class="d-flex justify-content-between align-items-center mb-5">
				<div>
					<span class="catering-label">Explora nuestra</span>
					<h2 class="mb-0" style="text-transform:uppercase;letter-spacing:-0.02em;">Carta de Sabores</h2>
				</div>
			</div>

			<div class="sabores-outer">
				<button class="sabores-arrow" id="sabores-prev" aria-label="Anterior">&lt;</button>
				<button class="sabores-arrow" id="sabores-next" aria-label="Siguiente">&gt;</button>
				<div class="sabores-carousel" id="sabores-carousel">
				<div class="sabores-track" id="sabores-track">

					<?php
					$sabores = array(
						array( 'img' => '01_Gilda.png',                   'nombre' => 'Gilda',                  'cat' => 'Cocktail — Salados', 'desc' => 'Bañado en chocolate blanco con anchoa, pepinillo y guindilla.',             'badge' => 'NUEVO' ),
						array( 'img' => '02_Pesto_y_Tomate.png',          'nombre' => 'Pesto y Tomate',          'cat' => 'Cocktail — Salados', 'desc' => 'Bañado en chocolate blanco y pasta de pistacho y albahaca.',               'badge' => 'NUEVO' ),
						array( 'img' => '03_Queso_Gorgonzola_y_Pera.png', 'nombre' => 'Queso Gorgonzola y Pera', 'cat' => 'Cocktail — Salados', 'desc' => 'Bañado en chocolate blanco con queso azul y mermelada de pera.',          'badge' => 'NUEVO' ),
						array( 'img' => '04_Foie_y_Frambuesa.png',        'nombre' => 'Foie y Frambuesa',        'cat' => 'Cocktail — Salados', 'desc' => 'Bañado en chocolate blanco, kikos en polvo y frambuesa liofilizada.',      'badge' => 'NUEVO' ),
						array( 'img' => '05_Salmon_y_Aguacate.png',       'nombre' => 'Salmón y Aguacate',  'cat' => 'Cocktail — Salados', 'desc' => 'Bañado en chocolate blanco con salmón ahumado y crema de aguacate.', 'badge' => 'NUEVO' ),
						array( 'img' => '06_Mango_y_Maracuya.png',        'nombre' => 'Mango y Maracuya', 'cat' => 'Locobó — Dulces', 'desc' => 'Bañado en chocolate negro 70% cacao artesano.',                       'badge' => '' ),
						array( 'img' => '07_Oreo.png',                    'nombre' => 'Oreo',                    'cat' => 'Locobó — Dulces', 'desc' => 'Bañado en chocolate negro con crema de galleta Oreo.',                    'badge' => '' ),
						array( 'img' => '08_Cacahuete.png',               'nombre' => 'Cacahuete',               'cat' => 'Locobó — Dulces', 'desc' => 'Bañado en chocolate negro con crema de cacahuete caramelizado.',         'badge' => '' ),
						array( 'img' => '09_Choco_Caramelo_Salado.png',   'nombre' => 'Choco Caramelo Salado',   'cat' => 'Locobó — Dulces', 'desc' => 'Bañado en chocolate negro con caramelo salado artesano.',                'badge' => '' ),
						array( 'img' => '10_Cafe.png',                    'nombre' => 'Café',               'cat' => 'Locobó — Dulces', 'desc' => 'Bañado en chocolate negro con crema de café intenso.',               'badge' => '' ),
						array( 'img' => '11_Choco_Vegano.png',            'nombre' => 'Choco Vegano',            'cat' => 'Locobó — Dulces', 'desc' => 'Bañado en chocolate 100% vegano, sin lácteos ni derivados.',        'badge' => 'VEGANO' ),
						array( 'img' => '12_Doble_Choco.png',             'nombre' => 'Doble Choco',             'cat' => 'Locobó — Dulces', 'desc' => 'Doble capa de chocolate negro intenso para los más chocolateros.',       'badge' => '' ),
						array( 'img' => '13_Pistacho.png',                'nombre' => 'Pistacho',                'cat' => 'Locobó — Dulces', 'desc' => 'Bañado en chocolate blanco con crema pura de pistacho.',                  'badge' => '' ),
						array( 'img' => '14_Choco_Avellana.png',          'nombre' => 'Choco Avellana',          'cat' => 'Locobó — Dulces', 'desc' => 'Bañado en chocolate negro con crema de avellana artesana.',              'badge' => '' ),
						array( 'img' => '15_Frambuesa.png',               'nombre' => 'Frambuesa',               'cat' => 'Locobó — Dulces', 'desc' => 'Bañado en chocolate blanco con frambuesa liofilizada.',                  'badge' => '' ),
					);
					foreach ( $sabores as $sabor ) :
					?>
					<div class="sabores-slide">
						<div class="flavor-card">
							<div class="flavor-img-wrapper">
								<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/catering/' . $sabor['img'] ); ?>" alt="<?php echo esc_attr( $sabor['nombre'] ); ?>">
								<?php if ( $sabor['badge'] ) : ?>
									<span class="flavor-badge"><?php echo esc_html( $sabor['badge'] ); ?></span>
								<?php endif; ?>
							</div>
							<div class="flavor-body">
								<span class="flavor-category"><?php echo esc_html( $sabor['cat'] ); ?></span>
								<h4><?php echo esc_html( $sabor['nombre'] ); ?></h4>
								<p><?php echo esc_html( $sabor['desc'] ); ?></p>
							</div>
						</div>
					</div>
					<?php endforeach; ?>

				</div>
			</div>
			</div><!-- /.sabores-outer -->

		</div>
	</section>

	<script>
	(function () {
		var track    = document.getElementById('sabores-track');
		var carousel = document.getElementById('sabores-carousel');
		var btnPrev  = document.getElementById('sabores-prev');
		var btnNext  = document.getElementById('sabores-next');
		var total    = track.querySelectorAll('.sabores-slide').length;
		var current  = 0;
		var timer    = null;

		function getVisible() {
			if (window.innerWidth < 576) return 1;
			if (window.innerWidth < 992) return 2;
			return 4;
		}

		function goTo(index) {
			var max = total - getVisible();
			if (index < 0)   index = max;
			if (index > max) index = 0;
			current = index;
			track.style.transform = 'translateX(-' + (100 / getVisible() * current) + '%)';
		}

		function startAuto() {
			stopAuto();
			timer = setInterval(function () { goTo(current + 1); }, 2500);
		}

		function stopAuto() {
			if (timer) { clearInterval(timer); timer = null; }
		}

		btnNext.addEventListener('click', function () { goTo(current + 1); startAuto(); });
		btnPrev.addEventListener('click', function () { goTo(current - 1); startAuto(); });
		carousel.addEventListener('mouseenter', stopAuto);
		carousel.addEventListener('mouseleave', startAuto);
		window.addEventListener('resize', function () { goTo(Math.min(current, total - getVisible())); });

		startAuto();
	})();
	</script>

	<!-- ===== BENEFICIOS ===== -->
	<section class="beneficios-section" id="beneficios">
		<div class="<?php echo esc_attr( $container ); ?>">

			<div class="text-center mb-5">
				<span class="catering-label">Ventajas del producto</span>
				<h2>Beneficios para caterings y eventos</h2>
			</div>

			<div class="row g-4">

				<div class="col-md-6 col-lg-4">
					<div class="benefit-card">
						<div class="benefit-icon">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 2C8 2 4 5 4 9c0 5.25 8 13 8 13s8-7.75 8-13c0-4-4-7-8-7z"/><circle cx="12" cy="9" r="2.5"/></svg>
						</div>
						<div>
							<h3>Postre que funciona</h3>
							<p>Mantiene forma y presencia durante todo el evento, sin riesgos ni imprevistos de temperatura.</p>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="benefit-card">
						<div class="benefit-icon">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="13 2 3 14 12 14 11 22 21 10 12 10 13 2"/></svg>
						</div>
						<div>
							<h3>Agilidad total</h3>
							<p>Más control en cocina y en sala durante el evento. Operativa completamente simplificada.</p>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="benefit-card">
						<div class="benefit-icon">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polygon points="12 2 15.09 8.26 22 9.27 17 14.14 18.18 21.02 12 17.77 5.82 21.02 7 14.14 2 9.27 8.91 8.26 12 2"/></svg>
						</div>
						<div>
							<h3>Diferenciación</h3>
							<p>Un postre diferente que eleva la experiencia y sorprende al cliente final de tu evento.</p>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="benefit-card">
						<div class="benefit-icon">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><polyline points="1 4 1 10 7 10"/><path d="M3.51 15a9 9 0 1 0 .49-3.03"/></svg>
						</div>
						<div>
							<h3>Formato versátil</h3>
							<p>Perfecto para eventos grandes, servicios continuos y distintas tipologías de catering.</p>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="benefit-card">
						<div class="benefit-icon">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="10"/><line x1="12" y1="8" x2="12" y2="16"/><line x1="8" y1="12" x2="16" y2="12"/></svg>
						</div>
						<div>
							<h3>Personalización</h3>
							<p>Nos adaptamos a la identidad y necesidades específicas de cada evento y catering.</p>
						</div>
					</div>
				</div>

				<div class="col-md-6 col-lg-4">
					<div class="benefit-card">
						<div class="benefit-icon">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
						</div>
						<div>
							<h3>Fácil de vender</h3>
							<p>Un concepto claro que tu cliente entiende y valora desde el primer momento.</p>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>

	<!-- ===== PACKAGING ===== -->
	<section class="packaging-section" id="packaging">
		<div class="<?php echo esc_attr( $container ); ?>">

			<div class="mb-5">
				<span class="catering-label">Cómo llega a tu evento</span>
				<h2 class="section-heading-underline">Presentación &amp; Packaging</h2>
			</div>

			<div class="row g-4">

				<div class="col-sm-6 col-lg-3">
					<div class="pkg-flip">
						<div class="pkg-flip-inner">
							<div class="pkg-front">
								<div class="pkg-img-wrapper">
									<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/catering/packaging/plato_postre.jpg' ); ?>" alt="Plato de postre">
								</div>
								<div class="pkg-label"><h4>Plato de postre</h4></div>
							</div>
							<div class="pkg-back">
								<span class="pkg-back-label">Presentación</span>
								<h4>Plato de postre</h4>
								<p>Presentación individual sobre plato de pizarra o cerámica. Ideal para eventos sentados y cenas de gala donde el producto brilla como postre principal.</p>
								</div>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-lg-3">
					<div class="pkg-flip">
						<div class="pkg-flip-inner">
							<div class="pkg-front">
								<div class="pkg-img-wrapper">
									<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/catering/packaging/caja_consumo.jpg' ); ?>" alt="Caja consumo directo">
								</div>
								<div class="pkg-label"><h4>Caja consumo directo</h4></div>
							</div>
							<div class="pkg-back">
								<span class="pkg-back-label">Presentación</span>
								<h4>Caja consumo directo</h4>
								<p>Caja de cartón con branding Locobó. Perfecta para consumo inmediato en eventos de pie, ferias y pop-ups. Fácil de transportar y abrir.</p>
								</div>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-lg-3">
					<div class="pkg-flip">
						<div class="pkg-flip-inner">
							<div class="pkg-front">
								<div class="pkg-img-wrapper">
									<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/catering/packaging/cubo_takeaway.png' ); ?>" alt="Cubo take away">
								</div>
								<div class="pkg-label"><h4>Cubo take away</h4></div>
							</div>
							<div class="pkg-back">
								<span class="pkg-back-label">Presentación</span>
								<h4>Cubo take away</h4>
								<p>Cubo individual con tapa hermética para llevar. Diseñado para festivales, mercados y eventos donde el cliente se mueve. Mantiene la cadena de frío.</p>
								</div>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-lg-3">
					<div class="pkg-flip">
						<div class="pkg-flip-inner">
							<div class="pkg-front">
								<div class="pkg-img-wrapper">
									<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/catering/packaging/bandeja_catering.jpg' ); ?>" alt="Bandeja de catering" class="no-zoom">
								</div>
								<div class="pkg-label"><h4>Bandeja de catering</h4></div>
							</div>
							<div class="pkg-back">
								<span class="pkg-back-label">Presentación</span>
								<h4>Bandeja de catering</h4>
								<p>Bandeja transparente de 60 unidades lista para el pase. El formato estrella para cocktails y eventos corporativos. Impacto visual garantizado.</p>
								</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>

	<!-- ===== FORMATOS ===== -->
	<section class="formatos-section" id="formatos">
		<div class="<?php echo esc_attr( $container ); ?>">

			<div class="mb-5">
				<span class="catering-label">De exposición</span>
				<h2 class="section-heading-underline">Formatos</h2>
			</div>

			<div class="row g-4 justify-content-center">

				<div class="col-sm-6 col-lg-4">
					<div class="pkg-flip">
						<div class="pkg-flip-inner">
							<div class="pkg-front">
								<div class="pkg-img-wrapper">
									<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/catering/formatos/mini_corner.jpg' ); ?>" alt="Mini Corner" class="no-zoom">
								</div>
								<div class="pkg-label"><h4>Mini Corner</h4></div>
							</div>
							<div class="pkg-back">
								<span class="pkg-back-label">Formato</span>
								<h4>Mini Corner</h4>
								<p>Expositor compacto de Locopolo. Ideal para corners en sala, puntos de venta en espacios reducidos y eventos con poco margen de instalación.</p>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-lg-4">
					<div class="pkg-flip">
						<div class="pkg-flip-inner">
							<div class="pkg-front">
								<div class="pkg-img-wrapper">
									<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/catering/formatos/kiosko_carro.jpg' ); ?>" alt="Kiosko / Carro" class="no-zoom">
								</div>
								<div class="pkg-label"><h4>Kiosko / Carro</h4></div>
							</div>
							<div class="pkg-back">
								<span class="pkg-back-label">Formato</span>
								<h4>Kiosko / Carro</h4>
								<p>Formato móvil autónomo para exteriores, festivales y eventos multitudinarios. Alto impacto visual y fácil desplazamiento entre zonas del evento.</p>
							</div>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-lg-4">
					<div class="pkg-flip">
						<div class="pkg-flip-inner">
							<div class="pkg-front">
								<div class="pkg-img-wrapper">
									<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/catering/formatos/vitrina.jpg' ); ?>" alt="Vitrina" class="no-zoom">
								</div>
								<div class="pkg-label"><h4>Vitrina</h4></div>
							</div>
							<div class="pkg-back">
								<span class="pkg-back-label">Formato</span>
								<h4>Vitrina</h4>
								<p>Expositor premium para hostelería y venues de lujo. Máxima visibilidad del producto en sala con una presencia elegante y profesional.</p>
							</div>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>

	<script>
	document.querySelectorAll('.pkg-flip').forEach(function(card) {
		card.addEventListener('click', function() {
			this.classList.toggle('flipped');
		});
	});
	</script>

	<!-- ===== TESTIMONIOS ===== -->
	<section class="testimonios-section">
		<div class="<?php echo esc_attr( $container ); ?>">

			<div class="row">
				<div class="col-lg-3 mb-4 mb-lg-0">
					<span class="catering-label">Lo que dicen</span>
					<h2>Testimonios reales</h2>
					<div class="testimonio-quote-mark">"</div>
				</div>
				<div class="col-lg-9">
					<div class="row g-4">
						<div class="col-md-6">
							<div class="testimonio-card">
								<p>"En eventos con muchos servicios, Locobó nos da tranquilidad porque sabemos que el postre llegará perfecto a mesa."</p>
								<span class="autor">Responsable de Catering</span>
							</div>
						</div>
						<div class="col-md-6 mt-md-4">
							<div class="testimonio-card">
								<p>"Funciona muy bien en eventos grandes porque no depende del último minuto ni de la cadena de frío estricta."</p>
								<span class="autor">Jefe de Cocina</span>
							</div>
						</div>
						<div class="col-12">
							<div class="testimonio-card">
								<p style="font-size:1.05rem;">"Sorprende al cliente final y refuerza la percepción de calidad del catering en eventos corporativos."</p>
								<span class="autor">Responsable F&amp;B · Eventos</span>
							</div>
						</div>
					</div>
				</div>
			</div>

		</div>
	</section>

	<!-- ===== CTA FINAL ===== -->
	<section class="catering-cta" id="contacto">
		<div class="<?php echo esc_attr( $container ); ?>">
			<span class="catering-label" style="color:#5F6776;justify-content:center;display:block;margin-bottom:0.5rem;">¿Listo para el siguiente nivel?</span>
			<h2 class="mb-3">Empieza a servir Locobó<br>en tu próximo evento</h2>
			<p class="mb-5">Sin pedido mínimo. Entrega en 24–48h. Soporte comercial cercano.</p>
			<div class="d-flex justify-content-center flex-wrap gap-3">
				<a href="<?php echo esc_url( home_url( '/contacto' ) ); ?>" class="btn btn-warning btn-lg px-5 py-3" style="font-family:'Space Mono',monospace;font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;border:3px solid #FEE800;box-shadow:4px 4px 0 0 #FEE800;color:#000;font-weight:700;">
					Solicitar información
				</a>
			</div>
		</div>
	</section>

	<!-- ===== MARQUEE ===== -->
	<div class="catering-marquee">
		<div class="catering-marquee-inner">
			LOCOPOLO &nbsp;·&nbsp; CATERING &amp; EVENTOS &nbsp;·&nbsp; POSTRES QUE RESISTEN EL CALOR &nbsp;·&nbsp; SABORES ÚNICOS &nbsp;·&nbsp; EXPERIENCIA PREMIUM &nbsp;·&nbsp;
			LOCOPOLO &nbsp;·&nbsp; CATERING &amp; EVENTOS &nbsp;·&nbsp; POSTRES QUE RESISTEN EL CALOR &nbsp;·&nbsp; SABORES ÚNICOS &nbsp;·&nbsp; EXPERIENCIA PREMIUM &nbsp;·&nbsp;
		</div>
	</div>

</div><!-- #catering-page-wrapper -->

<?php get_footer(); ?>