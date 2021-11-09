/**
 * @jest-environment jsdom
 */
 import componentABTests from '../abtests.js';

 test('test template of contentads',( )=>{
    var retrievedTemplate = componentABTests.template;
    expect(typeof retrievedTemplate).toBe('string');
    var htmlEscaped = retrievedTemplate.replace(/<\/?[^>]+(>|$)/g, "");
    expect( htmlEscaped === retrievedTemplate ).toBeFalsy();
});

test('test props attributes',()=>{
    var receivedProps = componentABTests.props;
    var expectedProps = [
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
    ]
    expect(receivedProps).toEqual(expectedProps);
})

test('test for methods', ()=>{
    const module = {
        test_count_:1,
        active_:true,
        selections:[],
        placement_label_:'',
        placements_names:'',
        placements_selected_:[
            {
                name:'placement 1',
                id:'1'
            }
        ],
        getPlacementsIdsToString(){
            this.selections = [];
            this.placement_label_ = ''
            this.placements_selected_.forEach((entry) => {
                this.selections.push(entry.id);
                this.placement_label_ += entry.name + ' ';
            });
            this.selections = this.selections.toString()
            this.placements_names = this.selections;
        },
        error_show_:false,

    }
    var boundGetTestName = componentABTests.methods.getTestName.bind(module);
    expect(boundGetTestName('value')).toEqual('test[1]value');

    var boundOnDeleteTest = componentABTests.methods.onDeleteTest.bind(module);
    boundOnDeleteTest();
    expect(module.active_).toBeFalsy();

    var boundGetTestId = componentABTests.methods.getTestId.bind(module);
    expect(boundGetTestId('id')).toEqual('id-1');

    var boundOnPlacementSelection = componentABTests.methods.onPlacementSelection.bind(module);
    boundOnPlacementSelection();
    expect(module.error_show_).toBeTruthy();

    var boundGetPlacementsIdsToString = componentABTests.methods.getPlacementsIdsToString.bind(module);
    boundGetPlacementsIdsToString();
    expect(module.placements_names).toEqual('1');
})