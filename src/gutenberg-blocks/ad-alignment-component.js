const { Component, } = wp.element;
const {IconButton }       = wp.components;
const { __, }       = wp.i18n;



class AdAlignment extends Component{
  constructor(){
    super(...arguments);
    this.state={
      value:this.props.currentAdAlignment,
    }

    this.onClick=this.onClick.bind(this);
  }



  onClick(value){
    this.setState({
      value:value
    });
    this.props.adAlignment(value);


  }
  render(){


    return <div style={{display:"flex",justifyContent:"center"}}>
    

    <div style={{display:"flex"}}>
      <div className="wpadcenter-align-container">
    <h3 style={{fontWeight:"300",textAlign:"center",marginTop:"20px",fontSize:"medium"}}>{__('Alignment','wpadcenter')}</h3>
    
    <IconButton
    isDefault

     icon="editor-alignleft"
     className={this.state.value==="wpadcenter-alignleft"?"wpadcenter-activebutton":''}
     onClick={ () => {
						this.onClick( 'wpadcenter-alignleft' );
					} }

    />
    <IconButton
    isDefault
     icon="editor-aligncenter"
     className={this.state.value==="wpadcenter-aligncenter"?"wpadcenter-activebutton":''}

     onClick={ () => {
           this.onClick( 'wpadcenter-aligncenter' );
         } }
    />
    <IconButton
    isDefault
     icon="editor-alignright"
     className={this.state.value==="wpadcenter-alignright"?"wpadcenter-activebutton":''}

     onClick={ () => {
           this.onClick( 'wpadcenter-alignright' );
         } }
    />
    <IconButton
    isDefault

     icon="no-alt"
     className={this.state.value==="wpadcenter-alignnone"?"wpadcenter-activebutton":''}
     onClick={ () => {
						this.onClick( 'wpadcenter-alignnone' );
					} }

    />
    </div>
    </div>
    </div>
  }
}

export default AdAlignment;
