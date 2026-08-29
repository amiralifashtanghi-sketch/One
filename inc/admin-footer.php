<?php
/**
 * Footer Settings Page Callback (Expanded Options)
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

function kish_harmony_footer_settings_page() {
	if ( ! current_user_can( 'manage_options' ) ) {
		wp_die( 'دسترسی غیرمجاز.' );
	}

	if ( isset( $_POST['kish_harmony_save_footer'] ) && check_admin_referer( 'kish_harmony_footer_nonce' ) ) {
		$copyright_text = sanitize_text_field( $_POST['copyright_text'] ?? '' );
		$footer_text    = sanitize_text_field( $_POST['footer_text'] ?? '' );
		$vip_text       = sanitize_text_field( $_POST['vip_text'] ?? '' );
		$cta_text     = sanitize_text_field( $_POST['cta_text'] ?? '' );
		$cta_link     = esc_url_raw( $_POST['cta_link'] ?? '' );
		$address      = sanitize_text_field( $_POST['address'] ?? '' );
		$map_link     = esc_url_raw( $_POST['map_link'] ?? '' );
		$phones       = isset( $_POST['phones'] ) ? array_map( 'sanitize_text_field', $_POST['phones'] ) : array();
		$socials      = array(
			'instagram' => esc_url_raw( $_POST['socials']['instagram'] ?? '' ),
			'telegram'  => esc_url_raw( $_POST['socials']['telegram'] ?? '' ),
			'whatsapp'  => esc_url_raw( $_POST['socials']['whatsapp'] ?? '' ),
		);
		$trust_badges = isset( $_POST['trust_badges'] ) ? $_POST['trust_badges'] : array();

		$sanitized_badges = array();
		if ( is_array( $trust_badges ) ) {
			foreach ( $trust_badges as $b ) {
				$raw_code = wp_unslash( $b['code'] ?? '' );
				if ( ! empty( $b['img_url'] ) || ! empty( $raw_code ) ) {
					$sanitized_badges[] = array(
						'img_url' => esc_url_raw( $b['img_url'] ?? '' ),
						'link'    => esc_url_raw( $b['link'] ?? '' ),
						'code'    => $raw_code,
					);
				}
			}
		}

		$footer_data = array(
			'copyright_text' => $copyright_text,
			'footer_text'    => $footer_text,
			'vip_text'       => $vip_text,
			'cta_text'     => $cta_text,
			'cta_link'     => $cta_link,
			'address'      => $address,
			'map_link'     => $map_link,
			'phones'       => array_values( array_filter( $phones ) ),
			'socials'      => $socials,
			'trust_badges' => $sanitized_badges,
		);

		update_option( 'kish_harmony_footer_options', $footer_data );
		echo '<div class="updated"><p>تنظیمات جامع فوتر با موفقیت ذخیره شد.</p></div>';
	}

	$options = get_option( 'kish_harmony_footer_options', array() );
	if ( ! is_array( $options ) ) {
		$options = array();
	}

	$copyright_text = $options['copyright_text'] ?? '© 2026 کلیه حقوق مادی و معنوی متعلق به Kishharmony می‌باشد. ساخته شده با ♥️ در ایران';
	$footer_text    = $options['footer_text'] ?? 'کیش هارمونی؛ مرجع رسمی رزرو خدمات و تفریحات جزیره کیش.';
	$vip_text     = $options['vip_text'] ?? 'پشتیبانی ۲۴ ساعته VIP';
	$cta_text     = $options['cta_text'] ?? 'مشاوره رایگان ←';
	$cta_link     = $options['cta_link'] ?? '#';
	$address      = $options['address'] ?? 'جزیره کیش، برج صدف، واحد ۲۰۴';
	$map_link     = $options['map_link'] ?? '#';
	$phones       = ! empty( $options['phones'] ) && is_array( $options['phones'] ) ? $options['phones'] : array( '076-44440000', '09120000000' );
	$socials      = ! empty( $options['socials'] ) && is_array( $options['socials'] ) ? $options['socials'] : array( 'instagram' => '#', 'telegram' => '#', 'whatsapp' => '#' );
	$trust_badges = ! empty( $options['trust_badges'] ) && is_array( $options['trust_badges'] ) ? $options['trust_badges'] : array();
	?>
	<div class="wrap">
		<h1>تنظیمات جامع فوتر "جزیره‌ی آبی"</h1>
		<form method="post" action="">
			<?php wp_nonce_field( 'kish_harmony_footer_nonce' ); ?>
			<table class="form-table">
				<tr>
					<th scope="row">متن حقوق مادی و معنوی (کپی‌رایت پایین فوتر):</th>
					<td>
						<input type="text" name="copyright_text" value="<?php echo esc_attr( $copyright_text ); ?>" class="large-text">
					</td>
				</tr>
				<tr>
					<th scope="row">متن درباره ما (فوتر):</th>
					<td>
						<textarea name="footer_text" rows="3" class="large-text"><?php echo esc_textarea( $footer_text ); ?></textarea>
					</td>
				</tr>
				<tr>
					<th scope="row">عنوان پشتیبانی VIP:</th>
					<td>
						<input type="text" name="vip_text" value="<?php echo esc_attr( $vip_text ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">متن دکمه مشاوره رایگان:</th>
					<td>
						<input type="text" name="cta_text" value="<?php echo esc_attr( $cta_text ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">لینک دکمه مشاوره رایگان:</th>
					<td>
						<input type="text" name="cta_link" value="<?php echo esc_url( $cta_link ); ?>" class="regular-text">
					</td>
				</tr>
				<tr>
					<th scope="row">آدرس آیکون لوکیشن و متن آدرس:</th>
					<td>
						<input type="text" name="address" value="<?php echo esc_attr( $address ); ?>" class="large-text" placeholder="متن آدرس"><br><br>
						<input type="text" name="map_link" value="<?php echo esc_url( $map_link ); ?>" class="large-text" placeholder="لینک گوگل مپ / نشان">
					</td>
				</tr>
			</table>

			<h2>تلفن‌های پشتیبانی (چند شماره)</h2>
			<div id="phones-repeater">
				<?php foreach ( $phones as $idx => $p ) : ?>
					<div class="phone-row" style="margin-bottom:10px;">
						<input type="text" name="phones[]" value="<?php echo esc_attr( $p ); ?>" class="regular-text">
						<button type="button" class="button remove-phone-btn" style="color:red;">حذف</button>
					</div>
				<?php endforeach; ?>
			</div>
			<button type="button" id="add-phone-btn" class="button" style="margin-bottom:20px;">+ افزودن شماره جدید</button>

			<h2>شبکه‌های اجتماعی</h2>
			<table class="form-table">
				<tr>
					<th scope="row">اینستاگرام:</th>
					<td><input type="text" name="socials[instagram]" value="<?php echo esc_url( $socials['instagram'] ?? '' ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row">تلگرام:</th>
					<td><input type="text" name="socials[telegram]" value="<?php echo esc_url( $socials['telegram'] ?? '' ); ?>" class="regular-text"></td>
				</tr>
				<tr>
					<th scope="row">واتساپ:</th>
					<td><input type="text" name="socials[whatsapp]" value="<?php echo esc_url( $socials['whatsapp'] ?? '' ); ?>" class="regular-text"></td>
				</tr>
			</table>

			<h2>نمادهای اعتماد و اینمادها (چند نماد)</h2>
			<div id="badges-repeater">
				<?php foreach ( $trust_badges as $idx => $b ) :
					$img_url = $b['img_url'] ?? '';
					$link    = $b['link'] ?? '';
					$code    = $b['code'] ?? '';
				?>
					<div class="badge-row" style="background:#fff; border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:8px; position:relative;">
						<button type="button" class="button remove-badge-btn" style="position:absolute; top:10px; left:10px; color:red; border-color:red;">حذف این نماد</button>
						<p>
							<label>آدرس عکس نماد:</label><br>
							<input type="text" name="trust_badges[<?php echo $idx; ?>][img_url]" value="<?php echo esc_url( $img_url ); ?>" class="large-text">
						</p>
						<p>
							<label>لینک کلیک نماد:</label><br>
							<input type="text" name="trust_badges[<?php echo $idx; ?>][link]" value="<?php echo esc_url( $link ); ?>" class="large-text">
						</p>
						<p>
							<label>یا کد HTML / script / iframe نماد (اختیاری):</label><br>
							<textarea name="trust_badges[<?php echo $idx; ?>][code]" rows="2" class="large-text"><?php echo esc_textarea( $code ); ?></textarea>
						</p>
					</div>
				<?php endforeach; ?>
			</div>
			<button type="button" id="add-badge-btn" class="button" style="margin-bottom:20px;">+ افزودن نماد جدید</button>

			<p class="submit">
				<input type="submit" name="kish_harmony_save_footer" class="button button-primary" value="ذخیره جامع تنظیمات فوتر">
			</p>
		</form>
	</div>

	<script>
	document.addEventListener('DOMContentLoaded', function() {
		const phoneContainer = document.getElementById('phones-repeater');
		const addPhoneBtn = document.getElementById('add-phone-btn');
		if (addPhoneBtn && phoneContainer) {
			addPhoneBtn.addEventListener('click', function() {
				const html = `
					<div class="phone-row" style="margin-bottom:10px;">
						<input type="text" name="phones[]" value="" class="regular-text">
						<button type="button" class="button remove-phone-btn" style="color:red;">حذف</button>
					</div>
				`;
				phoneContainer.insertAdjacentHTML('beforeend', html);
			});
			phoneContainer.addEventListener('click', function(e) {
				if (e.target.classList.contains('remove-phone-btn')) e.target.closest('.phone-row').remove();
			});
		}

		const badgeContainer = document.getElementById('badges-repeater');
		const addBadgeBtn = document.getElementById('add-badge-btn');
		if (addBadgeBtn && badgeContainer) {
			addBadgeBtn.addEventListener('click', function() {
				const idx = Date.now();
				const html = `
					<div class="badge-row" style="background:#fff; border:1px solid #ccc; padding:15px; margin-bottom:15px; border-radius:8px; position:relative;">
						<button type="button" class="button remove-badge-btn" style="position:absolute; top:10px; left:10px; color:red; border-color:red;">حذف این نماد</button>
						<p>
							<label>آدرس عکس نماد:</label><br>
							<input type="text" name="trust_badges[${idx}][img_url]" value="" class="large-text">
						</p>
						<p>
							<label>لینک کلیک نماد:</label><br>
							<input type="text" name="trust_badges[${idx}][link]" value="" class="large-text">
						</p>
						<p>
							<label>یا کد HTML / script / iframe نماد (اختیاری):</label><br>
							<textarea name="trust_badges[${idx}][code]" rows="2" class="large-text"></textarea>
						</p>
					</div>
				`;
				badgeContainer.insertAdjacentHTML('beforeend', html);
			});
			badgeContainer.addEventListener('click', function(e) {
				if (e.target.classList.contains('remove-badge-btn')) e.target.closest('.badge-row').remove();
			});
		}
	});
	</script>
	<?php
}
