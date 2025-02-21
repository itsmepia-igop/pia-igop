@if (session('message'))
    <div class="alert alert-success alert-dismissible mb-4" role="alert">
        <p class="mb-0">@lang(session('message'))</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if (session('error'))
    <div class="alert alert-danger alert-dismissible mb-4" role="alert">
        <p class="mb-0">@lang(session('error'))</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->has('message'))
    <div class="alert alert-danger alert-dismissible mb-4" role="alert">
        <p class="mb-0">@lang($errors->first('message'))</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif

@if ($errors->has('attempt'))
    <div class="alert alert-danger alert-dismissible mb-4" role="alert">
        <p class="mb-0">@lang($errors->first('attempt'))</p>
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif
@if (session('password_status'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('password_status') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
    </div>
@endif