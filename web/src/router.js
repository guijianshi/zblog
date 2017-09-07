import React from 'react';
import { Router, Route,IndexRoute } from 'dva/router';
import IndexPage from './routes/IndexPage';
import Home from './routes/Home'
import Article from './routes/Article'
import SingleArticle from './routes/SingleArticle'
import SearchResult from "./routes/SearchResult";


function RouterConfig({ history }) {
  return (
    <Router history={history}>
      <Route path="/" component={IndexPage} >
        <IndexRoute component={Home}/>
        <Route path="home" component={Home} />
        <Route path="singleArticle/:id" component={SingleArticle} />
        <Route path="article/:type" component={Article} />
        <Route path="search/:key" component={SearchResult} />
        <Route path="*" component={Home} />
      </Route>
    </Router>
  );
}

export default RouterConfig;
