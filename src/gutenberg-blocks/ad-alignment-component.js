const { Component, } = wp.element;
const {IconButton }       = wp.components;

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


    return <div >
    <h3 style={{fontWeight:"300",textAlign:"center"}}>Alignment</h3>

    <div style={{display:"flex"}}>
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
    </div>

    </div>
  }
}

export default AdAlignment;
