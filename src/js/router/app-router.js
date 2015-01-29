define(['backbone', 'config', 'model/annonce', 'view/annonce-detail-view', 'view/annonces-view', 'view/annonce-form-view', 'backbone-queryparams'],
       function(Backbone, config, Annonce, AnnonceDetailView, AnnoncesListView, AnnonceFormView) {
    
    var AppRouter = Backbone.Router.extend({
        
      initialize: function() {
        App.view = null;
        Backbone.history.start({ pushState: false, root: config.urlRoot });
      },

      routes: {
        '': 'main',
        '/': 'main',
        'annonce/:id': 'detail',
        'annonce/:id/modifier': 'updateAnnonce',
        'nouvelle-annonce': 'newAnnonce',
        'nouvelle-annonce/leboncoin': 'newAnnonceBonCoin',
        'nouvelle-annonce/seloger': 'newAnnonceSeLoger',
        '*notFound': 'notFound'
      },

      main: function() {
        // Instanciation des vues
        App.creationMenu.showButtonNew().hideButtonUpdate();
        App.view && App.view.remove();
        var annoncesListView = new AnnoncesListView({
            el: "#main"
        });
        annoncesListView.render();
        App.view = annoncesListView;
      },

      detail: function(id) {
        App.creationMenu.showButtonNew().showButtonUpdate(id);
        App.view && App.view.remove();
        $("html, body").animate({ scrollTop: 0 }, "slow");
        var detailView = new AnnonceDetailView({el : '#main', annonceId: id}).render();
        App.view = App.view;
      },

      updateAnnonce: function(id) {
        App.view && App.view.remove();
        App.creationMenu.hideButtonNew().hideButtonUpdate();
        var annonce = new Annonce({id: id});
        annonce.fetch({
          success: _.bind(function() {
            App.view = new AnnonceFormView({el : '#main', model: annonce}).render();
          }, this)
        });
      },
      
      newAnnonce: function() {
        App.view && App.view.remove();
        App.creationMenu.hideButtonNew().hideButtonUpdate();
        App.view = new AnnonceFormView({el : '#main'}).render();
      },
      
      newAnnonceBonCoin: function() {
        App.view && App.view.remove();
        App.creationMenu.hideButtonNew().hideButtonUpdate();
      },
      
      newAnnonceSeLoger: function() {
        App.view && App.view.remove();
        App.creationMenu.hideButtonNew().hideButtonUpdate();
      }
    });
    
    return AppRouter;

});