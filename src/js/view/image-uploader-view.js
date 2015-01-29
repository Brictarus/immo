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
        this.listenTo(this.model, "change", this.onModelChange);
        this.progress = this.model.isNew() ? 0 : 100;
      },

      onModelChange: function () {
        this.render();
      },

      render: function () {
        this.$el.html(template({model: this.model.toJSON(), progress: this.progress}));
        return this;
      },

      updateProgress: function (progress) {
        this.progress = progress;
        this.render();
      },

      removePhoto: function() {
        var favoriteId = this.$('input[name="favourite-pic"]:checked').val();
        if (favoriteId) {
          favoriteId = parseInt(favoriteId);
          var favorite = this.model.id == favoriteId;
        }
        this.$el.fadeOut(_.bind(function() {
          this.model.destroy({
            success: _.bind(function() {
              if (favorite) {
                this.trigger("delete:favourite");
              }
            }, this)
          });
          this.remove();
        }, this));
      }
    });
    return ImageUploaderView;
  });