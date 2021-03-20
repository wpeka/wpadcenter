const apiFetch       = wp.apiFetch;
const { Component, } = wp.element;



class SingleAd extends Component{
  constructor(){
    super(...arguments);
    this.state={
      ad_id:this.props.adId,

      ad_html:{
        __html:'',
      },
    }

  }

  componentDidMount() {
    this.setState( {
      ad_html:{
        __html:`<h1 style="font-weight:300">Loading Ad</h1>`,
      },
    } );
		  this.apiFetch();
	}

  apiFetch(){
    apiFetch({
      path:`wp/v2/wpadcenter-ads/${this.props.adId}`
    }).then(ad=>{
      this.setState( {
        ad_html:{
          __html:ad.ad_html,
        },
      } );
    }).catch(error=>{
      this.setState( {
        ad_html:{
          __html:`<h1 style="font-weight:300">Select Ad</h1>`,
        },
      } );
    });
  }


  render() {

    let adAlignmentCurrent=this.props.adAlignment;
    let adAlignment;
    if (adAlignmentCurrent=== 'wpadcenter-alignright'){
      adAlignment = {
    display:'flex',
    justifyContent:'flex-end',

  };

    }
    else if (adAlignmentCurrent=== 'wpadcenter-aligncenter'){
      adAlignment = {
    display:'flex',
    justifyContent:'center',

  };
}

  else if (adAlignmentCurrent=== 'wpadcenter-alignleft'){
    adAlignment = {
  display:'flex',
  justifyContent:'flex-start',

};
}






return (

  <div style={adAlignment} dangerouslySetInnerHTML={ this.state.ad_html } ></div>
)

  	}
}
export default SingleAd;
