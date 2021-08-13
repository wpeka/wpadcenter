// jquery $ as j
const j = jQuery.noConflict();
const componentABTests = {
    template:
        `<div class="new_ab_test" v-if="active_">
            <div class="delete_test">
                <c-button class="btn btn-danger" color="danger" @click.stop="onDeleteTest">Delete Test</c-button>
            </div>
            <div class="test">
            <div>
            <label for="test-name">Name the test:</label>
            <c-input type="text" v-model="test_name_" :id="getTestId('test_name')" ></c-input>
            <input type="hidden" :name="getTestName('[name]')" v-model="test_name_" />
            <input type="hidden" :name="getTestName('[id]')" v-bind:value="test_id_ ?test_id_:new Date().getTime()" />

        </div>
        <div>
            <label for="test-duration">Duration of test (in days):</label>
            <c-input type="number" v-model="test_duration_" :id="getTestId('test_duration')" min="1"></c-input>
            <input type="hidden" :name="getTestName('[duration]')" v-model="test_duration_" />
            </div>
        <div>
            <label for="test-placements">Select placements:</label>
            <v-select v-if="Array.isArray(placements)" :id="getTestId('test_placement')" :clearable="false" placeholder="Select Placements" :options="placements" v-model="placements_selected_" label="name" multiple @input="onPlacementSelection">
            </v-select>	
            <input type="hidden" :name="getTestName('[placements]')" v-bind:value="selections" />
        </div>
            </div>
            <label v-show="error_show_" class="error">You need to select at least 2 placements to be able to create A/B test</label>
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
        }
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
    ],
    methods: {
        getTestName: function (name) {
            return 'test[' + this.test_count_ + ']' + name;
        },
        onDeleteTest: function () {
            this.active_ = false;
        },
        getTestId: function (id) {
            return id + '-' + this.test_count_;
        },
        onPlacementSelection: function () {
            this.getPlacementsIdsToString();
            if (this.placements_selected_.length < 2) {
                this.error_show_ = true;
                return;
            } else {
                this.error_show_ = false;
            }

        },
        getPlacementsIdsToString: function () {
            this.selections = [];
            this.placements_selected_.forEach((entry) => {
                this.selections.push(entry.id);
            });
            this.selections = this.selections.toString()
        }
    },
    mounted() {
        j.ajax({
            type: "POST",
            url: './admin-ajax.php',
            data: {
                action: 'get_placements',
                security: this.ab_testing_security,
            }
        }).done(data => {
            this.placements = JSON.parse(data);
            console.log(this.placements);

            // Rendering already saved placements
            if (typeof this.placements_selected_ === 'string') {
                let temp = this.placements_selected_.split(',');
                this.placements_selected_ = [];
                //Get Ids of all placements
                var result = this.placements.map((obj) => {
                    return obj.id;
                });

                //Check if placement have the ID we are looking for 
                temp.forEach((entry) => {
                    console.log(result.includes(entry));
                    if (result.includes(entry)) {
                        this.placements.forEach((placement_entry) => {
                            //Select placement objects with the selected ids
                            if (placement_entry.id === entry) {
                                this.placements_selected_.push(placement_entry);
                                this.getPlacementsIdsToString();
                            }
                        })
                    }
                });

                if (!this.placements_selected_) {
                    this.onDeleteTest();
                }
            }
        });
    }
};

export default componentABTests;