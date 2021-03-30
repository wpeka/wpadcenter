const apiFetch       = wp.apiFetch;
const { Component, } = wp.element;
const { __, }       = wp.i18n;



class AdGroup extends Component{
  constructor(){
    super(...arguments);
    this.state={
      ad_ids:[],
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

    if(this.props.adIds.length===0){
        this.setState( {
            ad_html:{
             __html:`<h4 style="font-weight:300">${__('Select Ad Groups','wpadcenter')}</h4>`,
        },
    } );
    return;
    }


      let adgroup_html='';
      adgroup_html += '<div class=' + this.props.adgroupAlignment + '>';

      const load_ads=new Promise((resolve,reject)=>{
        var col_count = 0;
        var ad_count  = 0;
        this.props.adIds.forEach(ad_id=>{
          apiFetch({

            path:`wp/v2/wpadcenter-ads/${ad_id}`
          }).then(ad=>{
            if ( 0 === col_count || parseInt( this.props.num_columns ) === col_count ) {
              adgroup_html += '<div class="wpadcenter-adgroup-row">';
            }
            adgroup_html +='<div class="wpadcenter-ad-spacing">';
            adgroup_html+=ad.ad_html;
            adgroup_html +='</div>';
            ad_count+=1;
            col_count+=1;
            if ( parseInt( this.props.numAds ) === ad_count || parseInt( this.props.numColumns ) === col_count ) {
              adgroup_html += '</div>';
              return adgroup_html;
            }
            return adgroup_html;
          }).then(adgroup_html=>{
          if ( parseInt( this.props.numColumns ) === col_count ) {
            col_count = 0;
          }
          if(ad_count === parseInt(this.props.numAds) || ad_count === parseInt(this.props.adIds.length)){
            resolve(adgroup_html);
          }
          });
        });
      });


        load_ads.then(adgroup_html=>{
        adgroup_html += '</div>';
        return adgroup_html;
        }).then(adgroup_html=>{
        this.setState( {
            ad_html:{
            __html:adgroup_html,
        },
    } );
    });


    }


  render() {



    let adAlignment = {
      zIndex:"20",
      position:"relative",
  };

    




    return (

        <div style={adAlignment} className={this.props.adgroupAlignment} dangerouslySetInnerHTML={ this.state.ad_html } ></div>
        )

  	}
    }
export default AdGroup;
