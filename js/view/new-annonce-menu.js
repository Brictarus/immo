define(['underscore', 'backbone', 
        'hbs!template/new-annonce-menu'], 
       function(_, Backbone, template) {

  var NewAnnonceMenu = Backbone.View.extend({
    events: {
      'click #new-annonce': "createNewAnnonce"
    },

    render: function() {
      this.$el.html(template({}));
      return this;
    },
    
    createNewAnnonce: function() {
      App.router.navigate('#nouvelle-annonce', {trigger: true});
    },
    
    show: function() {
      this.$el.fadeIn();
    },
    
    hide: function() {
      this.$el.fadeOut();
    }

  });
  return NewAnnonceMenu;
});