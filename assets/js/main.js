$(function() {

    $(".send-pm").click(function(e) {
        e.preventDefault();
        var members = $(this).attr('data-members'),
            intArray = members.split(",").map(Number).filter(Boolean),
            windows = Math.ceil(intArray.length / 20),
            randomNum = Math.random();

        if (windows > 1) {
            var i = 0;
            if (confirm("Note about forum PM limitation")) {
                // open dialog and generate PM buttons to handle rows of "20's" 
            }
        }

    })

    $("#searchclear").click(function() {
        $("#member-search").val('');
        $('#member-search-results').empty();
    });


    // bug report / issue creation
    $(".create-issue").click(function(e) {
        e.preventDefault();
        $(".viewPanel .viewer").load("create/issue/");
        $(".viewPanel").modal();

    });

    $("#pm-checked").click(function(event) {
        event.preventDefault();
        var searchIDs = $("#squads input:checkbox:checked, #squad input:checkbox:checked").map(function() {
            return $(this).data('id');
        }).get();
        var joinedIds = searchIDs.join('&u[]=');
        var pm_url = 'http://www.clanaod.net/forums/private.php?do=newpm&u[]=' + joinedIds;

        if (searchIDs.length > 0) {
            windowOpener(pm_url, "Mass PM", "width=900,height=600,scrollbars=yes");
        } else {
            alert('You must select someone to PM!')
        }

    });

    $(".toggle-pm").click(function() {
        $("#squads input:checkbox, #squad input:checkbox").toggle();
        $("#pm-checked").toggle();
        $(".member-item").toggleClass('member-item-push');
    });


    $(":checkbox").click(function() {
        $('.count-pm').text($(":checkbox:checked").length);
    });

    $("#member-search").bind("keypress", function(e) {
        if (e.keyCode == 13) {
            return false;
        }
    });

    // powers live search for members
    $('#member-search').keyup(function(e) {
        clearTimeout($.data(this, 'timer'));
        if (e.keyCode == 13) {
            member_search();
        } else {
            $(this).data('timer', setTimeout(member_search, 900));
        }

        if (!$('#member-search').val()) {
            $('#member-search-results').empty();
        }

    })


    $(".alert").alert();

    $('.alert').bind('closed.bs.alert', function() {
        var id = $(this).data('id'),
            user = $(this).data('user');

        $.post("do/update-alert", {
            id: id,
            user: user
        });
    });

    // popup link
    $(".popup-link").click(function(e) {
        e.preventDefault();
        windowOpener($(this).attr("href"), "AOD Squad Tracking", "width=900,height=600,scrollbars=yes");
    });

    $(".edit-member").click(function() {
        var member_id = $(this).parent().attr('data-player-id');

        $(".viewPanel .viewer").load("edit/member/", {
            member_id: member_id
        });
        $(".viewPanel").modal();
    });

    $(".removeMember").click(function(e) {
        e.preventDefault();
        if (confirm("Are you SURE you want to REMOVE this player from AOD?")) {
            var member = $(this).closest('li').attr('data-player-id');

            if (member == undefined) {
                member = $(this).closest('.btn-group').attr('data-player-id');
            } else {
                $(this).closest('.list-group-item').remove();
            }

            $.post("do/remove-member", {
                id: member
            });
            windowOpener($(this).attr("href") + member, "AOD Squad Tracking", "width=900,height=600,scrollbars=yes");
        }
    });


    $(".divGenerator").click(function() {
        $(".viewPanel .viewer").load("get/division-structure");
        $(".viewPanel").modal();
    });


    $(".container").on("click", ".reload", function() {
        loadThreadCheck();
    });

    $('#rctTab a').click(function(e) {
        e.preventDefault();
        $(this).tab('show');
    });



    /**
     * navigation links for user cp
     */
    $('.logout-btn').click(function(e) {
        e.preventDefault();
        window.location.href = "logout";
    });
    $('.settings-btn').click(function(e) {
        e.preventDefault();
        window.location.href = "user/settings";
    });
    $('.profile-btn').click(function(e) {
        e.preventDefault();
        window.location.href = "user/profile";
    });
    $('.messages-btn').click(function(e) {
        e.preventDefault();
        window.location.href = "user/messages";
    });

    $('#register').submit(function(e) {
        e.preventDefault();

        $.post("do/register",
            $(this).serialize(),
            function(data) {
                if (data['success'] === true) {
                    $('.register-btn').removeClass('btn-primary').addClass('btn-success').text('Success!');
                    $('.msg').fadeOut();

                    setTimeout(function() {
                        window.location.href = "/";
                    }, 1000);

                } else if (data['success'] === false) {
                    $('#register-panel').addClass('has-error');
                    $('.msg').addClass('alert alert-danger').html("<i class=\"fa fa-times-circle\"></i> <small>" + data['message'] + "</small>");
                    $('.msg').effect("bounce");

                }
            }, "json");

    });

    $('.count-animated').each(function() {
        var $this = $(this);
        jQuery({
            Counter: 0
        }).animate({
            Counter: $this.text()
        }, {
            duration: 3000,
            easing: "easeOutQuart",
            step: function() {
                if ($this.hasClass('percentage')) {
                    $this.text(formatNumber(Math.ceil(this.Counter) + "%"));
                } else {
                    $this.text(formatNumber(Math.ceil(this.Counter)));
                }
            }
        });
    });

    $('.follow-tool').powerTip({
        followMouse: true
    });

    $('.tool').powerTip({
        placement: 'n'
    });

    $('.tool-s').powerTip({
        placement: 's'
    });

    $('.tool-e').powerTip({
        placement: 'e'
    });

    $('.tool-ne').powerTip({
        placement: 'ne'
    });

});

function formatNumber(num) {
    return num.toString().replace(/(\d)(?=(\d{3})+(?!\d))/g, "$1,")
}

function readCookie(name) {
    var cookiename = name + "=";
    var ca = document.cookie.split(';');
    for (var i = 0; i < ca.length; i++) {
        var c = ca[i];
        while (c.charAt(0) == ' ') c = c.substring(1, c.length);
        if (c.indexOf(cookiename) == 0) return c.substring(cookiename.length, c.length);
    }
    return null;
}

function setCookie(cname, cvalue, exdays) {
    var d = new Date();
    d.setTime(d.getTime() + (exdays * 24 * 60 * 60 * 1000));
    var expires = "expires=" + d.toUTCString();
    document.cookie = cname + "=" + cvalue + "; " + expires;
}

function member_search() {
    if ($('#member-search').val()) {
        $.ajax({
            url: 'do/search-members',
            type: 'POST',
            data: {
                name: $('input#member-search').val()
            },
            success: function(response) {
                $('#member-search-results').html(response);
            }
        });
    }
}



/**
 * ZeroClipboard support
 */

var client = new ZeroClipboard($('.copy-button'));

client.on("ready", function(readyEvent) {
    client.on("aftercopy", function(event) {
        alert("Copied text to clipboard");
    });
});


function windowOpener(url, name, args) {

    if (typeof(popupWin) != "object" || popupWin.closed) {
        popupWin = window.open(url, name, args);
    } else {
        popupWin.location.href = url;
    }

    popupWin.focus();
}


function selectText(containerid) {
    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(document.getElementById(containerid));
        range.select();
    } else if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(document.getElementById(containerid));
        window.getSelection().addRange(range);
    }
}


function ucwords(str) {
    return (str + '')
        .replace(/^([a-z\u00E0-\u00FC])|\s+([a-z\u00E0-\u00FC])/g, function($1) {
            return $1.toUpperCase();
        });
}


function memberPm(members) {

    var y = members.length,
        x = Math.ceil(y / 20),
        names = [];

    // iterate windows
    for (w = 0; w < x; w++) {

        // iterate members
        for (i = w * 20; i < w * 20 + 20; i++) {
            if (i >= y) {
                break;
            } else {
                names.push(members[i])
            }
        }

        alert(names);
        names = [];
    }
}