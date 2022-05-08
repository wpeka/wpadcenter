/*global obj*/

Vue.component( 'HeaderSection', {
	render( createElement ) {
		return createElement( 'header', {
			staticClass: 'adc-header-section',
		}, [ createElement( 'h1', {
			staticClass: 'adc-header-logo',
		}, [ createElement( 'div', {
			staticClass: 'adc-logo',
		}, [ createElement( 'div', {
			staticClass: 'adc-bg-img',
		} ) ] ) ] ) ] );
	},
} );

Vue.component( 'WelcomeSection', {
	render( createElement ) {
		return createElement( 'div', {
			staticClass: 'adc-welcome-section',
		}, [ createElement( 'div', {
			staticClass: 'adc-section-title',
		}, [ createElement( 'p', {
			staticClass: 'adc-title-heading',
			domProps: {
				textContent: obj.welcome_text,
			},
		} ), createElement( 'p', {
			staticClass: 'adc-title-subheading',
			domProps: {
				textContent: obj.welcome_subtext,
			},
		} ) ] ),
		createElement( 'div', {
			staticClass: 'adc-section-content',
		}, [ createElement( 'p', {
			domProps: {
				innerHTML: obj.welcome_description,
			},
		} ), createElement( 'p', {
			domProps: {
				innerHTML: obj.welcome_sub_desc,
			},
		} ) ] ) ] );
	},
} );

Vue.component( 'SettingsSection', {
	methods: {
		createSettingsItems: function( createElement, items ) {
			var html = [];
			items.forEach( ( value, index ) => {
				if ( index < 2 ) {
					var el = createElement( 'li', {
						domProps: {
							innerHTML: value,
						},
					} );
				} else if ( this.$parent.is_pro ) {
					var el = createElement( 'li', {
						domProps: {
							innerHTML: value,
						},
					} );
				} else {
					var el = createElement( 'li', {
						domProps: {
							innerHTML: value + ' <sup>Pro</sup>',
						},
					} );
				}
				html.push( el );
			} );
			return html;
		},
	},
	render( createElement ) {
		var self = this;
		return createElement( 'div', {
			staticClass: 'adc-settings-section',
		}, [ createElement( 'div', {
			staticClass: 'adc-section-content',
		}, [ createElement( 'p', {
			domProps: {
				textContent: obj.configure.text,
			},
		} ), createElement( 'ul', {
			staticClass: 'adc-settings-items',
		}, [ self.createSettingsItems( createElement, obj.configure.settings_items ) ] ), createElement( 'a', {
			staticClass: 'adc-button',
			domProps: {
				textContent: obj.configure.button_text,
				href: obj.configure.url,
				target: '_blank',
			},
		} ) ] ) ] );
	},
} );

var adcapp = new Vue( {
	el: '#adc-gettingstarted-app',
	data: {
		is_pro: obj.is_pro,
		disabled: obj.disabled,
	},
	render( createElement ) {
		return createElement( 'div', {
			staticClass: 'adc-container',
		}, [ createElement( 'header-section' ),
			createElement( 'div', {
				staticClass: 'adc-container-main',
			}, [ createElement( 'welcome-section' ), createElement( 'settings-section' ) ] ),

			createElement( 'div', {
				staticClass: 'adc-container-basic',
			}, [] ) ] );
	},
} );

