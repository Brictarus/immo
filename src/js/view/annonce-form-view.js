define(['backbone', 'view/add-image-form-view', 'hbs!template/annonce-form-view', 'i18n!nls/labels'], 
       function(Backbone, AddImageFormView, template, labels) {
  var AnnonceFormView = Backbone.View.extend({
    render: function() {
      this.$el.html(template({labels: labels}));
      var addImgForm = new AddImageFormView({ el: "#add-image-container" });
      addImgForm.render();
      return this;
    }
  });
  return AnnonceFormView;
});