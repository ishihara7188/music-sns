@component('mail::message')
{{-- Greeting --}}
@if (! empty($greeting))
# {{ $greeting }}
@else
@if ($level === 'error')
# @lang('Whoops!')
@else
# 本登録のご案内
@endif
@endif

{{-- Intro Lines --}}
@foreach ($introLines as $line)
{{ $line }}

@endforeach

{{-- Action Button --}}
@isset($actionText)
<?php
    switch ($level) {
        case 'success':
        case 'error':
            $color = $level;
            break;
        default:
            $color = 'primary';
    }
?>
@component('mail::button', ['url' => $actionUrl, 'color' => $color])
<!-- {{ $actionText }} -->
本登録を完了する
@endcomponent
@endisset

{{-- Outro Lines --}}
@foreach ($outroLines as $line)
{{ $line }}

@endforeach

{{-- Salutation --}}
@if (! empty($salutation))
{{ $salutation }}
@else
{{ config('app.name') }} より
@endif

{{-- Subcopy --}}
@isset($actionText)
@slot('subcopy')
もし、「{{ $actionText }}ボタン」がうまく機能しない場合、以下のURLをコピー＆ペーストして直接ブラウザからアクセスしてください。
[{{ $actionUrl }}]({{ $actionUrl }})
@endslot
@endisset
@endcomponent
