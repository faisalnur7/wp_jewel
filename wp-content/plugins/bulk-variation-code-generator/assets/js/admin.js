(function ($) {
	'use strict';

	const state = {
		running: false,
		csvText: '',
		maxImageSize: 10 * 1024 * 1024 * 1024
	};

	function getOversizedImageName(files) {
		for (let index = 0; index < files.length; index++) {
			if (files[index].size > state.maxImageSize) {
				return files[index].name || '';
			}
		}

		return '';
	}

	function getFormData() {
		return {
			product_id: $('#bvcg_product_id').val(),
			attribute: $('#bvcg_attribute').val(),
			prefix: $('#bvcg_prefix').val(),
			start: $('#bvcg_start').val(),
			end: $('#bvcg_end').val(),
			digits: $('#bvcg_digits').val(),
			price: $('#bvcg_price').val(),
			sku_prefix: $('#bvcg_sku_prefix').val(),
			stock_qty: $('#bvcg_stock_qty').val(),
			csv_text: state.csvText,
			seo_title: $('#bvcg_seo_title').val(),
			seo_alt: $('#bvcg_seo_alt').val(),
			seo_caption: $('#bvcg_seo_caption').val(),
			seo_description: $('#bvcg_seo_description').val()
		};
	}

	function buildMultipartFormData(action) {
		const payload = getFormData();
		const formData = new FormData();

		formData.append('action', action);
		formData.append('nonce', bvcgData.nonce);

			Object.keys(payload).forEach(function (key) {
				formData.append(key, payload[key]);
			});

			const imageInput = document.getElementById('bvcg_image_files');

			if (imageInput && imageInput.files && imageInput.files.length) {
				const oversizedFile = getOversizedImageName(Array.from(imageInput.files));

				if (oversizedFile) {
					return null;
				}

				Array.from(imageInput.files).forEach(function (file) {
					formData.append('variation_images[]', file, file.name);
				});
			}

		return formData;
	}

	function renderPreview(preview) {
		if (!preview || !preview.total) {
			$('#bvcg_preview').html('<p>' + (bvcgData.strings.noPreview || '') + '</p>');
			return;
		}

		const first = preview.first && preview.first.length ? preview.first : [];
		const last = preview.last && preview.last.length ? preview.last : [];
		let html = '<div class="bvcg-preview-group"><strong>First</strong><ul>';

		first.forEach(function (code) {
			html += '<li>' + escapeHtml(code) + '</li>';
		});

		html += '</ul></div>';
		html += '<div class="bvcg-preview-group"><strong>Last</strong><ul>';

		last.forEach(function (code) {
			html += '<li>' + escapeHtml(code) + '</li>';
		});

		html += '</ul></div>';
		html += '<div class="bvcg-preview-total"><strong>Total</strong> ' + escapeHtml(String(preview.total)) + ' variations</div>';

		$('#bvcg_preview').html(html);
	}

	function escapeHtml(text) {
		return String(text)
			.replace(/&/g, '&amp;')
			.replace(/</g, '&lt;')
			.replace(/>/g, '&gt;')
			.replace(/"/g, '&quot;')
			.replace(/'/g, '&#039;');
	}

	function updatePreview() {
		const payload = getFormData();
		const productId = parseInt(payload.product_id, 10) || 0;

		if (!productId) {
			$('#bvcg_preview').html('<p class="bvcg-error">' + escapeHtml(bvcgData.strings.saveFirst) + '</p>');
			return;
		}

		if (!payload.attribute || (!payload.csv_text && (!payload.prefix || !payload.start || !payload.end))) {
			$('#bvcg_preview').html('<p>' + (bvcgData.strings.noPreview || '') + '</p>');
			return;
		}

		$.post(bvcgData.ajaxUrl, {
			action: 'bvcg_preview',
			nonce: bvcgData.nonce,
			...payload
		}).done(function (response) {
			if (response && response.success) {
				renderPreview(response.data.preview);
				return;
			}

			$('#bvcg_preview').html('<p class="bvcg-error">' + escapeHtml((response && response.data && response.data.message) || bvcgData.strings.error) + '</p>');
		}).fail(function () {
			$('#bvcg_preview').html('<p class="bvcg-error">' + escapeHtml(bvcgData.strings.error) + '</p>');
		});
	}

	function setStatus(message, isError) {
		$('#bvcg_status')
			.text(message)
			.toggleClass('is-error', !!isError);
	}

	function buildProgressMessage(data) {
		const pieces = [];

		pieces.push(String(data.processed || 0) + ' / ' + String(data.total || 0));

		if (data.images_assigned) {
			pieces.push(String(data.images_assigned) + ' images attached');
		}

		if (data.images_skipped) {
			pieces.push(String(data.images_skipped) + ' image skips');
		}

		if (data.images_missing) {
			pieces.push(String(data.images_missing) + ' missing images');
		}

		if (data.done) {
			pieces.push(bvcgData.strings.completed);
		}

		return pieces.join(' | ');
	}

	function renderImagePreview() {
		const imageInput = document.getElementById('bvcg_image_files');
		const files = imageInput && imageInput.files ? Array.from(imageInput.files) : [];
		const preview = $('#bvcg_image_preview');

		if (!files.length) {
			preview.html('<p>' + escapeHtml(bvcgData.strings.noImagesSelected) + '</p>');
			return;
		}

		let html = '<div class="bvcg-image-summary">' + escapeHtml(String(files.length)) + ' ' + escapeHtml(bvcgData.strings.imagesSelected) + '</div><ul class="bvcg-image-list">';

		files.forEach(function (file) {
			html += '<li>' + escapeHtml(file.name) + '</li>';
		});

		html += '</ul><p class="description">' + escapeHtml(bvcgData.strings.matchedImages) + '</p>';
		preview.html(html);
	}

	function runJob(jobToken) {
		$.post(bvcgData.ajaxUrl, {
			action: 'bvcg_process_job',
			nonce: bvcgData.nonce,
			product_id: $('#bvcg_product_id').val(),
			job_token: jobToken,
			batch_size: bvcgData.batchSize
		}).done(function (response) {
			if (!response || !response.success) {
				state.running = false;
				setStatus((response && response.data && response.data.message) || bvcgData.strings.error, true);
				$('#bvcg_generate_button').prop('disabled', false);
				return;
			}

			const data = response.data || {};
			setStatus((data.done ? '' : bvcgData.strings.loading + ' ') + buildProgressMessage(data), false);

			if (data.done) {
				state.running = false;
				$('#bvcg_generate_button').prop('disabled', false);
				if ($('#variable_product_options').length) {
					$('#variable_product_options').trigger('reload');
				}
				return;
			}

			runJob(jobToken);
		}).fail(function () {
			state.running = false;
			setStatus(bvcgData.strings.error, true);
			$('#bvcg_generate_button').prop('disabled', false);
		});
	}

	function startGeneration() {
		if (state.running) {
			return;
		}

			const payload = getFormData();
			const productId = parseInt(payload.product_id, 10) || 0;
			const imageInput = document.getElementById('bvcg_image_files');
			const oversizedFile = imageInput && imageInput.files ? getOversizedImageName(Array.from(imageInput.files)) : '';

			if (!productId) {
				setStatus(bvcgData.strings.saveFirst, true);
				return;
			}

			if (oversizedFile) {
				setStatus((bvcgData.strings.imageTooLarge || 'Each image must be 10 GB or smaller.') + ' ' + oversizedFile, true);
				return;
			}

		state.running = true;
		$('#bvcg_generate_button').prop('disabled', true);
		setStatus(bvcgData.strings.loading, false);

		$.ajax({
			url: bvcgData.ajaxUrl,
			method: 'POST',
			data: buildMultipartFormData('bvcg_create_job'),
			processData: false,
			contentType: false
		}).done(function (response) {
			if (!response || !response.success) {
				state.running = false;
				$('#bvcg_generate_button').prop('disabled', false);
				setStatus((response && response.data && response.data.message) || bvcgData.strings.error, true);
				return;
			}

			const data = response.data || {};
			if (data.preview) {
				renderPreview(data.preview);
			}

			if (!data.token) {
				state.running = false;
				$('#bvcg_generate_button').prop('disabled', false);
				setStatus(bvcgData.strings.error, true);
				return;
			}

			runJob(data.token);
		}).fail(function () {
			state.running = false;
			$('#bvcg_generate_button').prop('disabled', false);
			setStatus(bvcgData.strings.error, true);
		});
	}

	function parseCsv(text) {
		const lines = text.trim().split(/\r?\n/);
		if (!lines.length) {
			return '';
		}
		return text;
	}

	$(function () {
		$(document).on('input change', '#bvcg_attribute, #bvcg_prefix, #bvcg_start, #bvcg_end, #bvcg_digits, #bvcg_price, #bvcg_sku_prefix, #bvcg_stock_qty, #bvcg_seo_title, #bvcg_seo_alt, #bvcg_seo_caption, #bvcg_seo_description', function () {
			updatePreview();
		});

		$(document).on('change', '#bvcg_csv_file', function (event) {
			const file = event.target.files && event.target.files[0];

			if (!file) {
				state.csvText = '';
				updatePreview();
				return;
			}

			const reader = new FileReader();
			reader.onload = function (loadEvent) {
				state.csvText = loadEvent.target.result || '';
				$('#bvcg_csv_text').val(state.csvText);
				updatePreview();
			};
			reader.readAsText(file);
		});

		$(document).on('change', '#bvcg_image_files', function () {
			renderImagePreview();
		});

		$(document).on('click', '#bvcg_generate_button', function (event) {
			event.preventDefault();
			startGeneration();
		});

		renderImagePreview();
		updatePreview();
	});
})(jQuery);
