define(['underscore', 'underscore.string', 'backbone', 'model/annonce',
        'hbs!template/new-annonce-menu'], 
       function(_, _s, Backbone, Annonce, template) {

  var NewAnnonceMenu = Backbone.View.extend({
    events: {
      'click #new-annonce': "createNewAnnonce",
      'click .delete-current-annonce': "deleteCurrentAnnonce"
    },

    updateAnnonceUrlTemplate: "#/annonce/%s/modifier",

    initialize: function() {
      this.buttonNewHidden = true;
      this.buttonUpdateHidden = true;
      this.annonceId = null;
      this.updateAnnonceUrl = this.updateAnnonceUrlTemplate;
    },

    render: function() {
      this.$el.html(template({
        buttonNewHidden: this.buttonNewHidden,
        buttonUpdateHidden: this.buttonUpdateHidden,
        updateAnnonceUrl: this.updateAnnonceUrl
      }));
      return this;
    },
    
    createNewAnnonce: function() {
      App.router.navigate('#nouvelle-annonce', {trigger: true});
    },
    
    showButtonNew: function() {
      this.buttonNewHidden = false;
      this.$(".new-annonces-btn-group").fadeIn();
      return this;
    },

    hideButtonNew: function() {
      this.buttonNewHidden = true;
      this.$(".new-annonces-btn-group").fadeOut();
      return this;
    },

    showButtonUpdate: function(id) {
      this.buttonUpdateHidden = false;
      this.annonceId = id;
      var $updateBtn = this.$(".update-current-annonce");
      this.updateAnnonceUrl = _s.sprintf(this.updateAnnonceUrlTemplate, id);
      if ($updateBtn.length) {
        $updateBtn.prop("href", this.updateAnnonceUrl);
        $updateBtn.fadeIn();
        this.$(".delete-current-annonce").fadeIn();
      }
      return this;
    },

    hideButtonUpdate: function() {
      this.buttonUpdateHidden = true;
      this.annonceId = null;
      this.$(".update-current-annonce, .delete-current-annonce").fadeOut();
      return this;
    },

    deleteCurrentAnnonce: function() {
      (new Annonce({ id: this.annonceId })).destroy({
        success: function() {
          App.router.navigate('#', {trigger: true});
        }
      });
    }
  });
  return NewAnnonceMenu;
});