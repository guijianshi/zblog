/**
 * Created by Administrator on 2017/7/13.
 */
import React from 'react'
import { connect } from 'dva';
import {Row, Col,Tabs,Card,Icon} from 'antd'
import reqwest from 'reqwest'
const TabPane = Tabs.TabPane;
import url from '../utils/url'
import CardArticleList from '../components/CardArticleList'
class Home extends React.Component{
  constructor(props) {
    super(props);
    this.state={
      articleList:''
    }
  }
  componentDidMount(){
    this.props.dispatch({type:'IndexPage/showArticleLoading'});
      reqwest({url:url+'index/index/index'}).then((data)=>{
        var articleList=data.data
        this.setState({articleList});
        this.props.dispatch({type:'IndexPage/hideArticleLoading'});
      })
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
      <CardArticleList {...props}   />
    )
  }
}

Home.contextTypes = {
  router: React.PropTypes.object.isRequired
};


export default connect(({ IndexPage }) => ({
  IndexPage,
}))(Home);
