define(['backbone', 'model/photo'], function (Backbone, Photo) {
  var Annonces = Backbone.Collection.extend({
    url: App.config.services.photo,
    model: Photo
  });

  return Annonces;
});