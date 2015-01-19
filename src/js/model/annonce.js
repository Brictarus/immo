define(['backbone'], function(Backbone) {
    var Annonce = Backbone.Model.extend({
      url: function() {
        if (this.isNew()) {
          return App.config.services.annonce;
        } else {
          return App.config.services.annonce + "?id=" + this.id;
        }
      }
    });
    
    return Annonce;
});