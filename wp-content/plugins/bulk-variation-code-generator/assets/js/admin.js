(function ($) {
	'use strict';

	const state = {
		running: false,
		jobToken: '',
		csvText: '',
		imageMap: {},
		uploadedImages: {},
		maxImageSize: 10 * 1024 * 1024 * 1024,
		previewRequest: null,
		previewTimer: null
	};

	const MAX_RETRIES = 3;
	const MAX_IMAGE_DIMENSION = 2400;
	// Keep concurrent media processing below typical shared-host PHP worker limits.
	const IMAGE_UPLOAD_CONCURRENCY = 4;

	function jobStorageKey() {
		return 'bvcg_job_' + ($('#bvcg_product_id').val() || '0');
	}

	function storeJobToken(token) {
		state.jobToken = token || '';

		if (state.jobToken) {
			sessionStorage.setItem(jobStorageKey(), state.jobToken);
			$('#bvcg_resume_button').prop('hidden', false);
		} else {
			sessionStorage.removeItem(jobStorageKey());
			$('#bvcg_resume_button').prop('hidden', true);
		}
	}

	function restoreJobToken() {
		const token = sessionStorage.getItem(jobStorageKey()) || '';

		if (token) {
			storeJobToken(token);
			setStatus('A previous generation can be resumed.', false);
		}
	}

	function imageUploadStorageKey() {
		return 'bvcg_images_' + ($('#bvcg_product_id').val() || '0');
	}

	function fileSignature(file) {
		return [file.name, file.size, file.lastModified].join('|');
	}

	function storeUploadedImages() {
		sessionStorage.setItem(imageUploadStorageKey(), JSON.stringify(state.uploadedImages));
	}

	function restoreUploadedImages() {
		const stored = sessionStorage.getItem(imageUploadStorageKey());

		if (!stored) {
			return;
		}

		try {
			state.uploadedImages = JSON.parse(stored) || {};
		} catch (error) {
			state.uploadedImages = {};
		}
	}

	function clearUploadedImages() {
		state.uploadedImages = {};
		sessionStorage.removeItem(imageUploadStorageKey());
	}

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
			product_type: $('#product-type').val() || '',
			price: $('#bvcg_price').val(),
			sku_prefix: $('#bvcg_sku_prefix').val(),
			stock_qty: $('#bvcg_stock_qty').val(),
			csv_text: state.csvText,
			image_map: JSON.stringify(state.imageMap),
			seo_title: $('#bvcg_seo_title').val(),
			seo_alt: $('#bvcg_seo_alt').val(),
			seo_caption: $('#bvcg_seo_caption').val(),
			seo_description: $('#bvcg_seo_description').val()
		};
	}

	function buildMultipartFormData(action, includeImages) {
		const payload = getFormData();
		const formData = new FormData();

		formData.append('action', action);
		formData.append('nonce', bvcgData.nonce);

			Object.keys(payload).forEach(function (key) {
				formData.append(key, payload[key]);
			});

			const imageInput = document.getElementById('bvcg_image_files');

			if (includeImages && imageInput && imageInput.files && imageInput.files.length) {
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

	function uploadImages(files) {
		const deferred = $.Deferred();
		const imageMap = {};
		const pendingFiles = [];
		let nextIndex = 0;
		let completed = 0;
		let failed = false;

		files.forEach(function (file) {
			const stored = state.uploadedImages[fileSignature(file)];

			if (stored && stored.key && stored.attachment_id) {
				imageMap[stored.key] = stored.attachment_id;
				completed++;
				return;
			}

			pendingFiles.push(file);
		});

		if (!pendingFiles.length) {
			deferred.resolve(imageMap);
			return deferred.promise();
		}

		function uploadNext() {
			if (failed || nextIndex >= pendingFiles.length) {
				return;
			}

			const index = nextIndex++;
			const sourceFile = pendingFiles[index];
			setStatus('Preparing images ' + String(completed) + ' / ' + String(files.length), false);

			prepareImageForUpload(sourceFile).done(function (file) {
				if (failed) {
					return;
				}

				const formData = new FormData();
				formData.append('action', 'bvcg_upload_image');
				formData.append('nonce', bvcgData.nonce);
				formData.append('product_id', $('#bvcg_product_id').val());
				formData.append('variation_image', file, file.name);
				setStatus('Uploading images ' + String(completed) + ' / ' + String(files.length), false);

					function sendImage(attempt) {
						$.ajax({
							url: bvcgData.ajaxUrl,
							method: 'POST',
							data: formData,
							processData: false,
							contentType: false
						}).done(function (response) {
					if (failed) {
						return;
				}

				if (!response || !response.success || !response.data || !response.data.key) {
					failed = true;
					deferred.reject((response && response.data && response.data.message) || bvcgData.strings.error);
					return;
				}

				imageMap[response.data.key] = response.data.attachment_id;
				state.uploadedImages[fileSignature(sourceFile)] = {
					key: response.data.key,
					attachment_id: response.data.attachment_id
				};
				storeUploadedImages();
				completed++;

				if (completed >= files.length) {
					deferred.resolve(imageMap);
					return;
				}

				uploadNext();
						}).fail(function () {
							if (failed) {
								return;
							}

							if (attempt < MAX_RETRIES) {
								setStatus('Retrying image ' + String(index + 1) + '...', true);
								setTimeout(function () {
									sendImage(attempt + 1);
								}, 1000 * (attempt + 1));
								return;
							}

							failed = true;
							deferred.reject(bvcgData.strings.error);
						});
					}

					sendImage(0);
			}).fail(function () {
				failed = true;
				deferred.reject('Could not prepare image ' + sourceFile.name + '.');
			});
		}

		for (let worker = 0; worker < Math.min(IMAGE_UPLOAD_CONCURRENCY, files.length); worker++) {
			uploadNext();
		}

		return deferred.promise();
	}

	function prepareImageForUpload(file) {
		const deferred = $.Deferred();
		const reader = new FileReader();

		reader.onload = function (event) {
			const image = new Image();

			image.onload = function () {
				if (image.width <= MAX_IMAGE_DIMENSION && image.height <= MAX_IMAGE_DIMENSION) {
					deferred.resolve(file);
					return;
				}

				const scale = Math.min(MAX_IMAGE_DIMENSION / image.width, MAX_IMAGE_DIMENSION / image.height);
				const canvas = document.createElement('canvas');
				canvas.width = Math.max(1, Math.round(image.width * scale));
				canvas.height = Math.max(1, Math.round(image.height * scale));
				canvas.getContext('2d').drawImage(image, 0, 0, canvas.width, canvas.height);
				canvas.toBlob(function (blob) {
					if (!blob) {
						deferred.reject();
						return;
					}

					const name = file.name.replace(/\.[^.]+$/, '') + '.jpg';
					deferred.resolve(new File([blob], name, { type: 'image/jpeg' }));
				}, 'image/jpeg', 0.88);
			};

			image.onerror = function () {
				deferred.reject();
			};
			image.src = event.target.result;
		};

		reader.onerror = function () {
			deferred.reject();
		};
		reader.readAsDataURL(file);

		return deferred.promise();
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
		if (state.previewRequest) {
			state.previewRequest.abort();
			state.previewRequest = null;
		}

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

		state.previewRequest = $.post(bvcgData.ajaxUrl, {
			action: 'bvcg_preview',
			nonce: bvcgData.nonce,
			...payload
		}).done(function (response) {
			if (response && response.success) {
				renderPreview(response.data.preview);
				return;
			}

			$('#bvcg_preview').html('<p class="bvcg-error">' + escapeHtml((response && response.data && response.data.message) || bvcgData.strings.error) + '</p>');
		}).fail(function (xhr, status) {
			if ('abort' === status) {
				return;
			}

			$('#bvcg_preview').html('<p class="bvcg-error">' + escapeHtml(bvcgData.strings.error) + '</p>');
		}).always(function () {
			state.previewRequest = null;
		});
	}

	function schedulePreview() {
		clearTimeout(state.previewTimer);
		state.previewTimer = setTimeout(updatePreview, 350);
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

	function runJob(jobToken, retryCount) {
		retryCount = retryCount || 0;
		storeJobToken(jobToken);
		state.running = true;
		$('#bvcg_generate_button, #bvcg_resume_button').prop('disabled', true);

		$.post(bvcgData.ajaxUrl, {
			action: 'bvcg_process_job',
			nonce: bvcgData.nonce,
			product_id: $('#bvcg_product_id').val(),
			job_token: jobToken,
			batch_size: bvcgData.batchSize
		}).done(function (response) {
			if (!response || !response.success) {
				if (retryCount < MAX_RETRIES && response && response.data && response.data.code === 'bvcg_job_locked') {
					setTimeout(function () {
						runJob(jobToken, retryCount + 1);
					}, 1000 * (retryCount + 1));
					return;
				}

				state.running = false;
				if (response && response.data && response.data.code === 'bvcg_missing_job') {
					storeJobToken('');
				}
				setStatus((response && response.data && response.data.message) || bvcgData.strings.error, true);
				$('#bvcg_generate_button, #bvcg_resume_button').prop('disabled', false);
				return;
			}

			const data = response.data || {};
			setStatus((data.done ? '' : bvcgData.strings.loading + ' ') + buildProgressMessage(data), false);

		if (data.done) {
			state.running = false;
			storeJobToken('');
			clearUploadedImages();
				$('#bvcg_generate_button, #bvcg_resume_button').prop('disabled', false);
				if ($('#variable_product_options').length) {
					$('#variable_product_options').trigger('reload');
				}
				return;
			}

			runJob(jobToken, 0);
		}).fail(function () {
			if (retryCount < MAX_RETRIES) {
				setStatus(bvcgData.strings.error + ' Retrying...', true);
				setTimeout(function () {
					runJob(jobToken, retryCount + 1);
				}, 1000 * (retryCount + 1));
				return;
			}

			state.running = false;
			setStatus(bvcgData.strings.error + ' Use Resume Generation to continue.', true);
			$('#bvcg_generate_button, #bvcg_resume_button').prop('disabled', false);
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
		state.imageMap = {};
		$('#bvcg_generate_button').prop('disabled', true);
		setStatus(bvcgData.strings.loading, false);
		const files = imageInput && imageInput.files ? Array.from(imageInput.files) : [];

		uploadImages(files).done(function (imageMap) {
			state.imageMap = imageMap;
			setStatus(bvcgData.strings.loading, false);

			$.ajax({
				url: bvcgData.ajaxUrl,
				method: 'POST',
				data: buildMultipartFormData('bvcg_create_job', false),
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
			storeJobToken(data.token);
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
		}).fail(function (message) {
			state.running = false;
			$('#bvcg_generate_button').prop('disabled', false);
			setStatus(message || bvcgData.strings.error, true);
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
		$(document).on('input change', '#bvcg_attribute, #bvcg_prefix, #bvcg_start, #bvcg_end, #bvcg_digits, #bvcg_price, #bvcg_sku_prefix, #bvcg_stock_qty, #bvcg_seo_title, #bvcg_seo_alt, #bvcg_seo_caption, #bvcg_seo_description, #product-type', function () {
			schedulePreview();
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

		$(document).on('click', '#bvcg_resume_button', function (event) {
			event.preventDefault();

			if (state.jobToken && !state.running) {
				runJob(state.jobToken, 0);
			}
		});

		renderImagePreview();
		updatePreview();
		restoreJobToken();
		restoreUploadedImages();
	});
})(jQuery);
