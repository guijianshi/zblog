/**
 * Created by Administrator on 2017/7/12.
 */
import React from 'react'
import  {Icon,Card,Tooltip} from  'antd'
import reqwest from  'reqwest'
class Introduce extends React.Component{
  constructor(props){
    super(props);
  }
  render(){
    const colorList=['#f50','#2db7f5','#87d068','#108ee9']
    return (
      <div>
        <Card title={<p style={{fontSize:16}}><Icon type="meh-o" /> 关于我</p>} >
          <img style={{height:200,borderRadius:'5px'}} src={this.props.avatar} alt=""/>
          <p style={{marginTop:10}}> {this.props.introduce?this.props.introduce:'一个记性不太好的前端萌新。'}</p>
          <ul className="introUl" >
            <li><a href={this.props.sina}><i className="iconfont icon-weibo1" /></a></li>
            <li>
              <Tooltip placement="top" title={this.props.email}>
                <i  className="iconfont icon-youxiang" />
              </Tooltip></li>
            <li><a href={this.props.zhihu}><i className="iconfont icon-zhihu" /></a></li>
            <li><a href={this.props.github}><i className="iconfont icon-githubsquare" /></a></li>
          </ul>
        </Card>
      </div>
    )
  }
}
export default Introduce
