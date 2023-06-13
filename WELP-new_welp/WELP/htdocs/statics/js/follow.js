$(function(){
    $('.follow-button').on('click', function(){
        var btn = $(this)[0];
        var params = new URL(window.location.href).searchParams;
        var target_user_id = params.get('user_id');
        $.ajax({
            url: '/ajax/follow.php',
            type: 'POST',
            data: {'target_user_id': target_user_id},
            dataType: 'json'
        })
        .done(function(data, textStatus, jqXHR){
            console.log(data);
            
            if(data[0]){
                btn.classList.add('followed');
                btn.textContent = 'フォロー中';
                
            }else{
                btn.classList.remove('followed');
                btn.textContent = 'フォロー';
            }
            document.getElementById('following').textContent = data[1];
            document.getElementById('follower').textContent = data[2];
        })

        .fail(function(jqXHR, textStatus, errorThrown){
            return;
        });
    });
});
