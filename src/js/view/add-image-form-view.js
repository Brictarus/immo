define(['underscore', 'backbone', 'view/image-uploader-view', 'hbs!template/add-image-form', 'hbs!template/alert-bootstrap', 'i18n!nls/labels'], 
       function(_, Backbone, ImageUploaderView, template, alertTemplate, labels) {
  var AddImageFormView = Backbone.View.extend({
    events: {
      'change input:file#image_file': 'onFileChange',
      'submit form[name="form-add-image"]': 'onFormSubmit'
    },
    
    initialize: function(options) {
      _.bindAll(this, 'beforeSubmit', 'onUploadProgress', 'onUploadSuccess');
      this.imageUploadsCount = 0;
      this.imageUploadViews = [];

      //this.photosIds = options.photosIds || [];
      this.photos = new Backbone.Collection(options.photos || []);
    },

    getPhotosIds: function() {
      return this.photos.pluck("id");
    },

    getPhotos: function() {
      return this.photos;
    },
    
    render: function() {
      this.$el.html(template({labels: labels}));
      return this;
    },
    
    onFileChange: function() {
      console.log(arguments);
      this.$el.find('form[name="form-add-image"]').submit();
    },
    
    onFormSubmit: function() {
      var options = { 
        //target:   '#output',   // target element(s) to be updated with server response 
        beforeSubmit:  this.beforeSubmit,  // pre-submit callback 
        uploadProgress: this.onUploadProgress, //upload progress callback 
        success: this.onUploadSuccess,
        resetForm: false        // reset the form after successful submit 
      }; 
      //this.beforeSubmit();
      var $form = this.$el.find('form[name="form-add-image"]');
      $form.ajaxSubmit(options);  //Ajax Submit form
      $form[0].reset();
      return false;
    },
    
    //function to check file size before uploading.
    beforeSubmit: function(files, $form, options) {
      //check whether browser fully supports all File API
      if (window.File && window.FileReader && window.FileList && window.Blob) {
        if( !$('#image_file').val()) { //check empty input filed
          return false;
        }

        var file = $('#image_file')[0].files[0];
        var fsize = file.size; //get file size
        var ftype = file.type; // get file type
        var fname = file.name; // get file name

        //allow only valid image file types 
        switch(ftype) {
          case 'image/png': 
          //case 'image/gif': 
          case 'image/jpeg': 
          case 'image/pjpeg':
            break;
          default:
            this.showError("Format de fichier non supporté !");
            return false;
        }

        //Allowed file size is less than 1 MB (1048576)
        if(fsize > 1048576) {
            this.showError('L\'image est trop volumineuse ! Merci de réduire la taille de votre photo en utilisant un éditeur d\'image.');
            return false;
        }

        /*$('#submit-btn').hide(); //hide submit button
        $('#loading-img').show(); //hide submit button
        $("#output").html("");  */
      }
      else
      {
        //Output error to older browsers that do not support HTML5 File API
        $("#output").html("Please upgrade your browser, because your current browser lacks some new features we need!");
        return false;
      }
      
      //this.showError('OK, ca passe !');
      options.tempId = this.imageUploadsCount;
      this.addNewUpload(fname, this.imageUploadsCount++);
    },
    
    addNewUpload: function(name, index) {
      var $tbody = this.$('.image-list > table > tbody');
      $tbody.append('<tr></tr>');
      this.imageUploadViews[index] = new ImageUploaderView({ name: name, index: index, el: $tbody.find("tr:last-child") });
      this.imageUploadViews[index].render();
    },
    
    onUploadProgress: function(event, position, total, percentComplete, options) {
      //Progress bar
      console.log(percentComplete + '%'); //update progressbar percent complete
      this.imageUploadViews[options.tempId].updateProgress(percentComplete);
    },

    onUploadSuccess: function(rawModel) {
      var photo = new Backbone.Model(rawModel);
      this.photos.add(photo);
    },
    
    showError: function(error) {
      var $alert = this.$el.find('.alert-container');
      $alert.html(alertTemplate({ type: 'alert-danger', title: 'Erreur :', content: error }));
      this.$el.find('.alert').fadeIn();
    },
    
    hideError: function() {
      this.$('.alert').alert('close');
    }
  });
  return AddImageFormView;
});