/**
 * Created by Mr Awesome on 20.06.2016.
 */
$(function(){

    function readURL(block,input) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                block.parents(".img_input_block").find("img").attr('src', e.target.result);

            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    
    $(".img-input").change(function(){
        readURL($(this),this);
    });
});