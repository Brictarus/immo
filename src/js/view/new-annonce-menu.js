define(['underscore', 'underscore.string', 'backbone',
        'hbs!template/new-annonce-menu'], 
       function(_, _s, Backbone, template) {

  var NewAnnonceMenu = Backbone.View.extend({
    events: {
      'click #new-annonce': "createNewAnnonce"
    },

    updateAnnonceUrlTemplate: "#/annonce/%s/modifier",

    initialize: function() {
      this.buttonNewHidden = true;
      this.buttonUpdateHidden = true;
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
        $updateBtn.fadeIn();
      }
      return this;
    },

    hideButtonUpdate: function() {
      this.buttonUpdateHidden = true;
      this.$(".update-current-annonce").fadeOut();
      return this;
    }

  });
  return NewAnnonceMenu;
});