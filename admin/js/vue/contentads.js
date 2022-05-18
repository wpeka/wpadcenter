/*global jQuery, localized_data*/
// jquery $ as j

const pro_ver_above_or_5_2_3 = localized_data.wpadcenter_pro_version.localeCompare( '5.2.3', undefined, { numeric: true, sensitivity: 'base' } ) >= 0;
const componentContentAds = {
	template:
        `<div class="contentAdsRules" v-if="active">
            <div class="rule" @click="onCollapseClick">
                <div class="rule_label">
                    <svg ref="wpadcenter_arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="10" role="presentation" class="vs__open-indicator"><path d="M9.211364 7.59931l4.48338-4.867229c.407008-.441854.407008-1.158247 0-1.60046l-.73712-.80023c-.407008-.441854-1.066904-.441854-1.474243 0L7 5.198617 2.51662.33139c-.407008-.441853-1.066904-.441853-1.474243 0l-.737121.80023c-.407008.441854-.407008 1.158248 0 1.600461l4.48338 4.867228L7 10l2.211364-2.40069z"></path></svg>
                    <label v-show="!pro_ver_above_or_5_2_3"> <span v-show="position_ !== 'in-feed'" >{{ post_ }} |</span> {{ position_ }} | {{ alignment_ }} | <span v-show="position_ !== 'in-feed'"> {{ adgroup_ }} </span> <span v-show="position_ === 'in-feed'"> {{ ad_ }} </span> </label>
                    <label v-show="pro_ver_above_or_5_2_3"> {{placement_name_}} | <span v-show="position_ !== 'in-feed'" >{{ post_ }} |</span> {{ position_ }} | {{ alignment_ }} | {{ adorgroup_ }}</label>
                </div>
                <c-button class="delete_rule" color="danger" @click.stop="onDeleteRule">Delete Rule</c-button>
            </div>
            <c-collapse :show="contentAdsRulesShow">
                <hr />
                <div class="placement_name" v-if="pro_ver_above_or_5_2_3">
                <label>Placement Name: </label>
                <input type="text" v-model="placement_name_" :id="getPlacementId('placement_name')" />
               </div>
                <input type="hidden" :name="getPlacementName('[name]')" v-model="placement_name_ ? placement_name_ : default_placement_name_" />
                <input type="hidden" :name="getPlacementName('[id]')" v-bind:value="placement_id_ ?placement_id_:new Date().getTime()" />
                <hr v-if="pro_ver_above_or_5_2_3"/>

                <label  v-show="position_ !== 'in-feed'" >Post Types: </label>
                <input v-show="position_ !== 'in-feed'" type="hidden" ref="Post_Selected" :name="getPlacementName('[post]')" v-model="post_selected_" />
                <v-select v-show="position_ !== 'in-feed'" :clearable="false" placeholder='Select Post And/or Pages' :options="PostOptions" multiple v-model="post_selected_" @input="onPostChange"></v-select>
                <hr v-show="position_ !== 'in-feed'" />

                <label>Placement type: </label>
                <div class="content-ads-alignment">    
                    <input type="radio" value="before-content" v-model="position_" :id="getPlacementId('before-content')" />
                    <label :for="getPlacementId('before-content')" >Before Content</label>
                    
                    <input type="radio" value="after-content" v-model="position_" :id="getPlacementId('after-content')"/>
                    <label :for="getPlacementId('after-content')" >After Content</label>
                    
                    <input type="radio" value="content" v-model="position_" :id="getPlacementId('content')"/>
                    <label :for="getPlacementId('content')" >Content</label>

                    <input type="radio" value="in-feed" v-model="position_" :id="getPlacementId('in-feed')"/>
                    <label :for="getPlacementId('in-feed')" >In Feed</label>

                </div>
                <input type="hidden" :name="getPlacementName('[type]')" v-model="position_" />
                <hr />
                <label v-show="position_ === 'content'">Content Options: </label>
                <div class="postition-content" v-show="position_ === 'content'">
                    <v-select :clearable="false" placeholder="Select Position" :options="positionOptions" v-model="position_selected_" label="key"></v-select>
				    <input type="hidden" :name="getPlacementName('[content][after-before]')" v-bind:value="position_ === 'content'?position_selected_.value:''" />
                    <input type="number" :name="getPlacementName('[content][number]')" v-model="number_"/>
                    <v-select :clearable="false" placeholder="Select Element" :options="elementOptions" v-model="element_selected_" label="key"></v-select>
                    <input type="hidden" :name="getPlacementName('[content][element]')" v-bind:value="position_ === 'content'?element_selected_.value:''" />                   
                </div>

                <div class="postition-in-feed" v-show="position_ === 'in-feed'">
                Before: <input type="number" :name="getPlacementName('[in-feed][number]')" v-model="in_feed_number_" min = "1" style="width: 120px;"  /> post   <br>       
                <label style="margin-top: 6px;">The page on which infeed will be displayed: {{ blog_page }}</label>
                </div>
                <hr v-show="position_ === 'in-feed'" />

                <input :id="getPlacementId('position-reverse')" type="checkbox" ref="position_reverse" v-model="position_reverse_" v-show="position_ === 'content'" />
                <label :for="getPlacementId('position-reverse')" v-show="position_ === 'content'">Start counting from bottom</label>
                <hr v-show="position_ === 'content'" />
                <label>Alignment Options: </label>
                <div class="content-ads-alignment">
                    <input type="radio" v-model="alignment_" value="none" :id="getPlacementId('none')" />
                    <label :for="getPlacementId('none')">None</label>
                    
                    <input type="radio" v-model="alignment_" value="left" :id="getPlacementId('left')"/>
                    <label :for="getPlacementId('left')">Left</label>
                    
                    <input type="radio" v-model="alignment_" value="right" :id="getPlacementId('right')" />
                    <label :for="getPlacementId('right')">Right</label>
                    
                    <input type="radio" v-model="alignment_" value="center" :id="getPlacementId('center')" />
                    <label :for="getPlacementId('center')">Center</label>
                </div>
                <input type="hidden" :name="getPlacementName('[align]')" v-model="alignment_" />
                <input type="hidden" :name="getPlacementName('[content][position-reverse]')" v-model="position_reverse_" />
                <hr />

                <div v-show="position_ !== 'in-feed' && !pro_ver_above_or_5_2_3"  class="content-ads-adgroup">
                <label>Select Adgroup: </label>
                <v-select :clearable="false" placeholder="Select Adgroup" :options="adGroups" label="name" v-model="adgroup_selected_" @input="onAdgroupChange"></v-select>
                <input type="hidden" :name="getPlacementName('[adgroup]')" v-bind:value="adgroup_selected_.term_id" />
                </div>
                <div v-show="position_ === 'in-feed' && !pro_ver_above_or_5_2_3" class="In-feed-ads">
                <label v-show="!pro_ver_above_or_5_2_3">Select Ad: </label>
                <v-select :clearable="false" placeholder="Select Ad" :options="ads" label ="post_title"  v-model="ad_selected_" @input="onAdChange"></v-select>
                <input type="hidden" :name="getPlacementName('[ad]')" v-bind:value = "ad_selected_.ID" />
                </div>
                <div v-show="pro_ver_above_or_5_2_3"  class="content-ads-adgroup">
                <label>Select From: </label>
                <div class="content-ads-alignment">
                    <input type="radio" v-model="ad_or_adgroup_" value="ads" :id="getPlacementId('ad')" @input="onAdChange"/>
                    <label :for="getPlacementId('ad')">Ads</label>

                    <input type="radio" v-model="ad_or_adgroup_" value="adgroups" :id="getPlacementId('adgroup')" @input="onAdgroupChange"/>
                    <label :for="getPlacementId('adgroup')">Adgroups</label>
                </div>
                <input type="hidden" :name="getPlacementName('[ad_or_adgroup]')" v-model="ad_or_adgroup_" />

                    <v-select style = "margin-top:6px;" v-show="ad_or_adgroup_==='adgroups'" :clearable="false" placeholder="Select Adgroup" :options="adGroups" label="name" v-model="adgroup_selected_" @input="onAdgroupChange"></v-select>
                    <input v-show="ad_or_adgroup_==='adgroups'" type="hidden" :name="getPlacementName('[adgroup]')" v-bind:value="adgroup_selected_.term_id" />
                    <v-select style = "margin-top:6px;" v-show="ad_or_adgroup_==='ads'" :clearable="false" placeholder="Select Ad" :options="ads" label ="post_title"  v-model="ad_selected_" @input="onAdChange"></v-select>
                    <input v-show="ad_or_adgroup_==='ads'" type="hidden" :name="getPlacementName('[ad]')" v-bind:value = "ad_selected_.ID" />
                </div>            
            
            </c-collapse>
        </div>`,

	data() {
		return {
			PostOptions: [ 'Post', 'Page' ],
			contentAdsRulesShow: this.show,
			positionOptions: [
				{ key: 'after', value: 'after' },
				{ key: 'before', value: 'before' },
			],
			elementOptions: [
				{ key: 'Paragraph (<p>)', value: 'p' },
				{ key: 'Paragraph without image (<p>)', value: 'pw' },
				{ key: 'Headline 2 (<h2>)', value: 'h2' },
				{ key: 'Headline 3 (<h3>)', value: 'h3' },
				{ key: 'Headline 4 (<h4>)', value: 'h4' },
				{ key: 'Any headline (h1-h6)', value: 'any' },
				{ key: 'Image Tag (<img>)', value: 'img' },
				{ key: 'table tag (<table>)', value: 'table' },
				{ key: 'list item (<li>)', value: 'li' },
				{ key: 'quote (<blockquote>)', value: 'blockquote' },
				{ key: 'iframe (<iframe>)', value: 'iframe' },
				{ key: 'div (<div>)', value: 'div' },
			],
			active: true,
			adGroups: [],
			ads: [],
			position_: this.position,
			placement_id_: this.placement_id,
			placement_name_: this.placement_name,
			alignment_: this.alignment,
			select_from_: this.select_from,
			ad_or_adgroup_: this.ad_or_adgroup,
			adgroup_selected_: this.adgroup_selected,
			ad_selected_: this.ad_selected,
			blog_page: this.blog_page,
			position_selected_: this.position_selected,
			element_selected_: this.element_selected,
			post_selected_: this.post_selected,
			default_placement_name_: 'Placement-' + this.count,
			count_: this.count,
			adgroups_security_: this.adgroups_security,
			number_: this.number,
			in_feed_number_: this.in_feed_number,
			position_reverse_: this.position_reverse,
			post_: '-',
			ad_: '-',
			adgroup_: '-',
			adorgroup_: '-',
			pro_ver_above_or_5_2_3: pro_ver_above_or_5_2_3,
		};
	},
	props: [
		'placement_id',
		'position',
		'placement_name',
		'alignment',
		'select_from',
		'ad_or_adgroup',
		'adgroup_selected',
		'ad_selected',
		'position_selected',
		'element_selected',
		'post_selected',
		'count',
		'adgroups_security',
		'number',
		'in_feed_number',
		'position_reverse',
		'show',
	],
	methods: {
		getPlacementName: function( name ) {
			return 'placement[' + this.count + ']' + name;
		},
		onDeleteRule: function() {
			this.active = false;
		},
		getPlacementId: function( id ) {
			return id + '-' + this.count;
		},
		onPostChange: function() {
			this.post_ = this.post_selected_.join( ',' );
			if ( this.post_ === '' ) {
				this.post_ = '-';
			}
		},
		onAdgroupChange: function() {
			if ( pro_ver_above_or_5_2_3 ) {
				this.adorgroup_ = this.adgroup_selected_.name;
			} else {
				this.adgroup_ = this.adgroup_selected_.name;
			}
			this.ad_selected_ = [];
		},
		onAdChange: function() {
			if ( pro_ver_above_or_5_2_3 ) {
				this.adorgroup_ = this.ad_selected_.post_title;
			} else {
				this.ad_ = this.ad_selected_.post_title;
			}
			this.adgroup_selected_ = [];
		},
		onCollapseClick: function() {
			this.contentAdsRulesShow = ! this.contentAdsRulesShow;
			if ( ! this.contentAdsRulesShow ) {
				if ( this.$refs.wpadcenter_arrow.classList.contains( 'vs__close-indicator-wp' ) ) {
					this.$refs.wpadcenter_arrow.classList.remove( 'vs__close-indicator-wp' );
				}
			} else if ( ! this.$refs.wpadcenter_arrow.classList.contains( 'vs__close-indicator-wp' ) ) {
				this.$refs.wpadcenter_arrow.classList.add( 'vs__close-indicator-wp' );
			}
		},
	},
	mounted() {
		j.ajax( {
			type: 'POST',
			url: './admin-ajax.php',
			data: {
				action: 'get_adgroups',
				security: this.adgroups_security,
			},
		} ).done( data => {
			this.adGroups = JSON.parse( data );
			this.adGroups.forEach( ( item ) => {
				if ( item.term_id === parseInt( this.adgroup_selected_ ) ) {
					this.adgroup_selected_ = item;
					if ( pro_ver_above_or_5_2_3 ) {
						this.adorgroup_ = item.name;
					} else {
						this.ad_ = item.post_title;
					}
				}
			} );
		} );

		j.ajax( {
			type: 'POST',
			url: './admin-ajax.php',
			data: {
				action: 'get_ads',
				security: this.adgroups_security,
			},
		} ).done( data => {
			this.ads = JSON.parse( data );
			this.blog_page = this.ads.pop();
			this.ads.forEach( ( item ) => {
				if ( item.post_title === this.ad_selected_ ) {
					this.ad_selected_ = item;
					this.adorgroup_ = item.post_title;
				}
			} );
		} );

		this.positionOptions.forEach( ( item ) => {
			if ( item.key === this.position_selected_ ) {
				this.position_selected_ = item;
			}
		} );
		this.elementOptions.forEach( ( item ) => {
			if ( item.value === this.element_selected_ ) {
				this.element_selected_ = item;
			}
		} );
		if ( this.post_selected_ === '' ) {
			this.post_selected_ = [];
		} else {
			this.post_ = this.post_selected_;
			this.post_selected_ = this.post_selected_.split( ',' );
		}
		if ( this.show !== true ) {
			this.contentAdsRulesShow = false;
		}
		if ( this.position_reverse_ === 'false' ) {
			this.$refs.position_reverse.checked = false;
		}
		if ( ! this.contentAdsRulesShow ) {
			if ( this.$refs.wpadcenter_arrow.classList.contains( 'vs__close-indicator-wp' ) ) {
				this.$refs.wpadcenter_arrow.classList.remove( 'vs__close-indicator-wp' );
			}
		} else if ( ! this.$refs.wpadcenter_arrow.classList.contains( 'vs__close-indicator-wp' ) ) {
			this.$refs.wpadcenter_arrow.classList.add( 'vs__close-indicator-wp' );
		}
	},
};


