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
											<img class="site-footer__logo-img" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/macoffice_footer-logo-light_smartphone.svg" alt="Logo mac)office - Ihr autorisierter Apple-Händler in Wiener Neustadt">
										</picture>
									</a>
								</div>
								<div class="site-footer__apple-haendler">
									<picture>
										<source srcset="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/apple-haendler_footer-logo-light_smartphone.svg" media="(prefers-color-scheme: dark)">
										<img class="site-footer__apple-haendler-img" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/apple-haendler_footer-logo-light_smartphone.svg" alt="Logo Autorisierter Händler">
									</picture>
								</div>
							</div>

							<div class="site-footer__contact">
								<div class="site-footer__contact-address">
									<img  class="site-footer__contact-address-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_address-footer_desktop.svg"/>
									<p class="site-footer__contact-address-title">Adresse</p>
									<p class="site-footer__contact-address-data">Fischauer Gasse 150<br/>2700 Wiener Neustadt<br/>Österreich</p>
								</div>

								<div class="site-footer__contact-information">
									<div class="site-footer__contact-information-phone">
										<img class="site-footer__contact-information-phone-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_phone-footer_desktop.svg"/>
										<a class="site-footer__contact-information-phone-number" href="tel:+43 2622 85 270">+43 2622 85 270</a>
									</div>
									<div class="site-footer__contact-information-mail">
										<picture>
										<img class="site-footer__contact-information-mail-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_mail-footer_desktop.svg"/>
										<a class="site-footer__contact-information-mail-address" href="mailto:info@macoffice.at">info@macoffice.at</a>
									</div>
									<div class="site-footer__contact-information-hotline">
										<img class="site-footer__contact-information-hotline-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_call-footer_desktop.svg"/>
										<a class="site-footer__contact-information-hotline-number" href="tel:0900 888 345">0900 888 345 <span class="additional-info">[ EUR 1,81/min ]</span></a>
									</div>
								</div>
								<div class="site-footer__contact-opening">
									<img class="site-footer__contact-opening-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_opening-hours-footer_desktop.svg"/>
									<p class="site-footer__contact-opening-title">Öffnungszeiten</p>
									<p class="site-footer__contact-opening-hours">Montag - Freitag von 10 - 18 Uhr<br/>Samstag von 10 - 13 Uhr</p>
								</div>
								<ul class="site-footer__social-media-area">
									<li><a href="https://www.facebook.com/macsworkeasier" target="_blank"><img class="site-footer__social-media-icon site-footer__social-media-icon--facebook" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_facebook-footer_smartphone.svg" alt="Facebook Logo"></a></li>
									<!-- <li><a href="#" target="_blank"><img class="site-footer__social-media-icon site-footer__social-media-icon--instragram" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_instagram-footer_smartphone.svg" alt="Instagram Logo"></a></li> -->
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

					<p class="site-footer__copyright">©&nbsp;2023 mac)office - macs work easier. <span>Ihr autorisierter Apple-Händler in Wiener Neustadt.</span></p>

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



			<!-- Search Modal -->
			<script async>
				// Get the modal
				var modal = document.getElementById("search_modal_desktop");

				// Get the button that opens the modal
				var btn = document.getElementById("search_button_desktop");

				// Get the <span> element that closes the modal
				var span = document.getElementsByClassName("modal-close-btn")[0];

				// When the user clicks on the button, open the modal
				btn.onclick = function() {
					modal.style.display = "block";
				}

				// When the user clicks on <span> (x), close the modal
				span.onclick = function() {
					modal.style.display = "none";
				}

				// When the user clicks anywhere outside of the modal, close it
				window.onclick = function(event) {
					if (event.target == modal) {
						modal.style.display = "none";
					}
				}
			</script>





			<!-- Dark Mode Toggle -->
			<script async>
					function switchMode() {
						var element = document.body;
						element.classList.toggle("dark-mode");
					}
			</script>

			<!-- Refreshing Store Hours Status
			<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.3.0/jquery.min.js" type="text/javascript"></script>

			<script type="text/javascript">
				jQuery(document).ready(function() {
				jQuery('.refresh').load('https://macoffice.dev/cms/refresh.php');

				var auto_refresh = setInterval(
					function () {
						jQuery('.refresh').load('https://macoffice.dev/cms/refresh.php').fadeIn("slow");
					}, 5000); // refresh every 5000 milliseconds
						$.ajaxSetup({ cache: true });
					});
			 </script>
			 -->

			<!-- Light Mode/Dark Mode Switcher -->


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