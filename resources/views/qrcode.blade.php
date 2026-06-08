@php
    $qrCode = QrCode::format('svg')
        ->size(300)
        ->errorCorrection('H')
        ->style('round')
        ->eye('square')
        ->margin(1)
        ->generate($url);

    $qrCodePng = base64_encode(
        QrCode::format('png')
            ->size(300)
            ->errorCorrection('H')
            ->style('round')
            ->eye('square')
            ->margin(1)
            ->generate($url)
    );

    $downloadName = 'qrcode-' . $code;

    if ($downloadName === 'qrcode-') {
        $downloadName = 'qrcode';
    }
@endphp

<div class="space-y-4">
    <div class="flex justify-center mb-5">
        {!! $qrCode !!}
    </div>

    <div class="flex justify-center gap-3">
        <x-filament::button
            tag="a"
            color="gray"
            download="{{ $downloadName }}.svg"
            href="data:image/svg+xml;charset=utf-8,{{ rawurlencode($qrCode) }}"
        >
            Download SVG
        </x-filament::button>

        <x-filament::button
            tag="a"
            color="gray"
            download="{{ $downloadName }}.png"
            href="data:image/png;base64,{{ $qrCodePng }}"
        >
            Download PNG
        </x-filament::button>
    </div>
</div>
