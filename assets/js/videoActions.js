function likeVideo(button, videoId){
    console.log(videoId);

    $.post("ajax/likeVideo.php", {videoId: videoId})
    .done(function(data){

        var likeBtn = $(button);
        var dislikeBtn = $(button).siblings(".dislikeButton");

        likeBtn.addClass("active");
        dislikeBtn.removeClass("active");

        //console.log(JSON.parse(data));

        var result = JSON.parse(data);
        updateLikes(likeBtn.find(".text"), result.likes);
        updateLikes(dislikeBtn.find(".text"), result.dislikes);

        if(result.likes < 0){
            likeBtn.removeClass("active");
            likeBtn.find("i:first").removeAttr('class');
            likeBtn.find("i:first").attr('class', "fa fa-thumbs-o-up fa-lg");
        }
        else{
            likeBtn.find("i:first").removeAttr('class');
            likeBtn.find("i:first").attr('class', "fa fa-thumbs-up fa-lg");
        }

        dislikeBtn.find("i:first").removeAttr('class');
        dislikeBtn.find("i:first").attr('class', "fa fa-thumbs-o-down fa-lg");
    });
}

function dislikeVideo(button, videoId){
    console.log(videoId);

    $.post("ajax/dislikeVideo.php", {videoId: videoId})
    .done(function(data){

        var dislikeBtn = $(button);
        var likeBtn = $(button).siblings(".likeButton");

        dislikeBtn.addClass("active");
        likeBtn.removeClass("active");

        //console.log(JSON.parse(data));

        var result = JSON.parse(data);
        updateLikes(likeBtn.find(".text"), result.likes);
        updateLikes(dislikeBtn.find(".text"), result.dislikes);

        if(result.dislikes < 0){
            dislikeBtn.removeClass("active");
            dislikeBtn.find("i:first").removeAttr('class');
            dislikeBtn.find("i:first").attr('class', "fa fa-thumbs-o-down fa-lg");
        }
        else{
            dislikeBtn.find("i:first").removeAttr('class');
            dislikeBtn.find("i:first").attr('class', "fa fa-thumbs-down fa-lg");
        }

        likeBtn.find("i:first").removeAttr('class');
        likeBtn.find("i:first").attr('class', "fa fa-thumbs-o-up fa-lg");
    });
}

function updateLikes(element, num){
    var likesCount = element.text() || 0;
    element.text(parseInt(likesCount) + parseInt(num));
}