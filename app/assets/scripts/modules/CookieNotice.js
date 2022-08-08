	//
	// CookieNotice.js
	//

	var dywc = {

			config: null,
			cookie_value: null,

			cookie: {

				reURIAllowed: /[\-\.\+\*]/g,
				reCNameAllowed: /^(?:expires|max\-age|path|domain|secure|samesite|httponly)$/i,

				makeSetterString: function(sKey, sValue, vEnd, sPath, sDomain, bSecure, vSameSite) {

					var sExpires = "";

					if (vEnd) {

						switch (vEnd.constructor) {

							case Number:

								sExpires = vEnd === Infinity ? "; expires=Fri, 31 Dec 9999 23:59:59 GMT" : "; max-age=" + vEnd;
								break;

							case String:

								sExpires = "; expires=" + vEnd;
								break;

							case Date:

								sExpires = "; expires=" + vEnd.toUTCString();
								break;

						}

					}

					return	encodeURIComponent(sKey) + "=" + encodeURIComponent(sValue) + sExpires + (sDomain ? "; domain=" + sDomain : "") + (sPath ? "; path=" + sPath : "") + (bSecure ? "; secure" : "") + (!vSameSite || vSameSite.toString().toLowerCase() === "no_restriction" || vSameSite < 0 ? "" : vSameSite.toString().toLowerCase() === "lax" || Math.ceil(vSameSite) === 1 || vSameSite === true ? "; samesite=lax" : "; samesite=strict");

				},

				getItem: function (sKey) {

					if (!sKey) { return null; }

					return decodeURIComponent(document.cookie.replace(new RegExp("(?:(?:^|.*;)\\s*" + encodeURIComponent(sKey).replace(this.reURIAllowed, "\\$&") + "\\s*\\=\\s*([^;]*).*$)|^.*$"), "$1")) || null;

				},

				setItem: function (sKey, sValue, vEnd, sPath, sDomain, bSecure, vSameSite) {

					if (!sKey || this.reCNameAllowed.test(sKey)) { return false; }

					document.cookie = this.makeSetterString(sKey, sValue, vEnd, sPath, sDomain, bSecure, vSameSite);
					return true;

				},

				hasItem: function (sKey) {

					if (!sKey || this.reCNameAllowed.test(sKey)) { return false; }

					return (new RegExp("(?:^|;\\s*)" + encodeURIComponent(sKey).replace(this.reURIAllowed, "\\$&") + "\\s*\\=")).test(document.cookie);

				},

				removeItem: function (sKey, sPath, sDomain, bSecure, vSameSite) {

					if (!this.hasItem(sKey)) { return false; }

					document.cookie = this.makeSetterString(sKey, "", "Thu, 01 Jan 1970 00:00:00 GMT", sPath, sDomain, bSecure, vSameSite);
					return true;

				}

			},

			init: function(config) {

				if (typeof config === 'undefined') config = this.config;

				if (typeof config !== 'object') {

					console.error('DoYouWantCookie muss mit einem Konfigurations Array initiiert werden.');
					return false;

				}

				this.config = config;

				this.log("Init");

				this.cookie_value = this.cookie.getItem(this.config.cookie_name);

				if (this.cookie_value === null) {

					// Noch keine Entscheidung getroffen

					let strCoookieHint = '';

					strCoookieHint += '<div id="' + this.config.id_cookielayer + '" class="' + this.config.position + '">';

					/* Info Layer */
					strCoookieHint += '<div class="info">';
					strCoookieHint += '<div class="inner">';

					for (let i in this.config.cookie_groups) {

						let cookie_group = this.config.cookie_groups[i];

						strCoookieHint += '<div class="group group_' + i + '">';
						strCoookieHint += '<p>' + cookie_group.info + '</p>';

						for (var j in cookie_group.cookies) {

							var cookie = cookie_group.cookies[j];

							strCoookieHint += '<div class="cookie">';

							strCoookieHint += '<div class="row"><div class="label">Name</div>';
							strCoookieHint += '<div class="value">' + cookie.label + '</div></div>';

							strCoookieHint += '<div class="row"><div class="label">Anbieter</div>';
							strCoookieHint += '<div class="value">' + cookie.publisher + '</div></div>';

							strCoookieHint += '<div class="row"><div class="label">Zweck</div>';
							strCoookieHint += '<div class="value">' + cookie.aim + '</div></div>';

							strCoookieHint += '<div class="row"><div class="label">Cookie Name</div>';
							strCoookieHint += '<div class="value">' + cookie.name + '</div></div>';

							strCoookieHint += '<div class="row"><div class="label">Cookie Laufzeit</div>';
							strCoookieHint += '<div class="value">' + cookie.duration + '</div></div>';

							strCoookieHint += '</div>';


						}

						strCoookieHint += '</div>';

					}

					strCoookieHint += '<p><a href="#" onclick="return dywc.hideinfo();">Infos schließen</a></p>';

					strCoookieHint += '</div>';
					strCoookieHint += '</div>';

					strCoookieHint += '<div class="content">';
					strCoookieHint += '<h2>' + this.config.text_title + '</h2>';
					strCoookieHint += '<p>' + this.config.text_dialog + '</p>';
					strCoookieHint += '<div>';

					strCoookieHint += '<div class="cookie_group_wrap">';

					for (var i in this.config.cookie_groups) {

						var cookie_group = this.config.cookie_groups[i];
						var group_checkbox_id = 'dywc_' + i;

						strCoookieHint += '<div class="cookie_group">';
						strCoookieHint += '<input type="checkbox" id="' + group_checkbox_id + '" ' + ((cookie_group.fixed)?'disabled="disabled" checked="checked"':'') + '>';
						strCoookieHint += '<label for="' + group_checkbox_id + '">' + cookie_group.label + '</label><a href="#" class="info" onclick="return dywc.info(' + i + ');">Info</a>';
						strCoookieHint += '</div>';

					}

					strCoookieHint += '</div>';

					if (this.config.mode === 1) {

						strCoookieHint += '<div class="accept_wrap">';
						strCoookieHint += '<a href="#" class="accept" onclick="return dywc.accept(true);">Alle akzeptieren</a>';
						strCoookieHint += '<a href="#" class="accept2" onclick="return dywc.accept();">speichern</a>';
						strCoookieHint += '</div>';

					} else {

						strCoookieHint += '<div class="accept_wrap">';
						strCoookieHint += '<a href="#" class="accept" onclick="return dywc.accept();">OK</a>';
						strCoookieHint += '</div>';

					}

					strCoookieHint += '</div>';
					strCoookieHint += '<p class="dsg">Die Auswahl kann in der <a href="' + this.config.url_legalnotice + '">Datenschutzerklärung</a> widerrufen werden.</p>';

					if (this.config.url_imprint !== null) {

						strCoookieHint += '<p class="imprint"><a href="' + this.config.url_imprint + '">Impressum</a></p>';

					}

					//strCoookieHint += '<div>';
					//strCoookieHint += 'Cookie Opt-In Script bereitgestellt von <br /><a href="https://daschmi.de" title="Homepage von Daniel Schmitzer - Webentwicklung - Programmierung - Burgenlandkreis - Sachsen Anhalt">https://daschmi.de</a>';
					//strCoookieHint += '</div>';

					strCoookieHint += '</div>';
					strCoookieHint += '</div>';

					let elem = document.querySelector('#' + this.config.id_cookielayer);
					if (elem !== null) elem.parentNode.removeChild(elem);

					if (this.config.bglayer === true) {

						strCoookieHint += '<div id="' + this.config.id_bglayer + '"></div>';

					}

					document.querySelector('body').innerHTML += strCoookieHint;

				} else {

					let arSet = this.cookie_value.split('-');

					for (let i in this.config.cookie_groups) {

						let cookie_group = this.config.cookie_groups[i];

						if (typeof arSet[parseInt(i) + 2] !== 'undefined') {

							if (arSet[parseInt(i) + 2] === '1') {

								if (typeof cookie_group.accept === 'function') cookie_group.accept();
								else if (typeof cookie_group.accept === 'string') eval(cookie_group.accept);

							}

						}

					}

				}

				this.update();

			},

			log: function(...msg) {

				if (this.config.debug) console.log(msg);

			},

			reset: function() {

				this.log('reset');

				this.cookie.removeItem(this.config.cookie_name, this.config.cookie_path);
				this.cookie_value = null;

				for (let i in this.config.cookie_groups) {

					let cookie_group = this.config.cookie_groups[i];

					if (typeof cookie_group.reject === 'function') cookie_group.reject();
					else if (typeof cookie_group.reject === 'string') eval(cookie_group.reject);

				}

				this.init();

				return false;

			},

			info: function(group_index) {

				this.log('Show Info ', group_index);

				let elem = document.querySelector('#' + this.config.id_cookielayer);
				let cookie_group = this.config.cookie_groups[group_index];

				if (!elem.classList.contains('show_info')) elem.classList.add('show_info');

				if (typeof cookie_group === "object") {

					[].forEach.call(document.querySelectorAll('#' + this.config.id_cookielayer + ' .info .group'), function(div) { div.style.display = 'none'; });

					document.querySelector('#' + this.config.id_cookielayer + ' .info .group_' + group_index).style.display = 'block';

				}

				return false;

			},

			hideinfo: function() {

				this.log('hideInfo');

				document.querySelector('#' + this.config.id_cookielayer).classList.remove('show_info');

				return false;

			},

			accept: function(check) {

				if (typeof check === "undefined") check = false;

				this.cookie_value = this.config.cookie_version + '-1';

				for (var i in this.config.cookie_groups) {

					let cookie_group = this.config.cookie_groups[i];
					let group_checkbox_id = 'dywc_' + i;

					if (document.getElementById(group_checkbox_id).checked || check) {

						this.cookie_value += '-1';

						if (typeof cookie_group.accept === 'function') cookie_group.accept();
						else if (typeof cookie_group.accept === 'string') eval(cookie_group.accept);

					} else {

						this.cookie_value += '-0';

					}

				}

				this.cookie_value += '-' + (new Date()).getTime();

				this.cookie.setItem(this.config.cookie_name, this.cookie_value, this.config.cookie_expire, this.config.cookie_path);

				this.update();

				let elemLayer = document.getElementById(this.config.id_cookielayer);
				let elemBg = document.getElementById(this.config.id_bglayer);

				if (elemLayer !== null) elemLayer.classList.add('hide');
				if (elemBg !== null) elemLayer.classList.add('hide');

				window.setTimeout(() => {

					if (elemLayer !== null) elemLayer.parentNode.removeChild(elemLayer);
					if (elemBg !== null) elemBg.parentNode.removeChild(elemBg);

				}, 250);

				return false;

			},

			update: function() {

				let elemInfo = document.getElementById(this.config.id_cookieinfo);
				let strCookieInfo = '';

				if (elemInfo !== null) {

					var arSet = [];

					if (this.cookie_value !== null) var arSet = this.cookie_value.split('-');

					for (var i in this.config.cookie_groups) {

						var cookie_group = this.config.cookie_groups[i];

						strCookieInfo += '<p>' + cookie_group.info + '</p>';

						for (var j in cookie_group.cookies) {

							var cookie = cookie_group.cookies[j];

							if (cookie_group.fixed) var strStatus = 'Verwendet, da notwendig und nicht von Drittanbieter';
							else {

								var strStatus = 'Unentschieden, nicht verwendet';

								if (typeof arSet[parseInt(i) + 2] !== "undefined") {

									if (arSet[parseInt(i) + 2] === '1') {

										strStatus = '<span class="accept">Akzeptiert</span>';

									} else {

										strStatus = '<span class="reject">Abgelehnt</span>';

									}

								}

							}

							strCookieInfo += '<div class="cookie">';

							strCookieInfo += '<div class="row"><div class="label">Name</div>';
							strCookieInfo += '<div class="value">' + cookie.label + '</div></div>';

							strCookieInfo += '<div class="row"><div class="label">Anbieter</div>';
							strCookieInfo += '<div class="value">' + cookie.publisher + '</div></div>';

							strCookieInfo += '<div class="row"><div class="label">Zweck</div>';
							strCookieInfo += '<div class="value">' + cookie.aim + '</div></div>';

							strCookieInfo += '<div class="row"><div class="label">Cookie Name</div>';
							strCookieInfo += '<div class="value">' + cookie.name + '</div></div>';

							strCookieInfo += '<div class="row"><div class="label">Cookie Laufzeit</div>';
							strCookieInfo += '<div class="value">' + cookie.duration + '</div></div>';

							strCookieInfo += '<div class="row"><div class="label"<strong>Status</strong></div>';
							strCookieInfo += '<div class="value">' + strStatus + '</div></div>';

							strCookieInfo += '</div>';

						}

					}

					strCookieInfo += '<p>';

					if (parseInt(arSet[0]) === this.config.cookie_version) {

						var date = new Date(parseInt(arSet[arSet.length - 1]));

						var d = date.getDate(); if (d < 10) d = '0' + d;
						var m = date.getMonth() + 1; if (m < 10) m = '0' + m;
						var y = date.getFullYear();

						var h = date.getHours(); if (h < 10) h = '0' + h;
						var min = date.getMinutes(); if (min < 10) min = '0' + min;

						var strDatum = d + '.' + m + '.' + y;
						var strTime = h + ':' + min;

						strCookieInfo += 'Sie haben sich am ' + strDatum + ' um ' + strTime + ' entschieden. Klicken Sie ';
						strCookieInfo += '<a href="#" onclick="return dywc.reset();">hier</a>, um die Entscheidung zu widerrufen.';

					} else {

						strCookieInfo += 'Sie haben sich noch nicht entschieden.';

					}

					strCookieInfo += '</p>';

					elemInfo.innerHTML = strCookieInfo;

				}

			}

		};


	document.addEventListener("DOMContentLoaded", function() {

	dywc.init({

	cookie_version: 1, // Version der Cookiedefinition, damit bei Konfigurationsänderung erneutes Opt-In erforderlich wird
	cookie_name: 'Media Lab Cookie', // Name des Cookies, der zur Speicherung der Entscheidung verwendet wird
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
	text_dialog: 'Wir nutzen Cookies auf unserer Website. Einige von ihnen sind essenziell, während andere uns helfen, diese Website und Ihre Erfahrung zu verbessern.',

	cookie_groups: [
		 {
			label: 'Notwendig',
			fixed: true,
			info: 'Zum Betrieb der Seite notwendige Cookies:',
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
						name: 'dywc',
						duration: '1 Jahr'
					}
				],

		}, {
			label: 'Statistiken',
			fixed: false,
			info: 'Cookies für die Analyse des Benutzerverhaltens:',
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
						el.src = 'https://www.googletagmanager.com/gtag/js?id=G-LWP0E8WTYM';
						el.async = 1;
						document.getElementsByTagName('head')[0].appendChild(el);

						window.dataLayer = window.dataLayer || [];

						function gtag(){dataLayer.push(arguments);}
						gtag('js', new Date());

						gtag('config', 'G-LWP0E8WTYM', { 'anonymize_ip': true });

					},

					reject: function() {

						dywc.log("Reject Statistic Tracking");

						var el = document.createElement('script');
						el.src = 'https://www.googletagmanager.com/gtag/js?id=G-LWP0E8WTYM';
						el.async = 1;
						document.getElementsByTagName('head')[0].appendChild(el);

						window['ga-disable-G-LWP0E8WTYM'] = true;
						window.dataLayer = window.dataLayer || [];

						function gtag(){dataLayer.push(arguments);}
						gtag('js', new Date());

						gtag('config', 'G-LWP0E8WTYM', { 'anonymize_ip': true });

					}
		}, {
			label: 'Erleichterte Bedienung',
			fixed: false,
			info: 'Cookies zur Erleichterung der Bedienung für den Benutzer:',
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

							var video_wrapper = document.querySelectorAll( '.route__map-wrapper' );

							if( !!video_wrapper.length ) {
								for( var i=0; i < video_wrapper.length; i++ ) {
									let _wrapper = video_wrapper[ i ];

									var video_triggers = _wrapper.querySelectorAll( '.route__map-trigger' );
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

														var video_layers = _trigger.parentElement.querySelectorAll( ".route__map-layer" );
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

				}, {

						label: 'Externe Medien',
						fixed: false,
						info: 'Cookies zum Einbinden fremder Inhalte:',
							cookies: [
								{
									label: 'Youtube',
									publisher: 'Google LLC',
									aim: 'Cookie von Google für die Benutzung von Youtube-Videos.',
									name: 'youtube, yt-remote-*',
									duration: '2 Jahre'
								}
							],

								accept: function() {

									dywc.log("Load Statistic Tracking");

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
											case "youtube":
											return "https://www.youtube-nocookie.com/embed/{SOURCE}?rel=0&controls=0&showinfo=0&autoplay=0&mute=0";

											/*
											case "youtube":
												return "https://www.youtube-nocookie.com/embed/HuEf86ATIys?rel=0&controls=0&showinfo=0&autoplay=0&mute=0";
											default: break;
											*/
										}
									}

									/* 2-Click Solution */
									document.ready( function() {

										var video_wrapper = document.querySelectorAll( '.video_wrapper' );

										if( !!video_wrapper.length ) {
											for( var i=0; i < video_wrapper.length; i++ ) {
												let _wrapper = video_wrapper[ i ];

												var video_triggers = _wrapper.querySelectorAll( '.video_trigger' );
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

																	var video_layers = _trigger.parentElement.querySelectorAll( ".video_layer" );
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