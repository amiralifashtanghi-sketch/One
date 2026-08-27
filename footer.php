<?php
/**
 * Blue Island Footer Template (جزیره‌ی آبی)
 */
$footer_options = get_option( 'kish_harmony_footer_options', array() );
$brand_name     = get_option( 'kish_harmony_header_options' )['brand_name'] ?? 'جزیره‌ی آبی';
$footer_text    = ! empty( $footer_options['footer_text'] ) ? $footer_options['footer_text'] : 'کیش هارمونی؛ مرجع رسمی رزرو خدمات و تفریحات جزیره کیش.';
$vip_text       = ! empty( $footer_options['vip_text'] ) ? $footer_options['vip_text'] : 'پشتیبانی ۲۴ ساعته VIP';
$address        = ! empty( $footer_options['address'] ) ? $footer_options['address'] : 'جزیره کیش، برج صدف، واحد ۲۰۴';
$map_link       = ! empty( $footer_options['map_link'] ) ? $footer_options['map_link'] : '#';
$phones         = ! empty( $footer_options['phones'] ) && is_array( $footer_options['phones'] ) ? $footer_options['phones'] : array( '076-44440000' );
$socials        = ! empty( $footer_options['socials'] ) && is_array( $footer_options['socials'] ) ? $footer_options['socials'] : array();
$trust_badges   = ! empty( $footer_options['trust_badges'] ) && is_array( $footer_options['trust_badges'] ) ? $footer_options['trust_badges'] : array();
?>

<footer class="footer-wrapper">

	<!-- Top Wave (White, Peak Facing Up) -->
	<div class="footer-top-wave" aria-hidden="true">
		<svg viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
			<path class="wave-fill" transform="scale(1, -1) translate(0, -80)"
				  d="M0,35 C80,10 160,65 240,35 C320,5 400,60 480,35 C560,10 640,70 720,35 C800,0 880,60 960,35 C1040,10 1120,65 1200,35 C1280,5 1360,55 1440,35 L1440,80 L0,80 Z">
				<animate attributeName="d" dur="4s" repeatCount="indefinite" values="
					M0,35 C80,10 160,65 240,35 C320,5 400,60 480,35 C560,10 640,70 720,35 C800,0 880,60 960,35 C1040,10 1120,65 1200,35 C1280,5 1360,55 1440,35 L1440,80 L0,80 Z;
					M0,45 C90,20 170,70 250,45 C330,20 410,65 490,40 C570,15 650,60 730,45 C810,30 890,55 970,40 C1050,25 1130,60 1210,45 C1290,30 1370,50 1440,45 L1440,80 L0,80 Z;
					M0,25 C70,0 150,55 230,25 C310,-5 390,50 470,25 C550,0 630,55 710,25 C790,-5 870,50 950,25 C1030,0 1110,55 1190,25 C1270,-5 1350,45 1440,25 L1440,80 L0,80 Z;
					M0,35 C80,10 160,65 240,35 C320,5 400,60 480,35 C560,10 640,70 720,35 C800,0 880,60 960,35 C1040,10 1120,65 1200,35 C1280,5 1360,55 1440,35 L1440,80 L0,80 Z
				" />
			</path>
		</svg>
	</div>

	<!-- Logo Strip -->
	<div class="logo-strip">
		<a href="<?php echo esc_url( home_url( '/' ) ); ?>" class="logo-link">
			<span class="logo-icon">🏝️</span>
			<div class="logo-text">
				<span class="logo-name"><?php echo esc_html( $brand_name ); ?></span>
				<span class="logo-tagline">Kish Harmony</span>
			</div>
		</a>
	</div>

	<!-- Middle Wave (Deep Blue) -->
	<div class="middle-wave" aria-hidden="true">
		<svg viewBox="0 0 1440 80" preserveAspectRatio="none" xmlns="http://www.w3.org/2000/svg">
			<path class="wave-deep"
				  d="M0,35 C80,10 160,65 240,35 C320,5 400,60 480,35 C560,10 640,70 720,35 C800,0 880,60 960,35 C1040,10 1120,65 1200,35 C1280,5 1360,55 1440,35 L1440,80 L0,80 Z">
				<animate attributeName="d" dur="4s" repeatCount="indefinite" values="
					M0,35 C80,10 160,65 240,35 C320,5 400,60 480,35 C560,10 640,70 720,35 C800,0 880,60 960,35 C1040,10 1120,65 1200,35 C1280,5 1360,55 1440,35 L1440,80 L0,80 Z;
					M0,45 C90,20 170,70 250,45 C330,20 410,65 490,40 C570,15 650,60 730,45 C810,30 890,55 970,40 C1050,25 1130,60 1210,45 C1290,30 1370,50 1440,45 L1440,80 L0,80 Z;
					M0,25 C70,0 150,55 230,25 C310,-5 390,50 470,25 C550,0 630,55 710,25 C790,-5 870,50 950,25 C1030,0 1110,55 1190,25 C1270,-5 1350,45 1440,25 L1440,80 L0,80 Z;
					M0,35 C80,10 160,65 240,35 C320,5 400,60 480,35 C560,10 640,70 720,35 C800,0 880,60 960,35 C1040,10 1120,65 1200,35 C1280,5 1360,55 1440,35 L1440,80 L0,80 Z
				" />
			</path>
		</svg>
	</div>

	<!-- Main Content Body -->
	<div class="main-content">
		<div class="glow-line"></div>
		<div class="particles">
			<span class="particle p1"></span>
			<span class="particle p2"></span>
			<span class="particle p3"></span>
			<span class="particle p4"></span>
		</div>

		<div class="footer-inner">
			<div class="link-group">
				<!-- Quick Links -->
				<div>
					<h4 class="col-title">دسترسی سریع</h4>
					<?php
					wp_nav_menu( array(
						'theme_location' => 'footer_menu',
						'container'      => false,
						'menu_class'     => 'footer-links',
						'fallback_cb'    => false,
					) );
					?>
				</div>
				<!-- Support & VIP Badge -->
				<div>
					<h4 class="col-title">پشتیبانی</h4>
					<ul class="footer-links">
						<li><a href="<?php echo esc_url( home_url( '/faq' ) ); ?>" class="footer-link">سوالات متداول</a></li>
						<li><a href="<?php echo esc_url( home_url( '/terms' ) ); ?>" class="footer-link">قوانین و مقررات</a></li>
						<li><span class="badge"><span class="badge-dot"></span> <?php echo esc_html( $vip_text ); ?></span></li>
					</ul>
				</div>
			</div>

			<!-- Contact Column -->
			<div>
				<h4 class="col-title">تماس با ما</h4>
				<?php if ( ! empty( $address ) ) : ?>
					<div class="contact-item">
						<span class="contact-icon">📍</span>
						<span><a href="<?php echo esc_url( $map_link ); ?>" target="_blank" style="color:inherit;"><?php echo esc_html( $address ); ?></a></span>
					</div>
				<?php endif; ?>

				<?php foreach ( $phones as $phone ) : ?>
					<div class="contact-item">
						<span class="contact-icon">📞</span>
						<span><a href="tel:<?php echo esc_attr( $phone ); ?>" style="color:inherit;"><?php echo esc_html( $phone ); ?></a></span>
					</div>
				<?php endforeach; ?>

				<a href="<?php echo esc_url( home_url( '/contact' ) ); ?>" class="cta-button">مشاوره رایگان ←</a>

				<div class="socials">
					<?php if ( ! empty( $socials['instagram'] ) ) : ?>
						<a href="<?php echo esc_url( $socials['instagram'] ); ?>" class="social-link" target="_blank"><i class="fa-brands fa-instagram"></i></a>
					<?php endif; ?>
					<?php if ( ! empty( $socials['telegram'] ) ) : ?>
						<a href="<?php echo esc_url( $socials['telegram'] ); ?>" class="social-link" target="_blank"><i class="fa-brands fa-telegram"></i></a>
					<?php endif; ?>
					<?php if ( ! empty( $socials['whatsapp'] ) ) : ?>
						<a href="<?php echo esc_url( $socials['whatsapp'] ); ?>" class="social-link" target="_blank"><i class="fa-brands fa-whatsapp"></i></a>
					<?php endif; ?>
				</div>

				<!-- Trust Badges Seals -->
				<?php if ( ! empty( $trust_badges ) ) : ?>
					<div class="trust-badges-seals" style="margin-top:15px; display:flex; gap:10px; flex-wrap:wrap;">
						<?php foreach ( $trust_badges as $tb ) : ?>
							<?php if ( ! empty( $tb['code'] ) ) : ?>
								<?php echo $tb['code']; ?>
							<?php elseif ( ! empty( $tb['img_url'] ) ) : ?>
								<a href="<?php echo esc_url( $tb['link'] ?: '#' ); ?>" target="_blank"><img src="<?php echo esc_url( $tb['img_url'] ); ?>" style="max-height:60px; background:#fff; padding:4px; border-radius:8px;"></a>
							<?php endif; ?>
						<?php endforeach; ?>
					</div>
				<?php endif; ?>
			</div>
		</div>

		<div class="footer-bottom">
			<span>© <?php echo date( 'Y' ); ?> کلیه حقوق مادی و معنوی متعلق به <?php echo esc_html( $brand_name ); ?> می‌باشد.</span>
			<span>ساخته شده با <span class="heart">♥</span> در ایران</span>
		</div>
	</div>
</footer>

<?php wp_footer(); ?>
</body>
</html>
