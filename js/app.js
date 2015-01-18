define(['config', 'lightbox'], function(config) {
    
  var App = window.App = {};
  App.config = config;
  
  require(['router/app-router'], function(AppRouter) {
    new AppRouter();
  });
});
