import React, { PropTypes } from 'react';
import {Header} from '../components/Layout'
import NewestArticleList from  '../components/NewestArticleList'
import TagList from  '../components/TagList'
import Introduce from  '../components/Introduce'
import { connect } from 'dva';
import url from '../utils/url'
import {Row, Col,Tabs,Card,Icon,Spin,Progress} from 'antd'
import reqwest from 'reqwest'
const TabPane = Tabs.TabPane;

class IndexPage extends React.Component{
  callback(activeKey){
  console.log(activeKey)
  }
  constructor(props) {
    super(props);
    this.state = {
      articleList: [],
      active:null,
      menuList:[],
      newestArticle:[],
      InitX:0,
      moveX:0,
      percent:0,
      scrollTop:0,
      go:false,
      bacList:[],
      activeRoute:'',
    }
  }
  componentWillReceiveProps(nextProps){
    console.log(nextProps.location.pathname)
   var routeList= nextProps.location.pathname.substring(1).split('/')
    console.log(routeList);
    var activeRoute=''
    if(!routeList[0]){
       activeRoute='home'
    }else{
      if (routeList[0]!='article'){
         activeRoute=routeList[0]
      }else {
        if(routeList[1]=='tag'){
           activeRoute='tag'
        }else {
           activeRoute=nextProps.location.query.name
        }
      }
    }

    console.log(activeRoute)
    this.setState({activeRoute})
    console.log(nextProps)
   /* if(this.props.location.pathname!=nextProps.location.pathname||this.props.location.search!=nextProps.location.search){
      this.props.dispatch({type:'IndexPage/showS'})
    }*/
  }
  handleScroll=(e)=>{
    var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;
    this.setState({scrollTop:scrollTop>0?true:false})
  }
  componentDidMount(){
    /*var dom = document.getElementById(opts['btnId']),
      _logoutTemplate=[
        //头像
        '<span><img src="{figureurl}" class="{size_key}"/></span>',
        //昵称
        '<span>{nickname}</span>',
        //退出
        '<span><a href="javascript:QC.Login.signOut();">退出</a></span>'
      ].join("");
    dom && (dom.innerHTML = window.QC.String.format(_logoutTemplate, {
      nickname : window.QC.String.escHTML(reqData.nickname), //做xss过滤
      figureurl : reqData.figureurl
    }));*/



    window.QC.api("get_user_info", {})
      .success((s)=>{
        console.log(this)
        if(window.QC.Login.check()){//如果已登录
          console.log(s)
          window.QC.Login.getMe((openid, accessToken)=>{
            this.props.dispatch({type:'IndexPage/setUser',payload:{userInfo:{openid,username:s.data.nickname,avatar:s.data.figureurl_qq_1,type:'qq',isLogin:true}}})
            /*alert(["当前登录用户的", "openId为："+openId, "accessToken为："+accessToken].join("\n"));*/
          });
        }
        if(window.opener){
          window.opener.location.reload(); //刷新父窗口中的网页
          window.close()
        }
        console.log(s)
      })
      .error(function(f){
      })
      //指定接口完成请求后的接收函数，c为完成请求返回Response对象
      .complete((c)=>{
        //完成请求回调
        console.log(c)
        /*alert("获取用户信息完成！");*/
      });





    window.addEventListener('scroll',this.handleScroll)
    document.body.scrollTop=0
    reqwest({url:url+'index/index/index'}).then((data)=>{
        const articleList=data.data
      if(data.data.length>5){
         var newestArticle= data.data.slice(0,5)
      }else {
        var newestArticle=[...data.data]
      }
      this.setState({articleList,newestArticle})
    })
    reqwest({url:url+'v1/category/get'}).then((data)=>{
      const menuList=data.data.map((item)=>{
        return {label:item.label,icon:item.icon,url:item.pic_url}
      });
      const bacList=data.data.map((item)=>{
        return {label:item.label,url:item.pic_url}
      });
      bacList.push({label:'home',url:'http://i4.bvimg.com/608112/aa08b9ac86a5da5f.jpg'},
        {label:'singleArticle',url:'http://i4.bvimg.com/608112/2217625fb504ba28.png'},
        {label:'search',url:'http://i4.bvimg.com/608112/f5616d00d24645ea.png'},
        {label:'tag',url:'http://i4.bvimg.com/608112/494cda5930835cef.png'})
      this.setState({menuList,bacList})
    })
    /*  this.setState({current:this.props.location.query.name})*/
  }
  componentWillUnmount(){
    window.removeEventListener('scroll',this.handleScroll)
  }
  toTag=(tname)=>{
    this.context.router.push({pathname:'/article/tag',query:{name:tname}})
  }
  showArticle(index){
    var scrollTop = document.documentElement.scrollTop||document.body.scrollTop;
    if(scrollTop>80){
      scrollTop=80
    }
    this.props.dispatch({type:'IndexPage/hideS'})
    this.setState({active:index})
  }
  hideArticle(){
    var scrollTop = document.documentElement.scrollTop||document.body.scrollTop;
    if(scrollTop>80){
      scrollTop=80
    }
    this.props.dispatch({type:'IndexPage/showS'})
    this.setState({active:null})
  }
  searchArticle(key){
    if(key){
      this.context.router.push({pathname:'search/'+key})
    }else {
      this.context.router.push({pathname:'home'})
    }
  }
  slideInit=(e)=>{
    this.setState({InitX:e.clientX})
  }
  slideOver=(e)=>{
    if(this.state.InitX){
      if(e.clientX-this.state.InitX>=150){
        this.props.dispatch({type:'IndexPage/hideS'})
      }
      this.setState({InitX:0},()=>{this.setState({moveX:0,percent:0})})
    }
  }
  sliding=(e)=>{
    if(this.state.InitX){
      if(e.clientX-this.state.InitX>0){
        if(e.clientX-this.state.InitX>150){
          var percent=100
        }else {
          var percent=parseInt((e.clientX-this.state.InitX)*2/3)
        }
        this.setState({moveX:e.clientX-this.state.InitX,percent:percent})
      }
    }
  }
  goTop=()=>{
    this.setState({go:true})
    var topInterVal=setInterval(()=>{
      var scrollTop = document.documentElement.scrollTop||document.body.scrollTop;
      var speed=Math.ceil(scrollTop/12)
      document.documentElement.scrollTop = document.body.scrollTop = scrollTop - speed;
      if(scrollTop==0){
        clearInterval(topInterVal)
        this.setState({go:false})
      }
    },20)

  }
  render(){
    return (
      <div  onMouseUp={this.slideOver}  style={{paddingBottom:20,color:'black'}} className="clearfix" >
        {/*头部*/}
        <Header scrollTop={this.state.scrollTop} activeRoute={this.state.activeRoute} searchArticle={(key)=>{this.searchArticle(key)}} menuList={this.state.menuList} bacList={this.state.bacList}    />
        {/*进度条*/}
        <Progress strokeWidth={4} showInfo={false} style={{position:'relative',top:'-12px',zIndex:99999}} percent={this.state.percent} />
        {/*内容区*/}
        <Col style={{marginTop:40}} span={14} offset={5}>
          <Col   style={{minHeight:1000,position:'relative'}} span={this.props.IndexPage.showSider?16:23} >
            <div >
              {this.props.IndexPage.articleLoading?<Spin size="large" style={{position:'absolute',left:'48%',top:'350px',zIndex:1111}} />:''}
              {this.props.children}
            </div>
          </Col>
          {/*边栏*/}
          {this.props.IndexPage.showSider?
            <Col className="rightContent" style={{overflow:'hidden'}} onMouseDown={this.slideInit} onMouseMove={this.sliding}  span={7} offset={1} >
              <div style={{position:'relative',left:this.state.moveX}} className={this.state.InitX?'':'transition'} >
                <TagList toTag={this.toTag }  />
                <Introduce/>
                <NewestArticleList newestArticle={this.state.newestArticle}/>
              </div>
          </Col>:''}
        </Col>
        {/*小火箭*/}
        {this.state.scrollTop?
          <i  onClick={this.goTop} className={this.state.go?'goTop go icon-huojiancopy iconfont':'goTop icon-huojiancopy iconfont'}  >
            <i  style={{position:'absolute',color:this.state.go?'red':''}} className="icon-huo3 iconfont fire " />
          </i>
          :''}
      </div>
    );
  }

}
IndexPage.contextTypes = {
  router: React.PropTypes.object.isRequired
};


export default connect(({ IndexPage }) => ({
  IndexPage,
}))(IndexPage);
