import Vue from 'vue';

Vue.component('HeaderSection', {
    render(createElement) {
        return createElement('header', {
            staticClass: 'adc-header-section'
        }, [createElement('h1',{
            staticClass: "adc-header-logo"
        },[createElement('div',{
            staticClass: "adc-logo"
        },[createElement('div',{
            staticClass: "adc-bg-img"
        })])])]);
    }
});

Vue.component('WelcomeSection', {
    render(createElement) {
        return createElement('div', {
            staticClass: 'adc-welcome-section'
        }, [createElement('div', {
            staticClass: 'adc-section-title'
        }, [createElement('p', {
            staticClass: 'adc-title-heading',
            domProps: {
                textContent: obj.welcome_text
            }
        }), createElement('p', {
            staticClass: 'adc-title-subheading',
            domProps: {
                textContent: obj.welcome_subtext
            }
        })]),
            createElement('div', {
                staticClass: 'adc-section-content'
            }, [createElement('p', {
                domProps: {
                    innerHTML: obj.welcome_description
                }
            }),createElement('p', {
                domProps: {
                    innerHTML: obj.welcome_sub_desc
                }
            })])]);
    }
});

Vue.component('SettingsSection', {
    methods: {
        createSettingsItems: function(createElement, items) {
            var html = [];
            items.forEach((value, index) => {
                if(index < 2 ) {
                    var el = createElement('li', {
                        domProps: {
                            innerHTML: value
                        }
                    });
                } else if(this.$parent.is_pro) {
                    var el = createElement('li', {
                        domProps: {
                            innerHTML: value
                        }
                    });
                } else {
                    var el = createElement('li', {
                        domProps: {
                            innerHTML: value + ' <sup>Pro</sup>'
                        }
                    });
                }
                html.push(el);
            });
            return html;
        },
    },
    render(createElement) {
        var self = this;
        return createElement('div', {
            staticClass: 'adc-settings-section'
        }, [createElement('div', {
            staticClass: 'adc-section-content'
        }, [createElement('p',{
            domProps: {
                textContent: obj.configure.text
            }
        }), createElement('ul',{
            staticClass: 'adc-settings-items'
        }, [self.createSettingsItems(createElement, obj.configure.settings_items)]),createElement('a', {
            staticClass: 'adc-button',
            domProps: {
                textContent: obj.configure.button_text,
                href:obj.configure.url,
                target:'_blank'
            }
        })])]);
    }
});



var adcapp = new Vue({
    el: '#adc-gettingstarted-app',
    data: {
        is_pro: obj.is_pro,
        disabled: obj.disabled,
    },
    render(createElement) {
        return createElement('div',{
            staticClass: 'adc-container'
        },[createElement('header-section'),
            createElement('div',{
                staticClass: 'adc-container-main'
            },[createElement('welcome-section'), createElement('settings-section')]),
           
            createElement('div',{
                staticClass: 'adc-container-basic'
            },[])]);
    }
});

var adcvm = new Vue({
    el: '#adc-mascot-app',
    data: function() {
        return {
            showMenu: !1,
            isPro:obj.is_pro
        }
    },
    computed: (
        {
            boxClass() {
                return {
                    'adc-mascot-quick-links adc-mascot-quick-links-open' : this.showMenu,
                    'adc-mascot-quick-links' : !this.showMenu,
                }
            },
            menuItems() {
                var mItems = [
                    {
                        icon: 'dashicons-lightbulb',
                        tooltip: obj.menu_items.support_text,
                        link: obj.menu_items.support_url,
                        key: 'support'
                    },
                    {
                        icon: 'dashicons-info',
                        tooltip: obj.menu_items.faq_text,
                        link: obj.menu_items.faq_url,
                        key: 'faq'
                    },
                    {
                        icon: 'dashicons-sos',
                        tooltip: obj.menu_items.documentation_text,
                        link: obj.menu_items.documentation_url,
                        key: 'documentation'
                    }
                ];
                if(!this.isPro) {
                    mItems.push({
                        icon: 'dashicons-star-filled',
                        tooltip: obj.menu_items.upgrade_text,
                        link: obj.menu_items.upgrade_url,
                        key: 'upgrade'
                    });
                }
                return mItems;
            }
        }
    ),
    methods:{
        buttonClick: function(){
            this.showMenu = !this.showMenu;
        },
        renderElements:function(createElement) {
            var html = [];
            if(this.showMenu) {
                this.menuItems.forEach((value, index) => {
                    html.push(createElement('a', {
                        key: value.key,
                        class: this.linkClass(value.key),
                        attrs: {
                            href: value.link,
                            'data-index': index,
                            target: '_blank'
                        }
                    }, [createElement('span', {
                        class: 'dashicons '+ value.icon
                    }), createElement('span', {
                        staticClass: 'adc-mascot-quick-link-title',
                        domProps: {
                            innerHTML: value.tooltip
                        }
                    })]));
                })
            }
            return html;
        },
        linkClass: function(key) {
            return 'adc-mascot-quick-links-menu-item adc-mascot-quick-links-item-' + key;
        },
        enter:function(t,e) {
            var n = 50 * t.dataset.index;
            setTimeout((function() {
                t.classList.add('adc-mascot-show'),
                    e()
            }), n)
        },
        leave:function(t,e) {
            t.classList.remove('adc-mascot-show'),
                setTimeout((function() {
                    e()
                }), 200)
        }
    },
    render(createElement){
        return createElement('div',{
            class: this.boxClass,
        }, [
            createElement('button', {
                class: 'adc-mascot-quick-links-label',
                on: {
                    click: this.buttonClick
                }
            },[
                createElement('span', {
                    class:'adc-mascot-bg-img adc-mascot-quick-links-mascot',
                }),
                createElement('span',{
                    class: 'adc-mascot-quick-link-title'
                }, obj.quick_links_text)
            ]),
            createElement('transition-group', {
                staticClass: 'adc-mascot-quick-links-menu',
                attrs:{
                    tag: 'div',
                    name: 'adc-staggered-fade'
                },
                on: {
                    enter: this.enter,
                    leave: this.leave
                }
            }, this.renderElements(createElement))
        ]);
    },
});