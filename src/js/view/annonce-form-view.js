define(['underscore',
    'backbone',
    'model/annonce',
    'view/add-image-form-view',
    'model/enum/type-stationnement',
    'hbs!template/annonce-form-view',
    'hbs!template/alert-bootstrap',
    'i18n!nls/labels',
    'backbone.validation'],
  function (_, Backbone, Annonce, AddImageFormView, TypeStationnementEnum, template,
            alertTemplate, labels) {
    var AnnonceFormView = Backbone.View.extend({
        events: {
          'submit #annonce-form': 'onSubmitForm'
        },

        typesLogements: ["T2BIS", "T3", "T3BIS", "T4", "T4BIS", "T5", "T5+"],

        initialize: function (options) {
          if (options.model) {
            this.model = options.model;
          } else {
            this.model = new Annonce({
              type_logement: "T3",
              surface: 70,
              label: "Mon annonce " + Date.now()
            });
          }
          Backbone.Validation.bind(this, {
            valid: _.bind(this.valid, this),
            invalid: _.bind(this.invalid, this)
          });
        },

        render: function () {
          this.$el.html(template({
            labels: labels,
            model: this.model.toJSON(),
            typesLogements: this.typesLogements,
            typesStationnement: TypeStationnementEnum.values
          }));

          this.addImgFormView = new AddImageFormView({
            el: "#add-image-container",
            photos: this.model.get('photos')
          });
          this.addImgFormView.render();
          return this;
        },

        onSubmitForm: function () {
          this.populateModel(this.model);
          console.log(this.model.toJSON());
          if (this.model.isValid(true)) {
            this.model.save({}, {
              success: _.bind(function () {
                App.router.navigate('#annonce/' + this.model.id, {trigger: true});
              }, this),
              error: function() {
                debugger;
              }
            });
          }
          return false;
        },

        populateModel: function (model) {
          var m = model || this.model;
          if (!m) throw 'no model to populate available';
          var attrs = {
            type_logement: this.$('#type-logement').val() || null,
            surface: this.$('#surface').val() || null,
            nb_chambres: this.$('#nb-chambres').val() || null,
            label: this.$('#label').val() || null,
            description: this.$('#description').val() || null,
            prix: this.$('#prix').val() || null,
            adresse: this.$('#adresse').val() || null,
            etage: this.$('#etage').val() || null,
            nb_etages: this.$('#nb-etages').val() || null,
            montant_charges: this.$('#montant-charges').val() || null,
            ch_entretien_commun: this.$('#ch-entretien-commun').is(':checked') ,
            ch_eau_froide: this.$('#ch-eau-froide').is(':checked'),
            ch_eau_chaude: this.$('#ch-eau-chaude').is(':checked'),
            ch_chauffage: this.$('#ch-chauffage').is(':checked'),
            ch_gardien: this.$('#ch-gardien').is(':checked'),
            taxe_habitation: this.$('#taxe-habitation').val() || null,
            taxe_fonciere: this.$('#taxe-fonciere').val() || null,
            ascenceur: this.radioToBool(this.$('input[name="ascenceur"]:checked').val()),
            cave: this.radioToBool(this.$('input[name="cave"]:checked').val()),
            cuisine_ouverte: this.radioToBool(this.$('input[name="cuisine-ouverte"]:checked').val()),
            stationnement: this.radioToBool(this.$('input[name="stationnement"]:checked').val()),
            type_stationnement: this.$('input[name="type-stationnement"]:checked').val() || null
          };
          var photos = this.addImgFormView.getPhotos();
          attrs.photos = (photos == null) ? null : photos.toJSON();

          model.set(attrs);
        },

        radioToBool: function (radioVal) {
          radioVal = radioVal || null;
          if (radioVal == null) {
            return null;
          } else {
            return $.parseJSON(radioVal);
          }
        },

        valid: function (view, attr, selector) {
          var attrSelector = '[' + selector + '~="' + attr + '"]';
          var formGroup = view.$(attrSelector).closest('.form-group');
          formGroup.removeClass('has-error');
          formGroup.removeClass('has-feedback');

          //view.$(attrSelector + " + .form-control-feedback").remove();
          view.$(attrSelector).parent().find('.form-control-feedback, .help-inline').remove();
        },
        invalid: function (view, attr, error, selector) {
          var attrSelector = '[' + selector + '~="' + attr + '"]';
          var formGroup = view.$(attrSelector).closest('.form-group');
          formGroup.addClass('has-error');
          formGroup.addClass('has-feedback');
          //view.$(attrSelector + " + .form-control-feedback").remove();
          view.$(attrSelector).parent().find('.form-control-feedback, .help-inline').remove();
          view.$(attrSelector).after('<span class="glyphicon glyphicon-remove form-control-feedback" aria-hidden="true"></span>');
          view.$(attrSelector).parent().append('<div class="help-inline">' + error + '</div>');
        }
      })
      ;
    return AnnonceFormView;
  })
;