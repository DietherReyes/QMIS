<div class="col-md-8  main">
    <h1 class="page-header">Account Management</h1>
    @if(count($users) > 0)
        @foreach($users as $user)
            <ul class="list-group">
                <li class="list-group-item"> Name: {{$user->name}}</li>
                <li class="list-group-item"> Position: {{$user->position}}</li>
                <li class="list-group-item"> Functional Unit: {{$user->functional_unit}}</li>
            </ul>
        @endforeach
    @endif
</div>