/**
 * Created by Administrator on 2017/7/13.
 */
import React from 'react'
import { connect } from 'dva';
import {Row, Col,Tabs,Card,Icon} from 'antd'
import reqwest from 'reqwest'
const TabPane = Tabs.TabPane;
import url from '../utils/url'
import CardArticleList from '../components/CardArticleList/CardArticleList'
class SearchResult extends React.Component{
  constructor(props) {
    super(props);
    this.state={
      articleList:''
    }
  }
  fetch(key){
    this.props.dispatch({type:'IndexPage/showArticleLoading'});
    reqwest({url:url+'index/article/search',data:{key}}).then((data)=>{

     var articleList=data.data.map((article)=>{
       var reg = new RegExp(key);
       article.title=article.title.replace(reg,'<span class="sKey">'+key+'</span>')
      return article
      })
      this.setState({articleList});
      this.props.dispatch({type:'IndexPage/hideArticleLoading'});
    })
  }
  componentDidMount(){
    this.fetch(this.props.params.key)
    }
  componentWillReceiveProps(newProps){
    if(newProps.params.key!=this.props.params.key){
      this.fetch(newProps.params.key)
    }
  }
  toggleSider=(method)=>{
    this.props.dispatch({type:'IndexPage/'+method})
  }
  toTag=(tname)=>{
    this.context.router.push({pathname:'article/tag',query:{name:tname}})
  }
  render(){
    const props={
      toTag:this.toTag,
      addComment:(payload)=>{this.props.dispatch({type:'IndexPage/addComment',payload})},
      toggleSider:this.toggleSider,
      articleList:this.state.articleList,
    }
    return (
      <div>
        <p style={{fontSize:16,position:'absolute',top:'-25px'}}><span style={{fontWeight:700}}>{this.props.params.key}</span> 的搜索结果为:</p>
        <CardArticleList {...props}  />

      </div>
    )
  }
}
SearchResult.contextTypes = {
  router: React.PropTypes.object.isRequired
};
export default connect(({ IndexPage }) => ({
  IndexPage,
}))(SearchResult);
