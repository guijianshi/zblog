/**
 * Created by Administrator on 2017/7/5.
 */
import React from  'react'
import { Link } from 'react-router'
import {Menu, Icon, Popover,Button,Input} from 'antd'
const SubMenu = Menu.SubMenu;
const MenuItemGroup = Menu.ItemGroup;
import Background from '../../assets/img/bac.jpg'
const defaultBac='http://i4.bvimg.com/608112/aa08b9ac86a5da5f.jpg'
class Header extends React.Component{
  constructor(props)
  {
    super(props);
    this.state={
      current:this.props.current,
      key:"",
      bac:'bac.jpg',
      fullPath:'../../assets/img/bac.jpg',
      fade:'',
      backgroundUrl:defaultBac
    }
  }
  componentWillReceiveProps(newProps){
    //只在初始化current没值时使用props传来的值
    if(!this.state.current){
      if(newProps.activeRoute){
        this.setState({current:newProps.activeRoute})
      }
    }
    if(newProps.scrollTop===true){
      this.setState({fade:'fadeIn'})
    }
    if(newProps.scrollTop===false)
    {
      this.setState({fade:'fadeOut'})
    }
   /* if(newProps.current!=this.state.current){
      console.log(newProps)
      this.setState({current:newProps.current})
    }*/
  }
  searchArticle=(e)=>{
    if(e.keyCode==13){
        this.props.searchArticle(this.state.key)
    }
  }
  handleClick=(e)=>{
    this.setState({
      current: e.key,
    });
  }
  render(){
    return (
      <div style={{position:'relative',top:0,width:'100%',zIndex:99999}}>
        <div className="clearfix header" style={{   height:300}}  >
          <span style={{width:200,overflow:'hidden',display:'inline-block'}}>
                      <img style={{height:50,marginTop:0,marginLeft:16}} src={require('../../assets/img/logowhite.png')} alt=""/>
          </span>

          {/*背景图*/}
          {this.props.bacList.map((bac,index)=>{
            return (
              <div key={index} className={this.props.activeRoute==bac.label?'fadeBacImg fadeActive':'fadeBacImg'} style={{backgroundImage:`url(${bac.url})`}} />
            )
          })}
          <p style={{position:'absolute',fontSize:60,top:90,width:'100%',textAlign:'center',color:'white'}}>
            {this.props.activeRoute}
          </p>
          {/*导航栏*/}
          <Menu
            className="whiteMenu"
            onClick={this.handleClick}
            selectedKeys={[this.state.current]}
            style={{float:'right',borderWidth:0,fontSize:16,lineHeight:"50px",background:'none',color:'white'}}
            mode="horizontal">
            <Menu.Item key="home">
              <Link to="/home"><span><Icon type="home" />首页</span></Link>
            </Menu.Item>
            {this.props.menuList.map((route)=>{
              return (
                <Menu.Item key={route.label}>
                  <Link to={{pathname:'/article/category',query:{name:route.label,type:'category'}}} ><span><Icon type={route.icon} />{route.label}</span></Link>
                </Menu.Item>
              )
            })}
            <Menu.Item>
              <a href=""><span><Icon type="setting" />网站设置</span></a>
            </Menu.Item>
          </Menu>

          {/*搜索*/}
          <Button.Group style={{background:'none',float:'right',lineHeight:'52px',marginRight:20}}>
            <Input onKeyUp={this.searchArticle} value={this.state.key}  onChange={(e)=>{this.setState({key:e.target.value}) }} style={{background:'none',width:160,borderTopRightRadius:0,borderBottomRightRadius:0,color:'white'}}  />
            <Button style={{background:'none',color:'white',borderLeft:'none'}} onClick={()=>{ this.props.searchArticle(this.state.key)}}><Icon type="search"></Icon>站内搜索</Button>
          </Button.Group>
        </div>

        {/*固定定位头*/}
        <div className={'clearfix fixHeader '+this.state.fade}  >
          <span style={{width:200,overflow:'hidden',display:'inline-block'}}>
              <img style={{height:50,marginTop:0,marginLeft:16}} src={require('../../assets/img/logo.png')} alt=""/>
          </span>
          <Menu
            className='fixMenu'
            onClick={this.handleClick}
            selectedKeys={[this.state.current]}
            style={{float:'right',borderWidth:0,fontSize:16,lineHeight:"50px"}}
            mode="horizontal">
            <Menu.Item key="index">
              <Link to="/home"><span><Icon type="home" />首页</span></Link>
            </Menu.Item>
            {this.props.menuList.map((route)=>{
              return (
                <Menu.Item key={route.label}>
                  <Link to={{pathname:'/article/category',query:{name:route.label,type:'category'}}} ><span><Icon type={route.icon} />{route.label}</span></Link>
                </Menu.Item>
              )
            })}
            <Menu.Item>
              <a href=""><span><Icon type="setting" />网站设置</span></a>
            </Menu.Item>
          </Menu>
          <Button.Group style={{float:'right',lineHeight:'52px',marginRight:20}}>
            <Input onKeyUp={this.searchArticle} value={this.state.key}  onChange={(e)=>{this.setState({key:e.target.value}) }} style={{width:160,borderTopRightRadius:0,borderBottomRightRadius:0,color:'rgba(0,0,0,0.6)',borderColor:'rgba(0,0,0,0.6)'}}  />
            <Button style={{color:'rgba(0,0,0,0.9)',borderColor:'rgba(0,0,0,0.6)',borderLeft:'none'}} onClick={()=>{ this.props.searchArticle(this.state.key)}}><Icon type="search"></Icon>站内搜索</Button>
          </Button.Group>
        </div>
      </div>


    )
  }
}
export default Header
