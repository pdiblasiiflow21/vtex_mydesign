'use strict';

/**
 * Event Load General
 */
window.addEventListener('load', () => {
	$('#SucursalIr').on('click', () => {
		let link = $('#SelectSucursal').val();
		if (link) {
			window.open(link, '_blank');
		}
	});

	$('#OpinarCloseForm').on('click', () => {
		$('#OverlayOpinarModal').removeClass('--show');
		$('#modalOpinar').modal('hide');
	});
});


/**
 * Event load Tracker
 */
window.addEventListener('load', () => {
	$('#TrackerHeaderForm').on('submit', e => {
		e.preventDefault();

		let form = document.querySelector('#TrackerHeaderForm');
		if (!form.numero.value) {
			return;
		}
		console.log(form.opcion.value);
		if (form.opcion.value == 'paqueteria') {
			window.open('/ecommerce/seguimiento/' + form.numero.value, '_blank');
			return;
		}

		$('#modalTracker').modal('show');
		let $loading = $('#modalTrackerLoading').show();
		let $flash = $('#modalTrackerFlash').empty();
		let $content = $('#modalTrackerContent').empty();

		$.ajax({
			url: '/seguimiento-postal',
			type: 'post',
			headers: {
				'X-CSRF-Token': csrfToken
			},
			data: {
				numero: form.numero.value
			}
		}).done(html => {
			$content.html(html);

			$content.find('.js-content-imagen').find('img').addClass('border mb-4 w-100 h-auto');

			let lat = $content.find('#lat').val();
			let lon = $content.find('#lon').val();

			if (lat && lon) {
				$('#LeafletScript').on('load', () => {
					console.log('pase');
					let mapTracking = L.map('map_canvas').setView([51.505, -0.09], 13);
					mapTracking.setView([lat, lon], 15);

					L.tileLayer('https://api.tiles.mapbox.com/v4/{id}/{z}/{x}/{y}.png?access_token=pk.eyJ1IjoibWFpbGV4cHJlc3MiLCJhIjoiY2pubWpxaXdqMDN6cjNrcGR3czBidmxwMSJ9.N9eEUnrZg7xHSbv3xp7bPA', {
						attribution: 'Map data &copy; <a href="https://www.openstreetmap.org/">OpenStreetMap</a> contributors, <a href="https://creativecommons.org/licenses/by-sa/2.0/">CC-BY-SA</a>, Imagery © <a href="https://www.mapbox.com/">Mapbox</a>',
						maxZoom: 18,
						id: 'mapbox.streets',
						accessToken: 'your.mapbox.access.token'
					}).addTo(mapTracking);
	
					L.marker([lat, lon]).addTo(mapTracking);

					$('#wrapper_ubicacion').show();
				});
			}

		}).fail(() => {
			$flash.append(
				$('<div>').addClass('alert alert-danger').text('Ocurrio un error, por favor, intente de nuevo.')
			);
		}).always(() => {
			$loading.hide();
		});

		return false;
	});
});


/**
 * Envia el formulario de opinión.
 * Este submit lo llama el recaptcha.
 */
function submitOpinar() {
	let $btn = $('#OpinarSubmit');
	let $form = $('#FormOpinar');
	let data = $form.serializeArray();
	let $flash = $('#FormOpinarFlash');

	if (!$form.valid()) {
		return;
	}

	console.log(csrfToken)

	$.ajax({
		url: '/pages/opinar_form',
		type: 'post',
		data: data,
		headers: {
			'X-CSRF-Token': csrfToken,
			'aceppt': 'application/json',
		},
		beforeSend: (xhr) => {
			$btn.attr('disabled', 'disabled');
			$flash.empty();
		}
	}).done(res => {
		if (!res || !res.ok) {
			let msg = res && res.message ? res.message : 'Error';
			msg = '<b>' + msg + '</b>';
			if ('errors' in res) {
				msg += '<br>';
				msg += '<ul class="mb-0"><li>' + res.errors.join('</li><li>') + '</li></ul>';
			}
			$('<div>').addClass('alert alert-danger').html(msg).appendTo($flash);
			return;
		}
		$('#OverlayOpinarModal').addClass('--show');
		$form[0].reset();
	}).fail(() => {
		$('<div>').addClass('alert alert-danger').html('Error en el servidor').appendTo($flash);
	}).always(() => {
		$btn.removeAttr('disabled');
	});

}