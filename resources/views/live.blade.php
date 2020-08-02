<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Mega Black Live!</title>

    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.14.0/css/all.css" integrity="sha384-HzLeBuhoNPvSl5KYnjx0BT+WB0QEEqLprO+NBkkk5gbc67FTaL7XIGa2w1L0Xbgc" crossorigin="anonymous">
    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.0/dist/umd/popper.min.js" integrity="sha384-Q6E9RHvbIyZFJoft+2mJbHaEWldlvI9IOYy5n3zV9zzTtmI3UksdQRVvoxMfooAo" crossorigin="anonymous"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.min.js" integrity="sha384-OgVRvuATP1z7JjHLkuOU7Xw704+h835Lr+6QL9UvYjZE3Ipu6Tp75j7Bh/kR0JKI" crossorigin="anonymous"></script>

    
    <style>
        body {
            background-color: #222;
            padding-top: 2em;
            padding-left: 4em;
            padding-right: 4em;
        }

        .chat-container {
            border: 1px solid #333;
            border-radius: 4px;
        }

        .chat-header {
            padding: 10px 10px;
            background-color: #111;
            color: #ddd;
            font-size: 24px;
            border-radius: 4px 4px 0 0;
        }

        .chat-messages {
            padding: 5px;
            height: 480px;
            overflow-y: auto;
        }

        .chat-message {
            color: #fff;
            border-radius: 4px;
            padding: 2px 5px;
        }

        .chat-message-author {
            padding: 0 5px;
            border-radius: 3px;
            color: grey;
            font-weight: bold;
        }

        .chat-bottom {
            padding: 10px;
            background-color: #111;
            border-radius: 0 0 4px 4px;
        }

        .stream-title {
            color: #eee;
            font-size: 24px;
        }

        .count-watching {
            color: #ccc;
        }
    </style>
</head>
<body>
    <template id="chat-message-template">
        <div class="chat-message">
            <span class="chat-message-author">{name}</span>
            <span class="chat-message-text">{message}</span>
        </div>
    </template>

    <div class="container-fluid">
        <div class="row">
            <div class="col-md-8">
                <div class="embed-responsive embed-responsive-16by9">
                    <iframe
                        src="https://player.twitch.tv/?channel=nitroow&parent=192.168.2.7&muted=false"
                        frameborder="0"
                        scrolling="no"
                        allowfullscreen="true">
                    </iframe>
                </div>
                <div class="stream-title">Mega Black Live - DJ Leo - DJ Jodson - DJ Dim | Black Charm - Ragga - R&B | 31/07/2020 21:00h</div>
                <div class="count-watching">
                    10 assistindo
                </div>
            </div>
            <div class="col-md-4">
                <div class="chat-container">
                    <div class="chat-header">
                        Chat Ao Vivo
                    </div>
                    <div class="chat-messages">
                    </div>
                    <div class="chat-bottom">
                        <form id="send-message-form" method="POST" action="{{ route('messages.store') }}">
                            <div class="form-row">
                                <div class="col-md-4">
                                    <input name="name" type="text" class="form-control" placeholder="Seu nome" required>
                                </div>
                                <div class="col-md-8">
                                    <input name="message" type="text" placeholder="Diga algo..." class="form-control" required>
                                </div>
                            </div>
                            <div class="mt-2 clearfix">
                                <button type="button" class="btn rounded-circle float-left" style="width: 40px; height: 40px;">
                                    <i class="fas fa-smile-wink text-white"></i>
                                </button>
                                <button type="submit" class="btn rounded-circle float-right" style="width: 40px; height: 40px;">
                                    <i class="fas fa-arrow-right text-white"></i>
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script type="text/javascript">
        $(document).ready(function() {
            let last_message_id = 0;

            // utils
            function zero(number) {
                return number < 10? '0' + number : number;
            }

            function view_add_message(message) {
                let template_html = $("#chat-message-template").html();

                let html = template_html.replace(/{name}/g, message.name)
                    .replace(/{message}/g, message.message);

                $(".chat-messages").append(html);
                $(".chat-messages").scrollTop($('.chat-messages').prop("scrollHeight"));
            };

            function server_send_message(message) {
                $.ajax({
                    url: "{{ route('messages.store') }}",
                    method: "POST",
                    data: {
                        'name': message.name,
                        'message': message.message
                    },
                    success: function(result) {
                        //view_add_message(result);
                    }
                })
            };

            function server_retrieve_messages() {
                $.ajax({
                    url: "{{ route('messages.index') }}",
                    method: "GET",
                    data: {
                        "last_message_id": last_message_id
                    },
                    success: function(messages) {
                        for(let i = messages.length - 1; i >= 0; i--) {
                            let message = messages[i];
                            view_add_message(message);
                            last_message_id = message.id;
                        }
                    }
                })
            };

            setInterval(server_retrieve_messages, 1000);

            // bind events
            $("#send-message-form").submit(function(event) {
                event.preventDefault();

                let formData = $(this).serializeArray();
                let message = {
                    name: formData[0]['value'],
                    message: formData[1]['value']
                };
                
                $("#send-message-form").find("[name='message']").val('');

                server_send_message(message);
            });
        });
    </script>
</body>
</html>