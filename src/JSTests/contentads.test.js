/**
 * @jest-environment jsdom
 */
import componentContentAds from '../contentads.js';

test('test template of contentads',( )=>{
    var retrievedTemplate = componentContentAds.template;
    expect(typeof retrievedTemplate).toBe('string');
    var htmlEscaped = retrievedTemplate.replace(/<\/?[^>]+(>|$)/g, "");
    expect( htmlEscaped === retrievedTemplate ).toBeFalsy();
});

test('test props attributes',()=>{
    var receivedProps = componentContentAds.props;
    var expectedProps = [
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
        'show'
    ]
    expect(receivedProps).toEqual(expectedProps);

})

test('test for methods', ()=>{
    const module = {
        count:1,
        active:true,
        post_selected_:['1','2'],
        post_:'-',
        adgroup_selected_ : {
            name:'adgroup-name'
        },
        ad_selected_ : {
            post_title:'post-title'
        },
        adorgroup_:'',

    }
    var boundGetTemplateName = componentContentAds.methods.getPlacementName.bind(module);
    expect(boundGetTemplateName('value')).toEqual('placement[1]value');

    var boundonDeleteRule = componentContentAds.methods.onDeleteRule.bind(module);
    boundonDeleteRule();
    expect(module.active).toBeFalsy();

    var boundGetPlacementId = componentContentAds.methods.getPlacementId.bind(module);
    expect(boundGetPlacementId('1')).toEqual('1-1');

    var boundOnPostChange = componentContentAds.methods.onPostChange.bind(module);
    boundOnPostChange();
    expect(module.post_).toEqual('1,2');

    var boundOnAdgroupChange = componentContentAds.methods.onAdgroupChange.bind(module);
    boundOnAdgroupChange();
    expect(module.adorgroup_).toEqual('adgroup-name');



    module.ad_selected_ = {
        post_title:'post-title'
    }

    var boundOnAdChange = componentContentAds.methods.onAdChange.bind(module);
    boundOnAdChange();
    expect(module.adorgroup_).toEqual('post-title');
    module.adgroup_selected_ = {
        name:'adgroup-name'
    }
})

test('test for data values', ()=>{
    const module = {
        show:true,
        position:1,
        placement_id:1,
        placement_name:'placement-name',
        alignment:'left',
        select_from: '1',
        ad_or_adgroup:'ad',
        adgroup_selected:1,
        ad_selected:1,
        blog_page:true,
        position_selected:1,
        element_selected:1,
        post_selected:1,
        count:1,
        adgroups_security:'xyz0',
        number:1,
        in_feed_number:1,
        position_reverse:true,

    }
    var boundData = componentContentAds.data.bind(module);
    var expectedData = {
        PostOptions: ['Post', 'Page'],
        contentAdsRulesShow: true,
        positionOptions: [
            { key: 'after', value: 'after' },
            { key: 'before', value: 'before' }
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
        position_: 1,
        placement_id_: 1,
        placement_name_: 'placement-name',
        alignment_: 'left',
        select_from_: '1',
        ad_or_adgroup_: 'ad',
        adgroup_selected_: 1,
        ad_selected_: 1,
        blog_page: true,
        position_selected_: 1,
        element_selected_: 1,
        post_selected_: 1,
        default_placement_name_: 'Placement-' + '1',
        count_: 1,
        adgroups_security_: 'xyz0',
        number_: 1,
        in_feed_number_: 1,
        position_reverse_: true,
        post_: '-',
        ad_ : '-',
        adgroup_ : '-',
        adorgroup_: '-',
        pro_ver_above_or_5_2_3: pro_ver_above_or_5_2_3,
    }

    var receivedData = boundData();

    expect(receivedData).toEqual(expectedData);
})