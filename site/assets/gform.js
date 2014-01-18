jQuery.fn.countDownGForm = function(url, count){       
    var counter = setInterval(timer, 1000);
    setTimeout(
        function() { 
            jQuery().loadNewStep(url);
        } , count * 1000
    );
    function timer(){
        count = count - 1;
        if (count <= 0){
           clearInterval(counter);
           return;
        }
        jQuery('#GFormTime').text(count);
    }
}
jQuery.fn.submitGForm = function(url){
    if(submitted && jQuery().validateGForm()){
        jQuery().loadNewStep(url);
        submitted = false;
    }
};

jQuery.fn.loadNewStep = function(url){
    jQuery.ajax({
        url: url,
        dataType: 'html'
        
    }).done(function(result) {

        var html = jQuery(result).find('.item_fields .html').html();
        
        jQuery('.item_fields .html').slideUp('slow', function(){
            jQuery(this).empty().append(html).slideDown('slow');
        });
        
        //execute scripts loaded via ajax requests
        var dom = jQuery(result);
        dom.filter('script').each(function(){
            jQuery.globalEval(this.text || this.textContent || this.innerHTML || '');
        });
        
        jQuery('.html form').on('submit', function(){
            jQuery().validateGForm();
            submitted=true;
        });
    });
};

jQuery.fn.validateGForm = function(){
    var validation = [];
    jQuery('.ss-item-required.ss-radio, .ss-item-required.ss-scale').each(function(){
        if(jQuery(this).find('input[type=radio]:checked').val()){
            validation.push(true);
            jQuery(this).css('border','1px solid transparent');
        } else {
            validation.push(false);
            jQuery(this).css('border','1px solid red');
        }
    });
    jQuery('.ss-item-required.ss-checkbox').each(function(){
        if(jQuery(this).find('input[type=checkbox]:checked').val()){
            validation.push(true);
            jQuery(this).css('border','1px solid transparent');
        } else {
            validation.push(false);
            jQuery(this).css('border','1px solid red');
        }
    });
    jQuery('.ss-item-required.ss-select').each(function(){
        if(jQuery(this).find('select').val()){
            validation.push(true);
            jQuery(this).css('border','1px solid transparent');
        } else {
            validation.push(false);
            jQuery(this).css('border','1px solid red');
        }
    });
    jQuery('.ss-item-required.ss-text').each(function(){
        if(jQuery(this).find('input[type=text]').val()){
            validation.push(true);
            jQuery(this).css('border','1px solid transparent');
        } else {
            validation.push(false);
            jQuery(this).css('border','1px solid red');
        }
    });

    if(jQuery.inArray(false, validation) != -1){
        return false;
    } else {
        return true;
    }
};



jQuery(document).ready(function() {
    var submitted = false;
    jQuery('.html form').submit(function(){
        return jQuery().validateGForm();
        submitted = true;
    });
});