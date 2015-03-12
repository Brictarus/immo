define(['underscore', 'backbone', 'view/external-annonce-modal'],
  function(_, Backbone, ExternalAnnonceModal) {

    var SeLoger = ExternalAnnonceModal.extend({
      providerDomain: "seloger.com",

      initialize: function(options) {
        options.title = options.title || "Ajout d'annonce SeLoger";
        SeLoger.__super__.initialize.apply(this, arguments)
      }
    });
    return SeLoger;
  });