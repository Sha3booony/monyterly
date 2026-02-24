@extends('layouts.dashboard')

@section('title', __('messages.edit_monitor') . ' — Monitorly')
@section('page-title', __('messages.edit_monitor'))

@section('content')
<div style="max-width:600px;">
    <div class="card">
        <form method="POST" action="{{ route('monitors.update', $monitor) }}">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label class="form-label" for="name">{{ __('messages.monitor_name') }}</label>
                <input class="form-input" type="text" id="name" name="name" value="{{ old('name', $monitor->name) }}" required>
                @error('name') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="url">{{ __('messages.monitor_url') }}</label>
                <input class="form-input" type="url" id="url" name="url" value="{{ old('url', $monitor->url) }}" required dir="ltr">
                @error('url') <div class="form-error">{{ $message }}</div> @enderror
            </div>

            <div class="form-group">
                <label class="form-label" for="interval">{{ __('messages.monitor_interval') }}</label>
                <select class="form-input" id="interval" name="interval">
                    @foreach([1, 3, 5, 10, 15, 30, 60] as $val)
                        <option value="{{ $val }}" {{ old('interval', $monitor->interval) == $val ? 'selected' : '' }}>
                            {{ $val }} {{ app()->getLocale() === 'ar' ? 'دقيقة' : 'min' }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <div class="form-check">
                    <input type="hidden" name="notify_email" value="0">
                    <input type="checkbox" id="notify_email" name="notify_email" value="1" {{ old('notify_email', $monitor->notify_email) ? 'checked' : '' }}>
                    <label for="notify_email">{{ __('messages.notify_email') }}</label>
                </div>
            </div>

            <div style="display:flex;gap:12px;margin-top:32px;">
                <button type="submit" class="btn btn-primary">💾 {{ __('messages.save') }}</button>
                <a href="{{ route('monitors.show', $monitor) }}" class="btn btn-secondary">{{ __('messages.cancel') }}</a>
            </div>
        </form>
    </div>

    <!-- Delete -->
    <div class="card" style="margin-top:24px;border-color:rgba(255,51,102,0.2);">
        <div style="display:flex;align-items:center;justify-content:space-between;">
            <div>
                <div style="font-weight:600;color:var(--danger);">⚠️ {{ app()->getLocale() === 'ar' ? 'منطقة الخطر' : 'Danger Zone' }}</div>
                <div style="font-size:0.85rem;color:var(--text-muted);margin-top:4px;">{{ __('messages.confirm_delete') }}</div>
            </div>
            <form method="POST" action="{{ route('monitors.destroy', $monitor) }}" onsubmit="return confirm('{{ __('messages.confirm_delete') }}')">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger btn-sm">🗑️ {{ __('messages.delete') }}</button>
            </form>
        </div>
    </div>
</div>
@endsection
