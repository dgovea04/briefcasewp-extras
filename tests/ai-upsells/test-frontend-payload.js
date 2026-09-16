const fs = require('fs');
const vm = require('vm');

const script = fs.readFileSync('modules/woo-checkout/assets/js/bcwp-ai-upsell.js', 'utf8');
const posts = [];
const widgetData = {};

function element() {
  return {
    attr() { return this; }, find() { return element(); }, text() { return this; },
    toggleClass() { return this; }, prop() { return this; }, removeAttr() { return this; },
    hide() { return this; }, show() { return this; }, html() { return this; }, empty() { return this; },
    addClass() { return this; }, removeClass() { return this; }, toggle() { return this; },
    slideUp(_, callback) { if (callback) callback(); return this; }, closest() { return this; },
    is() { return false; }, on() { return this; }, trigger() { return this; },
    data(key, value) { if (arguments.length === 2) { widgetData[key] = value; return this; } return widgetData[key]; }
  };
}

const widget = element();
widget.attr = function (name, value) {
  if (arguments.length === 2) { widgetData[name] = value; return widget; }
  if (name === 'data-settings') return '{}';
  if (name === 'data-layout') return 'card';
  return widgetData[name];
};
widget.find = () => element();

function $(target) {
  if (typeof target === 'function') { target($); return; }
  if (target === global.document) return { height: () => 1000 };
  if (target === global.window) return { scrollTop: () => 100, height: () => 400 };
  if (target === global.document.body) return element();
  if (target === '.bewia-ai-smart-wrapper') return { each(callback) { callback.call(widget); } };
  if (target === widget) return widget;
  return element();
}
$.post = (url, payload) => {
  posts.push({ url, payload });
  return { done() { return this; }, fail() { return this; }, always(callback) { if (callback) callback(); return this; } };
};

global.window = { setTimeout() {} };
global.document = { body: {} };
global.BEWIAUpsell = { ajax_url: '/wp-admin/admin-ajax.php', nonce: 'nonce' };
global.jQuery = $;

vm.runInThisContext(script, { filename: 'bcwp-ai-upsell.js' });
const request = posts.find((post) => post.payload.action === 'bcwp_get_ai_upsell');
if (!request) throw new Error('Expected an upsell AJAX request.');
if (!Number.isInteger(request.payload.scroll_depth) || request.payload.scroll_depth < 0 || request.payload.scroll_depth > 100) throw new Error('Expected a bounded scroll_depth payload.');
if (!Number.isInteger(request.payload.checkout_started_at) || request.payload.checkout_started_at <= 0) throw new Error('Expected checkout_started_at in the payload.');
console.log('Frontend payload test passed.');