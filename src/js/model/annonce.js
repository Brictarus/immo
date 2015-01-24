define(['backbone'], function (Backbone) {
  var Annonce = Backbone.Model.extend({
    url: function () {
      if (this.isNew()) {
        return App.config.services.annonce;
      } else {
        return App.config.services.annonce + "?id=" + this.id;
      }
    },

    validation: {
      type_logement: [{
        required: true,
        msg: 'Le type de logement est obligatoire'
      }, {
        oneOf: ["T2BISS", "T3T", "T3BIS", "T4", "T4BIS", "T5", "T5+"],
        msg: 'Le type de logement n\'est pas supporté'
      }],
      surface: [{
        required: true,
        msg: "La surface est obligatoire"
      }, {
        pattern: "number",
        msg: "La surface doit être un nombre entier"
      }, {
        min: 0,
        msg: "La surface doit être un nombre positif"
      }],
      label: [{
        required: true,
        msg: 'Le type de logemet est obligatoire'
      }, {
        maxLength: 128,
        msg: "Le titre ne doit pas excéder 128 caratères"
      }],
      prix : [{
        required: false
      }, {
        pattern: "number",
        msg: "Le prix doit être un nombre entier"
      }, {
        min: 0,
        msg: "Le prix doit être un nombre positif"
      }],
      adresse: [{
        required: false
      }, {
        maxLength: 256,
        msg: "L\'adresse ne doit pas excéder 256 caratères"
      }],
      montant_charges: [{
        required: false
      }, {
        pattern: "number",
        msg: "Le montant des charges doit être un nombre entier"
      }, {
        min: 0,
        msg: "Le montant des charges doit être un nombre positif"
      }],
      taxe_habitation: [{
        required: false
      }, {
        pattern: "number",
        msg: "La taxe d\'habitation doit être un nombre entier"
      }, {
        min: 0,
        msg: "La taxe d\'habitation doit être un nombre positif"
      }],
      taxe_fonciere: [{
        required: false
      }, {
        pattern: "number",
        msg: "La taxe foncière doit être un nombre entier"
      }, {
        min: 0,
        msg: "La taxe foncière doit être un nombre positif"
      }]
    }


  });

  return Annonce;
});