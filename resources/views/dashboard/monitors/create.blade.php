@extends('layouts.dashboard')

@section('title', __('messages.add_monitor') . ' — Monitorly')
@section('page-title', __('messages.add_monitor'))

@section('content')
<div style="max-width:600px;">
    <div class="card">
        <form method="POST" action="{{ route('monitors.store') }}">
            @csrf

            <div class="form-group">
                <label class="form-label" for="name">{{ __('messages.monitor_name') }}</label>
                <input class="form-input" type="text" id="name" name="name" value="{{ old('name') }}" required
                    placeholder="{{ app()->getLocale() === 'ar' ? 'مثال: موقعي الرئيسي' : 'e.g. My Main Website' }}">
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="url">{{ __('messages.monitor_url') }}</label>
                <input class="form-input" type="url" id="url" name="url" value="{{ old('url') }}" required
                    placeholder="https://example.com" dir="ltr">
                @error('url') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="interval">{{ __('messages.monitor_interval') }}</label>
                <select class="form-input" id="interval" name="interval">
                    <option value="1" {{ old('interval') == 1 ? 'selected' : '' }}>1 {{ app()->getLocale() === 'ar' ? 'دقيقة' : 'minute' }}</option>
                    <option value="3" {{ old('interval') == 3 ? 'selected' : '' }}>3 {{ app()->getLocale() === 'ar' ? 'دقائق' : 'minutes' }}</option>
                    <option value="5" {{ old('interval', 5) == 5 ? 'selected' : '' }}>5 {{ app()->getLocale() === 'ar' ? 'دقائق' : 'minutes' }}</option>
                    <option value="10" {{ old('interval') == 10 ? 'selected' : '' }}>10 {{ app()->getLocale() === 'ar' ? 'دقائق' : 'minutes' }}</option>
                    <option value="15" {{ old('interval') == 15 ? 'selected' : '' }}>15 {{ app()->getLocale() === 'ar' ? 'دقيقة' : 'minutes' }}</option>
                    <option value="30" {{ old('interval') == 30 ? 'selected' : '' }}>30 {{ app()->getLocale() === 'ar' ? 'دقيقة' : 'minutes' }}</option>
                    <option value="60" {{ old('interval') == 60 ? 'selected' : '' }}>60 {{ app()->getLocale() === 'ar' ? 'دقيقة' : 'minutes' }}</option>
                </select>
                @error('interval') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="hidden" name="notify_email" value="0">
                    <input type="checkbox" id="notify_email" name="notify_email" value="1" {{ old('notify_email', true) ? 'checked' : '' }}>
                    <label for="notify_email">{{ __('messages.notify_email') }}</label>
                </div>
            </div>

            <div style="display:flex;gap:12px;margin-top:32px;">
                <button type="submit" class="btn btn-primary">🚀 {{ __('messages.save') }}</button>
                <a href="{{ route('monitors.index') }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>
</div>
@endsection
