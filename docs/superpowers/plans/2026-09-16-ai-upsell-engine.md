# AI Upsell Engine Implementation Plan

> **For agentic workers:** REQUIRED SUB-SKILL: Use superpowers:subagent-driven-development (recommended) or superpowers:executing-plans to implement this plan task-by-task. Steps use checkbox (`- [ ]`) syntax for tracking.

**Goal:** Completar el motor AI de upsells de `briefcasewp-extras`, excluyendo la integración Bricks.

**Architecture:** Mantener `BEWIA_AI_Upsell_Engine` como fachada y extender sus proveedores Rules, AI y Hybrid con contexto normalizado, validación y fallback local. Separar ranking, copy, variantes y analítica para que Elementor, shortcode y AJAX consuman contratos estables.

**Tech Stack:** PHP WordPress/WooCommerce, MySQL mediante `$wpdb`/`dbDelta`, JavaScript jQuery, AJAX WordPress, PHPUnit/WP test harness si está disponible.

**Spec:** `docs/superpowers/specs/2026-09-16-ai-upsell-engine-design.md`

## Global Constraints

- Bricks queda fuera de alcance; no modificar ni crear integración para el plugin Bricks.
- Rules debe seguir funcionando sin API externa.
- Las claves y llamadas AI permanecen en servidor.
- Todo producto recomendado debe existir, estar publicado, ser comprable y no estar en el carrito.
- Los errores AI/copy deben degradar a reglas y textos manuales.
- No enviar PII al proveedor externo.

---

### Task 1: Normalizar contexto y señales de checkout

**Files:**
- Modify: `modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-engine.php`
- Modify: `modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-ajax.php`
- Modify: `modules/woo-checkout/assets/js/bcwp-ai-upsell.js`
- Test: `tests/ai-upsells/test-context.php`

**Interfaces:**
- Produce `BEWIA_AI_Upsell_Engine::get_cart_context()` with `cart_total`, `product_ids`, `category_ids`, `is_logged_in`, `customer_type`, `device_type`, `coupon_codes`, `checkout_elapsed_seconds`, `location_country`.
- AJAX may accept only non-sensitive aggregated frontend signals: `scroll_depth` and `checkout_started_at`.

- [ ] Add server-side context fields using WooCommerce session, cart coupons, customer state and coarse country data.
- [ ] Add bounded validation for frontend scroll/time values and ignore malformed values.
- [ ] Add tests for empty cart, guest/logged-in users, coupon presence and invalid client signals.
- [ ] Run the focused test file and PHP syntax checks.

### Task 2: Real AI provider contract and secure configuration

**Files:**
- Modify: `modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-ai-provider.php`
- Modify: `modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-engine.php`
- Modify: `modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-analytics.php`
- Create: `modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-ai-client.php`
- Test: `tests/ai-upsells/test-ai-provider.php`

**Interfaces:**
- `BEWIA_AI_Upsell_AI_Client::recommend( array $payload ): array|WP_Error`.
- Provider returns normalized recommendations containing `product_id`, `confidence`, and `provider_metadata`.

- [ ] Add server-side settings for provider, endpoint, model, timeout and encrypted/hidden API key handling consistent with existing plugin settings.
- [ ] Implement a WordPress HTTP client with timeout, response-size limit and `WP_Error` handling.
- [ ] Send only normalized cart/product/context data and candidate IDs.
- [ ] Validate JSON, confidence range, candidate membership, product status and purchasability.
- [ ] Ensure AI failure invokes the existing Rules fallback.
- [ ] Test success, timeout, malformed JSON, invalid product and missing credentials.

### Task 3: Hybrid ranking and AI copy generation

**Files:**
- Modify: `modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-hybrid-provider.php`
- Create: `modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-copy-generator.php`
- Modify: `modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-engine.php`
- Test: `tests/ai-upsells/test-hybrid-and-copy.php`

**Interfaces:**
- `BEWIA_AI_Upsell_Copy_Generator::generate( $product, array $context, array $settings ): array|WP_Error`.
- Copy result keys: `title`, `description`, `cta`, `urgency`.

- [ ] Combine manual priority/rule constraints with AI confidence without allowing AI to bypass exclusions.
- [ ] Add copy cache keyed by product, locale, context bucket and prompt version.
- [ ] Enforce maximum lengths and sanitize all generated fields.
- [ ] Use manual fallback title, description and button text on every failure.
- [ ] Add filters for custom AI clients/providers without exposing credentials.
- [ ] Test cache hit, cache miss, fallback copy and invalid generated fields.

### Task 4: A/B campaign and stable variant assignment

**Files:**
- Create: `modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-experiments.php`
- Modify: `modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-analytics.php`
- Modify: `modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-ajax.php`
- Modify: `modules/woo-checkout/widgets/class-bcwp-elementor-ai-upsell.php`
- Test: `tests/ai-upsells/test-experiments.php`

**Interfaces:**
- `BEWIA_AI_Upsell_Experiments::assign_variant( string $campaign_key, string $session_id, array $variants ): array`.
- Variant carries `variant_id`, `product_ids`, `layout`, and copy overrides.

- [ ] Add Elementor controls for optional campaign key and variant definitions.
- [ ] Assign deterministically from campaign/session hash and persist through checkout refreshes.
- [ ] Keep product/layout/copy variants within configured allowlists.
- [ ] Include campaign and variant IDs in every analytics event.
- [ ] Test stable assignment, refresh persistence, invalid variants and disabled experiments.

### Task 5: Confirmed order attribution and analytics dashboard

**Files:**
- Modify: `modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-analytics.php`
- Modify: `modules/woo-checkout/ai-upsells/class-bcwp-ai-upsell-ajax.php`
- Modify: `modules/woo-checkout/assets/js/bcwp-ai-upsell.js`
- Test: `tests/ai-upsells/test-order-attribution.php`

**Interfaces:**
- Analytics rows add `campaign_key`, `variant_id`, `confidence`, `customer_type`, `device_type`, `country`, and `order_id` where available.
- `BEWIA_AI_Upsell_Analytics::confirm_order_revenue( $order_id, $order )` confirms accepted offer revenue from order line items.

- [ ] Extend schema through `dbDelta` without breaking existing installations.
- [ ] Store offer identity in order metadata during checkout and confirm only after order processing.
- [ ] Attribute actual line-item totals, not estimated product price.
- [ ] Add dashboard comparisons for AI, Hybrid, Rules and variants.
- [ ] Preserve CSV export and existing filters.
- [ ] Test duplicate confirmation, refunds/failed orders, multiple offers and missing metadata.

### Task 6: Frontend integration and regression verification

**Files:**
- Modify: `modules/woo-checkout/assets/js/bcwp-ai-upsell.js`
- Modify: `modules/woo-checkout/assets/css/bcwp-ai-upsell.css`
- Modify: `modules/woo-checkout/widgets/class-bcwp-elementor-ai-upsell.php`
- Test: `tests/ai-upsells/test-frontend-contract.php`

- [ ] Send bounded scroll/time context with recommendation requests.
- [ ] Render generated copy and urgency safely for checkbox/card/minimal layouts.
- [ ] Preserve loading, dismiss, add-to-cart and `updated_checkout` behavior.
- [ ] Prevent duplicate shown/rendered events during checkout refreshes.
- [ ] Verify Elementor editor preview remains static and frontend remains dynamic.
- [ ] Run all focused tests, PHP lint, JavaScript lint if configured, and manual checkout smoke test.

## Verification Checklist

- [ ] Rules mode works with no AI credentials.
- [ ] AI mode falls back safely on every provider error.
- [ ] No PII reaches the external provider.
- [ ] Product validation prevents arbitrary cart additions.
- [ ] A/B assignment remains stable for one checkout session.
- [ ] Confirmed revenue comes from completed orders only.
- [ ] Existing shortcode and Elementor widget remain backward compatible.
- [ ] Bricks files are not changed.
