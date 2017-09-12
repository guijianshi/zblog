import reqwest from 'reqwest'
import {message} from 'antd'
import url from '../utils/url'
export default {

  namespace: 'IndexPage',

  state: {
    url:'http://www.guijianshi.cn/',
    showSider:true,
    commentList:[],
    commentLoading:false,
    articleLoading:false,
    userInfo:{
      isLogin:false,
      openid:'',
      avatar:'',
      type:'',
      username:''
    },
    setting:{
      zhihu:'',
      sina:'',
      email:'',
      github:'',
      introduce:'',
      logo:'',
      avatar:''
    }

  },

  subscriptions: {
    setup({ dispatch, history }) {  // eslint-disable-line
      dispatch({type:'getSetting'})
    },
  },

  effects: {
    *fetch({ payload }, { call, put }) {  // eslint-disable-line
      yield put({ type: 'save' });
    },
    *getSetting({ payload },{call,put}){
      const data=yield call(function request(){
        return reqwest({
          url:url+'setting',
          method:'get',
        }).then((data)=>{return data})
      });
      if(data.ret==1){
        yield put({type:'saveSetting',payload:data.data})
      }
    },
    *fetchComment({ payload }, { call, put }){
      yield put({ type: 'showCommentLoading'});
      const data=yield call(function request(){
        return reqwest({
          url:url+'index/comment/'+payload.aid,
          method:'get',
        }).then((data)=>{return data})
      });
      if(data.ret==1){
        if(data.data){
         var commentList=data.data.map((comment,index)=>{
            comment.replyContent=''
           return comment
         })
        }else {
          var commentList=[]
        }
        yield put({ type: 'showComment',payload:{commentList,commentLoading:false} });
      }
    },
    *addComment({ payload }, { call, put }){
      const data=yield call(function request(){
        return reqwest({
          url:url+'index/comment/create',
          method:'post',
          data:payload
        }).then((data)=>{return data})

      });
      if(data.ret==1){
        message.success('评论添加成功！')
        yield put({type:'fetchComment',payload:{aid:payload.aid}})
      }
    }
  },

  reducers: {
    saveSetting(state,{payload}){
      console.log(payload)
      return {...state,setting:payload}
    },
    setUser(state,{payload}){
      return { ...state,...payload };
    },
    logout(state){
      return {...state,userInfo:{
        isLogin:false,
        openId:'',
        avatar:'',
        type:'',
        username:''
      }}
    },
    showS(state) {
      return { ...state, showSider:true };
    },
    hideS(state) {
      return { ...state, showSider:false };
    },
    showComment(state,{payload}) {
      return { ...state, ...payload};
    },
    showCommentLoading(state){
      return {...state,commentLoading:true}
    },
    showArticleLoading(state){
      return {...state,articleLoading:true}
    },
    hideArticleLoading(state){
      return {...state,articleLoading:false}
    }
  },

};
