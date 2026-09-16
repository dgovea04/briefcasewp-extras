<?php

class Bewaa_I18n {

	public function load_plugin_textdomain() {

		load_plugin_textdomain(
			'bewaa',
			false,
			dirname( dirname( plugin_basename( __FILE__ ) ) ) . '/languages/'
		);

	}

}
