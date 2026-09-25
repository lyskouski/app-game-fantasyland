@foreach ($users as $user)
    <img width="11" height="11" onclick="document.getElementById('chat_message').value = 'priv({{ $me }},{{ $user['login'] }}) ';toggleSidebar();" src="https://www.fantasyland.ru/images/miscellaneous/e_private.gif" />
    <a onclick="document.getElementById('chat_message').value = '{{ $user['login'] }}, ';toggleSidebar();"><small>{!! $user['img'] !!}</small></a>
    <br />
@endforeach
