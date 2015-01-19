define(['backbone', 'hbs!template/annonce-form-view', 'i18n!nls/labels'], 
       function(Backbone, template, labels) {
  var AnnonceFormView = Backbone.View.extend({
    render: function() {
      this.$el.html(template({labels: labels}));
      return this;
    }
  });
  return AnnonceFormView;
});