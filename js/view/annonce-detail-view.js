define(['underscore', 'backbone', 
        'model/annonce', 'hbs!template/annonce-detail', 'config', 'i18n!nls/labels'], 
       function(_, Backbone, Annonce, template, config, labels) {

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
                config: config,
                labels: labels
            }));
        }
     
    });
    return AnnonceDtailView;
});