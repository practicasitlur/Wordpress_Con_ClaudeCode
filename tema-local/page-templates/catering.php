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
		.catering-hero .hero-image-wrapper img {
			position: relative;
			z-index: 1;
			width: 100%;
			height: 420px;
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
			color: #000;
			opacity: 0.08;
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
		.producto-image-wrapper img {
			width: 100%;
			height: auto;
			border: 3px solid #000;
			box-shadow: 4px 4px 0 0 #000;
			filter: grayscale(100%);
			transition: filter 0.6s;
		}
		.producto-image-wrapper img:hover {
			filter: grayscale(0%);
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

		/* Flavor cards */
		.sabores-section {
			background-color: #000;
			color: #fff;
			padding: 5rem 0;
		}
		.sabores-section h2 {
			color: #FEE800;
		}
		.sabores-section .catering-label {
			color: #b0b0a2;
		}
		.flavor-card {
			background: #1a1a1a;
			border: 3px solid #333;
			overflow: hidden;
			transition: border-color 0.2s, transform 0.2s;
		}
		.flavor-card:hover {
			border-color: #FEE800;
			transform: translateY(-4px);
		}
		.flavor-card .flavor-img-wrapper {
			height: 180px;
			background-color: #2a2a2a;
			display: flex;
			align-items: center;
			justify-content: center;
			overflow: hidden;
			position: relative;
		}
		.flavor-card .flavor-img-wrapper img {
			height: 150px;
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
		}
		.flavor-card .flavor-category {
			font-family: 'Space Mono', monospace;
			font-size: 0.6rem;
			letter-spacing: 0.1em;
			text-transform: uppercase;
			color: #b0b0a2;
			display: block;
			margin-bottom: 0.4rem;
		}
		.flavor-card h4 {
			font-size: 1.05rem;
			font-weight: 700;
			margin-bottom: 0.4rem;
			color: #fff;
		}
		.flavor-card p {
			font-size: 0.8rem;
			color: #b0b0a2;
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

		/* Packaging */
		.packaging-section {
			background-color: #fff;
			border-top: 3px solid #000;
			border-bottom: 3px solid #000;
			padding: 5rem 0;
		}
		.packaging-card {
			background: #EDEDED;
			border: 3px solid #000;
			box-shadow: 4px 4px 0 0 #000;
			padding: 2.5rem 1.5rem;
			text-align: center;
			height: 100%;
			transition: background-color 0.2s, box-shadow 0.2s, transform 0.2s;
		}
		.packaging-card:hover {
			background-color: #FEE800;
			box-shadow: 6px 6px 0 0 #000;
			transform: translate(-2px, -2px);
		}
		.packaging-card .pkg-icon {
			width: 64px;
			height: 64px;
			background: #fff;
			border: 3px solid #000;
			border-radius: 50%;
			display: flex;
			align-items: center;
			justify-content: center;
			margin: 0 auto 1.25rem;
		}
		.packaging-card .pkg-icon svg {
			width: 30px;
			height: 30px;
		}
		.packaging-card h4 {
			font-family: 'Space Mono', monospace;
			font-size: 0.7rem;
			font-weight: 700;
			letter-spacing: 0.08em;
			text-transform: uppercase;
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
			background-color: #000;
			color: #fff;
			padding: 5rem 0;
			text-align: center;
		}
		.catering-cta h2 {
			color: #FEE800;
			font-size: clamp(1.8rem, 4vw, 3rem);
			font-weight: 800;
		}
		.catering-cta p {
			color: #b0b0a2;
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
			.catering-hero .hero-image-wrapper img {
				height: 280px;
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

				<div class="col-lg-5 offset-lg-1">
					<div class="hero-image-wrapper">
						<img
							src="<?php echo esc_url( $child_uri . '/img/random-image/tobias-keller-2ecH5Lw3zSk-unsplash.jpg' ); ?>"
							alt="Locobó en un evento de catering"
						/>
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
							src="<?php echo esc_url( $child_uri . '/img/random-image/grant-lemons-jTCLppdwSEc-unsplash.jpg' ); ?>"
							alt="Detalle del producto Locobó"
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

			<div class="d-flex justify-content-between align-items-end mb-5">
				<div>
					<span class="catering-label">Explora nuestra</span>
					<h2 class="mb-0" style="text-transform:uppercase;letter-spacing:-0.02em;">Carta de Sabores</h2>
				</div>
				<a href="<?php echo esc_url( home_url( '/sabores' ) ); ?>" class="btn btn-outline-light btn-sm px-4" style="font-family:'Space Mono',monospace;font-size:0.65rem;letter-spacing:0.08em;text-transform:uppercase;border:2px solid #fff;">
					Ver todos →
				</a>
			</div>

			<div class="row g-4">

				<!-- Salados -->
				<div class="col-sm-6 col-lg-3">
					<div class="flavor-card h-100">
						<div class="flavor-img-wrapper">
							<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/catering/01_Gilda.png' ); ?>" alt="Gilda">
							<span class="flavor-badge">SALADO</span>
						</div>
						<div class="flavor-body">
							<span class="flavor-category">Cocktail — Salados</span>
							<h4>Gilda</h4>
							<p>Bañado en chocolate blanco con anchoa, pepinillo y guindilla.</p>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-lg-3">
					<div class="flavor-card h-100">
						<div class="flavor-img-wrapper">
							<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/catering/02_Pesto_y_Tomate.png' ); ?>" alt="Pesto y Tomate">
						</div>
						<div class="flavor-body">
							<span class="flavor-category">Cocktail — Salados</span>
							<h4>Pesto y Tomate</h4>
							<p>Bañado en chocolate blanco y pasta de pistacho y albahaca.</p>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-lg-3">
					<div class="flavor-card h-100">
						<div class="flavor-img-wrapper">
							<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/catering/04_Foie_y_Frambuesa.png' ); ?>" alt="Foie y Frambuesa">
							<span class="flavor-badge">NUEVO</span>
						</div>
						<div class="flavor-body">
							<span class="flavor-category">Cocktail — Salados</span>
							<h4>Foie y Frambuesa</h4>
							<p>Bañado en chocolate blanco, kikos en polvo y frambuesa liofilizada.</p>
						</div>
					</div>
				</div>

				<div class="col-sm-6 col-lg-3">
					<div class="flavor-card h-100">
						<div class="flavor-img-wrapper">
							<img src="<?php echo esc_url( get_stylesheet_directory_uri() . '/img/catering/06_Mango_y_Maracuya.png' ); ?>" alt="Mango y Maracuyá">
						</div>
						<div class="flavor-body">
							<span class="flavor-category">Locobó — Dulces</span>
							<h4>Mango y Maracuyá</h4>
							<p>Bañado en chocolate negro 70% cacao artesano.</p>
						</div>
					</div>
				</div>

			</div>
		</div>
	</section>

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
					<div class="packaging-card">
						<div class="pkg-icon">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 11l19-9-9 19-2-8-8-2z"/></svg>
						</div>
						<h4>Plato de postre</h4>
					</div>
				</div>

				<div class="col-sm-6 col-lg-3">
					<div class="packaging-card">
						<div class="pkg-icon">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8a2 2 0 0 0-1-1.73l-7-4a2 2 0 0 0-2 0l-7 4A2 2 0 0 0 3 8v8a2 2 0 0 0 1 1.73l7 4a2 2 0 0 0 2 0l7-4A2 2 0 0 0 21 16Z"/><polyline points="3.27 6.96 12 12.01 20.73 6.96"/><line x1="12" y1="22.08" x2="12" y2="12"/></svg>
						</div>
						<h4>Caja consumo directo</h4>
					</div>
				</div>

				<div class="col-sm-6 col-lg-3">
					<div class="packaging-card">
						<div class="pkg-icon">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M6 2 3 6v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2V6l-3-4z"/><line x1="3" y1="6" x2="21" y2="6"/><path d="M16 10a4 4 0 0 1-8 0"/></svg>
						</div>
						<h4>Cubo take away</h4>
					</div>
				</div>

				<div class="col-sm-6 col-lg-3">
					<div class="packaging-card">
						<div class="pkg-icon">
							<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="2" y="7" width="20" height="14" rx="2"/><path d="M16 7V5a2 2 0 0 0-2-2h-4a2 2 0 0 0-2 2v2"/><line x1="12" y1="12" x2="12" y2="17"/><line x1="9.5" y1="14.5" x2="14.5" y2="14.5"/></svg>
						</div>
						<h4>Bandeja de catering</h4>
					</div>
				</div>

			</div>
		</div>
	</section>

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
			<span class="catering-label" style="color:#b0b0a2;justify-content:center;display:block;margin-bottom:0.5rem;">¿Listo para el siguiente nivel?</span>
			<h2 class="mb-3">Empieza a servir Locobó<br>en tu próximo evento</h2>
			<p class="mb-5">Sin pedido mínimo. Entrega en 24–48h. Soporte comercial cercano.</p>
			<div class="d-flex justify-content-center flex-wrap gap-3">
				<a href="<?php echo esc_url( home_url( '/contacto' ) ); ?>" class="btn btn-warning btn-lg px-5 py-3" style="font-family:'Space Mono',monospace;font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;border:3px solid #FEE800;box-shadow:4px 4px 0 0 #FEE800;color:#000;font-weight:700;">
					Solicitar información
				</a>
				<a href="<?php echo esc_url( home_url( '/sabores' ) ); ?>" class="btn btn-outline-light btn-lg px-5 py-3" style="font-family:'Space Mono',monospace;font-size:0.7rem;letter-spacing:0.1em;text-transform:uppercase;border:3px solid #fff;box-shadow:4px 4px 0 0 #fff;">
					Ver catálogo completo
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