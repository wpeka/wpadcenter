import AsyncSelect from 'react-select/lib/Async';

const {registerBlockType} = wp.blocks;
const apiFetch = wp.apiFetch;
const { Placeholder,IconButton } = wp.components;



import SingleAd from  './single-ad-component';
import AdAlignment from '../ad-alignment-component';



registerBlockType('wpadcenter/single-ad',{

     title:'WPAdCenter Single Ad',
     description: 'Block to generate WPAdCenter single ad',
     icon:'embed-generic',
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

    // boxShadow: state.isFocused ? null : null,
    minWidth: "300px",

    fontSize:"125%",
    borderRadius: state.isFocused ? "3px 3px 0 0" : 3,
   // Removes border around container
   boxShadow: state.isFocused ? null : null,

 }),
  menu: base => ({
    ...base,
    // override border radius to match the box
    borderRadius: 0,
    // kill the gap
    marginTop: 0,
    minWidth: "300px",
  }),
  menuList: base => ({
    ...base,
    // kill the white space on first and last option
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







       return <div>
       { !! props.isSelected ? (

       <Placeholder label="WPAdCenter Single Ad"  isColumnLayout="true">

       <div style={{display:"flex",justifyContent:"space-around"}}>
       <div>
       <h3 style={{fontWeight:"300",textAlign:"center"}}>Select Ad</h3>
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

      </div>

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
