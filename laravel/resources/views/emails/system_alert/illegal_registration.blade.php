Consecutive member registrations from the same IP have been detected.

Datetime: 
{{ $datetime }}

IP: 
{{ $ip }}

Locked user id. 
@foreach ($locked_id_list as $locked_user_id)
・{{ $locked_user_id }}
@endforeach