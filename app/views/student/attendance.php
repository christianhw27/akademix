<section class="page-head">
    <div>
        <span class="eyebrow">Siswa</span>
        <h1>Kehadiran</h1>
    </div>
</section>

<?php
$daysInMonth = cal_days_in_month(CAL_GREGORIAN, $month, $year);
$firstDayOfMonth = date('w', strtotime("$year-$month-01"));
$monthNames = [1=>'Januari','Februari','Maret','April','Mei','Juni','Juli','Agustus','September','Oktober','November','Desember'];
$monthName = $monthNames[$month];

$prevMonth = $month - 1;
$prevYear = $year;
if ($prevMonth < 1) { $prevMonth = 12; $prevYear--; }

$nextMonth = $month + 1;
$nextYear = $year;
if ($nextMonth > 12) { $nextMonth = 1; $nextYear++; }

// Map day number to Indonesian day name
$dayMap = [0=>'Minggu',1=>'Senin',2=>'Selasa',3=>'Rabu',4=>'Kamis',5=>'Jumat',6=>'Sabtu'];

// Build JSON of schedulesByDay for JS usage
$schedulesByDayJson = htmlspecialchars(json_encode($schedulesByDay), ENT_QUOTES, 'UTF-8');
?>

<style>
.calendar-container {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 12px;
    padding: 24px;
    box-shadow: 0 1px 3px rgba(0,0,0,0.05);
}
.calendar-header {
    display: flex;
    justify-content: space-between;
    align-items: center;
    margin-bottom: 24px;
}
.calendar-grid {
    display: grid;
    grid-template-columns: repeat(7, 1fr);
    gap: 8px;
}
.calendar-day-label {
    text-align: center;
    font-weight: 700;
    font-size: 13px;
    color: #64748b;
    padding-bottom: 8px;
    border-bottom: 1px solid #e2e8f0;
    margin-bottom: 8px;
}
.calendar-cell {
    min-height: 100px;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 8px;
    cursor: pointer;
    transition: all 0.2s;
    background: #ffffff;
    position: relative;
}
.calendar-cell:hover:not(.empty):not(.gray) {
    border-color: #94a3b8;
    box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
    transform: translateY(-2px);
}
.calendar-cell.empty {
    background: transparent;
    border-color: transparent;
    cursor: default;
}
.calendar-cell.gray { background: #f1f5f9; cursor: default; }
.calendar-cell.green { background: #f0fdf4; border-color: #bbf7d0; }
.calendar-cell.yellow { background: #fefce8; border-color: #fef08a; }
.calendar-cell.red { background: #fef2f2; border-color: #fecaca; }

.calendar-date-num {
    font-weight: 600;
    font-size: 14px;
    color: #1e293b;
    margin-bottom: 8px;
}
.calendar-cell.gray .calendar-date-num { color: #94a3b8; }

.calendar-status-text {
    font-size: 11px;
    font-weight: 600;
    text-transform: uppercase;
    text-align: center;
    margin-top: 16px;
}
.calendar-cell.green .calendar-status-text { color: #16a34a; }
.calendar-cell.yellow .calendar-status-text { color: #ca8a04; }
.calendar-cell.red .calendar-status-text { color: #dc2626; }
.calendar-cell.gray .calendar-status-text { color: #94a3b8; }

/* Legend */
.calendar-legend {
    display: flex;
    gap: 20px;
    margin-bottom: 20px;
    flex-wrap: wrap;
}
.legend-item {
    display: flex;
    align-items: center;
    gap: 6px;
    font-size: 13px;
    color: #475569;
}
.legend-dot {
    width: 14px;
    height: 14px;
    border-radius: 4px;
}

/* Modal Styles */
.modal-overlay {
    display: none;
    position: fixed;
    top: 0; left: 0; right: 0; bottom: 0;
    background: rgba(15, 23, 42, 0.5);
    z-index: 1000;
    align-items: center;
    justify-content: center;
    padding: 20px;
}
.modal-overlay.active {
    display: flex;
}
.modal-content {
    background: #ffffff;
    border-radius: 12px;
    width: 100%;
    max-width: 520px;
    max-height: 80vh;
    overflow-y: auto;
    box-shadow: 0 20px 25px -5px rgba(0,0,0,0.1);
}
.modal-header {
    padding: 16px 24px;
    border-bottom: 1px solid #e2e8f0;
    display: flex;
    justify-content: space-between;
    align-items: center;
}
.modal-body {
    padding: 24px;
}
.modal-close {
    background: transparent;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #64748b;
}
</style>

<div class="calendar-container">
    <div class="calendar-header">
        <a href="<?= e(route_url('student/attendance&month=' . $prevMonth . '&year=' . $prevYear)) ?>" class="btn small" style="text-decoration:none;">&larr; Sebelumnya</a>
        <h2 style="margin:0; font-size:1.25rem;"><?= $monthName . ' ' . $year ?></h2>
        <a href="<?= e(route_url('student/attendance&month=' . $nextMonth . '&year=' . $nextYear)) ?>" class="btn small" style="text-decoration:none;">Berikutnya &rarr;</a>
    </div>

    <div class="calendar-legend">
        <div class="legend-item"><div class="legend-dot" style="background:#bbf7d0; border:1px solid #86efac;"></div> Hadir Penuh</div>
        <div class="legend-item"><div class="legend-dot" style="background:#fef08a; border:1px solid #fde047;"></div> Hadir Sebagian</div>
        <div class="legend-item"><div class="legend-dot" style="background:#fecaca; border:1px solid #fca5a5;"></div> Tidak Hadir</div>
        <div class="legend-item"><div class="legend-dot" style="background:#e2e8f0; border:1px solid #cbd5e1;"></div> Libur / Akhir Pekan</div>
    </div>

    <div class="calendar-grid">
        <div class="calendar-day-label">Min</div>
        <div class="calendar-day-label">Sen</div>
        <div class="calendar-day-label">Sel</div>
        <div class="calendar-day-label">Rab</div>
        <div class="calendar-day-label">Kam</div>
        <div class="calendar-day-label">Jum</div>
        <div class="calendar-day-label">Sab</div>

        <?php
        for ($i = 0; $i < $firstDayOfMonth; $i++) {
            echo '<div class="calendar-cell empty"></div>';
        }

        for ($day = 1; $day <= $daysInMonth; $day++) {
            $dateStr = sprintf('%04d-%02d-%02d', $year, $month, $day);
            $dayOfWeek = date('w', strtotime($dateStr));
            $dayName = $dayMap[$dayOfWeek];
            
            $statusColor = '';
            $statusText = '';
            $recordsJson = '[]';
            
            if ($dayOfWeek == 0 || $dayOfWeek == 6) {
                $statusColor = 'gray';
                $statusText = 'Libur';
            } else {
                if (isset($attendanceByDate[$dateStr])) {
                    $records = $attendanceByDate[$dateStr];
                    $recordsJson = htmlspecialchars(json_encode($records), ENT_QUOTES, 'UTF-8');
                    
                    $hadirCount = 0;
                    $absenCount = 0;
                    foreach ($records as $r) {
                        if (strtolower($r['status']) == 'hadir') {
                            $hadirCount++;
                        } else {
                            $absenCount++;
                        }
                    }
                    if ($hadirCount > 0 && $absenCount == 0) {
                        $statusColor = 'green';
                        $statusText = 'Hadir Penuh';
                    } elseif ($hadirCount == 0 && $absenCount > 0) {
                        $statusColor = 'red';
                        $statusText = 'Tidak Hadir';
                    } elseif ($hadirCount > 0 && $absenCount > 0) {
                        $statusColor = 'yellow';
                        $statusText = 'Sebagian';
                    }
                }
            }
            ?>
            <div class="calendar-cell <?= $statusColor ?>" 
                 <?= $dayOfWeek != 0 && $dayOfWeek != 6 ? "onclick='openModal(\"$dateStr\", \"$dayName\", this.getAttribute(\"data-records\"))'" : "" ?>
                 data-records="<?= $recordsJson ?>">
                <div class="calendar-date-num"><?= $day ?></div>
                <div class="calendar-status-text"><?= $statusText ?></div>
            </div>
            <?php
        }
        ?>
    </div>
</div>

<!-- Modal -->
<div class="modal-overlay" id="attendanceModal" onclick="if(event.target===this) closeModal()">
    <div class="modal-content">
        <div class="modal-header">
            <h3 style="margin:0; font-size:1.1rem;" id="modalTitle">Detail Kehadiran</h3>
            <button class="modal-close" onclick="closeModal()">&times;</button>
        </div>
        <div class="modal-body" id="modalBody"></div>
    </div>
</div>

<script>
const schedulesByDay = <?= json_encode($schedulesByDay) ?>;

function getBadgeStyle(status) {
    status = status.toLowerCase();
    if (status === 'hadir') return 'background:#dcfce7; color:#166534; border:1px solid #bbf7d0;';
    if (status === 'izin') return 'background:#dbeafe; color:#1e40af; border:1px solid #bfdbfe;';
    if (status === 'sakit') return 'background:#fef9c3; color:#854d0e; border:1px solid #fef08a;';
    return 'background:#fee2e2; color:#991b1b; border:1px solid #fecaca;'; // alfa/alpha
}

function openModal(dateStr, dayName, recordsJson) {
    const dayNames = {'Senin':'Senin','Selasa':'Selasa','Rabu':'Rabu','Kamis':'Kamis','Jumat':'Jumat'};
    document.getElementById('modalTitle').innerText = dayName + ', ' + dateStr;
    const body = document.getElementById('modalBody');
    body.innerHTML = '';
    
    // Get the schedule for this day
    const daySchedules = schedulesByDay[dayName] || [];
    
    // Parse attendance records
    let attendanceRecords = [];
    try {
        attendanceRecords = JSON.parse(recordsJson);
    } catch (e) {}
    
    // Build a map: subject_name -> attendance status
    const attendanceMap = {};
    attendanceRecords.forEach(r => {
        const key = r.subject_name;
        if (!attendanceMap[key]) {
            attendanceMap[key] = r.status;
        }
    });
    
    let html = '';
    
    if (daySchedules.length === 0) {
        html = '<div style="text-align:center; padding:24px; color:#64748b;">Tidak ada jadwal pelajaran di hari ini.</div>';
    } else {
        html += '<div style="display:flex; flex-direction:column; gap:12px;">';
        daySchedules.forEach(s => {
            const startTime = s.start_time.substring(0, 5);
            const endTime = s.end_time.substring(0, 5);
            const status = attendanceMap[s.subject_name] || null;
            
            let statusHtml = '';
            if (status) {
                statusHtml = `<span style="padding:4px 10px; border-radius:6px; font-size:12px; font-weight:600; ${getBadgeStyle(status)}">${status.toUpperCase()}</span>`;
            } else {
                statusHtml = '<span style="padding:4px 10px; border-radius:6px; font-size:12px; font-weight:600; background:#f1f5f9; color:#94a3b8; border:1px solid #e2e8f0;">BELUM</span>';
            }
            
            html += `
                <div style="display:flex; justify-content:space-between; align-items:center; padding:14px; border:1px solid #e2e8f0; border-radius:10px; background:#fafafa;">
                    <div style="flex:1;">
                        <div style="font-weight:700; font-size:15px; color:#0f172a; margin-bottom:4px;">${s.subject_name}</div>
                        <div style="font-size:12px; color:#64748b; display:flex; align-items:center; gap:12px;">
                            <span>🕐 ${startTime} — ${endTime}</span>
                            <span>👤 ${s.teacher_name}</span>
                        </div>
                    </div>
                    ${statusHtml}
                </div>
            `;
        });
        html += '</div>';
    }
    
    body.innerHTML = html;
    document.getElementById('attendanceModal').classList.add('active');
}

function closeModal() {
    document.getElementById('attendanceModal').classList.remove('active');
}
</script>
