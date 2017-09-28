/**
 * Created by Administrator on 2017/7/13.
 */
import React from 'react';
import {Row, Col,Tabs,Card,Icon,Spin} from 'antd'
import reqwest from 'reqwest'
import CardArticleList from './CardArticleList/CardArticleList'
const TabPane = Tabs.TabPane;
class TabsArticle extends React.Component{
  constructor(props) {
    super(props);
    this.state = {
      articleList: '',
      childrens:[],

    }
  }
  componentDidMount(){
    document.body.scrollTop=0;
    this.setState({articleList:this.props.articleList,childrens:this.props.childrens})
  }
  componentWillReceiveProps(newProps){
    if(newProps.articleList!==this.props.articleList){
      document.body.scrollTop=0;
      this.setState({articleList:newProps.articleList,childrens:newProps.childrens})
    }
  }
  /*fetchArticle(activeKey){
    if(activeKey!='all'){
      this.setState({loading:true});
      reqwest({url:this.props.url+'/'+activeKey,
        data:{p_cname:activeKey}}).then((data)=>{
        console.log(data)
        const childrens=this.state.childrens;
        childrens.map((item)=>{
          if(item.label==activeKey){
            item.data=data.data
          }
        })
        this.setState({childrens,loading:false})
      })
    }
  }*/
  render(){
    return (
      <div style={{position:'relative',marginTop:20}} >
            <Tabs type="card" className='minH'  style={{background:'white'}} tabPosition="top" onChange={(key)=>{this.props.fetchChildCategory(key)}} >
              <TabPane tab={this.props.type=='tag'?<span><Icon type="tag" />{this.props.name}</span>:'全部'} key="all">
                  <CardArticleList toTag={(tag)=>{this.props.toTag(tag)}} urlParams={this.props.urlParams}   toggleSider={(method)=>{this.props.toggleSider(method)}}  articleList={this.state.articleList} />
              </TabPane>
              {
                this.state.childrens?this.state.childrens.map((item,index)=>{
                  console.log(item)
                  return(
                    <TabPane tab={<span>{item.icon?<Icon type={item.icon}/>:''}{item.label}</span>} key={item.label}>
                      <CardArticleList toTag={(tag)=>{this.props.toTag(tag)}} urlParams={this.props.urlParams}  toggleSider={(method)=>{this.props.toggleSider(method)}}  articleList={item.data} />
                    </TabPane>
                  )
                }):''
              }
            </Tabs>
      </div>
    );
  }


}

TabsArticle.propTypes = {
};

export default TabsArticle
