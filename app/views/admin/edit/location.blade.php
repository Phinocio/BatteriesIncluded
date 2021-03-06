@extends('layouts.admin')

@section('title')
	Batteries Included - Edit Location
@stop

@section('content')
	<div class="content-card col-md-10 col-md-offset-1">
		<h2>Edit {{$location->city}}</h2>

		{{Form::open(array('url' =>  '/admin/locations/' . $location->id, 'class' => 'form-horizontal', 'id' => 'locationedit-form', 'role' => 'form', 'method' => 'put', 'files' => true))}}

		    <div class="form-group">
		      {{ Form::label('location-address', 'Address', array('class' => 'col-sm-3 control-label')) }}

		      <div class="col-sm-5">
		        {{ Form::text('location-address', $location->address, array('class' => 'form-control', 'id' => 'location-address', 'placeholder' => 'Address')) }}
		      </div>
		    </div>

		    <div class="form-group">
		      {{ Form::label('location-email', 'Email', array('class' => 'col-sm-3 control-label')) }}

		      <div class="col-sm-5">
		        {{ Form::text('location-email', $location->email, array('class' => 'form-control', 'id' => 'location-email', 'placeholder' => 'Email')) }}
		      </div>
		    </div>

		    <div class="form-group">
		      {{ Form::label('location-phone', 'Phone Number', array('class' => 'col-sm-3 control-label')) }}

		      <div class="col-sm-5">
		        {{ Form::text('location-phone', $location->phone, array('class' => 'form-control', 'id' => 'location-phone', 'placeholder' => 'Phone Number')) }}
		      </div>
		    </div>

		    <div class="form-group">
		      {{ Form::label('location-description', 'Description', array('class' => 'col-sm-3 control-label')) }}

		      <div class="col-sm-5">
		        {{ Form::textarea('location-description', $location->description, array('class' => 'form-control', 'id' => 'location-description', 'placeholder' => 'Description')) }}
		      </div>
		    </div>


		    <div class="form-group">
		      {{ Form::label('image', 'Image', array('class' => 'col-sm-3 control-label')) }}

		      <div class="col-sm-5">
		        {{ Form::file('image', '', array('class' => 'form-control', 'id' => 'image')) }}
		      </div>
		    </div>

		    <!-- TODO: Edit hours -->

    		<div class="form-group">
    			<table class="table table-striped table-bordered">
			      <tr>
			        <th></th>
			        <th>Sunday</th>
			        <th>Monday</th>
			        <th>Tuesday</th>
			        <th>Wednesday</th>
			        <th>Thursday</th>
			        <th>Friday</th>
			        <th>Saturday</th>
			      </tr>
			      <tr>
			        <th>Open</th>
			        @foreach($hours as $hour)
			        	<td>{{Form::text($hour->day .'-open', $hour->open, array('class' => 'form-control', 'id' => $hour->day .'-open', 'placeholder' => 'Open'))}}</td>
			        @endforeach
			      </tr>
			      <tr>
			        <th>Close</th>
			        @foreach($hours as $hour)
			        	<td>{{Form::text($hour->day .'-close', $hour->close, array('class' => 'form-control', 'id' => $hour->day .'-close', 'placeholder' => 'Close'))}}</td>
			        @endforeach
			      </tr>
			    </table>
    		</div>


		    <div class="form-group">
		      <div class="col-sm-offset-3 col-sm-10">
		        {{ Form::submit('Submit', array('class' => '', 'id' => 'submit-button', 'name' => 'submit')) }}
		      </div>
		    </div>
		{{ Form::close() }}
	</div>
@stop