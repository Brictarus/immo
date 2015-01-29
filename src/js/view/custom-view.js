define(['backbone'], function(Backbone) {
  return Backbone.View.extend({
    remove: function() {
      this.$el.off();
      this.$el.children().remove();
      this.stopListening();
      return this;
    }
  })
});