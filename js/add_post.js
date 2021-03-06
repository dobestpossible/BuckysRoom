(function($){
    var fileUploaded = false;
    var jcropObj = null;
    
    //New Post Nav
    $('#new-post-nav a').click(function(){
        if(!$(this).hasClass('selected'))
        {
            if( $(this).hasClass('post-text') )
            {
                $('#new-video-url').hide();
                $('label[for="post_visibility_profile"]').hide();
                $('.file-row').hide();
                $('#save-btn').show();
                $('#newpostform #type').val('text');
            }else if( $(this).hasClass('post-image') ){
                $('#new-video-url').hide();
                $('label[for="post_visibility_profile"]').show();
                $('.file-row').show();
                
                if( $('#file_upload-queue .uploadify-progress-bar').size() < 1 ) {
                    $('#save-btn').hide();
                }
                else {
                    $('#save-btn').show();
                }
                
                
                
                $('#newpostform #type').val('image');
            }else if( $(this).hasClass('post-video') ){
                $('#new-video-url').show();
                $('label[for="post_visibility_profile"]').hide();
                $('.file-row').hide();
                $('#save-btn').show();
                $('#newpostform #type').val('video');
            }
            $('#new-post-nav a.selected').removeClass('selected');
            $(this).addClass('selected');
        }
        return false;        
    })
    
    //Add New Post
    $('#newpostform').submit(function(){
        var postType = $('#newpostform #type').val();
        if(postType == 'text' && $('#newpostform textarea').val() == '')
        {
            showMessage($(this), 'Please write something hoss.', true);
            return false;
        }
        if(postType == 'video' && $('#youtube_url').val() == '')
        {
            showMessage($(this), 'Please enter a youtube url.', true);
            return false;
        }
        if(postType == 'image')
        {
            if(!fileUploaded){
                if( $('#file_upload-queue .uploadify-progress-bar').size() < 1 )
                {
                    showMessage($(this), 'Please choose a image.', true);    
                }else {
                    $('#file_upload').uploadify('upload', '*');    
                }
                return false;
            }
        }
    })
    
    
    //Ajax Upload
    $('#file_upload').uploadify({
        'swf' : '/js/uploadify/uploadify.swf',
        'uploader' : '/photo_uploader.php',
        'buttonText' : 'Choose File',
        'height' : 19,
        'width' : 76,
        'removeTimeout' : 1,
        'multi' : false,
        'auto' : false,
        'onSelect' : function(){
            //Clear Queue
            /*if($('#file_upload-queue .uploadify-progress-bar').size() > 1){
                $('#file_upload').uploadify('cancel');
            }*/
            $('#save-btn').show();
//            $('#newpostform .file-row p').hide();
            $('#file_upload').addClass('hide');
            if( jcropObj != null)
                jcropObj.destroy();
            jcropObj = null;
            $('#save-btn').unbind('click');
            $('#jcrop-row').html('').hide();
            fileUploaded = false;
        },
        'onCancel': function(){
            $('#save-btn').hide();
            //$('#newpostform .file-row p').show();
            $('#file_upload').removeClass('hide');
            fileUploaded = false;
        },
        'onUploadSuccess' : function(file, data, response) {                
            var rsp = $.parseJSON(data);
            
            if(rsp.success == 0)
            {
                $('#newpostform .loading-wrapper').hide();
                showMessage($('#newpostform'), rsp.msg, true);
                hideMessage($('#newpostform'), 2);
                $('#save-btn').hide();
    //            $('#newpostform .file-row p').show();
                $('#file_upload').removeClass('hide');
                fileUploaded = false;
            }else{
                //If checked profile
                if($('#newpostform input[name="post_visibility"]:checked').val() == 2){
                    
                    $('#newpostform .loading-wrapper').hide();
                    //Show jCrop
                    $('#jcrop-row').html('<img src="/photos/tmp/' + rsp.file + '" />').show();
                    if( jcropObj != null)
                        jcropObj.destroy();
                    
                    $('#jcrop-row img').Jcrop({
                        aspectRatio: 1,
                        allowSelect: false,
                        minSize: [ 50, 50 ],
                        onChange: function(c){
                            $('#newpostform #x1').val(c.x);
                            $('#newpostform #x2').val(c.x2);
                            $('#newpostform #y1').val(c.y);
                            $('#newpostform #y2').val(c.y2);
                            $('#newpostform #width').val($('#jcrop-row .jcrop-holder').width());
                        }
                    }, function(){
                        jcropObj = this;
                        jcropObj.animateTo([0,0,230,230]);
                    });
                    fileUploaded = true;
                    $('#newpostform').append('<input type="hidden" name="file" value="' + rsp.file + '" />');
                } else {    
                    $('#newpostform').append('<input type="hidden" name="file" value="' + rsp.file + '" />');                    
                    fileUploaded = true;
                    $('#newpostform').submit();
                }
            }
        },
        'onUploadStart' : function(file) {
            //Check File Extension Validation
            var ext = file.name.substring(file.name.lastIndexOf(".")).toLowerCase();
            if( ext != '.jpg' && ext != '.jpeg' && ext != '.png' && ext != '.gif' )
            {
                showMessage($('#newpostform'), 'Invalid file type! Please upload JPG, JPEG, PNG or GIF file.', true);
                hideMessage($('#newpostform'), 2);
                $('#file_upload').uploadify('cancel', '*');    
            }else{
                $('#newpostform .loading-wrapper').show();
            }
            
        }
    });


})(jQuery);