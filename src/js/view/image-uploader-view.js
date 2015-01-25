define(['backbone', 'hbs!template/image-uploader', 'i18n!nls/labels'],
  function (Backbone, template, labels) {
    var ImageUploaderView = Backbone.View.extend({
      tagName: 'tr',

      events: {
        "click .photo-remove": "removePhoto"
      },

      initialize: function (options) {
        /*this.name = options.name;*/
        this.index = options.index;
        this.model = options.model;
        if (this.model.isNew()) {
          this.listenToOnce(this.model, "change", this.onModelChange);
        }
        this.progress = this.model.isNew() ? 0 : 100;
      },

      onModelChange: function () {
        this.render();
      },

      render: function () {
        this.$el.html(template({model: this.model.toJSON(), name: this.name, progress: this.progress}));
        return this;
      },

      updateProgress: function (progress) {
        this.progress = progress;
        this.render();
      },

      removePhoto: function() {
        this.$el.fadeOut(_.bind(function() {
          this.model.destroy();
          this.remove();
        }, this));
      }
    });
    return ImageUploaderView;
  });