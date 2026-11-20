@php
    $status = strtolower($status ?? 'inactive');
    $class = match(true) {
        in_array($status, ['active', 'completed', 'confirmed']) => 'sehat-badge-active',
        in_array($status, ['pending', 'provisioning', 'scheduled']) => 'sehat-badge-pending',
        in_array($status, ['expired', 'failed', 'cancelled', 'no_show']) => 'sehat-badge-expired',
        in_array($status, ['trial']) => 'sehat-badge-trial',
        in_array($status, ['inactive', 'suspended']) => 'sehat-badge-inactive',
        default => 'sehat-badge-scheduled',
    };
@endphp
<span class="sehat-badge {{ $class }}">{{ str_replace('_', ' ', $status) }}</span>

