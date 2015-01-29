define(['underscore', 'backbone', 'view/custom-view',
    'model/annonce', 'model/enum/type-stationnement', 'hbs!template/annonce-detail', 'config', 'i18n!nls/labels'],
  function (_, Backbone, CustomView, Annonce, TypeStationnementEnum, template, config, labels) {

    var AnnonceDtailView = CustomView.extend({
      initialize: function (options) {
        _.bindAll(this, "onAnnonceFetched");
        this.model = new Annonce({id: options.annonceId});
      },

      render: function () {
        this.model.fetch({
          success: this.onAnnonceFetched
        });
        return this;
      },

      onAnnonceFetched: function () {
        var context = {
          model: this.model.toJSON(),
          config: config,
          labels: labels
        };
        var typeStationnement = this.model.get('type_stationnement');
        context.typeStationnementLabel = (typeStationnement ? TypeStationnementEnum[typeStationnement].label : null);
        this.$el.html(template(context));
      }

    });
    return AnnonceDtailView;
  });