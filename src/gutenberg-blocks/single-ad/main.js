import AsyncSelect from 'react-select/async';

const {registerBlockType} = wp.blocks;
const apiFetch = wp.apiFetch;
const { Placeholder } = wp.components;
const { __, }       = wp.i18n;




import SingleAd from  './single-ad-component';
import AdAlignment from '../ad-alignment-component';



registerBlockType('wpadcenter/single-ad',{

     title:__('WPAdCenter Single Ad','wpadcenter'),
     description: __('Block to generate WPAdCenter single ad','wpadcenter'),
     icon:'flag',
     category:'wpadcenter',

     attributes:{
       ad_id: {
       type: 'number',
     },
     ad_name: {
       type: 'text',
     },
     ad_alignment: {
       type: 'text',
     },
     align : {
      type:'string',
      default:'wide'
    },
     },

     edit(props) {

const getOptions=(value,callback)=>{


  apiFetch( {
    path:'/wp/v2/wpadcenter-ads/',
  } )
  .then( ( ads ) => {
    ads = ads.map( ( ad ) => {
      return {
  							value: ad.id,
  							label: ad.title.rendered,
  						};
    } );
    callback(ads);
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



const onAdSelection = ( selection ) => {

      props.setAttributes( {
        ad_id   : selection.value,
        ad_name : selection.label,

      } );

    }
    const defaultValue = {
      value: props.attributes.ad_id,
      label: props.attributes.ad_name,
    }


    const adAlignment=(value)=>{
      props.setAttributes({
        ad_alignment: value,
      });

    }


       return <div className="Wpadcenter-gutenberg-container">
       { !! props.isSelected ? (

       <Placeholder label="WPAdCenter Single Ad"  isColumnLayout="true">

      <h3 style={{fontWeight:"300",textAlign:"center",fontSize:"medium"}}>{__('Select Ad','wpadcenter')}</h3>
       <div style={{display:"flex",justifyContent:"center"}}>
       
       <AsyncSelect
       styles={customStyles}
       className="wpadcenter-async-select"
       defaultOptions
			 loadOptions={ getOptions }
			 defaultValue={ defaultValue }
			 onChange={ onAdSelection }


      />
      </div>
      <AdAlignment
      adAlignment={adAlignment}
      currentAdAlignment={props.attributes.ad_alignment}
      />

     

      </Placeholder>):(
        <SingleAd
        adId={props.attributes.ad_id}
        adAlignment={props.attributes.ad_alignment}
        />

      )}

      </div>;

     },

     save(){
       return null;
     }

});
