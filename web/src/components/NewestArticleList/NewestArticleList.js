/**
 * Created by Administrator on 2017/7/12.
 */
import React from 'react'
import  {Icon,Card} from  'antd'
import { Link } from 'react-router'
class NewestArticleList extends React.Component{
  constructor(props){
    super(props);
    this.state={
      newestArticle:[]
    }
  }
  componentWillReceiveProps(newProps){
    this.setState({newestArticle:newProps.newestArticle})
  }
  componentDidMount(){
    this.setState({newestArticle:this.props.newestArticle})
  }
  render(){
    return (
      <div>
        <Card  title={<p style={{fontSize:16}}><Icon type="clock-circle-o" /> 最近文章</p>} >
          <ul>
            {this.state.newestArticle.map(function (item,index) {
              return ( <li className="clearfix" key={index} style={{marginTop:4}}><Link className="articleLink" to={{pathname:'singleArticle/'+item.aid}} style={{color:'black',fontSize:14}} ><span className="articleIndex" style={{float:'left'}}>{index+1}.</span> <span style={{float:'left',width:'90%',marginLeft:'3%'}}>{item.title}</span></Link></li> )
            })}
          </ul>
        </Card>
      </div>
    )
  }
}


export default NewestArticleList
