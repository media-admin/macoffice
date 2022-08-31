			<footer class="site-footer">
				<div class="site-footer__container--gray">

					<!--
					<nav class="site-footer__navigation">
						<ul class="site-footer__navigation-list">
							<li class="site-footer__navigation-list-item"><a class="" href="#">Datenschutz</a></li>
							<li class="site-footer__navigation-list-item"><a class="" href="#">Impressum</a></li>
							<li class="site-footer__navigation-list-item"><a class="site-footer__link-teamviewer btn--gray" href="#">TeamViewer</a></li>
							<li class="site-footer__navigation-list-item"><a class="" href="#">AGBs (gewerblich)</a></li>
							<li class="site-footer__navigation-list-item"><a class="" href="#">AGBs (privat)</a></li>
						</ul>
					</nav>
					-->

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

				</div>

				<div class="site-footer__container--black">
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
								<li><a href="#" target="_blank"><img class="site-footer__social-media-icon site-footer__social-media-icon--facebook" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_facebook-footer_smartphone.svg" alt="Facebook Logo"></a></li>
								<li><a href="#" target="_blank"><img class="site-footer__social-media-icon site-footer__social-media-icon--instragram" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_instagram-footer_smartphone.svg" alt="Instagram Logo"></a></li>
							</ul>
						</div>
					</div>

					<div class="site-footer__main-navigation-container">
						<nav class="site-footer__main-navigation">
							<ul class="site-footer__main-navigation-list">

							<?php
								wp_nav_menu(array(
									'walker'	=> new FooterNavwalker(),
									'menu' => 'Footernavigation',
									'theme_location' => 'footer-navigation',
									'depth'          => 2,
									'container'      => FALSE,
									'container_class'   => '',
									'menu_class'     => '',
									'items_wrap'     => '%3$s',
									'fallback_cb' => false
								));
							?>

							</ul>
						</nav>
					</div>

					<!--
					<nav class="site-footer__main-navigation">
						<ul class="site-footer__main-navigation-list">
							<li class="site-footer__main-navigation-list-item"><a href="#">News</a></li>
							<li class="site-footer__main-navigation-list-item"><a href="#">Produkte</a>
								<ul class="site-footer__main-navigation-list-item--submenu">
									<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="https://www.apple.com/at/mac/" target="_blank">Mac</a></li>
									<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="https://www.apple.com/at/iphone/" target="_blank">iPhone</a></li>
									<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="https://www.apple.com/at/ipad/" target="_blank">iPad</a></li>
									<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="https://www.apple.com/at/watch/" target="_blank">Apple Watch</a></li>
									<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="https://www.apple.com/at/airpods/" target="_blank">AirPods</a></li>
									<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="https://www.apple.com/at/airtag/" target="_blank">AirTag</a></li>
									<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="https://www.apple.com/at/apple-tv-4k/" target="_blank">Apple TV</a></li>
									<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="https://www.apple.com/at/homepod-mini/" target="_blank">HomePod mini</a></li>
									<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="https://www.apple.com/at/shop/accessories/all" target="_blank">Zubehör</a></li>
									<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="https://www.apple.com/at/shop/gift-cards" target="_blank">Apple Gift Card</a></li>
								</ul>
							</li>
							<li class="site-footer__main-navigation-list-item"><a href="#">Leistungen</a>
								<ul class="site-footer__main-navigation-list-item--submenu">
									<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="#">Beratung & Consulting</a></li>
									<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="#">Service & Support</a></li>
									<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="#">Reparatur & Garantie</a></li>
									<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="#">Finanzierung</a></li>
								</ul>
							</li>
							<li class="site-footer__main-navigation-list-item"><a href="#">Fragen & Antworten</a></li>
							<li class="site-footer__main-navigation-list-item"><a href="#">Über uns</a>
							<ul class="site-footer__main-navigation-list-item--submenu">
								<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="#">Kontakt & Anfahrt</a></li>
								<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="#">Unser Team</a></li>
								<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="#">Das Unternehmen</a></li>
								<li class="site-footer__main-navigation-list-item site-footer__main-navigation-list-item--submenu-item"><a href="#">Jobs</a></li>
							</ul>
						</ul>
					</nav>
					-->


					<p class="site-footer__copyright">©&nbsp;2022 mac)office - macs work easier. <span>Ihr autorisierteer Apple-Händler in Wiener Neustadt.</span></p>

				</div>

			</footer>

			<?php wp_footer();?>

			<!-- === START SCRIPTS AREA === -->

			<!-- Hamburger Menu Toggle -->
			<script>
				var navigation = document.querySelector(".main-navigation");
				var hamburger = document.querySelector(".burger-menu");

					navigation.onclick = function () {
					this.classList.toggle ("is-active");
				}

				hamburger.onclick = function () {
					this.classList.toggle ("checked");
				}
			</script>
			<script>
					function switchMode() {
						var element = document.body;
						element.classList.toggle("dark-mode");
					}
			</script>


			<!-- Light Mode/Dark Mode Switcher -->


			<!-- Accordion Functionality -->
			<script>
				const items = document.querySelectorAll(".accordion button");

				function toggleAccordion() {
					const itemToggle = this.getAttribute('aria-expanded');

					for (i = 0; i < items.length; i++) {
						items[i].setAttribute('aria-expanded', 'false');
					}

					if (itemToggle == 'false') {
						this.setAttribute('aria-expanded', 'true');
					}
				}

				items.forEach(item => item.addEventListener('click', toggleAccordion));
			</script>

		</body>
</html>