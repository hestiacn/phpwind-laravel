{{-- resources/views/about/mourning.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <i class="fas fa-info-circle"></i> @lang('messages.mourning_title')
                </div>
                
                <div class="card-body">
                    <div class="text-center mb-4">
                        <i class="fas fa-ribbon fa-4x text-secondary"></i>
                        <h3 class="mt-3">@lang('messages.mourning_title')</h3>
                    </div>
                    
                    <div class="alert alert-secondary">
                        <i class="fas fa-clock"></i>
                        @lang('messages.mourning_desc', ['name' => __('messages.' . $holidayKey)])
                    </div>
                    
                    <div class="text-center mt-4">
                        <a href="{{ url()->previous() }}" class="btn btn-outline-secondary">
                            <i class="fas fa-arrow-left"></i> @lang('messages.back')
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection