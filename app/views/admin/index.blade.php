 @extends('layouts.admin')

@section('title')
    Batteries Included - Admin Dashboard
@stop

@section('content')

    <div class="content-card col-md-10 col-md-offset-1">
        <h2>Info For <em>{{Auth::user()->username}}</em></h2>

        <p><b>Created at:</b> {{Auth::user()->created_at->format('F j, Y')}}</p>
        <p><b>Last password change:</b> {{Auth::user()->last_password_change->format('F j, Y')}} (passwords are required to be changed every 90 days!)</p>
        <p><b>Next forced password change:</b> {{Auth::user()->last_password_change->addDays(90)->format('F j, Y')}} ({{Carbon::now()->diffInDays(Auth::user()->last_password_change->addDays(90), false)}} Days)</p>


    </div>

	<div class="content-card col-md-10 col-md-offset-1">
		<h2>Manage Content</h2>
		<h4><a href="/admin/home">Home Page</a></h4>
		<h4><a href="/admin/products">Products</a></h4>
		<h4><a href="/admin/categories">Categories</a></h4>
		<h4><a href="/admin/services">Servicing</a></h4>
		<h4><a href="/admin/faqs">FAQs</a></h4>
		<h4><a href="/admin/locations">Locations</a></h4>
	</div>

@stop