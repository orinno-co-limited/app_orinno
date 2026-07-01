(function ($) {
    "use strict";
    $(document).on('submit', "#send-form", function (event) {
        event.preventDefault();
        var enctype = $(this).prop("enctype");
        if (!enctype) {
            enctype = "application/x-www-form-urlencoded";
        }

        $('#form-receiver-id').val($('.content-chat-message-user.active').data('id'));

        commonAjax($(this).prop('method'), $(this).prop('action'), window[$(this).data("handler")], window[$(this).data("handler")], new FormData($(this)[0]));
    });


    window.sendResponse = function (response) {
        if (response.status === true) {
            $(document).find('#send-form')[0].reset();
            $(document).find("#files-names").html('');
            $(document).find("#mAttachment").trigger('change');
            toastr.success(response.message);
            loadMorePageCount = 0;
            loadSingleUserChat($('#single-user-chat-route').val(), loadMorePageCount, 0, 1);
        } else {
            if (response.status === 422) {
                var errors = response.responseJSON.errors;
                $(document).find('.error-message').remove();
                $.each(errors, function (index, items) {
                    toastr.error(items[0]);
                });
            } else {
                toastr.error(response.message);
            }
        }
    };

    window.loadSingleUserChat = function (url, page, prepend = true, reload) {
        postAjaxCalled = 1;
        let selector = $('.content-chat-message-user.active');
        let receiver_id = $(selector).data('id');
        $.ajax({
            type: "GET",
            url: url,
            data: {
                'receiver_id': receiver_id,
                'page': page,
                '_token': $('meta[name="csrf-token"]').attr('content')
            },
            datatype: "json",
            success: function (res) {
                loadMorePageCount++;
                postAjaxCalled = 0;
                if (typeof prepend != 'undefined' && prepend == 1) {
                    $(selector).find('.body-chat-message-user').prepend(res.data.html);
                }else if (typeof reload != 'undefined' && reload == 1) {
                    $(selector).find('.body-chat-message-user').html(res.data.html);
                } else {
                    $(selector).find('.body-chat-message-user').append(res.data.html);
                }

                if(res.data.total_unseen_message){
                    $(document).find('#unseen-user-message').html(res.data.total_unseen_message);
                }else{
                    $(document).find('#unseen-user-message').html(res.data.total_unseen_message);
                }

                $(document).find("#chat-body-"+receiver_id).scrollTop($(document).find("#chat-body-"+receiver_id)[0].scrollHeight);

                $(selector).find(".gallery").each(function () {
                    $(this).magnificPopup({
                        delegate: "a",
                        type: "image",
                        showCloseBtn: false,
                        preloader: false,
                        gallery: {
                            enabled: true,
                        },
                        callbacks: {
                            elementParse: function (item) {
                                if (item.el[0].className == "video") {
                                    item.type = "iframe";
                                } else {
                                    item.type = "image";
                                }
                            },
                        },
                    });
                });
            }
        });
    };

    window.loadMorePageCount = 1;
    window.postAjaxCalled = 0;

    window.addEventListener('load', function () {
        loadSingleUserChat($('#single-user-chat-route').val(), loadMorePageCount, 0, 1);
    });

    const userChats = document.querySelectorAll(".user-chat");
    const chatMessages = document.querySelectorAll(".content-chat-message-user");

    userChats.forEach((userChat) => {
        userChat.addEventListener("click", () => {
            const selectedId = userChat.getAttribute("data-id");

            chatMessages.forEach((chatMessage) => {
                const messageId = chatMessage.getAttribute("data-id");

                if (messageId === selectedId) {
                    chatMessage.classList.add("active");
                } else {
                    chatMessage.classList.remove("active");
                }
            });

            userChats.forEach((chat) => {
                chat.classList.remove("active");
            });
            userChat.classList.add("active");
            loadMorePageCount = 0;
            loadSingleUserChat($('#single-user-chat-route').val(), loadMorePageCount, 0, 1);
        });
    });


})(jQuery)


