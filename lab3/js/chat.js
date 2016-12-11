$(function() {
    
    var refreshButton = $('h1'),
        chatForm = $('.chat-form'),
        form = chatForm.find('form'),
        closeForm = chatForm.find('h2.span'),
        nameElement = form.find('#chat-name'),
        commentElement = form.find('#chat-comment'),
        ul = $('ul.chat-content');
    
    load();
        
    var canPostComment = true;

    form.submit(function(e){
        
        e.preventDefault();

        if(!canPostComment) return;
        
        var name = nameElement.val().trim();
        var comment = commentElement.val().trim();

        if(name.length && comment.length && comment.length < 240) {
        
            post(name, comment);

            canPostComment = false;

            setTimeout(function(){
                canPostComment = true;
            }, 6000);

        }

    });
    
    chatForm.on('click', 'h2', function(e){
        
        if(form.is(':visible')) {
            formClose();
        }
        else {
            formOpen();
        }
        
    });

    var canReload = true;

    refreshButton.click(function(){

        if(!canReload) return false;
        
        load();
        canReload = false;

        setTimeout(function(){
            canReload = true;
        }, 2000);
    });

    setInterval(load,20000);

    function formOpen(){
        
        if(form.is(':visible')) return;

        form.slideDown();
        closeForm.fadeIn();
    }

    function formClose(){

        if(!form.is(':visible')) return;

        form.slideUp();
        closeForm.fadeOut();
    }
    
    function post(name,comment){
    

    }
    
    function load(){
        
    }
    
    function appendComments(data) {


    }

});
