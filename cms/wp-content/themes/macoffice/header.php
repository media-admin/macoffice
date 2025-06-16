<!DOCTYPE html>
<html class="outer-html" lang="de" data-theme="light">
	<head>

		<!-- Meta Data -->
		<meta charset="<?php bloginfo( 'charset' ); ?>">
		<meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
		<meta http-equiv="content-type" content="text/html; charset=macintosh" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scaleable=no">
		<meta name="color-scheme" content="light dark" />
		<meta name="keywords" content="macoffice mac)office Apple Autorisierter H&auml;ndler Reseller Service Provider Wr. Wiener Neustadt 2700 Österreich Niederösterreich Hardware Software Dienstleistung Beratung Kauf iPad iPhone 14 MacBook Pro Air iMac M1 M2 Mac mini Watch Ultra Series 8 TV AirTag Pencil macOS iOS iPadOS watchOS tvOS Macintosh Magic Mouse Keyboard Adobe Creative Cloud RAM Speicher Displaytausch Batterietausch Akku Reparatur Reparaturbonus">

		<!-- === FAVICONS === -->
		<!-- Default -->
		<link rel="icon" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/favicon.svg" type="image/x-icon">
		<link rel="shortcut icon" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/favicon.ico" type="image/x-icon">

		<!-- PNG icons with different sizes -->
		<link rel="icon" type="image/png" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/favicon-32x32.png" sizes="32x32">
		<link rel="icon" type="image/png" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/favicon-194x194.png" sizes="194x194">
		<link rel="icon" type="image/png" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/favicon-96x96.png" sizes="96x96">
		<link rel="icon" type="image/png" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/favicon-192x192.png" sizes="192x192">
		<link rel="icon" type="image/png" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/favicon-16x16.png" sizes="16x16">

		<!-- Apple Touch Icons -->
		<link rel="apple-touch-icon" sizes="57x57" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="60x60" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/apple-touch-icon-60x60.png">
		<link rel="apple-touch-icon" sizes="72x72" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/apple-touch-icon-57x57.png">
		<link rel="apple-touch-icon" sizes="76x76" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/apple-touch-icon-76x76.png">
		<link rel="apple-touch-icon" sizes="114x114" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/apple-touch-icon-114x114.png">
		<link rel="apple-touch-icon" sizes="120x120" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/apple-touch-icon-120x120.png">
		<link rel="apple-touch-icon" sizes="144x144" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/apple-touch-icon-144x144.png">
		<link rel="apple-touch-icon" sizes="152x152" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/apple-touch-icon-152x152.png">
		<link rel="apple-touch-icon" sizes="180x180" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/apple-touch-icon-180x180.png">
		<link rel="apple-touch-icon" sizes="192x192" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/apple-touch-icon-192x192.png">

		<!-- Apple macOS Safari Mask Icon -->
		<link rel="mask-icon" href="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/favicon.svg" color="#525050">

		<!-- Apple iOS Safari Theme -->
		<meta name="apple-mobile-web-app-status-bar-style" content="#525050">
		<meta name="apple-mobile-web-app-title" content="mac)office - Ihr autorisierter Apple-Händler in Wiener Neustadt">
		<meta name="apple-mobile-web-app-capable" content="yes">

		<!-- Microsoft Windows Tiles -->
		<meta name="theme-color" content="#707173">
		<meta name="msapplication-navbutton-color" content="#525050">
		<meta name="msapplication-TileColor" content="#838282">
		<meta name="msapplication-TileImage" content="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/windows-tile-icon-144x144.png">
		<meta name="application-name" content="mac)office - Ihr autorisierter Apple-Händler in Wiener Neustadt">

		<!-- Internet Explorer 11 Tiles -->
		<meta name="msapplication-square70x70logo" content="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/ms-ie11-icon-70x70.png">
		<meta name="msapplication-square150x150logo" content="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/ms-ie11-icon-150x150.png">
		<meta name="msapplication-wide310x150logo" content="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/ms-ie11-icon-310x150.png">
		<meta name="msapplication-square310x310logo" content="<?php bloginfo( 'template_directory' ); ?>/assets/images/favicon/ms-ie11-icon-310x310.png">

		<!-- Open Graph -->
		<meta property="og:title" content="mac)office - Ihr autorisierter Apple-Händler in Wiener Neustadt">
		<meta property="og:type" content="website">
		<meta property="og:url" content="https://www.macoffice.at">
		<meta property="og:image" content="og_image_url">
		<meta property="og:site_name" content="mac)office - Ihr autorisierter Apple-Händler in Wiener Neustadt">
		<meta property="og:locale" content="de_AT">

		<!-- Site Title -->
		<?php if (is_front_page() ) : ?>
			<title>Startseite | <?php bloginfo( 'name' ); ?></title>
		<?php else : ?>
			<title><?php wp_title($sep = ''); ?></title>
		<?php endif; ?>

		<?php // require 'classes/servicePrices/config.php';  ?>
		<?php // require 'classes/servicePrices/meta.php'; ?>

		<?php wp_enqueue_script('jquery'); ?>
		<?php wp_head(); ?>

	</head>

	<body <?php body_class( 'site-body' ); ?>>

		<header class="site-header-top">
			<div class="site-header-top__contact-information">
				<div class="site-header-top__contact-information-opening">
					<a class="site-header-top__contact-information-opening-link" href="/ueber-uns">
						<img class="site-header-top__contact-information-opening-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_opening-hours-header-light_desktop.svg"/>
						<p class="site-header-top__contact-information-opening-hours">Mo - Fr 10 - 18 Uhr <span class="additional-info"> | </span> Sa 10 - 13 Uhr
							<div class="site-header-top__contact-information-opening-sign">
								<div class="refresh-opening-state">
									<?php // include ('classes/storeHours/open-closed-sign.php'); ?> <!-- Dot for open/closed -->
								</div>
							</div>
						</p>
						</a>
				</div>
				<div class="site-header-top__contact-information-address">
					<a class="site-header-top__contact-information-address-link" href="/ueber-uns">
						<img class="site-header-top__contact-information-address-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_address-header-light_desktop.svg"/>
						<!-- <p class="site-header-top__contact-information-address-data">Fischauer Gasse 150, 2700 Wiener Neustadt</p> -->
						<p class="site-header-top__contact-information-address-data">Fischauer Gasse 150, 2700 Wiener Neustadt</p>
					</a>
				</div>
				<div class="site-header-top__contact-information-phone">
					<a class="site-header-top__contact-information-phone-link" href="tel:+43262285270">
						<img class="site-header-top__contact-information-phone-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_phone-header-light_desktop.svg"/>
						<p class="site-header-top__contact-information-phone-number">+43 2622 85270</p>
					</a>
				</div>
				<div class="site-header-top__contact-information-mail site-header-top__contact-information-mail-link">
					<div class="site-header-top__contact-information-mail-address">
						<div id="email-info__button-desktop" class="email-info__button">
							<img class="site-header-top__contact-information-mail-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_mail-header-light_desktop.svg"/>
							info[at]macoffice.at
						</div>
						<?php echo do_shortcode('[shortcode_email_info]'); ?>
					</div>
				</div>
				<div class="site-header-top__contact-information-hotline site-header-top__contact-information-hotline-link">
						<div class="site-header-top__contact-information-hotline-number">
							<div id="email-emergency__button-desktop" class="email-emergency__button">
								<img class="site-header-top__contact-information-hotline-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_call-header-light_desktop.svg"/>
								Notfall
							</div>
						<?php echo do_shortcode('[shortcode_email_emergency]');  ?>
					</div>
				</div>
				<div class="site-header-top__contact-information-remote-support">
					<a class="site-header-top__contact-information-remote-support-link" href="/fernwartung" target="_self">
						<img class="site-header-top__contact-information-remote-support-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_remote-support-header-light_desktop.svg"/>
						<p class="site-header-top__contact-information-remote-support-url">Fernwartung</p>
					</a>
				</div>
			</div>
		</header>

		<header class="site-header">
			<div class="site-header__branding">
				<div class="site-header__logo">
					<a class="site-header-logo__link wrapper" href="<?php echo get_home_url(); ?>">
						<img id="site-header__logo-img-light" class="site-header__logo-img" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/macoffice_header-logo-light_desktop.svg" alt="Logo mac)office - Ihr autorisierter Apple-Händler in Wiener Neustadt">
						<img id="site-header__logo-img-dark" class="site-header__logo-img" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/macoffice_header-logo-dark_desktop.svg" alt="Logo mac)office - Ihr autorisierter Apple-Händler in Wiener Neustadt">
					</a>
				</div>

				<div class="site-header__apple-certificates">

					<div class="site-header__apple-autorisierter-haendler">
						<a class="site-header__apple-autorisierter-haendler-link" href="/produkte">
							<img id="site-header__apple-autorisierter-haendler-img" class="site-header__apple-autorisierter-haendler-img" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/apple-haendler_header-logo-light_desktop.svg" alt="Logo Autorisierter Händler">
						</a>
					</div>

					<div class="site-header__apple-certificates-combo">
						<img id="site-header__apple-certificates-combo-img" class="site-header__apple-certificates-combo-img" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/apple-certificates-1-line--black.svg" alt="Logo Autorisierter Händler">
					</div>

					<!-- <div class="site-header__apple-autorisierter-haendler">
						<a class="site-header__apple-autorisierter-haendler-link" href="/produkte">
							<img id="site-header__apple-autorisierter-haendler-img" class="site-header__apple-autorisierter-haendler-img" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/apple-haendler_header-logo-light_desktop.svg" alt="Logo Autorisierter Händler">
						</a>
					</div>

					<div class="site-header__apple-autorisierter-service-provider">
						<a class="site-header__apple-autorisierter-service-provider-link" href="/leistungen">
							<img id="site-header__apple-autorisierter-service-provider-img" class="site-header__apple-autorisierter-service-provider-img" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/logos/apple-service-provider_header-logo--dark.svg" alt="Logo Autorisierter Service Provider">
						</a>
					</div> -->

				</div>
			</div>

			<!-- Hamburger Menu Toggle -->
			<nav class="main-navigation">
				<menu class="site-menu">
					<div class="burger-menu">
						<span class="line"></span>
						<span class="line"></span>
						<span class="line"></span>
					</div>
				</menu>
				<!-- Main Navigation -->
				<div class="navbar">
					<ul class="navbar__navigation-list">
						<?php
							$defaults = array(
								'walker'         => new Navwalker(),
								'menu'           => 'Hauptnavigation',
								'theme_location' => 'nav-menu-main',
								'depth'          => 2,
								'container'      => FALSE,
								'container_class'   => '',
								'menu_class'     => '',
								'items_wrap'     => '%3$s',
								'fallback_cb'		=>	'NavWalker::fallback'
							);
							wp_nav_menu( $defaults );
						?>
					</ul>
				</div>
			</nav>

			<!-- Smartphone only START -->




			<div class="site-header__navi-contact-information">
				<div class="site-header__navi-contact-information-phone">
					<img class="site-header__navi-contact-information-phone-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_phone-header-dark_desktop.svg"/>
					<a class="site-header__navi-contact-information-phone-number" href="tel:+43 2622 85 270">+43 2622 85 270</a>
				</div>
<!-- 				<div class="site-header__navi-contact-information-mail site-header__navi-contact-information-mail-address">
					<img class="site-header__navi-contact-information-mail-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_mail-header-dark_desktop.svg"/>
					<a class="site-header__navi-contact-information-mail-address" href="mailto:info@macoffice.at">info@macoffice.at</a>
				</div> -->


				<div class="site-header__navi-contact-information-mail site-header__navi-contact-information-mail-address">
					<img class="site-header__navi-contact-information-mail-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_mail-header-dark_desktop.svg"/>
					<p class="site-header__navi-contact-information-mail-address"><?php echo do_shortcode('[shortcode_email_info]'); ?></p>
				</div>

				<!-- <div class="site-header__navi-contact-information-call">
					<img class="site-header__navi-contact-information-call-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_call-header-dark_desktop.svg"/>
					<a class="site-header__navi-contact-information-call-number" href="mailto:notfall@macoffice.at">Notfallservice</a>
				</div> -->

				<div class="site-header__navi-contact-information-remote-support">
					<img class="site-header__navi-contact-information-remote-support-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_remote-support-header-dark_desktop.svg"/>
					<a class="site-header__navi-contact-information-remote-support-link" href="/fernwartung">Fernwartung</a>
				</div>

				<div class="site-header__additional-area--smartphone">
					<div class="site-header__search-area--smartphone">
						<a id="search_button_smartphone" class="site-header__search-link">
							<img class="site-header__search-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_search.svg" alt="Suchen Icon"/>
						</a>
						<div id="search_modal_smartphone" class="modal">
							<div class="modal-background"></div>
							<div class="modal-content">
								<span class="modal-close-btn--smartphone">&times;</span>
								<?php if ( function_exists( 'aws_get_search_form' ) ) { aws_get_search_form( true, array( 'id' => 1 ) ); } ?>
							</div>
						</div>
					</div>

					<div class="site-header__mode-switcher site-header__mode-switcher--smartphone">
						<span id="mode">system</span>
						<button id="theme-toggle-smartphone" class="site-header__mode-switcher-link btn wrapper">
							<img class="site-header__mode-switcher-link-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_light-mode_desktop.svg" alt="Icon Suchen">
						</button>
					</div>

				</div>
			</div>
			<!-- Smartphone only END -->


			<!-- Desktop only START -->
			<div class="site-header__navi-additional-area">

				<div class="site-header__search-area">
					<a id="search_button_desktop" class="site-header__search-link">
						<img class="site-header__search-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_search.svg" alt="Suchen Icon"/>
					</a>

					<div id="search_modal_desktop" class="modal">
						<div class="modal-background"></div>
						<div class="modal-content">
							<span id="modal-close-btn--desktop" class="modal-close-btn--desktop">&times;</span>
							<?php if ( function_exists( 'aws_get_search_form' ) ) { aws_get_search_form( true, array( 'id' => 1 ) ); } ?>
						</div>
					</div>
				</div>

				<div class="site-header__mode-switcher">
					<span id="mode">system</span>
					<button id="theme-toggle-desktop" class="site-header__mode-switcher-link btn wrapper" data-theme-toggle>
						<img class="site-header__mode-switcher-link-icon" src="<?php bloginfo( 'template_directory' ); ?>/assets/images/icons/icon_light-mode_desktop.svg" alt="Icon Suchen">
					</button>
				</div>

			</div>
			<!-- Desktop only END -->

		</header>