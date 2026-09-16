# BriefcaseWP AI Upsell Engine — Diseño

## Alcance

Este diseño amplía el motor de upsells de `briefcasewp-extras` según el PRD. El alcance incluye WooCommerce, el widget Elementor existente, AJAX, proveedores de recomendación, generación de copy, A/B testing y analítica. La integración Bricks queda explícitamente fuera de alcance porque pertenece a otro plugin independiente.

## Objetivos

- Mantener Rules como fallback funcional sin API externa.
- Incorporar un proveedor AI real, configurable y seguro.
- Capturar señales útiles sin almacenar datos personales innecesarios.
- Permitir variantes de producto, copy y layout con asignación estable por sesión.
- Medir impresiones, renderizado, aceptación, fallos, pedidos confirmados y revenue atribuido.
- Mantener compatibilidad con el widget Elementor y el shortcode actuales.

## Arquitectura

El `BEWIA_AI_Upsell_Engine` seguirá siendo la fachada. Recibirá un contexto normalizado y resolverá un proveedor según el modo `rules`, `ai` o `hybrid`. Rules seguirá calculando recomendaciones localmente. AI construirá una solicitud estructurada, validará la respuesta y devolverá productos existentes; cualquier error, timeout, respuesta inválida o API no configurada activará fallback a Rules.

El copy se resolverá separadamente del ranking. Primero se buscará una variante A/B; después copy cacheado; luego proveedor AI; y finalmente textos manuales del widget. Ningún texto generado se imprimirá sin sanitización y límites de longitud.

El A/B testing usará una variante persistente por sesión y campaña/widget. La asignación no cambiará durante el checkout. Los eventos conservarán campaña, variante, modo, provider, producto y contexto suficiente para comparar resultados.

## Contexto y privacidad

El contexto incluirá carrito, total, categorías, dispositivo, estado de login, clasificación nuevo/recurrente, ubicación aproximada disponible en WordPress/WooCommerce, cupón activo, tiempo desde entrada al checkout y señales frontend agregadas como profundidad máxima de scroll. No se enviarán nombres, emails ni direcciones completas al proveedor AI.

## Contrato del proveedor AI

El proveedor recibirá JSON con señales normalizadas y una lista de productos candidatos. La respuesta aceptada contendrá `product_id`, `confidence` opcional y razones/metadatos no visibles. El servidor verificará que el producto sea válido, comprable, candidato y no esté en el carrito. Las credenciales se leerán de configuración segura; nunca del frontend.

## Flujo

1. Elementor/shortcode renderiza el contenedor.
2. AJAX solicita la recomendación con nonce y configuración normalizada.
3. El servidor construye contexto y resuelve Rules, AI o Hybrid.
4. Se selecciona variante, copy y layout permitidos.
5. Se registra `shown`/`rendered`.
6. La aceptación añade el producto mediante WooCommerce y registra el resultado.
7. Al procesar el pedido se confirma la atribución y el revenue real.

## Seguridad y errores

- Nonce, sanitización, capacidades administrativas y validación de productos en cada endpoint.
- Timeouts cortos y errores no bloqueantes para proveedores externos.
- Fallback local cuando AI o copy AI no estén disponibles.
- Deduplicación de eventos para evitar doble conteo en `updated_checkout`.
- No revelar claves API ni mensajes internos al cliente.

## Pruebas

Se cubrirán normalización de contexto, reglas, validación de respuestas AI, fallback, asignación estable de variantes, endpoints AJAX, deduplicación y atribución de revenue. También se hará lint PHP/JS y una comprobación manual del checkout Elementor.

## Fuera de alcance

- Elemento Bricks y sus controles.
- Popup/sidebar genérico fuera del checkout Elementor.
- Entrenamiento de modelos propios.
- Predicción estadística avanzada antes de disponer de datos suficientes.
