/**
 * Created by Administrator on 2017/7/13.
 */
import React from 'react'
import {Row, Col,Tabs,Card,Icon,Input,Button,Spin,Tag} from 'antd'
import reqwest from 'reqwest'
import { Link } from 'react-router'
const TabPane = Tabs.TabPane;
class CardArticleList extends React.Component{
  constructor(props) {
    super(props);
    this.state = {
      articleList:'',
      active:null,
      commentValue:'',
      commentLoading:false
    }
  }
  componentDidMount(){
      document.body.scrollTop=0
      const articleList=this.props.articleList;
      this.setState({articleList})
  }
  componentWillReceiveProps(newProps){
    if(this.props.urlParams!=newProps.urlParams){
      this.setState({active:null})
    }
    this.setState({
        articleList:newProps.articleList
    })

    if(!newProps.commentLoading){
      this.setState({commentLoading:false,commentValue:''})
    }
  }
  showArticle(index,aid){
    if(document.body.scrollTop>80){
      document.body.scrollTop=80
    }
    this.props.toggleSider('hideS');
    this.props.fetchComment(aid)
    this.setState({active:index})

  }
  hideArticle(){
      document.body.scrollTop=0;
    this.props.toggleSider('showS')
    this.setState({active:null})

  }
  addComment(aid){
    this.setState({commentLoading:true})
    this.props.addComment({content:this.state.commentValue,aid})
  }
  render(){
    const colorList=['#f50','#2db7f5','#87d068','#108ee9','rgb(0, 133, 161)']
    console.log(this.state.articleList)
    return (
      <div>
        {(typeof this.state.articleList=='object'&&this.state.articleList.length==0)?
          <span style={{display:'block',height:500,textAlign:'center',fontSize:30,paddingTop:100}}> 暂无相关文章(┬＿┬)</span>:''}
        {this.state.articleList.length>0?this.state.articleList.map((item,index)=>(
          <Card  className="articleCard"   bodyStyle={{}} key={index}  >
            <div className="cardCname">
              {item.cname}
            </div>
            <div style={{position:'relative'}}>
              <div className="ant-card-abs">
                <Link className="rotateA" to={{pathname:'singleArticle/'+item.aid}}>阅读全文<Icon className="rotate" type="down" /></Link>
              </div>
              <p  style={{fontSize:18,marginBottom:10,fontWeight:700}} className='apHover'  dangerouslySetInnerHTML={{__html:item.title}} ></p>
              <p className="articleContent"></p>
              <p className='apHover' style={{fontSize:14,marginTop:15}}>
                <span><Icon type="clock-circle-o"  /> {item.create_time}</span>
                <span style={{marginLeft:15}}><Icon type="eye"  /> 阅读量:{item.click}</span>
                <span style={{marginLeft:15}}><Icon type="message"  /> 评论:{item.comment_count}</span>
              </p>
              <span  className='tagGroup' style={{marginLeft:20,marginTop:15,display:item.tag.length>0?'block':'none'}}><Icon style={{fontSize:16,marginRight:5}} type="tag-o" /> {item.tag?item.tag.map((tag,subIndex)=>{
                const randomColor=colorList[parseInt(Math.random()*colorList.length)]
                return <Tag className='defaultTag' onClick={()=>{this.props.toTag(tag)}}   key={subIndex} style={{borderColor:randomColor,color:randomColor }} >{tag}</Tag>
              }):''}</span>
            </div>

          </Card>
        )   ):''}
      </div>
    )
  }
}
export  default  CardArticleList
