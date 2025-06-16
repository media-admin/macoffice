			<footer class="site-footer">
			<!-- 	<div class="site-footer__container--gray">

					<nav class="site-footer__navigation">
						<ul class="site-footer__navigation-list">
							<?php
								wp_nav_menu(array(
									'walker' => new FooterMenuNavwalker(),
									'menu' => 'Footermenü',
									'theme_location'=> 'nav-menu-footer',
									'container'=> '<ul>',
									'menu_class' => 'footer-navigation__list',
									'items_wrap'=> '%3$s',
									'fallback_cb'=> false
								));
							?>
						</ul>
					</nav>

				</div> -->

				<div class="site-footer__container--black">
					<div class="site-footer__row">
						<div class="site-footer__information">
							<div class="site-footer__branding">
								<div class="site-footer__logo">
									<a class="site-footer__logo-link wrapper" href="<?php echo get_home_url(); ?>">
										<picture>
											<source srcset="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/macoffice_footer-logo-dark_smartphone.svg" media="(prefers-color-scheme: dark)">
											<img id='site-footer__logo' class="site-footer__logo-img" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/macoffice_footer-logo-light_smartphone.svg" alt="Logo mac)office - Ihr autorisierter Apple-Händler in Wiener Neustadt">
										</picture>
									</a>
								</div>

								<div class="site-footer__apple-certificates">


									<div class="site-footer__apple-authorisierter-haendler">
										<a class="site-footer__logo-link wrapper" href="/produkte">
											<picture>
												<source srcset="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/apple-certificates-2-lines--white.svg" media="(prefers-color-scheme: dark)">
												<img class="site-footer__apple-haendler-img" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/apple-certificates-2-lines--white.svg" alt="Logo Autorisierter Händler">
											</picture>
										</a>
									</div>



									<!-- <div class="site-footer__apple-authorisierter-haendler">
										<a class="site-footer__logo-link wrapper" href="/produkte">
											<picture>
												<source srcset="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/apple-haendler_footer-logo.svg" media="(prefers-color-scheme: dark)">
												<img class="site-footer__apple-haendler-img" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/apple-haendler_footer-logo.svg" alt="Logo Autorisierter Händler">
											</picture>
										</a>
									</div>

									<div class="site-footer__apple-authorisierter-service-provider">
										<a class="site-footer__logo-link wrapper" href="/leistungen">
											<picture>
												<source srcset="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/apple-service-provider_footer-logo.svg" media="(prefers-color-scheme: dark)">
												<img class="site-footer__apple-haendler-img" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/apple-service-provider_footer-logo.svg" alt="Logo Autorisierter Service Provider">
											</picture>
										</a>
									</div> -->


								</div>

							</div>

							<div class="site-footer__contact">
								<div class="site-footer__contact-address">
									<a class="site-header-top__contact-information-phone-number" href="/ueber-uns">
										<img  class="site-footer__contact-address-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_address-footer_desktop.svg"/>
										<p class="site-footer__contact-address-title">Adresse</p>
										<p class="site-footer__contact-address-data">Fischauer Gasse 150<br/>2700 Wiener Neustadt<br/>Österreich</p>
									</a>
								</div>

								<div class="site-footer__contact-information">
									<div class="site-footer__contact-information-phone">
										<img class="site-footer__contact-information-phone-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_phone-footer_desktop.svg"/>
										<a class="site-footer__contact-information-phone-number" href="tel:+43 2622 85 270">+43 2622 85270</a>
									</div>
									<div class="site-footer__contact-information-mail">
										<a class="site-footer__contact-information-mail-address" href="mailto:info@macoffice.at">
											<picture>
											<img class="site-footer__contact-information-mail-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_mail-footer_desktop.svg"/>
											info@macoffice.at
										</a>
									</div>
									<div class="site-footer__contact-information-hotline">
										<a class="site-footer__contact-information-hotline-link" href="mailto:notfall@macoffice.at">
											<img class="site-footer__contact-information-hotline-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_call-footer_desktop.svg"/>
											<!-- <a class="site-footer__contact-information-hotline-number" href="tel:0900 888 345">0900 888 345 <span class="additional-info">[ EUR 1,81/min ]</span></a> -->
											Notfall
										</a>

									</div>
								</div>
								<div class="site-footer__contact-opening">
									<img class="site-footer__contact-opening-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_opening-hours-footer_desktop.svg"/>
									<p class="site-footer__contact-opening-title">Öffnungszeiten</p>
									<p class="site-footer__contact-opening-hours">Montag - Freitag von 10 - 18 Uhr<br/>Samstag von 10 - 13 Uhr</p>
								</div>
								<ul class="site-footer__social-media-area">
									<li><a href="https://www.facebook.com/macsworkeasier" target="_blank"><img class="site-footer__social-media-icon site-footer__social-media-icon--facebook" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_facebook-footer_smartphone.svg" alt="Facebook Logo"></a></li>
									<li><a href="https://www.instagram.com/_macoffice/" target="_blank"><img class="site-footer__social-media-icon site-footer__social-media-icon--instagram" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_instagram-footer_smartphone.svg" alt="Instagram Logo"></a></li>
									<li><a href="https://wa.me/436801115350" target="_blank"><img class="site-footer__social-media-icon site-footer__social-media-icon--whatsapp-business" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_whatsapp-business-footer_smartphone.svg" alt="WhatsApp Business Logo"></a></li>
								</ul>
							</div>

							<div class="site-footer__vcard">
								<a class="site-footer__vcard-link" href="https://www.macoffice.at/macoffice.vcf" target="_blank">
									<img class="site-footer__vcard-img" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/visitenqr.svg"/>
								</a>
							</div>

						</div>

						<div class="site-footer__main-navigation-container">
							<nav class="site-footer__main-navigation">
								<ul class="site-footer__main-navigation-list">

								<?php
									wp_nav_menu(array(
										/* 'walker'	=> new FooterNavwalker(),
										'menu' => 'Footernavigation',
										'theme_location' => 'footer-navigation',
										'depth'          => 2,
										'container'      => FALSE,
										'container_class'   => '',
										'menu_class'     => '',
										'items_wrap'     => '%3$s',
										'fallback_cb' => false */

										'walker' => new FooterMenuNavwalker(),
										'menu' => 'Footermenü',
										'theme_location'=> 'nav-menu-footer',
										'container'=> '<ul>',
										'menu_class' => 'footer-navigation__list',
										'items_wrap'=> '%3$s',
										'fallback_cb'=> false
									));
								?>

								</ul>
							</nav>
						</div>

					</div>

					<div class="site-footer__copyright">©&nbsp;<?php echo date("Y"); ?> mac)office - macs work easier. <div class="wrap-here-mobile">
						<span>Ihr autorisierter Apple Händler und Service Provider in Wiener Neustadt.</span></div></div>

				</div>

			</footer>

			<?php wp_footer();?>

			<!-- === START SCRIPTS AREA === -->

			<!-- Hamburger Menu Toggle -->
			<script async>
				jQuery(document).ready(function(){
					var navigation = document.querySelector(".main-navigation")
					var hamburger = document.querySelector(".burger-menu")

					navigation.onclick = function () {
						this.classList.toggle("is-active")
					}

					hamburger.onclick = function () {
						this.classList.toggle("checked")
					}
				});
			</script>



			<!-- Search Modal Smartphone -->
			<script async>
				// Get the modal
				var modal_smartphone = document.getElementById("search_modal_smartphone");

				// Get the button that opens the modal
				var btn_smartphone = document.getElementById("search_button_smartphone");

				// Get the <span> element that closes the modal
				var span = document.getElementsByClassName("modal-close-btn--smartphone")[0];

				// When the user clicks on the button, open the modal
				btn_smartphone.onclick = function() {
					modal_smartphone.style.display = "block";
				}

				// When the user clicks on <span> (x), close the modal
				span.onclick = function() {
					modal_smartphone.style.display = "none";
				}

				// When the user clicks anywhere outside of the modal, close it
				window.onclick = function(event) {
					if (event.target == modal_smartphone) {
						modal_smartphone.style.display = "none";
					}
				}
			</script>



			<!-- Search Modal Desktop -->
			<script async>
				// Get the modal
				var modal_desktop = document.getElementById("search_modal_desktop");

				// Get the button that opens the modal
				var btn_desktop = document.getElementById("search_button_desktop");

				// Get the <span> element that closes the modal
				var span_desktop = document.getElementById("modal-close-btn--desktop");

				// When the user clicks on the button, open the modal
				btn_desktop.onclick = function() {
					modal_desktop.style.display = "block";
				}

				// When the user clicks on <span> (x), close the modal
				span_desktop.onclick = function() {
					modal_desktop.style.display = "none";
				}

				// When the user clicks anywhere outside of the modal, close it
				window.onclick = function(event) {
					if (event.target == modal_desktop) {
						modal_desktop.style.display = "none";
					}
				}
			</script>


			<!-- Error Found Modal -->
			<script async>
				// Get the modal
				var modal_error_found = document.getElementById("error-found__modal-desktop");

				// Get the button that opens the modal
				var btn_error_found = document.getElementById("error-found__button-desktop");

				// Get the <span> element that closes the modal
				var span_error_found = document.getElementById("error-found__modal-close-btn");

				// When the user clicks on the button, open the modal
				btn_error_found.onclick = function() {
					modal_error_found.style.display = "block";
				}

				// When the user clicks on <span> (x), close the modal
				span_error_found.onclick = function() {
					modal_error_found.style.display = "none";
				}

				// When the user clicks anywhere outside of the modal, close it
				window.onclick = function(event) {
					if (event.target == modal_error_found) {
						modal_error_found.style.display = "none";
					}
				}
			</script>






			<!-- E-Mail info@macoffice.at Desktop-->
			<script async>
				// Get the modal
				var modal_email_info = document.getElementById("email-info__modal");

				// Get the button that opens the modal
				var btn_email_info = document.getElementById("email-info__button");

				// Get the <span> element that closes the modal
				var span_email_info = document.getElementById("email-info__modal-close-btn");

				// When the user clicks on the button, open the modal
				btn_email_info.onclick = function() {
					modal_email_info.style.display = "block";
				}

				// When the user clicks on <span> (x), close the modal
				span_email_info.onclick = function() {
					modal_email_info.style.display = "none";
				}

				// When the user clicks anywhere outside of the modal, close it
				window.onclick = function(event) {
					if (event.target == modal_email_info) {
						modal_email_info.style.display = "none";
					}
				}
			</script>


			<!-- E-Mail info@macoffice.at Smartphone-->
			<script async>
				// Get the modal
				var modal_email_info = document.getElementById("email-info__modal--smartphone");

				// Get the button that opens the modal
				var btn_email_info = document.getElementById("email-info__button--smartphone");

				// Get the <span> element that closes the modal
				var span_email_info = document.getElementById("email-info__modal-close-btn--smartphone");

				// When the user clicks on the button, open the modal
				btn_email_info.onclick = function() {
					modal_email_info.style.display = "block";
				}

				// When the user clicks on <span> (x), close the modal
				span_email_info.onclick = function() {
					modal_email_info.style.display = "none";
				}

				// When the user clicks anywhere outside of the modal, close it
				window.onclick = function(event) {
					if (event.target == modal_email_info) {
						modal_email_info.style.display = "none";
					}
				}
			</script>



			<!-- Error E-Mail notfall@macoffice.at -->
			<script async>
				// Get the modal
				var modal_email_emergency = document.getElementById("email-emergency__modal-desktop");

				// Get the button that opens the modal
				var btn_email_emergency = document.getElementById("email-emergency__button-desktop");

				// Get the <span> element that closes the modal
				var span_email_emergency = document.getElementById("email-emergency__modal-close-btn");

				// When the user clicks on the button, open the modal
				btn_email_emergency.onclick = function() {
					modal_email_emergency.style.display = "block";
				}

				// When the user clicks on <span> (x), close the modal
				span_email_emergency.onclick = function() {
					modal_email_emergency.style.display = "none";
				}

				// When the user clicks anywhere outside of the modal, close it
				window.onclick = function(event) {
					if (event.target == modal_email_emergency) {
						modal_email_emergency.style.display = "none";
					}
				}
			</script>



			<!-- Dark Mode Toggle #3 -->
			<script type="text/javascript">



				function getUserPreference() {
					return localStorage.getItem('theme') || 'system';
				}
				function saveUserPreference(userPreference) {
					localStorage.setItem('theme', userPreference);
				}

				function getAppliedMode(userPreference) {
					if (userPreference === 'light') {
						return 'light';
					}
					if (userPreference === 'dark') {
						return 'dark';
					}
					// system
					if (matchMedia('(prefers-color-scheme: light)').matches) {
						return 'light';
					}
					return 'dark';
				}

				function setAppliedMode(mode) {
					document.documentElement.dataset.appliedMode = mode;
				}

				function rotatePreferences(userPreference) {
					// if (userPreference === 'system') {
					//	return 'light'
					// }
					if (userPreference === 'light') {
						return 'dark';
					}
					if (userPreference === 'dark') {
						// return 'system';
						return 'light';
					}
					// for invalid values, just in case
					// return 'system';
					return 'light';
				}

				const themeDisplay = document.getElementById('mode');
				const themeTogglerSmartphone = document.getElementById('theme-toggle-smartphone');
				const themeTogglerDesktop = document.getElementById('theme-toggle-desktop');

				let userPreference = getUserPreference();
				setAppliedMode(getAppliedMode(userPreference));
				themeDisplay.innerText = userPreference;

				themeTogglerSmartphone.onclick = () => {
					const newUserPref = rotatePreferences(userPreference);
					userPreference = newUserPref;
					saveUserPreference(newUserPref);
					themeDisplay.innerText = newUserPref;
					setAppliedMode(getAppliedMode(newUserPref));
				}

				themeTogglerDesktop.onclick = () => {
					const newUserPref = rotatePreferences(userPreference);
					userPreference = newUserPref;
					saveUserPreference(newUserPref);
					themeDisplay.innerText = newUserPref;
					setAppliedMode(getAppliedMode(newUserPref));
				}

			</script>














			<!-- Accordion Functionality -->
			<script async>
				const items = document.querySelectorAll(".accordion button");

				function toggleAccordion() {
					const itemToggle = this.getAttribute('aria-expanded');

					/* Only one accordion might be open at the same time
					for (i = 0; i < items.length; i++) {
						items[i].setAttribute('aria-expanded', 'false');
					}
					*/

					if (itemToggle == 'false') {
						this.setAttribute('aria-expanded', 'true');
					}

					if (itemToggle == 'true') {
						this.setAttribute('aria-expanded', 'false');
					}
				}

				items.forEach(item => item.addEventListener('click', toggleAccordion));
			</script>

		</body>
</html>