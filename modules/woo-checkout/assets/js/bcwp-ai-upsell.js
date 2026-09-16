jQuery(function ($) {
	'use strict';

	if (typeof BEWIAUpsell === 'undefined' || !(BEWIAUpsell.ajax_url || BEWIAUpsell.ajaxurl)) {
		return;
	}

	var ajaxUrl = BEWIAUpsell.ajax_url || BEWIAUpsell.ajaxurl;

	var checkoutStartedAt = Math.floor(Date.now() / 1000);
	var maxScrollDepth = 0;
	var selectors = {
		widget: '.bewia-ai-smart-wrapper',
		list: '.bewia-ai-smart-upsells__list',
		status: '.bewia-ai-smart-upsells__status',
		button: '.bewia-ai-smart-upsells__button',
		dismiss: '.bewia-ai-smart-upsells__dismiss',
		item: '.bewia-ai-smart-upsells__item'
	};

	function parseSettings($widget) {
		var raw = $widget.attr('data-settings');

		if (!raw) {
			return {};
		}

		try {
			return JSON.parse(raw);
		} catch (e) {
			return {};
		}
	}

	function storeSettings($widget, settings) {

	function getScrollDepth() {
		var documentHeight = Math.max($(document).height(), 1);
		var viewportBottom = $(window).scrollTop() + $(window).height();
		var depth = Math.floor((viewportBottom / documentHeight) * 100);

		maxScrollDepth = Math.max(maxScrollDepth, Math.min(100, Math.max(0, depth)));

		return maxScrollDepth;
	}
		$widget.attr('data-settings', JSON.stringify(settings || {}));
	}

	function setStatus($widget, message, isError) {
		var $status = $widget.find(selectors.status);
		$status.text(message || '');
		$status.toggleClass('is-error', !!isError);
	}

	function setAcceptedState($widget, accepted) {
		$widget.data('bewiaAccepted', !!accepted);
	}

	function isAccepted($widget) {
		return !!$widget.data('bewiaAccepted');
	}

	function trackRendered($widget, productId) {
		if (!productId || $widget.data('bewiaRenderedProductId') === productId) {
			return;
		}

		$widget.data('bewiaRenderedProductId', productId);

		$.post(ajaxUrl, {
			action: 'bcwp_track_ai_upsell_rendered',
			nonce: BEWIAUpsell.nonce,
			product_id: productId,
			settings: parseSettings($widget)
		});
	}

	function getLayout($widget) {
		return $widget.attr('data-layout') || 'card';
	}

	function renderSkeleton($widget) {
		var layout = getLayout($widget);
		var html = '';

		if (layout === 'checkbox') {
			html += '<div class="bewia-ai-upsell-checkbox bewia-ai-upsell-skeleton">';
			html += '<div class="bewia-ai-skeleton bewia-ai-skeleton--checkbox"></div>';
			html += '<div class="bewia-ai-skeleton bewia-ai-skeleton--thumb"></div>';
			html += '<div class="bewia-ai-upsell-content"><div class="bewia-ai-skeleton bewia-ai-skeleton--title"></div><div class="bewia-ai-skeleton bewia-ai-skeleton--price"></div><div class="bewia-ai-skeleton bewia-ai-skeleton--text"></div></div>';
			html += '</div>';
		} else if (layout === 'minimal') {
			html += '<div class="bewia-ai-upsell-minimal bewia-ai-upsell-skeleton">';
			html += '<div class="bewia-ai-upsell-content"><div class="bewia-ai-skeleton bewia-ai-skeleton--title"></div><div class="bewia-ai-skeleton bewia-ai-skeleton--price"></div></div>';
			html += '<div class="bewia-ai-skeleton bewia-ai-skeleton--button"></div>';
			html += '</div>';
		} else {
			html += '<div class="bewia-ai-upsell-card bewia-ai-upsell-skeleton">';
			html += '<div class="bewia-ai-skeleton bewia-ai-skeleton--thumb"></div>';
			html += '<div class="bewia-ai-upsell-content"><div class="bewia-ai-skeleton bewia-ai-skeleton--title"></div><div class="bewia-ai-skeleton bewia-ai-skeleton--price"></div><div class="bewia-ai-skeleton bewia-ai-skeleton--text"></div><div class="bewia-ai-skeleton bewia-ai-skeleton--text short"></div></div>';
			html += '</div>';
		}

		$widget.find(selectors.list).html(html);
	}

	function renderProduct($widget, product) {
		var html = '';

		if (!product || !product.product_id) {
			$widget.find(selectors.list).empty();
			$widget.find(selectors.button)
				.prop('disabled', true)
				.removeAttr('data-product-id')
				.removeAttr('data-quantity')
				.hide();
			$widget.find(selectors.dismiss)
				.removeAttr('data-product-id')
				.hide();
			return;
		}

		html += '<div class="bewia-ai-smart-upsells__item is-selected" data-product-id="' + product.product_id + '">';
		html += '<div class="bewia-ai-smart-upsells__media">' + (product.image_html || '') + '</div>';
		html += '<div class="bewia-ai-smart-upsells__content">';
		html += '<div class="bewia-ai-smart-upsells__title">' + (product.title || '') + '</div>';
		html += '<div class="bewia-ai-smart-upsells__price">' + (product.price_html || '') + '</div>';
		html += '<div class="bewia-ai-smart-upsells__description">' + (product.short_description || '') + '</div>';
		html += '</div>';
		html += '</div>';

		$widget.find(selectors.list).html(html);
		$widget.find(selectors.button)
			.prop('disabled', false)
			.attr('data-product-id', product.product_id)
			.attr('data-quantity', 1)
			.text(product.add_to_cart_text || 'Add this offer')
			.show();
		if (parseSettings($widget).show_dismiss === 'yes') {
			$widget.find(selectors.dismiss)
				.attr('data-product-id', product.product_id)
				.show();
		}

		trackRendered($widget, product.product_id);
	}

	function clearUpsell($widget, message, isError) {
		renderProduct($widget, null);
		setStatus($widget, message || '', !!isError);
	}

	function collapseWidget($widget) {
		$widget.addClass('bewia-is-collapsing');

		window.setTimeout(function () {
			$widget.slideUp(220, function () {
				$widget.addClass('bewia-is-collapsed');
			});
		}, 140);
	}

	function showSuccessState($widget, message) {
		var delay = parseInt(parseSettings($widget).collapse_delay_ms, 10);
		if (isNaN(delay) || delay < 0) {
			delay = 1200;
		}

		$widget.addClass('bewia-has-success');
		$widget.find(selectors.list).html('<div class="bewia-ai-upsell-success">' + (message || BEWIAUpsell.successText || 'Offer added to cart.') + '</div>');
		$widget.find(selectors.dismiss).hide();

		window.setTimeout(function () {
			collapseWidget($widget);
		}, delay);
	}

	function dismissUpsell($widget, productId) {
		if (!productId) {
			return;
		}

		setStatus($widget, BEWIAUpsell.dismissingText || 'Updating offer...', false);
		renderSkeleton($widget);
		$widget.addClass('is-loading');

		$.post(ajaxUrl, {
			action: 'bcwp_dismiss_ai_upsell',
			nonce: BEWIAUpsell.nonce,
			product_id: productId,
			settings: parseSettings($widget)
		}).done(function (response) {
			if (response && response.success && response.data && response.data.product) {
				renderProduct($widget, response.data.product);
				setStatus($widget, response.data.message || '', false);
				return;
			}

			clearUpsell($widget, response && response.data && response.data.message ? response.data.message : (BEWIAUpsell.emptyText || 'No recommendation available.'), false);
		}).fail(function (xhr) {
			var response = xhr && xhr.responseJSON ? xhr.responseJSON : null;
			var message = response && response.data && response.data.message ? response.data.message : '';
			clearUpsell($widget, message || (BEWIAUpsell.emptyText || 'No recommendation available.'), false);
		}).always(function () {
			$widget.removeClass('is-loading');
		});
	}

	function requestUpsell($widget) {
		var settings = parseSettings($widget);

		if (isAccepted($widget)) {
			return;
		}

		setStatus($widget, BEWIAUpsell.loadingText || 'Loading recommendation...', false);
		renderSkeleton($widget);
		$widget.addClass('is-loading');

		$.post(ajaxUrl, {
			action: 'bcwp_get_ai_upsell',
			nonce: BEWIAUpsell.nonce,
			settings: settings,
			scroll_depth: getScrollDepth(),
			checkout_started_at: checkoutStartedAt
		}).done(function (response) {
			if (response && response.success && response.data && response.data.product) {
				if (response.data.provider_source) {
					settings.provider_source = response.data.provider_source;
					storeSettings($widget, settings);
				}
				renderProduct($widget, response.data.product);
				setStatus($widget, '', false);
				$widget.removeClass('bewia-ai-upsell-loading');
				return;
			}

			clearUpsell($widget, response && response.data && response.data.message ? response.data.message : (BEWIAUpsell.emptyText || 'No recommendation available.'), false);
			$widget.removeClass('bewia-ai-upsell-loading');
		}).fail(function (xhr) {
			var response = xhr && xhr.responseJSON ? xhr.responseJSON : null;
			var message = response && response.data && response.data.message ? response.data.message : '';

			if (xhr && xhr.status === 404) {
				clearUpsell($widget, message || (BEWIAUpsell.emptyText || 'No recommendation available.'), false);
				$widget.removeClass('bewia-ai-upsell-loading');
				return;
			}

			clearUpsell($widget, message || (BEWIAUpsell.errorText || 'Unable to load recommendation.'), true);
		}).always(function () {
			$widget.removeClass('is-loading');
		});
	}

	function acceptUpsell($widget, productId, quantity) {
		if (!productId) {
			return;
		}

		setStatus($widget, BEWIAUpsell.addingText || 'Adding offer...', false);
		$widget.addClass('is-adding');

		$.post(ajaxUrl, {
			action: 'bcwp_accept_ai_upsell',
			nonce: BEWIAUpsell.nonce,
			product_id: productId,
			quantity: quantity || 1,
			settings: parseSettings($widget)
		}).done(function (response) {
			if (response && response.success) {
				setAcceptedState($widget, true);
				setStatus($widget, '', false);
				$widget.find(selectors.button)
					.prop('disabled', true)
					.hide();
				$widget.find(selectors.dismiss).hide();
				$widget.removeClass('bewia-ai-upsell-loading');
				$(document.body).trigger('added_to_cart', [
					response.data && response.data.fragments && response.data.fragments.fragments ? response.data.fragments.fragments : {},
					response.data ? response.data.cart_hash : '',
					$widget.find(selectors.button)
				]);
				$(document.body).trigger('update_checkout');
				showSuccessState($widget, response.data && response.data.message ? response.data.message : (parseSettings($widget).success_message || BEWIAUpsell.successText || 'Added to cart.'));
				return;
			}

			setStatus($widget, response && response.data && response.data.message ? response.data.message : (BEWIAUpsell.errorText || 'Unable to add offer.'), true);
		}).fail(function () {
			setStatus($widget, BEWIAUpsell.errorText || 'Unable to add offer.', true);
		}).always(function () {
			$widget.removeClass('is-adding');
		});
	}

	function initWidget($widget) {
		if ($widget.data('bewiaReady')) {
			return;
		}

		$widget.data('bewiaReady', true);
		setAcceptedState($widget, false);
		$widget.find(selectors.dismiss).toggle(parseSettings($widget).show_dismiss === 'yes');
		requestUpsell($widget);
	}

	$(selectors.widget).each(function () {
		initWidget($(this));
	});

	$(document.body).on('click', selectors.button, function (event) {
		var $button = $(this);
		var $widget = $button.closest(selectors.widget);

		event.preventDefault();

		if ($button.is(':disabled')) {
			return;
		}

		acceptUpsell($widget, parseInt($button.attr('data-product-id'), 10) || 0, parseInt($button.attr('data-quantity'), 10) || 1);
	});

	$(document.body).on('click', selectors.dismiss, function (event) {
		var $button = $(this);
		var $widget = $button.closest(selectors.widget);

		event.preventDefault();

		if ($button.is(':disabled') || $widget.hasClass('is-loading') || $widget.hasClass('is-adding')) {
			return;
		}

		dismissUpsell($widget, parseInt($button.attr('data-product-id'), 10) || 0);
	});

	$(document.body).on('updated_checkout', function () {
		$(selectors.widget).each(function () {
			var $widget = $(this);

			if (isAccepted($widget)) {
				return;
			}

			requestUpsell($widget);
		});
	});
});
