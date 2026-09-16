/*global alert, ajaxurl, console */
(function($){
	"use strict";

	var $body;

	window.BEW_ADMIN = {
		//run after DOM is loaded
		onReady : function(){
			$body = $(document.body);
			this.tabs();
			this.importer();
			this.activate();
			this.settings();
		},

		tabs : function(){
			var tabs = $('nav.nav-tab-wrapper').children();

			if (tabs.length) {
				var sections = $('div.bew-settings-section'),
					active_tab_class = 'nav-tab-active',
				    active_section_class = 'active-section',
					tabsClick               = function (event) {
						event.preventDefault();
						event.currentTarget.focus(); // Safari does not focus the tab automatically
					},

					tabsFocus               = function () { // Using focus event to enable navigation by tab key
						var hrefWithoutHash = location.href.replace(/#.*/, '');

						history.pushState({}, '', hrefWithoutHash + this.hash);

						goToSettingsTabFromHash();
					},

					goToSettingsTabFromHash = function () {
						var hash = location.hash.slice(1);

						if (hash) {
							goToSettingsTab(hash);
						}
					},

					goToSettingsTab         = function (tabName) {
						var $active_section = $('#'+tabName),
							$active_tab = $('#bew-settings-'+tabName);

						if (!$active_section.length) {
							return;
						}
						//switch tabs
						tabs.removeClass(active_tab_class);
						$active_tab.addClass(active_tab_class);

						//switch sections
						sections.removeClass(active_section_class);
						$active_section.addClass(active_section_class);
					};

				//bind events
				tabs.on({
					click: tabsClick,
					focus: tabsFocus
				});

				//init
				goToSettingsTabFromHash();
			}
		},

		importer : function(){
			var import_button = $('#start-import'),
				progress_bar  = $('div.import_progress'),
				status = $('#demo_data_import_status'),
				error_count,last_response,
				
				startImport = function(){
					//reset
					error_count = 0;
					last_response = {
						level : '',
						sublevel:  ''
					};
										
					var check_password = passed_object.check_password;
					var check_password_confirm = passed_object.check_password_confirm;
					
					import_button.prop('disabled', true);

					//start progress bar
					progress_bar.addClass('running');
					
					//start confirm account
					if(check_password == "no"){ 
						if(check_password_confirm == "no"){ 
						loginBriefcase();
						}else{
							//start import
							if(check_password_confirm == "yes" || check_password == "yes" ){ 
							nextLevel('','');
							}		
						}
					}else {
						//start import
						if(check_password_confirm == "yes" || check_password == "yes" ){ 
						nextLevel('','');
						}	
					}
					
				},
				
				loginBriefcase = function(){
										
						$('#popup_confirm').addClass('show');						
						
						$('#close_popup').click(function(){	
								$('#popup_confirm').removeClass('show');								
								$("#demo_data_import_status").text("The Importer is not ready to start.");
								return;
						});
						
						$('#bew-extras-connect').click(function(){	
							var $box = $('#bew-extras-body');
							var confirm_password = $box.find('input[name="password"]').val();
							
							var data = {
							'action': 'bew_confirm_password', //the function in php functions to call
							'confirm_password': confirm_password,
							};		
							//send data to the php file admin-ajax which was stored in the variable ajaxurl
							$.post(frontEndAjax.ajaxurl, data, function( response ) {
								
								var message = response;
								
								if (message == 'connected0'){	
									$('#popup_confirm').removeClass('show');								 										
									//start import
									nextLevel('','');
															 
								} else {
									if ($('#no-right').length === 0) {
									 $('#bew-extras-body').append('<span id="no-right">the password is incorrect, try again</span>');	
									}
								}
							});
							
						});
				},
				
				nextLevel = function(level, sublevel){
					$.ajax({
						type: "POST",
						url: ajaxurl,
						data:  {
							action : 'bew_import_templates', //called in backend
							level : level,
							sublevel : sublevel
						},
						success: function(r) { //r = response
							if(r !== false ){
								console.log(r);
								//save last reply
								last_response = r;
								//reset error counter
								error_count = 0;

								setupStatus(r);

								if(r.is_it_end === false){//end of importing
									nextLevel(r.level, r.sublevel);
								}
								else{
									progress_bar.css('width','100%').removeClass('running');
									import_button.prop('disabled', false );
								}
							}
						},
						error: function(  jqXHR,  textStatus,  errorThrown ){
							var message;

							//check what type of error happened
							if(typeof jqXHR.status !=='undefined' && (jqXHR.status == 404 || jqXHR.status == 403)){
								message = 'Server returned status '+jqXHR.status+'. Hopefully it is temporary.';
							}
							else if(textStatus == 'parsererror'){
								message = 'Importer returned data in wrong format. Probably unexpected HTML leaked into server reply instead of JSON format.';
							}
							else if( errorThrown == 'Internal Server Error' ){
								message = 'Server returned "Internal Server Error" while importing. It usually mean misconfiguration in server/WordPress.';
							}
							else{
								message = 'Unknown error: '+textStatus+' - '+errorThrown;
							}

							//try to recover from error
							if( error_count < 10 ){
								//count this error
								error_count++;

								//wait and try again
								setTimeout(function(){nextLevel(last_response.level, last_response.sublevel);}, 5000);

								//done here
								return;
							}

							//report error
							alert( message );

							progress_bar.removeClass('running');
							import_button.prop('disabled', true);
						},
						dataType: 'json'
					});
				},

				setupStatus = function(r){
					var content = r.level_name;
					if(r.sublevel_name.length){
						content += ' - '+r.sublevel_name;
					}

					status.html(content);
					progress_bar.css('width',r.progress+'%');

					if(r.alert == true){
						alert(r.log);
					}
				};

			import_button.on( 'click', startImport);
		},
		
		activate : function(){
			var updated = $('#udmupdater_not_connected');
			var import_buttondc = $('#start-import');
			
			if (updated.length) {			
			$(".import-section").prepend("<div class='text_activate'>Activate Briefcase Elementor Widgets plugin to import the templates. Please enter your customer login and password at the top, then refresh the page.</div>");	
			import_buttondc.prop('disabled', true);
			$("#box_confirm").prepend("<div class='text_activate'>Activate Briefcase Elementor Widgets plugin to use Briefcase Extras Plugin. Please enter your customer login and password at the top, then refresh the page.</div>");	
			
			}
			
		},
		
		settings : function(){
			
			$('#bew-extras-connect-settings').click(function(){	
				var $box = $('#bew-extras-body-settings');
				var confirm_password = $box.find('input[name="password"]').val();
				alert(confirm_password);
				var data = {
				'action': 'bew_confirm_password', //the function in php functions to call
				'confirm_password': confirm_password,
				};		
				//send data to the php file admin-ajax which was stored in the variable ajaxurl
				$.post(frontEndAjax.ajaxurl, data, function( response ) {
								
					var message = response;
						
					if (message == 'connected'){															 										
						if ($('#right').length === 0) {
						$('#bew-extras-body-settings').append('<span id="right">Your Account is Confirmed</span>');
						}						 
					} else {
						if ($('#no-right').length === 0) {
						 $('#bew-extras-body').append('<span id="no-right">the password is incorrect, try again</span>');	
						}
					}
				});
							
			});
			
		}
	};

	var BEW_ADMIN = window.BEW_ADMIN;

	//start ADMIN
	$( function() {
		BEW_ADMIN.onReady();
	} );

})(jQuery);