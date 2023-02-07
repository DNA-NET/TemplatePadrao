function PrintElem(elem) {
	var conteudo_janela = document.getElementById(elem).innerHTML;
	var mywindow = window.open('', 'PRINT');

	mywindow.document.write('<html><head><title>' + document.title + '</title>');
	mywindow.document.write('<style>html, body { font-family:Open Sans, Arial, sans-serif; } @media print { .no-print { display:none; } }</style>');
	mywindow.document.write('</head><body >');
	//mywindow.document.write('<h1>' + document.title  + '</h1>');
	mywindow.document.write('<img src="/fccr/assets/images/logofccr.png" alt="logo" style="max-width:220px; margin-bottom:15px;"><br><br>');
	mywindow.document.write(conteudo_janela);
	mywindow.document.write('</body></html>');

	mywindow.document.close(); // necessary for IE >= 10
	mywindow.focus(); // necessary for IE >= 10*/

	mywindow.print();
	mywindow.close();

	return true;
}

/*********************************************
* Short Link using Bitly
*********************************************/
function ShortLinkBitly(pLongUrl) { /*pLongUrl is the long URL*/
	
	var shortURL = "";

	/*Long URL must start with a protocol, bitly can't (and won't) figure out the right protocol.*/
	if (!pLongUrl.match(/(ftp|http|https):\/\//i)) {
		return "Error: Link must start with a protocol (e.g.: http or https).";
	}

	var apiKey = 'R_b38ce3d5e7dc4f99a7eaff553d625186';
	var username = 'gbilefete';

	/*Ajax call*/
	$.ajax(
		{
			url: 'https://api-ssl.bitly.com/v3/shorten?login=' + username + '&apiKey=' + apiKey + '&format=json&longUrl=' + encodeURIComponent(pLongUrl),

			//dataType: 'jsonp',

			success: function (response) {
				if (response.status_code == 500) {

					/*500 status code means the link is malformed.*/
					shortURL = "Error: Invalid link.";

				} else if (response.status_code != 200) {

					/*If response is not 200 then an error ocurred. It can be a network issue or bitly is down.*/
					shortURL = "Error: Service unavailable.";

					/*Uncomment the following line only for debugging purposes*/
					/*console.log('Response: ' + response.status_code + '-' + response.status_txt);*/
				} else {
					shortURL = response.data.url; /* OK, return the short link */
				}
			},
			async:false,
			//contentType: 'application/json'
		});

	return shortURL;

}
/* END: Short Link using Bitly */


(function ($) {
	"use strict";

	// Plugin name
	var name = "sharebox";

	// jQuery plugin
	var Self = function (options) {
		var Defaults = Self.defaults,
			Services = Self.services;

		this.each(function () {
			var $this = $(this),
				attrs = {
					url: $this.data("url"),
					title: $this.data("title"),
					services: $this.data("services")
				},
				settings = $.extend({}, Defaults, attrs, options),
				services = settings.services.match(/\S+/g);

			// Container
			var $ul = $this.find("ul.sharebox-buttons");
			if (!$ul.length)
				$ul = $("<ul></ul>").addClass("sharebox-buttons").appendTo($this);

			// Buttons
			$.each(services, function (i, name) {
				name = name.replace("google+", "google-plus");

				var Service = Services[name];
				if (!Service) return;

				// li
				var $li = $ul.find("li.share-" + name);
				if (!$li.length)
					$li = $("<li></li>").addClass("share-" + name).appendTo($ul);

				// a
				$li.append(
					$("<a></a>")
						.attr({
							href: ($.isFunction(Service.url) ? Service.url(settings) : Service.url),
							title: (Service.label || name)
						})
						.data({
							width: Service.width,
							height: Service.height
						})
						.click(function () {
							return Service.click.call(this, settings);
						})
						.append(
							$("<span></span>")
								.text(Service.label || name)
						)
				);
			});
		});

		return this;
	};

	// Autoinit `.sharebox` elements
	$(document).ready(function () {
		$(".sharebox").sharebox();
	});

	Self.popup = function (options) {
		var $this = $(this),
			url = $this.attr("href"),
			width = $this.data("width") || 400,
			height = $this.data("height") || 300;

		window.open(url, "share",
			"menubar=no,status=no,resizable=yes,menubar=no,width={WIDTH},height={HEIGHT}"
				.replace("{WIDTH}", width)
				.replace("{HEIGHT}", height)
		);

		return false;
	};

	// Defaults options
	Self.defaults = {
		url: document.location.href,
		title: $(document).attr("title"),
		services: "facebook twitter whatsapp print"
	};

	// Services
	Self.services = {
		digg: {
			label: "Digg",
			width: 720,
			height: 320,
			url: function (options) {
				return "https://digg.com/submit?url={URL}&title={TITLE}"
					.replace("{URL}", encodeURIComponent(options.url))
					.replace("{TITLE}", encodeURIComponent(options.title));
			},
			click: Self.popup
		},
		facebook: {
			label: "Facebook",
			width: 500,
			height: 300,
			url: function (options) {
				return "https://www.facebook.com/sharer/sharer.php?u=https://www.fccr.org.br{URL}&title={TITLE}"
					.replace("{URL}", encodeURIComponent(options.url))
					.replace("{TITLE}", encodeURIComponent(options.title));
			},
			click: Self.popup
		},
		whatsapp: {
			label: "Whatsapp",
			width: 500,
			height: 700,
			url: function (options) {
				return "https://api.whatsapp.com/send?text={TITLE}: {URL}"
					.replace("{URL}", ShortLinkBitly(window.location.href))
					.replace("{TITLE}", "Conselho Regional de Contabilidade do Paraná - " + encodeURIComponent(options.title));
			},
			click: Self.popup
		},
		"google-plus": {
			label: "Google+",
			width: 500,
			height: 460,
			url: function (options) {
				return "https://plus.google.com/share?url={URL}"
					.replace("{URL}", encodeURIComponent(options.url));
			},
			click: Self.popup
		},
		linkedin: {
			label: "LinkedIn",
			width: 500,
			height: 400,
			url: function (options) {
				return "https://www.linkedin.com/shareArticle?mini=true&url=https://www.fccr.org.br{URL}&title={TITLE}&summary=&source="
					.replace("{URL}", encodeURIComponent(options.url))
					.replace("{TITLE}", encodeURIComponent(options.title));
			},
			click: Self.popup
		},
		pinterest: {
			label: "Pinterest",
			width: 500,
			height: 300,
			url: function (options) {
				return "https://pinterest.com/pin/create/link/?url={URL}&description={TITLE}"
					.replace("{URL}", encodeURIComponent(options.url))
					.replace("{TITLE}", encodeURIComponent(options.title));
			},
			click: Self.popup
		},
		pocket: {
			label: "Pocket",
			width: null,
			height: null,
			url: function (options) {
				return "https://getpocket.com/save?url={URL}&title={TITLE}"
					.replace("{URL}", encodeURIComponent(options.url))
					.replace("{TITLE}", encodeURIComponent(options.title));
			},
			click: Self.popup
		},
		reddit: {
			label: "Reddit",
			width: 500,
			height: 300,
			url: function (options) {
				return "https://reddit.com/submit?url={URL}&title={TITLE}"
					.replace("{URL}", encodeURIComponent(options.url))
					.replace("{TITLE}", encodeURIComponent(options.title));
			},
			click: Self.popup
		},
		stumbleupon: {
			label: "StumbleUpon",
			width: 500,
			height: 300,
			url: function (options) {
				return "https://www.stumbleupon.com/submit?url={URL}&title={TITLE}"
					.replace("{URL}", encodeURIComponent(options.url))
					.replace("{TITLE}", encodeURIComponent(options.title));
			},
			click: Self.popup
		},
		tumblr: {
			label: "Tumblr",
			width: 500,
			height: 300,
			url: function (options) {
				return "https://www.tumblr.com/share/link?url={URL}&name={TITLE}&description="
					.replace("{URL}", encodeURIComponent(options.url))
					.replace("{TITLE}", encodeURIComponent(options.title));
			},
			click: Self.popup
		},
		twitter: {
			label: "Twitter",
			width: 500,
			height: 300,
			url: function (options) {
				return "https://twitter.com/intent/tweet?url={URL}&text={TITLE}"
					.replace("{URL}", ShortLinkBitly(window.location.href))
					.replace("{TITLE}", encodeURIComponent(options.title) + " - via @fccr");
			},
			click: Self.popup
		},
		print: {
			label: "Imprimir",
			url: "#",
			click: function () {
				//window.print();
				PrintElem('corpo_pagina');
				return false;
			}
		},
		email: {
			label: "Enviar por e-mail",
			url: "#",
			click: function () {
				document.getElementById('envia_email').click();
			}
		}
	};

	$.fn[name] = Self;

})(jQuery);
