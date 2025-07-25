document.addEventListener("DOMContentLoaded", function() {

dywc.init({

	cookie_version: 1, // Version der Cookiedefinition, damit bei Konfigurationsänderung erneutes Opt-In erforderlich wird
	cookie_name: 'dywc', // Name des Cookies, der zur Speicherung der Entscheidung verwendet wird
	cookie_expire: 31536e3, // Laufzeit des Cookies in Sekunden (31536e3 = 1Jahr)
	cookie_path: '/', // Pfad auf dem der Cookie gespeichert wird
	mode: 1, // 1 oder 2, bestimmt den Buttonstil
	bglayer: true, // Verdunklung des Hintergrunds aktiv (true) oder inaktiv (false)
	position: 'mt', // mt, mm, mb, lt, lm, lb, rt, rm, rb

	id_bglayer: 'dywc_bglayer',
	id_cookielayer: 'dywc',
	id_cookieinfo: 'dywc_info',

	url_legalnotice: '/datenschutz', // or null
	url_imprint: '/impressum', // or null

	text_title: 'Datenschutzeinstellungen',
	text_dialog: 'Wir nutzen Cookies auf unserer Website. Einige von ihnen sind technisch notwendig, während andere uns helfen, Ihre Erfahrung auf dieser Website zu verbessern und optimieren.',

	cookie_groups: [
		{
			label: 'Notwendig',
			fixed: true,
			info: 'Zum Betrieb der Seite notwendige Cookies',
				cookies: [
					{
						label: 'PHP Session Cookie',
						publisher: 'Eigentümer dieser Website',
						aim: 'Absicherung Kontaktformular / SPAM Schutz',
						name: 'PHPSESSID',
						duraction: 'Session'
					}, {
						label: 'Cookiespeicherung Entscheidungscookie',
						publisher: 'Eigentümer dieser Website',
						aim: 'Speichert die Einstellungen der Besucher bezüglich der Speicherung von Cookies.',
						name: 'mac)office Consent Cookie',
						duration: '1 Jahr'
					}
				]
		}, {
			label: 'Statistiken',
			fixed: false,
			info: 'Cookies für die Analyse des Benutzerverhaltens',
			cookies: [
				{
					label: 'Google Analytics',
					publisher: 'Google LLC',
					aim: 'Cookie von Google für Website-Analysen. Erzeugt statistische Daten darüber, wie der Besucher die Website nutzt.',
					name: '_ga, _gid, _gat, _gtag',
					duration: '2 Jahre'
				}
			],
				accept: function() {
					dywc.log("Load Statistic Tracking");

					var el = document.createElement('script');
					el.src = "https://www.googletagmanager.com/gtag/js?id=G-553SQ74KCP";
					el.async = 1;
					document.getElementsByTagName('head')[0].appendChild(el);

					window.dataLayer = window.dataLayer || [];

					function gtag(){dataLayer.push(arguments); }
					gtag('js', new Date());
					gtag('config', 'G-553SQ74KCP', { 'anonymize_ip': true });

				},
				reject: function() {
					// Hier kommt der Opt-Out Code rein
					// Folgendes Beispiel für Google Analytics
					var disableStr = 'ga-disable-G-553SQ74KCP';

					window[disableStr] = true; document.cookie = disableStr + '=true; expires=Thu, 31 Dec 2099 23:59:59 UTC; path=/';

					dywc.cookie.removeItem('_ga', '/', '.macoffice.at');
					dywc.cookie.removeItem('_gid', '/', '.macoffice.at');
					dywc.cookie.removeItem('_gat', '/', '.macoffice.at');
					dywc.cookie.removeItem('_gat_gtag_G-553SQ74KCP', '/', '.macoffice.at');
				}
			}, {
				label: 'Erleichterte Bedienung',
				fixed: false,
				info: 'Cookies zur Erleichterung der Bedienung für den Benutzer',
				cookies: [
					{
						label: 'Google Maps',
						publisher: 'Google LLC',
						aim: 'Cookie von Google für die Nutzung von Google Maps.',
						name: 'NID',
						duration: '6 Monate'
					}
				],
					accept: function() {
						dywc.log("Load Statistic Tracking");

						(function (d) {
							var container = d.querySelector("#gmap-opt-in"),
								wrap = d.querySelector("#gmap-opt-in .gmap-opt-in-wrap"),
								btn = d.querySelector("#gmap-opt-in .gmap-opt-in-button"),
								iframe = d.createElement("iframe"),
								gmapSrc = "https://www.google.com/maps/embed?pb=!1m18!1m12!1m3!1d19656.77575432525!2d16.209917483926358!3d47.810833882697544!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x476dcbe0da27d3e5%3A0x1c6428c578efbef6!2smacoffice!5e0!3m2!1sde!2sat!4v1608589777546!5m2!1sde!2sat";

								btn.addEventListener("click", function () {
								// set iframe attributes
								iframe.setAttribute("style", "border:0;");
								iframe.setAttribute("src", gmapSrc);
								iframe.setAttribute("frameborder", "0");

								// remove all in container
								 container.removeChild(wrap);

								// add iframe to container
								container.appendChild(iframe);
							}, false);
						 });

					/* Document Ready Script */
					document.ready = function( callback ) {
						if( document.readyState != 'loading' ) {
							callback();
						}
						else {
							document.addEventListener( 'DOMContentLoaded', callback );
						}
					};

					/* Automatically resize the iFrame */
					var iFrame2C = {};

					iFrame2C.rescale = function( iframe, format ) {
						let formatWidth = parseInt( format.split(':')[0] );
						let formatHeight = parseInt( format.split(':')[1] );
						let formatRatio = formatHeight / formatWidth;
						var iframeBounds = iframe.getBoundingClientRect();

						let currentWidth = iframeBounds.width;
						let newHeight = formatRatio * currentWidth;

						iframe.style.height = Math.round( newHeight ) + "px";
					};

					/* Resize iFrame */
					function iframeResize() {
						var iframes = document.querySelectorAll( 'iframe[data-scaling="true"]' );

						if( !!iframes.length ) {
							for( var i=0; i < iframes.length; i++ ) {
								let iframe = iframes[ i ];
								let videoFormat = '16:9';
								let is_data_format_existing = typeof iframe.getAttribute( 'data-format' ) !== "undefined";
								if( is_data_format_existing ) {
									let is_data_format_valid = iframe.getAttribute( 'data-format' ).includes( ':' );
									if( is_data_format_valid ) {
										videoFormat = iframe.getAttribute( 'data-format' );
									}
								}
								iFrame2C.rescale( iframe, videoFormat );
							}
						}
					}

					/* Event Listener on Resize for iFrame-Resizing */
					document.ready( function() {
						window.addEventListener( "resize", function() {
							iframeResize();
						});
						iframeResize();
					});

					/* Source-URLs */
					/*
					 data_type will be the value of the attribute "data-type" on element "video_trigger"
					 data_souce will be the value of the attribute "data-source" on element "video_trigger", which will be replaced on "{SOURCE}"
					*/
					function get_source_url( data_type ) {

						switch( data_type ) {

							case "google-maps":
								return "https://www.google.com/maps/embed?pb={SOURCE}";
							default: break;
						}
					}

					/* 2-Click Solution */
					document.ready( function() {

						var video_wrapper = document.querySelectorAll( '.map_wrapper' );

						if( !!video_wrapper.length ) {
							for( var i=0; i < video_wrapper.length; i++ ) {
								let _wrapper = video_wrapper[ i ];

								var video_triggers = _wrapper.querySelectorAll( '.map_trigger' );
								if( !!video_triggers.length ) {

									for( var l=0; l < video_triggers.length; l++ ) {

										var video_trigger = video_triggers[ l ];
										var accept_buttons = video_trigger.querySelectorAll( 'input[type="button"]' );

										if( !!accept_buttons.length ) {
											for( var j=0; j < accept_buttons.length; j++ ) {

												var accept_button = accept_buttons[ j ];
												accept_button.addEventListener( "click", function() {

													var _trigger = this.parentElement;
													var data_type = _trigger.getAttribute( "data-type" );
													var source = "";
													_trigger.style.display = "none";

													source = get_source_url( data_type );

													var data_source = _trigger.getAttribute( 'data-source' );
													source = source.replace( "{SOURCE}", data_source );

													var video_layers = _trigger.parentElement.querySelectorAll( ".map_layer" );
													if( !!video_layers.length ) {
														for( var k=0; k < video_layers.length; k++ ) {

															var video_layer = video_layers[ k ];
															video_layer.style.display = "block";
															video_layer.querySelector( "iframe" ).setAttribute( "src", source );

														}
													}

													_wrapper.style.backgroundImage = "";
													_wrapper.style.height = "auto";

													var timeout = 100; // ms
													setTimeout( function() {
														iframeResize();
													}, timeout );
												});
											}
										}
									}
								}
							};
						}
					});

				},
				reject: function() {
					dywc.log("Reject Statistic Tracking");
				}
			}

		]

	});
});