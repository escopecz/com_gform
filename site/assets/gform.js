(function($) {
    $.fn.submitGForm = function(url){
        if(submitted && $().validateGForm()){
            $().loadNewStep(url);
            submitted = false;
        }
    };
    
    $.fn.loadNewStep = function(url){
        $.ajax({
            url: url,
            dataType: 'html'
            
        }).done(function(result) {

            var html = $(result).find('.item_fields .html').html();
            
            $('.item_fields .html').slideUp('slow', function(){
                $(this).empty().append(html).slideDown('slow');
            });
            
            //execute scripts loaded via ajax requests
            var dom = $(result);
            dom.filter('script').each(function(){
                $.globalEval(this.text || this.textContent || this.innerHTML || '');
            });
            
            $('.html form').on('submit', function(){
                $().validateGForm();
                submitted=true;
            });
        });
    };
    
    $.fn.validateGForm = function(){
        var validation = [];
        
        $('.ss-item-required.ss-radio, .ss-item-required.ss-scale').each(function(){
            if($(this).find('input[type=radio]:checked').val()){
                validation.push(true);
                $(this).css('border','1px solid transparent');
            } else {
                validation.push(false);
                $(this).css('border','1px solid red');
            }
        });
        $('.ss-item-required.ss-select').each(function(){
            if($(this).find('select').val()){
                validation.push(true);
                $(this).css('border','1px solid transparent');
            } else {
                validation.push(false);
                $(this).css('border','1px solid red');
            }
        });
        $('.ss-item-required.ss-text').each(function(){
            if($(this).find('input[type=text]').val()){
                validation.push(true);
                $(this).css('border','1px solid transparent');
            } else {
                validation.push(false);
                $(this).css('border','1px solid red');
            }
        });

        if($.inArray(false, validation) != -1){
            return false;
        } else {
            return true;
        }
    };
    
    $.fn.countDownGForm = function(url, count){       
        var counter = setInterval(timer, 1000);
        setTimeout(
            function() { 
                $().loadNewStep(url);
            } , count * 1000
        );
        function timer(){
            count = count - 1;
            if (count <= 0){
               clearInterval(counter);
               return;
            }
            $('#GFormTime').text(count);
        }
    }
})( jQuery );

var submitted = false;
jQuery(document).ready(function($) {
    $('.html form').submit(function(){
        return $().validateGForm();
        submitted = true;
    });
});