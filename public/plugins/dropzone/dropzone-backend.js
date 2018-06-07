var photo_counter = 0;
var minImageWidth = 750, minImageHeight = 390;
Dropzone.options.realDropzone = {

    uploadMultiple: true,
    parallelUploads: 100,
    maxFilesize: 8,
    autoProcessQueue: false,
    previewsContainer: '#dropzonePreview',
    previewTemplate: document.querySelector('#preview-template').innerHTML,
    addRemoveLinks: true,
    acceptedFiles: "image/*",
    dictRemoveFile: 'Remove',
    dictFileTooBig: 'Image is bigger than 8MB',
    // The setting up of the dropzone
    init:function() {
      var myDropzone = this;
        this.on("thumbnail", function(file) {
            if (file.width < minImageWidth || file.height < minImageHeight) {
                file.rejectDimensions();
            } else {
                file.acceptDimensions();
            }
        });

        $('#submitfiles').on("click", function (e) {
            e.preventDefault();
            e.stopPropagation();
            if(myDropzone.getQueuedFiles().length > 0){
                myDropzone.processQueue();
            }else{
                alert('No Files to upload!');
            }
        });

        $.get('/backend/products/get-image/'+$('#idProduct').val()+'', function(data) {
           $.each(data.images, function (key, value) {
               var file = {name: value.original, size: value.size};
               myDropzone.options.addedfile.call(myDropzone, file);
               myDropzone.createThumbnailFromUrl(file, $('base').data('url') + '/images/products/thumb/' + value.server);
               myDropzone.emit("complete", file);
               $('.serverfilename', file.previewElement).val(value.server);
               photo_counter++;
               $("#photoCounter").text( "(" + photo_counter + ")");
           });
       });

       this.on("removedfile", function(file) {
           $.ajax({
               type: 'POST',
               url: $('base').data('url') + '/backend/products/delete-image',
               data: {idDb: $('#idProduct').val(), id: $('.serverfilename', file.previewElement).val() , _token: $('#csrf-token-upload').val()},
               dataType: 'html',
               success: function(data){
                   var rep = JSON.parse(data);
                   if(rep.code == 200)
                   {
                       $('#imageId').val(JSON.stringify(rep.data));
                       photo_counter--;
                       $("#photoCounter").text( "(" + photo_counter + ")");
                   }
               }
           });
        });
    },
    accept: function(file, done) {
        file.acceptDimensions = done;
        file.rejectDimensions = function() { alert("The image must be at least 1024 by 768 pixels in size"); };
        // Of course you could also just put the `done` function in the file
        // and call it either with or without error in the `thumbnail` event
        // callback, but I think that this is cleaner.
    },
    success: function(file, response){
        if(response.code == 200)
        {
            $('.dz-remove').hide();
            $('#imageId').val(JSON.stringify(response.data));
        }

    }
}
