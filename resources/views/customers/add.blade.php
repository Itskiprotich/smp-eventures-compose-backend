@extends('layouts.app')

@section('content')
<div class="app-title">
  <div>
    <h1><i class="fa fa-user"></i> Customer</h1>

  </div>
  <ul class="app-breadcrumb breadcrumb">
    <li class="breadcrumb-item"><i class="fa fa-home fa-lg"></i></li>
    <li class="breadcrumb-item"><a href="#">New Customer</a></li>
  </ul>
</div>
<div class="row">
  <!-- To be worked on -->
  <div class="col-md-12">
    <div class="tile">
      @if ($errors->any())
      <div class="alert alert-danger">
        <ul>
          @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
      @endif
      @if (session()->has('success'))

      <div class="alert alert-dismissible alert-success">
        <button class="close" type="button" data-dismiss="alert">×</button>
        <p>{{ session('success') }}</p>
      </div>
      @endif

      @if (session()->has('error'))

      <div class="alert alert-dismissible alert-danger">
        <button class="close" type="button" data-dismiss="alert">×</button>
        <p>{{ session('error') }}</p>
      </div>
      @endif

      <h3 class="tile-title">Collect Data</h3>
      <div class="tile-body">

        <form method="POST" action="/customer/register">
          @csrf
          <div class="row">
            <div class="form-group col-md-4">
              <label class="control-label">First Name</label>
              <input name="firstname" class="form-control"  value="{{ old('firstname') }}"  required="required" type="text" placeholder="Enter First Name">
            </div>
            <div class="form-group col-md-4">
              <label class="control-label">Last Name</label>
              <input name="lastname" class="form-control"  value="{{ old('lastname') }}"  required="required" type="text" placeholder="Enter Last Name">
            </div>

          </div>
          <div class="row">
            <div class="form-group col-md-4">
              <label class="control-label">Phone</label>
              <input name="phone" class="form-control"  value="{{ old('phone') }}"  required="required" type="number" placeholder="Enter Phone">
            </div>
            <div class="form-group col-md-4">
              <label class="control-label">Email</label>
              <input name="email" class="form-control"  value="{{ old('email') }}"  required="required" type="email" placeholder="Enter Email">
            </div>

          </div>
          <div class="row">
            <div class="form-group col-md-4">
              <label class="control-label">ID Number</label>
              <input name="national" class="form-control"  value="{{ old('national') }}"  required="required" type="number" placeholder="Enter ID Number">
            </div>
            <div class="form-group col-md-4">
              <label class="control-label">Gender</label>

              <select name="gender" required="required" class="form-control" id="exampleSelect1">
                <option value="Male">Male</option>
                <option value="Female">Female</option>

              </select>
            </div>

          </div>
          <div class="row">
            <div class="form-group col-md-4">
              <label class="control-label">Password</label>
              <input name="password" class="form-control"  value="{{ old('password') }}"  required="required" type="password" placeholder="Enter Password">
            </div>
            <div class="form-group col-md-4">
              <label class="control-label">Confirm Password</label>
              <input name="confirm" class="form-control"  value="{{ old('confirm') }}" required="required" type="password" placeholder="Confirm Password">
            </div>

          </div>
          <div class="row">
            <div class="form-group col-md-4 align-self-end">
              <button class="btn btn-primary" type="submit"><i class="fa fa-fw fa-lg fa-check-circle"></i>Register</button>
            </div>
          </div>
        </form>
      </div>
    </div>
  </div>

</div>

@endsection