define(['backbone'], function (Backbone) {
  var Photo = Backbone.Model.extend({
    url: function () {
      if (this.isNew()) {
        return App.config.services.photo;
      } else {
        return App.config.services.photo + "?id=" + this.id;
      }
    },

    test: "toto"
  });

  return Photo;
});
