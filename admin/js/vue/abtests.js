/*global jQuery*/
// jquery $ as j

const componentABTests = {
	template:
        `<div class="contentAdsRules" v-if="active_">
            <div class="rule" @click="onCollapseClick">
                <div class="rule_label">
                <svg ref="wpadcenter_arrow" xmlns="http://www.w3.org/2000/svg" width="14" height="10" role="presentation" class="vs__open-indicator"><path d="M9.211364 7.59931l4.48338-4.867229c.407008-.441854.407008-1.158247 0-1.60046l-.73712-.80023c-.407008-.441854-1.066904-.441854-1.474243 0L7 5.198617 2.51662.33139c-.407008-.441853-1.066904-.441853-1.474243 0l-.737121.80023c-.407008.441854-.407008 1.158248 0 1.600461l4.48338 4.867228L7 10l2.211364-2.40069z"></path></svg>
                <label> {{test_name_}} | {{test_duration_}} | {{ placement_label_ }}</label>
                </div>
                <div class="delete_test">
                    <c-button class="btn btn-danger" color="danger" @click.stop="onDeleteTest">Delete Test</c-button>
                 </div>
            </div>
        <c-collapse :show="contentABTestsShow">
        <hr />
            <div class="test">
                <div>
                    <label for="test-name">Name the test:</label>
                    <c-input type="text" v-model="test_name_" :id="getTestId('test_name')" ></c-input>
                    <input type="hidden" :name="getTestName('[name]')" v-model="test_name_ ? test_name_ : default_test_name_" />
                    <input type="hidden" :name="getTestName('[id]')" v-bind:value="test_id_ ?test_id_:new Date().getTime()" />
                </div>
                <div>
                    <label for="test-duration">Duration of test (in days):</label>
                    <c-input type="number" v-model="test_duration_" :id="getTestId('test_duration')" min="1"></c-input>
                    <input type="hidden" :name="getTestName('[duration]')" v-model="test_duration_" />
                </div>
                <div>
                    <label for="test-placements">Select placements:</label>
                    <v-select :id="getTestId('test_placement')" :clearable="false" placeholder="Select Placements" :options="placements" v-model="placements_selected_" label="name" multiple @input="onPlacementSelection" style="background:#fff;">
                    </v-select>	
                    <input type="hidden" :name="getTestName('[placements]')" v-bind:value="selections" />
                    <input type="hidden" :name="getTestName('[placement_label]')" v-bind:value="placement_label_" />
                    <input type="hidden" :name="getTestName('[date]')" v-bind:value="date_" />
                    </div>
            </div>
            <label v-show="error_show_" class="error">You need to select at least 2 placements to be able to create A/B test</label>
            </c-collapse>
            </div>`,
	data() {
		return {
			active_: this.active,
			placements: [],
			test_id_: this.test_id,
			ab_testing_security_: this.ab_testing_security,
			test_count_: this.test_count,
			placements_selected_: this.placements_selected,
			test_duration_: this.test_duration,
			test_name_: this.test_name,
			error_show_: this.error_show,
			selected_placement_name_: this.selected_placement_name,
			selections: '',
			contentABTestsShow: false,
			placement_label_: this.placement_label,
			placements_names: '',
			date_: this.date,
			default_test_name_: 'Test - ' + this.test_count,
		};
	},
	props: [
		'active',
		'ab_testing_security',
		'test_count',
		'placements_selected',
		'test_duration',
		'test_name',
		'error_show',
		'selected_placement_name',
		'test_id',
		'placement_label',
		'date',
	],
	methods: {
		getTestName: function( name ) {
			return 'test[' + this.test_count_ + ']' + name;
		},
		onDeleteTest: function() {
			this.active_ = false;
		},
		getTestId: function( id ) {
			return id + '-' + this.test_count_;
		},
		onPlacementSelection: function() {
			this.getPlacementsIdsToString();
			if ( this.placements_selected_.length < 2 ) {
				this.error_show_ = true;
			} else {
				this.error_show_ = false;
			}
		},
		getPlacementsIdsToString: function() {
			this.selections = [];
			this.placement_label_ = '';
			this.placements_selected_.forEach( ( entry ) => {
				this.selections.push( entry.id );
				this.placement_label_ += entry.name + ' ';
			} );
			this.selections = this.selections.toString();
			this.placements_names = this.selections;
		},

		onCollapseClick: function() {
			this.contentABTestsShow = ! this.contentABTestsShow;
			if ( ! this.contentABTestsShow ) {
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
				action: 'get_placements',
				security: this.ab_testing_security,
			},
		} ).done( data => {
			this.placements = JSON.parse( data );
			let placement = [];
			// If this.placements is object convert it into array
			if ( typeof this.placements === 'object' ) {
				for ( const [ key, value ] of Object.entries( this.placements ) ) {
					placement.push( value );
				}
				this.placements = placement;
			}
			// Rendering already saved placements labels
			if ( typeof this.placements_selected_ === 'string' ) {
				let temp = this.placements_selected_.split( ',' );
				this.placements_selected_ = [];

				temp.forEach( ( entry ) => {
					for ( const [ key, value ] of Object.entries( this.placements ) ) {
						// Check for matching placements objects from placements array for selected placement label
						if ( value.id === entry ) {
							this.placements_selected_.push( value );
							this.getPlacementsIdsToString();
						}
					}
				} );
			}
		} );
	},
};


