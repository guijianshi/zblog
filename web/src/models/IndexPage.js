import reqwest from 'reqwest'
import {message} from 'antd'
export default {

  namespace: 'IndexPage',

  state: {
    showSider:true,
    commentList:[],
    commentLoading:false,
    articleLoading:false,

  },

  subscriptions: {
    setup({ dispatch, history }) {  // eslint-disable-line
    },
  },

  effects: {
    *fetch({ payload }, { call, put }) {  // eslint-disable-line
      yield put({ type: 'save' });
    },
    *fetchComment({ payload }, { call, put }){
      yield put({ type: 'showCommentLoading'});
      const data=yield call(function request(){
        return reqwest({
          url:'http://localhost:8888/index/comment/'+payload.aid,
          method:'get',
        }).then((data)=>{return data})
      });
      if(data.ret==1){
        yield put({ type: 'showComment',payload:{commentList:data.data,commentLoading:false} });
      }
    },
    *addComment({ payload }, { call, put }){
      const data=yield call(function request(){
        return reqwest({
          url:'http://localhost:8888/index/comment/create',
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
