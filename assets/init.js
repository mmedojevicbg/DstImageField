$(function(){
    var STATE_EMPTY = 0;
    var STATE_CHOSEN = 1;
    var STATE_UNCHANGED = 2;
    
    
    var getContainer = function(element) {
        return $(element).parents('.dst-image-field-container');
    }
    
    var extractFileFromPath = function(path) {
        return path.replace(/^.*[\\\/]/, '');
    }
    
    $('.dst-image-field-fileupload').change(
        function() {
            var imageName = extractFileFromPath($(this).val());
            var container = getContainer(this);
            container.children('.dst-image-field-value').val(imageName);
            
            var state = container.children('.dst-image-field-state');
            if(imageName) {
                state.val(STATE_CHOSEN);
            } else {
                state.val(STATE_EMPTY);
            }
            
            container.children('.dst-image-field-current-image-container').hide();
        }
    ); 
    
    $('.dst-image-field-delete-link').click(function() {
        var container = getContainer(this);   
        
        container.children('.dst-image-field-value').val('');
        container.children('.dst-image-field-state').val(STATE_EMPTY);
        container.children('.dst-image-field-current-image-container').hide();
        
        return false;
    });
    
    $('.dst-image-field-zoom-link').click(function() {
        var container = getContainer(this);   
        
        var imagePath = container.find('.dst-image-field-current-image').attr('src');
        window.open(imagePath, "ImagePreview", "menubar=0,location=0,width=640,height:480" );
        
        return false;
    });
    
    $('.dst-image-field-state[value=0]').each(function(index) {
        var container = getContainer(this);   
        container.children('.dst-image-field-current-image-container').hide();
    });
});
