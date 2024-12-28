$(function(){
    $('#release').click(function(){
        $.when(
            $('.banks__auth__btn').hide(),
            $('#wait').show()
        ).done(function(){ 
            const url = "/kdol/release/";
            window.open(url, '_blank')
        });
        user_check_polling();
    });
    $('#oauth').click(function(){
        $.when(
            $('.banks__auth__btn').hide(),
            $('#wait').show()
        ).done(function(){ 
            const url = "/kdol/oauth/"
            window.open(url, '_blank')
        });
        user_check_polling();
    });

    function user_check_polling(){
        var POLLLING_INVERVAL_TIME_IN_MILLIS =  5000;//5s
        var count = 30;
        (function polling() {
            if(count){
                check_auth();
                window.setTimeout(polling, POLLLING_INVERVAL_TIME_IN_MILLIS);
            }else{
                if(!alert('処理に失敗しました。最初からやり直してください。')){
                    const url = "/kdol/"
                    location.href = url;
                }
            }
            count--;
        
        }());
    }
    

        function check_auth() {
            var session_key = $('#session_key').val();
            var user_key = $('#user_key').val();
            $.ajax({
                type: 'get',
                url: '/kdol/account_check/'+user_key+'/'+session_key+'/',
                dataType: "json",
                data: {
                },
                success: function(response) {console.log(response);
                    var check = response.status;
                    if (check === 1) {
                        const url = "/kdol/account/"
                        location.href = url;
                    }
                    if (check === 2) {//error
                        if(!alert('処理に失敗しました。最初からやり直してください。')){
                            const url = "/kdol/"
                            location.href = url;
                        }
                    }

                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                }
            });
        }
});