define(['underscore', 'backbone', 
        'model/annonce', 'hbs!template/annonce-detail', 'config'], 
       function(_, Backbone, Annonce, template, config) {

    var AnnonceDtailView = Backbone.View.extend({
        initialize: function(options) {
            _.bindAll(this, "onAnnonceFetched");
            this.annonce = new Annonce({id: options.annonceId});
        },
        
        render: function() {
            this.annonce.fetch({
                success: this.onAnnonceFetched
            });
        },
            
        onAnnonceFetched: function() {
            this.$el.html(template({
                model: this.annonce.toJSON(),
                config: config
            }));
        }
     
    });
    return AnnonceDtailView;
});