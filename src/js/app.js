define(['config', 'lightbox', 'jquery.form', 'bootstrap'], function(config) {
    
  var App = window.App = {};
  App.config = config;
  
  require(['router/app-router', 'view/new-annonce-menu'], 
          function(AppRouter, AnnonceCreationMenu) {
    App.creationMenu = new AnnonceCreationMenu({ el: "#new-annonce-menu"});
    App.router = new AppRouter();
    App.creationMenu.render();
  });
});
