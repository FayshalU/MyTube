function postComment(button, postedBy, videoId, replyTo, containerClass){
    var textarea = $(button).siblings("textarea");
    var text = textarea.val();
    textarea.val("");

    if(text){

        $.post("ajax/postComment.php", {text: text, postedBy: postedBy, videoId: videoId, responseTo: replyTo})
        .done(function(data){
            console.log(data);

            $("." + containerClass).prepend(data);
        });

    }
    else{
        alert("You can't post an empty comment");
    }
}