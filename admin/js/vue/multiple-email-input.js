var multipleEmailInput = {
	template: `
    <div>
        <input v-show="hide" id="global_email_recipients_field" name="global_email_recipients_field" ref="global_email_recipients" class="form-control" ></input>
        <input type="text" class="form-control" id="email" @keyup="emailKeyUp($event)" @blur="printEmailList($event.target.value)">
        <div v-show="emailList.length > 0" id="show-emails"><ul style="styleListContainer">
            <li  v-for="(value, index) in emailList" :key="index" v-bind:style="styleList">{{value}}<span class='float-right remove' @click="removeEmail" v-bind:data-index="index">X</span></li>
            </ul>
        </div>
    </div>
    `,
	data() {
		return {
			hide: false,
			emailList: [],
			recipientList: '',
			settings: {
				color: '#343a40',
				textColor: '#000000',
				fontAwesome: false,
			},
			styleList: '',
			styleListContainer: '',

		};
	},
	methods: {
		uniqueEmails: function( emails ) {
			let uniqueEmails = [];
			j.each( this.emailList, function( i, el ) {
				if ( j.inArray( el, uniqueEmails ) === -1 ) {
					uniqueEmails.push( el );
				}
			} );

			return uniqueEmails;
		},

		emailKeyUp: function( e ) {
			j( '.email-error' ).remove();
			var keynum;
			if ( window.event ) { // IE
				keynum = e.keyCode;
			} else if ( e.which ) { // Netscape/Firefox/Opera
				keynum = e.which;
			}

			if ( keynum == 188 ) {
				let email = j( '#email' ).val().replace( ',', '' );

				this.printEmailList( email );
			}
		},

		printEmailList: function( email ) {
			const re = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;

			if ( re.test( String( email ).toLowerCase() ) ) {
				this.emailList.push( email );
				this.recipientList = this.uniqueEmails( this.emailList ).toString();
				this.emailList = this.uniqueEmails( this.emailList );
				this.$refs.global_email_recipients.value = this.uniqueEmails( this.emailList ).toString();
				j( '#email' ).val( '' );
			} else {
				var errMessage = '';
				if ( j( '#email' ).val() == '' ) {
					errMessage = j( '#wpadcenter_email_recipient_warning_data' ).data( 'empty' );
				} else {
					errMessage = j( '#wpadcenter_email_recipient_warning_data' ).data( 'invalid' );
				}
				if ( ( ! j( '.email-error' )[0] && this.uniqueEmails( this.emailList ).length && j( '#email' ).val() != '' ) || ( ! j( '.email-error' )[0] && ! this.uniqueEmails( this.emailList ).length ) ) {
					let errrMessage = "<div class='email-error'>" + errMessage + '</div>';
					if ( j( '#show-emails' ).length ) {
						j( '#show-emails' ).after( errrMessage );
					} else {
						j( '#email' ).parent().after( errrMessage );
					}
				}
			}
		},
		hexToRgbA: function( hex ) {
			var c;
			if ( /^#([A-Fa-f0-9]{3}){1,2}$/.test( hex ) ) {
				c = hex.substring( 1 ).split( '' );
				if ( c.length == 3 ) {
					c = [ c[0], c[0], c[1], c[1], c[2], c[2] ];
				}
				c = '0x' + c.join( '' );
				return 'rgba(' + [ ( c >> 16 ) & 255, ( c >> 8 ) & 255, c & 255 ].join( ',' ) + ',0.08)';
			}
			throw new Error( 'Bad Hex' );
		},
		removeEmail: function( e ) {
			let index = e.target.getAttribute( 'data-index' );
			this.emailList.splice( index, 1 );
			this.$refs.global_email_recipients.value = this.uniqueEmails( this.emailList ).toString();
		},
	},
	mounted() {
		var that = this;
		var savedRecipientList = j( '#wpadcenter_email_recipient_warning_data' ).data( 'recipients' );
		var List = savedRecipientList.split( ',' );
		List.forEach( function( email ) {
			if ( email ) {
				that.printEmailList( email );
			}
		} );
		this.recipientList = savedRecipientList;
		this.styleList = {
			backgroundColor: this.hexToRgbA( this.settings.color ),
			borderLeft: '3px solid ' + this.settings.color,
		};
		this.styleListContainer = {
			color: this.settings.textColor,
		};
	},
};

