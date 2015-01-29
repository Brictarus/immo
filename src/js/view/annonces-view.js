define(['underscore', 'backbone', 'view/custom-view',
        'collection/annonces', 'hbs!template/annonces', 'config'], 
       function(_, Backbone, CustomView, Annonces, template, config) {

    var AnnoncesListView = CustomView.extend({
        initialize: function() {
            _.bindAll(this, "onAnnoncesFetched");
            this.annonces = new Annonces();
        },
        
        render: function() {
          this.annonces.fetch({
              success: this.onAnnoncesFetched
          });
          return this;
        },
            
        onAnnoncesFetched: function() {
          this.$el.html(template({
            collection: this.annonces.toJSON(),
            lastIndex: this.annonces.length - 1,  
            config: config
          }));
        }
     
    });
    return AnnoncesListView;
});