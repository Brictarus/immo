define(['underscore', 'backbone', 'view/external-annonce-modal'],
  function(_, Backbone, ExternalAnnonceModal) {

    var Lbc = ExternalAnnonceModal.extend({
      providerDomain: "leboncoin.fr",

      initialize: function(options) {
        options.title = options.title || "Ajout d'annonce LeBonCoin";
        Lbc.__super__.initialize.apply(this, arguments)
      }
    });
    return Lbc;
  });