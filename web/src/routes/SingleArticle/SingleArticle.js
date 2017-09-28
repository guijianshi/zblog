/**
 * Created by Administrator on 2017/7/17.
 */
import React from 'react'
import { connect } from 'dva';
import {Row, Col,Tabs,Card,Icon,Spin,Input,Button,message,Tag,Alert,notification,Menu,Dropdown} from 'antd'
import reqwest from 'reqwest'
import url from '../utils/url'
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
    reqwest({url:url+'index/article/'+id}).then((data)=>{
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
  openLogin=()=>{
    var subWindow=window.open('https://graph.qq.com/oauth2.0/authorize?client_id=101419757&response_type=token&scope=all&redirect_uri=http%3A%2F%2Fwww.guijianshi.cn', 'oauth2Login_10262' ,'height=525,width=585, toolbar=no, menubar=no, scrollbars=no, status=no, location=yes, resizable=yes')
  }
  logout=()=>{
    window.QC.Login.signOut()
    this.props.dispatch({type:'IndexPage/logout'})
  }
  addComment(aid,cmid,replyContent){
    if(this.props.IndexPage.userInfo.isLogin){
      this.setState({commentLoading:true})
      this.props.dispatch({type:'IndexPage/addComment',payload:{content:cmid?replyContent:this.state.commentValue,aid,...this.props.IndexPage.userInfo,pid:cmid?cmid:''}})
    }else {
      notification['warning']({
        placement:'bottomLeft',
        message: '请先登录！',
      });
    }
  }
  setReply(e,index){
    let commentList=this.state.commentList
    commentList[index].replyContent=e.target.value
    this.setState({commentList})
  }
  replyComment(index){
      if(!this.props.IndexPage.userInfo.isLogin){
        notification['warning']({
          placement:'bottomLeft',
          message: '请先登录！',
        });
      }else {
        let commentList=this.state.commentList
        commentList[index].reply=true
        this.setState({commentList})
      }
  }
  cancelReply(index){
    let commentList=this.state.commentList
    commentList[index].replyContent='';
    commentList[index].reply=false
    this.setState({commentList})
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
    const menu = (
      <Menu>
        <Menu.Item>
          <a style={{fontSize:14,color:'black'}} onClick={this.logout}>退出</a>
        </Menu.Item>
      </Menu>
    );
    const colorList=['#f50','#2db7f5','#87d068','#108ee9','rgb(0, 133, 161)']
    const props={
      toggleSider:(method)=>{this.props.dispatch({type:'IndexPage/'+method})},
      url:url+'index/index/'+this.props.routeParams.type,
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
            <p style={{textAlign:'center',fontSize:14,marginTop:5,marginBottom:20}}>
              <span><Icon type="clock-circle-o"  /> {this.state.article.create_at}</span>
              <span style={{marginLeft:15}}><Icon type="eye"  /> 阅读量:{this.state.article.click}</span>
              <span style={{marginLeft:15}}><Icon type="message"  /> 评论:{this.state.article.comment_count}</span>
            </p>
            <p style={{marginBottom:20}} className="articleContent" ref="article"  dangerouslySetInnerHTML={{__html:this.state.article.content}}></p>

          </div>
          <Card  className="commentCard" title={this.state.commentList.length>0?this.state.commentList.length+' 条评论':'暂无评论'}   style={{  }}>
              <ul>
                {this.state.commentList.map((comment,index)=>{
                  return (
                    <li key={index}  className="commentLi">
                      <div className="commentHeader">

                        <img style={{float:'left',width:20,height:20,borderRadius:2,marginRight:6}} src={comment.user.avatar?comment.user.avatar:''} />
                        <span style={{float:'left',lineHeight:'20px'}}>{comment.user.username?comment.user.username:'游客'}</span>
                        {comment.pid?
                          <span><span style={{fontSize:10,marginRight:8,marginLeft:8,color:'#108ee9'}}>回复</span>{comment.pid}</span>
                          :''
                        }
                        <span className="commentCreateTime">
                            {comment.create_time}
                          </span>
                      </div>
                      <p className="commentContent">
                        {comment.content}
                      </p>
                      {
                        this.props.IndexPage.userInfo.isLogin?
                        comment.reply?
                        <div style={{paddingRight:140,position:'relative'}}>
                          <Input type="textarea"  placeholder="请输入评论..." value={comment.replyContent}  onChange={(e)=>{this.setReply(e,index)   }} autosize />
                          <Button className="cancelReply"  onClick={()=>{this.cancelReply(index)}}>取消</Button>
                          <Button className="replyBtn" type="primary" onClick={()=>{this.addComment(this.state.article.aid,comment.cmid,comment.replyContent)}}>评论</Button>
                        </div>
                      :
                        <a className="replyA" onClick={()=>{this.replyComment(index)}}>回复</a>
                      :''}

                    </li>
                  )
                })}
              </ul>
              <div className="writeComment">

                {this.state.commentLoading?<Spin  />:''}
                <Input type="textarea"  placeholder="请输入评论..." value={this.state.commentValue}  onChange={(e)=>{this.setState({commentValue:e.target.value}) }} autosize />
                <Button className="commentBtn" type="primary" onClick={()=>{this.addComment(this.state.article.aid)}}>评论</Button>
              </div>
            {this.props.IndexPage.userInfo.isLogin?
              <div className="clearfix">
                <img style={{height:30,width:30,borderRadius:30,marginRight:5,float:'left'}} src={this.props.IndexPage.userInfo.avatar} alt=""/>
                <Dropdown overlay={menu} >
                  <a className="ant-dropdown-link" href="#" style={{color:'black',fontSize:14,float:'left',lineHeight:'30px'}}>
                    {this.props.IndexPage.userInfo.username} <Icon type="down" />
                  </a>
                </Dropdown>
              </div>:
              <i onClick={this.openLogin} style={{fontSize:30,color:'rgb(18,183,245)',cursor:'pointer'}} className="icon-QQ iconfont"/>
            }

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
