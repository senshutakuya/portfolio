$(function(){
    $('.favorite-button').on('click', function(){
        var btn = $(this);
        var post = btn.closest('.post-box');
        if(post.length != 0){
            var post_id = post[0].id;
        }else{
            var post_id = btn.closest('.post-origin')[0].id;
        }
        $.ajax({
            url: '/ajax/favorite.php',
            type: 'POST',
            data: {'post_id': post_id},
            dataType: 'json'
        })
        .done(function(data, textStatus, jqXHR){
            var element = document.getElementById(post_id);
            var element2 = element.getElementsByClassName('favorite-label');
            var element3 = element.getElementsByClassName('favorite-svg');
            if(element2.length != 0){
                element2[0].innerText = data[2];
            }
            if(data[0]){
                element3[0].setAttribute('fill', 'red');
            }else{
                element3[0].setAttribute('fill', 'none');
            }
        })
        .fail(function(jqXHR, textStatus, errorThrown){
            return;
        });
    });
});
