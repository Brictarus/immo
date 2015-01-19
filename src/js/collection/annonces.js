define(['backbone', 'model/annonce'], function (Backbone, Annonce) {
    var Annonces = Backbone.Collection.extend({
      url: App.config.services.annonce,
      model: Annonce
    });
    
    return Annonces;
});