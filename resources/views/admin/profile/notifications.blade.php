@extends('layouts.backend', [
    'class' => '',
    'elementActive' => 'profile'
]) 

@section('content')
    <div class="content">
        <div class="container">
            @include('layouts.alert')
            <div class="row">
                <div class="col-12">
                    <div class="card mb-4">
                        <div class="card-header pb-0">
                            <h6>Notifications</h6>
                        </div>
                        <div class="card-body">
                            <div class="row">
                                <div class="col-12">
                                    <form action="{{ route('notifications.read') }}" method="POST">
                                        @csrf
                                        <div class="d-flex mb-3 gap-2">
                                            <label class="btn btn-primary my-0 text-black d-flex align-items-center gap-2" style="width: fit-content;">
                                                <input type="checkbox" class="select-all" value=""> Select/Unselect All
                                            </label>
                                            <button type="submit" class="btn btn-danger my-0">Mark As Read </button>
                                        </div>
                                        <table class="table align-items-center justify-content-center mb-0">
                                            <tbody>
                                                @forelse(auth()->user()->unreadNotifications as $notification)
                                                    <tr>
                                                        <td class="text-center ps-4">
                                                            @if(!$notification->read_at)
                                                                <label><input type="checkbox" class="notifications"  name="notificationIds[]" value="{{ $notification->id }}"> </label>
                                                            @endif
                                                        </td>
                                                        <td class="ps-2 border-start">
                                                            <a class="text-dark d-flex py-2" href="{{ $notification->data['link'] ?? '' }}?read={{ $notification->id }}">
                                                                <div class="flex-grow-1 pe-2">
                                                                    <div class="fw-semibold">{{ $notification->data['message'] }}</div>
                                                                    <span class="fw-medium text-muted" data-bs-toggle="tooltip" title="{{ $notification->created_at->format('d/m/Y h:i A') }}">{{ $notification->created_at->diffForHumans() }}</span>
                                                                </div>
                                                            </a>
                                                        </td>
                                                    </tr>
                                                @empty
                                                    <tr>
                                                        <td colspan="2" class="text-center ps-4">
                                                            <h6>No Notifications Found</h6>
                                                        </td>
                                                    </tr>
                                                @endforelse
                                            </tbody>
                                        </table>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        $(document).ready(function () {
            $('.select-all').change(function() {
            if(this.checked) {
                $('.notifications').prop("checked", true);
            }else{
                $('.notifications').prop("checked", false);
            }
            });
        });
    </script>
@endpush