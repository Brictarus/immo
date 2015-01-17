define(['backbone', 'config', 'view/annonce-detail-view', 'view/annonces-view', 'backbone-queryparams'], 
       function(Backbone, config, AnnonceDetailView, AnnoncesListView) {
    
    var AppRouter = Backbone.Router.extend({
        
      initialize: function() {
          Backbone.history.start({ pushState: false, root: config.urlRoot });
      },

      routes: {
        '': 'main',
        '/': 'main',
        'annonce/:id': 'detail',
        '*notFound': 'notFound'
      },

      main: function() {
          // Instanciation des vues
          var annoncesListView = new AnnoncesListView({
              el: "#main"
          });
          annoncesListView.render();
      },

      detail: function(id) {
        new AnnonceDetailView({el : '#main', annonceId: id}).render();
      }
    });
    
    return AppRouter;

});
