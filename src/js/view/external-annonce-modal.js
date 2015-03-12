define(['underscore', 'backbone', 'view/custom-view',
    'hbs!template/external-annonce-modal', 'config'],
  function(_, Backbone, CustomView, template, config) {

    var Modal = CustomView.extend({
      events: {
        "click .save-external": "save"
      },

      providerDomain: "to_be_inherited",

      initialize: function(options) {
        this.title = options.title || "Ajout d'annonce externe";
        this.render();
      },

      render: function() {
        this.$el.html(template({
          title: this.title,
          config: config
        }));
        var $modal = this.$('#external-annonce-modal');
        $modal.one('shown.bs.modal', _.bind(function() {
          this.$('#url-external-annonce').focus();
        }, this));
        $modal.modal();
        return this;
      },

      onSaveSuccess: function (response) {
        var $modal = this.$('#external-annonce-modal');
        this.restoreButtonsState();
        $modal.one('hidden.bs.modal', function () {
          App.router.navigate('#annonce/' + response.id, {trigger: true});
        });
        $modal.modal('hide');
      },

      save: function() {
        var url = this.$('#url-external-annonce').val();
        this.$('.save-external').button('loading');
        this.$('.reset').attr('disabled', true);
        if (url.indexOf(this.providerDomain) !== -1) {
          $.post(
            App.config.services.annonceExterne + "?url=" + url,
            null,
            _.bind(this.onSaveSuccess, this),
            "json"
          );
        } else {
          this.restoreButtonsState();
        }
      },

      restoreButtonsState: function() {
        this.$('.save-external').button('reset');
        this.$('.reset').attr('disabled', false);
      }

    });
    return Modal;
  });