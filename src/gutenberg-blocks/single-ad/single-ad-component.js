const apiFetch       = wp.apiFetch;
const { Component, } = wp.element;
const { __, }       = wp.i18n;



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
        __html:`<h4 style="font-weight:300">${__('Loading Ad','wpadcenter')}</h4>`,
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
          __html:`<h4 style="font-weight:300">${__('Select Ad','wpadcenter')}</h4>`,
        },
      } );
    });
  }


  render() {

    let adAlignmentCurrent=this.props.adAlignment;
    let adAlignment;
    if (adAlignmentCurrent=== 'wpadcenter-alignright'){
      adAlignment = {
    display:'inline',
    float:'right',
    marginLeft:"1.5em",
    zIndex:"20",
    position:"relative",

  };

    }
    else if (adAlignmentCurrent=== 'wpadcenter-aligncenter'){
      adAlignment = {
    display:'flex',
    justifyContent:'center',
    zIndex:"20",
    position:"relative",

  };
}

  else if (adAlignmentCurrent=== 'wpadcenter-alignleft'){
    adAlignment = {
      display:'inline',
      float:'left',
      marginRight:"1.5em",
      zIndex:"20",
      position:"relative",

};
}
else{
    adAlignment = {
        zIndex:"20",
        position:"relative",
    };
}





return (

  <div style={adAlignment} dangerouslySetInnerHTML={ this.state.ad_html } ></div>
)

  	}
}
export default SingleAd;
