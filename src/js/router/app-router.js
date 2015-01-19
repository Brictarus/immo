define(['backbone', 'config', 'view/annonce-detail-view', 'view/annonces-view', 'view/annonce-form-view', 'backbone-queryparams'], 
       function(Backbone, config, AnnonceDetailView, AnnoncesListView, AnnonceFormView) {
    
    var AppRouter = Backbone.Router.extend({
        
      initialize: function() {
          Backbone.history.start({ pushState: false, root: config.urlRoot });
      },

      routes: {
        '': 'main',
        '/': 'main',
        'annonce/:id': 'detail',
        'nouvelle-annonce': 'newAnnonce',
        'nouvelle-annonce/leboncoin': 'newAnnonceBonCoin',
        'nouvelle-annonce/seloger': 'newAnnonceSeLoger',
        '*notFound': 'notFound'
      },

      main: function() {
        // Instanciation des vues
        App.creationMenu.show();
        var annoncesListView = new AnnoncesListView({
            el: "#main"
        });
        annoncesListView.render();
      },

      detail: function(id) {
        App.creationMenu.show();
        new AnnonceDetailView({el : '#main', annonceId: id}).render();
      },
      
      newAnnonce: function() {
        App.creationMenu.hide();
        new AnnonceFormView({el : '#main'}).render();
      },
      
      newAnnonceBonCoin: function() {
        App.creationMenu.hide();
      },
      
      newAnnonceSeLoger: function() {
        App.creationMenu.hide();
      }
    });
    
    return AppRouter;

});