define(['underscore', 'backbone', 
        'collection/annonces', 'hbs!template/annonces', 'config'], 
       function(_, Backbone, Annonces, template, config) {

    var AnnoncesListView = Backbone.View.extend({
        initialize: function() {
            _.bindAll(this, "onAnnoncesFetched");
            this.annonces = new Annonces();
        },
        
        render: function() {
            this.annonces.fetch({
                success: this.onAnnoncesFetched
            });
        },
            
        onAnnoncesFetched: function() {
            this.$el.html(template({
                collection: this.annonces.toJSON(),
                config: config
            }));
        }
     
    });
    return AnnoncesListView;
});