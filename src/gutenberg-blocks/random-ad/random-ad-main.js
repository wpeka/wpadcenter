import AsyncSelect from 'react-select/async';

const {registerBlockType} = wp.blocks;
const apiFetch = wp.apiFetch;
const { Placeholder } = wp.components;
const { __, }       = wp.i18n;




import RandomAd from  './random-ad-component';
import AdAlignment from '../ad-alignment-component';
import MaxWidth from '../maxwidth-component';




registerBlockType('wpadcenter/random-ad',{

     title:__('WPAdCenter Random Ad','wpadcenter'),
     description: __('Block to generate random WPAdCenter Ad from Adgroups','wpadcenter'),
     icon:'flag',
     category:'wpadcenter',

     attributes:{
      
       adgroup_ids: {
       type: 'array',
       default:[],
     },
     adgroups: {
       type: 'array',
       default:[],
     },
     adgroup_alignment: {
       type: 'text',
     },
    max_width_check:{
      type: 'boolean',
    default:false, 
    },
    max_width_px:{
      type: 'text',
    default:100, 
    },
     align : {
      type:'string',
      default:'wide'
    },
     },

     edit(props) {

const getOptions=(value,callback)=>{


  apiFetch( {
    path:'/wp/v2/wpadcenter-adgroups/',
  } )
  .then( ( adgroups ) => {
    adgroups = adgroups.map( ( adgroup ) => {
      return {
  							value: adgroup.id,
  							label: adgroup.name,
  						};
    } );
    callback(adgroups);
  } );

}


const customStyles = {
  control: (base, state) => ({
    ...base,

    minWidth: "300px",
    maxWidth:"400px",


    fontSize:"125%",
    borderRadius: state.isFocused ? "3px 3px 0 0" : 3,

   boxShadow: state.isFocused ? null : null,

 }),
  menu: base => ({
    ...base,

    borderRadius: 0,

    marginTop: 0,
    minWidth: "300px",
  }),
  menuList: base => ({
    ...base,
    padding: 0,
    maxWidth: "300px",

  })
};

const onMaxWidthControlChange=(value)=>{
  props.setAttributes( {
    max_width_check   : value,

  } );
}
const onMaxWidthChange=(value)=>{
  props.setAttributes( {
    max_width_px   : value,

  } );
}

const onAdSelection = ( selection ) => {
      let current_adgroup_ids=[];
      selection.forEach((adgroup)=>{
        current_adgroup_ids.push(adgroup['value']);
      })
      props.setAttributes( {
        adgroup_ids   : current_adgroup_ids,
        adgroups: selection,

      } );

    }
    const defaultValue = props.attributes.adgroups;




    const adAlignment=(value)=>{
      props.setAttributes({
        adgroup_alignment: value,
      });

    }
    const headingStyles={
      fontWeight:"300",
      textAlign:"center",
      fontSize:"medium",
    }



       return <div className="Wpadcenter-gutenberg-container">
       { !! props.isSelected ? (

       <Placeholder label="WPAdCenter Random Ad"  isColumnLayout="true">

      <h3 style={headingStyles}>{__('Select Ad Groups','wpadcenter')}</h3>
       <div style={{display:"flex",justifyContent:"center"}}>

       <AsyncSelect
       styles={customStyles}
       className="wpadcenter-async-select"
       defaultOptions
       isMulti
			 loadOptions={ getOptions }
			 defaultValue={ defaultValue }
			 onChange={ onAdSelection }


      />
      </div>

      <AdAlignment
      adAlignment={adAlignment}
      currentAdAlignment={props.attributes.adgroup_alignment}
      />

    <MaxWidth
      maxWidthCheck={props.attributes.max_width_check}
      maxWidthControlChange={onMaxWidthControlChange}
      maxWidthChange={onMaxWidthChange}
      maxWidth={props.attributes.max_width_px}
      />


      </Placeholder>):(
        <RandomAd
        adGroupIds={props.attributes.adgroup_ids}
        adgroupAlignment={props.attributes.adgroup_alignment}
        max_width_check={props.attributes.max_width_check}
        max_width_px={props.attributes.max_width_px}
        />

      )}

      </div>;

     },

     save(){
       return null;
     }

});
