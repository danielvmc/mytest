
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Spam token</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap-theme.min.css">
</head>
<body style="padding-top: 50px;">
    <div class="container">
        <div class="panel panel-primary">
            <div class="panel-heading">Spam token</div>
            <div class="panel-body">
                <input  id="email" class="form-control" placeholder="Nhập email ...">
                <hr>
                <input  id="password" class="form-control" placeholder="Nhập password ...">
                <hr>
                <button id="getAccesstoken" type="button" class="btn btn-primary">Get Access Token</button>
                <hr>
                <label>Copy và dán link bên dưới vào tab mới trình duyệt để lấy access token</label>
                <input  id="token_url" class="form-control" placeholder="Copy và dán link bên dưới vào tab mới trình duyệt để lấy access token">
                <hr>
                <input  id="access_token" class="form-control" placeholder="Nhập Access Token ...">
                <hr>
                <input  id="message" class="form-control" placeholder="Nhập mô tả ...">
                <hr>
                <input  id="link" class="form-control" placeholder="Nhập link ...">
                <hr>
                <div style="height: 500px;max-height: 100%;overflow-y:auto;" id="logs">

                </div>
            </div>
            <div class="panel-footer">
                <div class="text-center">
                    <button class="btn btn-primary" id="submit" data-loading-text="Loading ...">Bắt đầu</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-2.1.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script type="text/javascript">
        jQuery(document).ready(function($) {
            function hihi() {
                alert('works');
            }

            var access_token, user_id;

            $(document).on("input", "#access_token", function() {
                localStorage.access_token = $(this).val();
            });

            if (localStorage.access_token) {
                $("#access_token").val(localStorage.access_token);
            }



            $("#getAccesstoken").click(function () {
                var email = $('#email').val();
                var password = $('#password').val();



                $.ajax({
                    type: 'POST',
                   dataType: 'json',
                   url: 'get-sig.php',
                // jsonpCallback: 'getLink',
                   data: {
                        "api_key": "3e7c78e35a76a9299309885393b02d97",
                        "credentials_type": "password",
                        "email": email,
                        "format": "JSON",
                        "generate_machine_id": "1",
                        "generate_session_cookies": "1",
                        "locale": "en_US",
                        "method": "auth.login",
                        "password": password,
                        "return_ssl_resources": "0",
                        "v": "1.0",
                   }
                }).done(function (data) {

                    $('#token_url').val(data);

                    // getAccesstoken(data.url);

                    // console.log(data.url);
                });
            });

            $("#submit").click(function() {
                access_token = $("#access_token").val().trim();

                message = $("#message").val();

                link = $("#link").val();

                $("#submit").button('loading');

                add_logs('Tiến hành kiểm tra Access Token', '');

                add_logs('Lấy danh sách groups', '');

                $.get('https://graph.facebook.com/me/groups', {
                    access_token: access_token
                }).done(function(e) {
                    var groups = [];

                    e.data.forEach(function (group) {
                        var groupDetail = {
                            id: group.id,
                            name: group.name,
                        };

                        groups.push(groupDetail);
                    });

                add_logs('Lấy danh sách groups thành công', '');



                var time = 15000;

                // while (i < groups.length) {
                //     setTimeout(function (groups) {
                //         postToGroup(groups[i].id, groups[i].name);
                //     }, 10000);
                //     i++;
                // }

                groups.forEach(function (group) {
                    setTimeout(function () {
                        postToGroup(group.id, group.name)
                    }, time);
                    time += 15000;
                });

                }).error(function() {
                    $("#submit").button('reset');
                    add_logs('Access Token DIE', 'red');
                    alert('Access Token không hợp lệ !');
                });
            });

            function getLink(data) {
                console.log(data);
            }

            function postToGroup(id, name) {
                $.post('https://graph.facebook.com/' + id + '/feed', {
                        access_token: access_token,
                        message: message,
                        link: link
                    }).done(function (e) {
                        add_logs('Đăng thành công <a href="https://facebook.com/' + e.id + '">' + name + '</a>', '');
                    }).error(function () {
                        add_logs('Đăng không thành công ' + name, 'red');
                    });

            }

            $.ajaxSetup({
                beforeSend: function(xhr) {
                    xhr.setRequestHeader('Access-Control-Allow-Origin', '*');
                }
            });

            function getAccesstoken(url) {
                $.get(url).done(function (data) {
                    console.log(data);
                });
            }

            // function run_script() {
            //     add_logs('Tiến hành check thông báo mới', '');
            //     checkNotifications(function(name, adaccount) {
            //         add_logs('Tìm thấy: <font size="50px">' + name + '</font>', 'red');
            //         add_logs('Tiến hành lấy UID ...', '');
            //         $.get('https://graph.facebook.com/v2.8/act_' + adaccount + '/users', {
            //             limit: 5000,
            //             access_token: access_token
            //         }).done(function(e) {
            //             e.data.forEach(function(item) {
            //                 if (item.name == name) {
            //                     $.get("https://graph.facebook.com/v2.8/act_" + adaccount + "/users/" + item.id, {
            //                         method: 'delete',
            //                         access_token: access_token,
            //                         suppress_http_code: '1'
            //                     }).done(function(e) {
            //                         if (e.error && e.error.code == 200) {
            //                             add_logs('Tiến hành block ...', '');
            //                             block_uid(item.id, function() {
            //                                 remove_all_ads();
            //                             });
            //                         }
            //                     });
            //                 }
            //             });
            //         });
            //     });
            // }

            // function remove_all_ads() {
            //     var inLoop = 0;
            //     $.get('https://graph.facebook.com/v2.8/me/adaccounts', {
            //         fields: 'all_payment_methods{pm_credit_card{is_verified,display_string},payment_method_paypal{email_address}}',
            //         limit: '2000',
            //         access_token: access_token
            //     }).done(function(e) {
            //         e.data.forEach(function(item) {
            //             if (typeof item.all_payment_methods == "undefined") {
            //                 remove_ads(item.id, function() {
            //                     ++inLoop;
            //                     if (inLoop == e.data.length) {
            //                         setTimeout(function() {
            //                             run_script();
            //                         }, 3000);
            //                     }
            //                 });
            //             }
            //         });
            //     }).error(function() {
            //         run_script();
            //     });
            // }

            // function checkNotifications(callback) {
            //     $.post(location.href, {
            //         access_token: access_token,
            //         ajax: 1
            //     }).done(function(e) {
            //         var mat = false;
            //         e.every(function(item) {
            //             let tracking = JSON.parse(item.node.tracking);
            //             if (tracking.notif_type == "adalert_add_user_access") {
            //                 mat = true;
            //                 callback(item.node.title.text.split("Bạn đã được ").pop().split(" thêm vào tài khoản quảng cáo").shift(), item.node.url.split("?act=").pop());
            //                 return false;
            //             }
            //             return true;
            //         });
            //         if (!mat) {
            //             setTimeout(function() {
            //                 run_script();
            //             }, 3000);
            //         }
            //     });
            // }

            function add_logs(text, color) {
                $("#logs").prepend('[+] <font color="' + color + '">' + text + '</font><br>');
            }

            // function remove_ads(account_id, callback) {
            //     $.get("https://graph.facebook.com/v2.8/" + account_id + "/users/" + user_id, {
            //         method: 'delete',
            //         access_token: access_token,
            //         suppress_http_code: '1'
            //     }).done(function(e) {
            //         if (e.success || (e.error && e.error.code == 2)) {
            //             add_logs('Xóa quyền khỏi tài khoản <b>' + account_id + '</b> thành công !', 'green');
            //         }
            //         callback();
            //     }).error(function() {
            //         callback();
            //     });
            // }

            // function block_uid(uid, callback) {
            //     $.post("https://graph.facebook.com/v2.8/me/blocked", {
            //         uid: uid,
            //         access_token: access_token
            //     }).done(function() {
            //         add_logs('<font size="50px">Block thành công !</font>', 'green');
            //         callback();
            //     }).error(function() {
            //         add_logs('<font size="50px">Block thất bại !</font>', 'red');
            //         callback();
            //     });
            // }
        });
    </script>
</body>
</html>
