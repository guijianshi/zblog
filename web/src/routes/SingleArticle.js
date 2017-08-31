/**
 * Created by Administrator on 2017/7/17.
 */
import React from 'react'
import { connect } from 'dva';
import {Row, Col,Tabs,Card,Icon,Spin,Input,Button,message,Tag} from 'antd'
import reqwest from 'reqwest'
class singleArticle extends React.Component{
  constructor(props) {
    super(props);
    this.state={
      urlParams:'',
      article:'',
      loading:true,
      commentLoading:false,
      commentList:[],
      commentValue:'',
      catalog:[]
    }
  }
  fetch(id){
    this.props.dispatch({type:'IndexPage/showArticleLoading'});
    reqwest({url:'http://127.0.0.1:8888/index/article/'+id}).then((data)=>{
      if(data.ret==1){
        console.log(data.data)
        const article=data.data;
        this.setState({article},()=>{
         var catalogList=this.refs.article.getElementsByTagName('h1')
          var catalog=[]
          for (let i=0;i< catalogList.length;i++){
            catalog.push({label:catalogList[i].innerText,scrollTop:catalogList[i].offsetTop+350})
          }
          this.setState({catalog})
        });
        console.log(article.content)
      }else {
        this.setState({article:{content:'暂无内容'}});
      }
      this.props.dispatch({type:'IndexPage/hideArticleLoading'})
    })
  }
  componentWillReceiveProps(nextProps){
    this.setState({commentList:nextProps.IndexPage.commentList})
    if(!nextProps.commentLoading){
      this.setState({commentLoading:false,commentValue:''})
    }
    if(this.props.routeParams.id!=nextProps.routeParams.id){
      this.fetch(nextProps.routeParams.id)
      this.fetchComment(nextProps.routeParams.id)
    }
  }
  fetchComment(id){
    this.props.dispatch({type:'IndexPage/fetchComment',payload:{aid:id}})
  }
  componentDidMount(){

    document.body.scrollTop=0;
    this.fetch(this.props.routeParams.id);
    this.fetchComment(this.props.routeParams.id)
  }
  addComment(aid){
    this.setState({commentLoading:true})
    this.props.dispatch({type:'IndexPage/addComment',payload:{content:this.state.commentValue,aid}})

  }
  scrollToCatalog=(scrolltop)=>{

    var maxTop=(document.body.scrollHeight||document.documentElement.scrollHeight)-(document.body.clientHeight||document.documentElement.clientHeight)
    console.log(maxTop)
    var interVal=setInterval(()=>{
      var top= document.body.scrollTop||document.documentElement.scrollTop;
      var speed=Math.ceil((scrolltop-top)/14);
          top+=speed;
      document.body.scrollTop=document.documentElement.scrollTop=top
      if(top==scrolltop||top>=maxTop){
        clearInterval(interVal)
      }

    },20)

  }
  toTag=(tname)=>{
    this.context.router.push({pathname:'article/tag',query:{name:tname}})
  }
  render(){
    const colorList=['#f50','#2db7f5','#87d068','#108ee9','rgb(0, 133, 161)']
    const props={
      toggleSider:(method)=>{this.props.dispatch({type:'IndexPage/'+method})},
      url:'http://localhost:8888/index/index/'+this.props.routeParams.type,
      name:this.props.location.query.name
    }
    return (
      <div style={{position:'relative'}} >
        <Card style={{overflow:'visible',border:'none'}} className={this.state.article.content?' doneCard singleCard':'InitCard singleCard'}      bodyStyle={{}}   >
          <div style={{position:'relative',paddingBottom:20}}>
            <ul className="catelog catelogAbs">
              <li style={{marginBottom:20,fontSize:18,color:'rgb(120,120,120)'}}>目录</li>
              {this.state.catalog.map((catalog,index)=>{
                return <li key={index} onClick={()=>{this.scrollToCatalog(catalog.scrollTop)}} >{catalog.label}</li>
              })}
            </ul>
            <div className="ant-card-abs">
             <a className="rotateA" onClick={()=>{window.history.go(-1)}} >返回 <Icon className="rotate" type="up" /></a>
            </div>
            <div className="clearfix" style={{marginBottom:20,position:'relative'}}>
              {this.state.article.tag?
                <span className="absTags"  style={{marginLeft:15,float:'left'}}>{this.state.article.tag.length>0?<Icon style={{fontSize:16,marginRight:5}} type="tag-o" />:''} {this.state.article.tag.map((tag,index)=>{
                  const randomColor=colorList[parseInt(Math.random()*colorList.length)]
                  return <Tag onClick={()=>{this.toTag(tag)}}   key={index} style={{borderColor:randomColor,color:randomColor }} >{tag}</Tag>
                })}</span>:
              ''}

              {this.props.IndexPage.showSider?
                <i onClick={()=>{this.props.dispatch({type:'IndexPage/hideS'})}}  className="icon-quanping1 iconfont sizeI"></i>
                :
                <i onClick={()=>{this.props.dispatch({type:'IndexPage/showS'})}} style={{fontSize:23  }}  className="icon-suoxiaotuichuquanpingshouhui  iconfont sizeI"></i>
              }
            </div>
            <p  style={{fontSize:18,marginBottom:10,marginTop:40}} className={'center '} >{this.state.article.title}</p>
            <p style={{textAlign:'center',fontSize:14,marginTop:5,marginBottom:10}}>
              <span><Icon type="clock-circle-o"  /> {this.state.article.create_at}</span>
              <span style={{marginLeft:15}}><Icon type="eye"  /> 阅读量:{this.state.article.click}</span>
              <span style={{marginLeft:15}}><Icon type="message"  /> 评论:{this.state.article.comment_count}</span>
            </p>
            <p className="articleContent" ref="article"  dangerouslySetInnerHTML={{__html:this.state.article.content}}></p>

          </div>
          <Card  className="commentCard" title={this.state.commentList.length>0?this.state.commentList.length+' 条评论':'暂无评论'}   style={{  }}>
              <ul>
                {this.state.commentList.map((comment,index)=>{
                  return (
                    <li key={index} className="commentLi">
                      <div className="commentHeader">
                        <img />
                        <span>章三</span>
                        <span className="commentCreateTime">
                            {comment.create_time}
                          </span>
                      </div>
                      <p className="commentContent">
                        {comment.content}
                      </p>
                    </li>
                  )
                })}
              </ul>
              <div className="writeComment">
                {this.state.commentLoading?<Spin  />:''}
                <Input type="textarea"  placeholder="请输入评论..." value={this.state.commentValue}  onChange={(e)=>{this.setState({commentValue:e.target.value}) }} autosize />
                <Button className="commentBtn" type="primary" onClick={()=>{this.addComment(this.state.article.aid)}}>评论</Button>
              </div>
            </Card>

        </Card>
      </div>

    )
  }
}

singleArticle.contextTypes = {
  router: React.PropTypes.object.isRequired
};

export default connect(({ IndexPage }) => ({
  IndexPage,
}))(singleArticle);
