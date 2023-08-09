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
		} ) ] ),
		createElement( 'div', {
			staticClass: 'adc-section-content',
		}, [ createElement( 'p', {
			domProps: {
				innerHTML: obj.welcome_description,
			},
		} ) ] ) ] );
	},
} );

Vue.component('VideoSection', {
    render(createElement) {
        return createElement('div', {
            staticClass: 'adc-video-section'
        }, [createElement('iframe', {
            attrs: {
                width: '746',
                height: '350',
                src: obj.video_url
            },
        })]);
    }
});

Vue.component( 'HelpSection', {
	methods: {
        createHelpCards: function(createElement) {
            var helpCards = [];
            for (const [key, value] of Object.entries(obj.help_section)){

                var helpCard = [createElement('div', {
                    staticClass: 'adc-help-card'
                },[createElement('div', {
                    staticClass: 'adc-help-card-top',
                    },[createElement('div', {
                    staticClass: 'adc-help-card-icon',
                    domProps: {
                        innerHTML: '<img class="adc-help-img" src='+ value.image_src + key + '.png >'
                    }
                }),
                createElement('div', {
                    staticClass: 'adc-help-card-description'
                },
                [createElement('h3', {
                    staticClass: 'adc-help-card-heading',
                    domProps: {
                        innerHTML: value.title
                    }
                }),
                createElement('p', {
                    staticClass: 'adc-help-card-summary',
                    domProps: {
                        innerHTML: value.description
                    }
                }),])]),
                createElement('p', {
                    staticClass: 'adc-help-card-link',
                    domProps: {
                        innerHTML: '<a  target="_blank" href=' + value.link +' >' + value.link_title + '</a>'
                    }
                })])];
                helpCards.push(helpCard);
            };
            return helpCards;

        }
    },
    render(createElement) {
        var self = this;
        return createElement('div', {
            staticClass: 'adc-help-section',
            attrs:{
                display: 'flex',
                justifyContent:'space-between',
            }
        }, [self.createHelpCards(createElement)]);
    }
} );

Vue.component('NextStepsSection', {
    render(createElement) {
        return createElement('div', {
            staticClass: 'adc-next-steps-section'
        }, [
            createElement('p', {
                staticClass: 'adc-next-steps-title-heading',
                domProps: {
                    textContent: obj.next_steps_title
                }
            }),createElement('a', {
                staticClass: 'adc-button',
                domProps: {
                    textContent: obj.configure_settings.text,
                    href: obj.configure_settings.url
                }
            }), createElement('a', {
                staticClass: 'adc-button adc-button-small',
                domProps: {
                    textContent: obj.create_ad.text,
                    href: obj.create_ad.url
                }
            }), createElement( 'p', {
                staticClass: 'adc-next-steps-section-link',
                domProps: {
                    innerHTML: '<a  target="_blank" href=' + obj.tutorial.url +' >' + obj.tutorial.text + '</a>'
                }
            } )
        ]);
    }
});

Vue.component('FeatureSection', {
    methods: {
        createFeatureCards(createElement, start, end) {
            var featureCards = []
            for(var i=start; i<end; i++) {
                var featureCard = [
                    createElement('li', {
                        staticClass: 'adc-feature-section-card-list-item',
                        domProps: {
                            textContent: obj.features[i]
                        }
                    })
                ];
                featureCards.push(featureCard);
            }
            return createElement('ul', {
                staticClass: 'adc-feature-section-card-list'
            }, [featureCards]);
        }
    },
    render(createElement) {
        var self = this;
        if( !obj.is_pro ) {
            return createElement('div', {
                staticClass: 'adc-feature-section',
            }, [
                createElement('p', {
                    staticClass: 'adc-title-heading',
                    domProps: {
                        textContent: obj.features_title
                    }
                }),
                createElement('div', {
                    staticClass: 'adc-feature-section-content'
                }, [
                    self.createFeatureCards( createElement, 0, 4 ),
                    self.createFeatureCards( createElement, 4, 8 )
                ]),
                createElement('a', {
                    staticClass: 'adc-button',
                    domProps: {
                        textContent: obj.upgrade_button.text,
                        href: obj.upgrade_button.url
                    }
                }),
                createElement('p', {
                    staticClass: 'adc-feature-section-coupon',
                }, [createElement('span', {
                    domProps: {
                        textContent: obj.coupon_text.limited_offer_text
                    }
                }), createElement('span', {
                    staticClass: 'adc-feature-section-coupon-code',
                    domProps: {
                        textContent: obj.coupon_text.coupon_code
                    }
                }), createElement('span', {
                    domProps: {
                        textContent: obj.coupon_text.discount_text
                    }
                })])
            ])
        }
    }
})


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
			}, [ createElement( 'welcome-section' ), createElement( 'video-section' ), createElement( 'help-section' ), createElement( 'next-steps-section' ), createElement( 'feature-section' ) ] ),
			createElement( 'div', {
				staticClass: 'adc-container-basic',
			}, [] ) ] );
	},
} );

