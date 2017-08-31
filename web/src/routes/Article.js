/**
 * Created by Administrator on 2017/7/17.
 */
import React from 'react'
import { connect } from 'dva';
import {Row, Col,Tabs,Card,Icon,Spin} from 'antd'
import reqwest from 'reqwest'
import TabsArticle from '../components/TabsArticle'
class Article extends React.Component{
  constructor(props) {
    super(props);
    this.state={
      urlParams:'',
      articleList:'',
      childrens:[],
      loading:true
    }
  }
  fetch(type,name){
    this.props.dispatch({type:'IndexPage/showArticleLoading'});
    reqwest({url:'http://localhost:8888/index/index/'+type+'/'+name}).then((data)=>{
      const articleList=data.data;
      const childrens=data.child;
      this.setState({articleList,childrens});
      this.props.dispatch({type:'IndexPage/hideArticleLoading'})
    })
  }
  fetchChildCategory=(activeKey)=>{
    if(activeKey!='all'){
      reqwest({url:'http://localhost:8888/index/index/'+this.props.routeParams.type+'/'+activeKey}).then((data)=>{
       /* const articleList=data.data;*/
        const childrens=this.state.childrens;
        childrens.map((item)=>{
          if(item.label==activeKey){
            item.data=data.data
          }
        })
        console.log(activeKey)
        this.setState({childrens})
       /* console.log(articleList)
        this.setState({articleList});*/
        this.props.dispatch({type:'IndexPage/hideArticleLoading'})
      })
    }
  }
  componentWillReceiveProps(nextProps){
    if(this.props.location.search!=nextProps.location.search){
      this.setState({urlParams:nextProps.location.search})
      this.fetch(nextProps.routeParams.type,nextProps.location.query.name)
    }
  }
  componentDidMount(){
    document.body.scrollTop=0;
    this.fetch(this.props.routeParams.type,this.props.location.query.name)
  }
  toTag=(tname)=>{
    this.context.router.push({pathname:'article/tag',query:{name:tname}})
  }
  render(){
    const props={
      fetchChildCategory:this.fetchChildCategory,
      toTag:this.toTag,
      type:this.props.routeParams.type,
      name:this.props.location.query.name,
      urlParams:this.state.urlParams,
      addComment:(payload)=>{this.props.dispatch({type:'IndexPage/addComment',payload})},
      commentList:this.props.IndexPage.commentList,
      commentLoading:this.props.IndexPage.commentLoading,
      fetchComment:(aid)=>{this.props.dispatch({type:'IndexPage/fetchComment',payload:{aid:aid}})},
      toggleSider:(method)=>{this.props.dispatch({type:'IndexPage/'+method})},
      articleList:this.state.articleList,
      childrens:this.state.childrens
    };
    return (
      <div style={{position:'relative'}}>
        <TabsArticle
          {...props}
          /*toTag={this.toTag}
          type={this.props.routeParams.type} name={this.props.location.query.name}
          urlParams={this.state.urlParams}  addComment={(payload)=>{this.props.dispatch({type:'IndexPage/addComment',payload})}} commentList={this.props.IndexPage.commentList} commentLoading={this.props.IndexPage.commentLoading} fetchComment={(aid)=>{this.props.dispatch({type:'IndexPage/fetchComment',payload:{aid:aid}})}} toggleSider={(method)=>{this.props.dispatch({type:'IndexPage/'+method})}}
                     articleList={this.state.articleList} childrens={this.state.childrens}*/
        />
      </div>

    )
  }
}


Article.contextTypes = {
  router: React.PropTypes.object.isRequired
};

export default connect(({ IndexPage }) => ({
  IndexPage,
}))(Article);
