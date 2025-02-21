@extends('layouts.auth', [
    'class' => 'overflow-auto mh-100 h-100',
    'navbarClass' => 'bg-white py-1',
    'sectionClass' => 'bg-light'
])

@section('content')
    <div class="content">
        <div class="container">
            <div class="row mt-5">
                @forelse ($options as $option)
                    <div class="col-md-6">
                        <div class="card">
                            <div class="card-header">
                                <h4 class="card-title">
                                    {{ $option->bank }}
                                </h4>
                            </div>
                            <div class="card-body text-center">
                                <div class="qr-display">
                                    @if (!empty($option->qr))
                                        <img src="{{ Storage::url($option->qr) }}" alt="Payment QR Code" style="width: 450px; height: 400px; object-fit: cover;">
                                    @endif
                                </div>
                                <h5 class="mt-3"><b>Account/Phone Number</b>: {{ $option->number }}</h5>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="col-12">
                        <h1>Payments options are not yet added by the administrators.</h1>
                    </div>
                @endforelse
            </div>
        </div>
     </div> 
@endsection

@push('scripts')
    <script>
        $(document).ready(function() {
            demo.checkFullPageBackgroundImage();
        });
    </script>
@endpush
