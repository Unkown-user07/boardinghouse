@extends('layouts.user')

@section('styles')
<style>
    /* 1. The Container: Uses Columns to prevent uneven gaps */
    .announcement-grid {
        column-count: 1;
        column-gap: 1.5rem;
    }

    @media (min-width: 768px) { .announcement-grid { column-count: 2; } }
    @media (min-width: 1200px) { .announcement-grid { column-count: 3; } }

    /* 2. The Card: Prevents "splitting" across columns */
    .announcement-card {
        break-inside: avoid;
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 1px solid #eef2ff;
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.05);
        transition: transform 0.2s ease;
    }

    .announcement-card:hover {
        transform: translateY(-5px);
        border-color: #4361ee;
    }

    /* 3. The "Unbreakable" Text: Limits height automatically */
    .announcement-body {
        display: -webkit-box;
        -webkit-line-clamp: 4; /* Show only 4 lines max */
        -webkit-box-orient: vertical;
        overflow: hidden;
        color: #4b5563;
        line-height: 1.6;
        margin-bottom: 1rem;
    }

    .announcement-meta {
        display: flex;
        align-items: center;
        gap: 10px;
        font-size: 0.85rem;
        color: #9ca3af;
        border-top: 1px solid #f3f4f6;
        padding-top: 1rem;
    }

    .priority-high { border-left: 5px solid #ef4444; }
    .priority-normal { border-left: 5px solid #4361ee; }
</style>
@endsection

@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4 class="fw-bold mb-0">Community Announcements</h4>
        <span class="badge bg-primary-subtle text-primary px-3 py-2">5 New Updates</span>
    </div>

    <div class="announcement-grid">
        @php
            // Mock data for testing the UI
            $announcements = [
                ['title' => 'Water Maintenance', 'type' => 'high', 'date' => 'Feb 28', 'body' => 'Please be advised that there will be a scheduled water interruption from 1 PM to 5 PM for tank cleaning.'],
                ['title' => 'New WiFi Password', 'type' => 'normal', 'date' => 'Feb 25', 'body' => 'The WiFi password for the 2nd floor has been updated to StayEase2026! Please update your devices.'],
                ['title' => 'Monthly Socials', 'type' => 'normal', 'date' => 'Feb 20', 'body' => 'Join us this Friday for our monthly lobby mixer! Free snacks and drinks for all residents. This is a great time to meet your neighbors and enjoy some music.'],
                ['title' => 'Garbage Collection', 'type' => 'high', 'date' => 'Feb 15', 'body' => 'Reminder: Please place your sorted trash in the designated bins before 8 AM every Tuesday and Thursday. Failure to comply may result in fines.']
            ];
        @endphp

        @foreach($announcements as $item)
            <div class="announcement-card priority-{{ $item['type'] }}">
                <div class="d-flex justify-content-between align-items-start mb-2">
                    <h5 class="fw-bold text-dark mb-0">{{ $item['title'] }}</h5>
                    @if($item['type'] == 'high')
                        <i class="bi bi-exclamation-triangle-fill text-danger"></i>
                    @endif
                </div>
                
                <div class="announcement-body">
                    {{ $item['body'] }}
                </div>

                <div class="announcement-meta">
                    <img src="https://ui-avatars.com/api/?name=Admin&size=24&background=4361ee&color=fff" class="rounded-circle" alt="">
                    <span>Admin • {{ $item['date'] }}</span>
                    <button class="btn btn-link btn-sm ms-auto p-0 text-decoration-none" onclick="viewFull('#')">Read More</button>
                </div>
            </div>
        @endforeach
    </div>
</div>

<div class="modal fade" id="announcementModal" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow">
            <div class="modal-header border-0">
                <h5 class="modal-title fw-bold" id="m-title"></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body py-0" id="m-body"></div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<script>
    function viewFull(title) {
        // Just a quick example to show it works
        document.getElementById('m-title').innerText = title;
        document.getElementById('m-body').innerText = "Full content would load here from the database...";
        new bootstrap.Modal(document.getElementById('announcementModal')).show();
    }
</script>
@endsection