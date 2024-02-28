
$(document).ready(function() {
    const urlAjax = "http://localhost/!chekerlife/controller/UserAjaxConroller.php";
    $friend = $('.friend');

    $(document).on('click', '#addFriend', function(e) {
        disable("addFriend");
        $friend.html("");
        $rechercheFriend = $('<div class="rechercheFriend"></div>')
            $rechercheFriend.append('<input type="text" placeholder="pseudo ?" maxlength="30" id="inputeFriend">')
            $rechercheFriend.append('<button id="parametreFriend"><i class="fa-solid fa-gear"></i></button>')
        $suggestion = $('<div class="suggestion"></div>');            
        $friend.append($rechercheFriend, $suggestion);
    })
    
    $(document).on("input", '#inputeFriend', function(e) {
        var pseudo = $(this).val();
        disable("online");
        $('.suggestion').html("");
        if(pseudo != ""){
            $.ajax({
                url: urlAjax,
                type: 'POST',
                data: {
                    action: "returnFriend",
                    pseudo: pseudo,
                },
                dataType: 'html',
                success: function (response) {
                    $('.suggestion').append(response);
                    ftrSize();
                },
                error: function (xhr, status, error) {
                    console.log(xhr);
                }
            });
        }else{
            ftrSize();
        }
        
    })

    $(document).on('click', '#online', function(e) {
        disable("online");
        $friend.html("");
        console.log("text");
    })

    $(document).on('click', '#all', function(e) {
        $friend.html("");
        disable("all");
            $.ajax({
                url: urlAjax,
                type: 'POST',
                data: {
                    action: "allFriend",
                },
                dataType: 'html',
                success: function (response) {
                    $friend.append(response);
                    ftrSize();
                },
                error: function (xhr, status, error) {
                    console.log(xhr);
                }
            });
    })

    $(document).on('click', '#requette', function(e) {
        disable("requette");
        $friend.html("");
        $.ajax({
            url: urlAjax,
            type: 'POST',
            data: {
                action: "requetteFriend",
            },
            dataType: 'html',
            success: function (response) {
                $friend.append(response);
                ftrSize();
            },
            error: function (xhr, status, error) {
                console.log(xhr);
            }
        });
    })

    $(document).on('click', '#blocket', function(e) {
        disable("blocket");
        $friend.html("");
        $.ajax({
            url: urlAjax,
            type: 'POST',
            data: {
                action: "friendBloque",
            },
            dataType: 'html',
            success: function (response) {
                $friend.append(response);
                ftrSize();
            },
            error: function (xhr, status, error) {
                console.log(xhr);
            }
        });
    })
    
    function disable(id){
        $("#online, #all, #requette, #blocket, #addFriend").prop("disabled", false);
        $("#" + id).prop("disabled", true);
    }
    
    $(document).on('click', '#removeFriend', function(e) {
        var pseudo = $(this).attr('class');
        console.log(pseudo);

        $.ajax({
            url: urlAjax,
            type: 'POST',
            data: {
                action: "removeFriend",
                pseudo: pseudo,
            },
            dataType: 'json',
            success: function (response) {
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.log(xhr);
            }
        });
    }); 
    
    $(document).on('click', '#Friendtrue, #FriendFalse', function(e) {
        var id_btn = $(this).attr('id');
        var pseudo = $(this).attr('class');

        var update = (id_btn == "Friendtrue") ? "confirme" : "refuse";

        $.ajax({
            url: urlAjax,
            type: 'POST',
            data: {
                action: "friendStatue",
                pseudo: pseudo,
                update: update
            },
            dataType: 'json',
            success: function (response) {
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.log(xhr);
            }
        });
    }); 
    
    $(document).on('click', '#addFriendBtn', function(e) {
        var pseudo = $(this).attr('class');

        $.ajax({
            url: urlAjax,
            type: 'POST',
            data: {
                action: "addFriend",
                pseudo: pseudo,
            },
            dataType: 'json',
            success: function (response) {
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.log(xhr);
            }
        });
    }); 
    
    $(document).on('click', '#blockFriend', function(e) {
        var pseudo = $(this).attr('class');
        console.log(pseudo);

        $.ajax({
            url: urlAjax,
            type: 'POST',
            data: {
                action: "blockFriend",
                pseudo: pseudo,
            },
            dataType: 'json',
            success: function (response) {
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.log(xhr);
            }
        });
    }); 
    
    $(document).on('click', '#unblockedFriend', function(e) {
        var pseudo = $(this).attr('class');
        console.log(pseudo);

        $.ajax({
            url: urlAjax,
            type: 'POST',
            data: {
                action: "unblockedFriend",
                pseudo: pseudo,
            },
            dataType: 'json',
            success: function (response) {
                console.log(response);
            },
            error: function (xhr, status, error) {
                console.log(xhr);
            }
        });
    }); 

})