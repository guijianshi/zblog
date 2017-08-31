/**
 * Created by Administrator on 2017/7/12.
 */
import React from 'react'
import  {Icon,Card,Tag} from  'antd'
import reqwest from  'reqwest'
class tagList extends React.Component{
  constructor(props){
    super(props);
    this.state={
      tagList:[],

    }
  }
  shouldComponentUpdate(nextProps,nextState){
    //避免tag颜色一直变
    if(nextState.tagList!=this.state.tagList){
      return true
    }else {
      return false
    }
  }
  componentDidMount(){
    reqwest({
      url:'http://localhost:8888/v1/tag/get',
    }).then((data)=>{
      if(data.ret==1){
        var tagList=data.data.map((item)=>({label:item.tname,tid:item.tid}))
        this.setState({tagList});
      }
    })
  }
  render(){
    const colorList=['#f50','#2db7f5','#87d068','#108ee9','rgb(0, 133, 161)']
    return (
      <div>
        <Card  title={<p style={{fontSize:16}}><Icon type="tag-o" /> 标签</p>} >

            {this.state.tagList.map((item,index)=> {
              const randomColor=colorList[parseInt(Math.random()*colorList.length)]
              return ( <Tag   onClick={()=>{this.props.toTag(item.label)}} key={index} style={{marginTop:10,marginLeft:5,borderColor:randomColor,color:randomColor }} >{item.label}</Tag> )
            })}

        </Card>


      </div>
    )
  }
}
export default tagList
