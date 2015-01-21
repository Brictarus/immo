define(['backbone', 'hbs!template/image-uploader', 'i18n!nls/labels'], 
       function(Backbone, template, labels) {
  var ImageUploaderView = Backbone.View.extend({
    tagName: 'tr',
    
    initialize: function(options) {
      this.name = options.name;
      this.index = options.index;
      this.progress = 0;
      this.hasDoneFirstRender
    },
    
    render: function() {
      this.$el.html(template({ name : this.name, progress: this.progress }));
      return this;
    },
    
    updateProgress: function(progress) {
      this.progress = progress;
      this.render();
    }
  });
  return ImageUploaderView;
});