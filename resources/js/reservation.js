// 日時選択画面(datetime.blade.php)

let currentStartDate = new Date();
const today = new Date();
today.setHours(0, 0, 0, 0);

// datetime.blade.phpで定義された staffId を使うために window.staffId を使うように変更
document.addEventListener('DOMContentLoaded', () => {
    if (typeof window.staffId !== 'undefined') {
        loadAvailability(currentStartDate);
    }
});

// windowを追加して、HTML側のonclickから呼べるようにする
window.changeWeek = function(days) {
    const next = new Date(currentStartDate);
    next.setDate(next.getDate() + days);
    if (next < today && days < 0) return;
    currentStartDate = next;
    loadAvailability(currentStartDate);
}

async function loadAvailability(baseDate) {
    document.getElementById('prev-week').style.visibility = (baseDate <= today) ? 'hidden' : 'visible';
    const dateStr = baseDate.toISOString().split('T')[0];

    // ★ window.staffId を使うように変更
    const response = await fetch(`/api/reservations/check-week?staff_id=${window.staffId}&start_date=${dateStr}`);
    const data = await response.json();
    renderCalendar(baseDate, data);
}

function renderCalendar(startDate, data) {
    const { booked, holidays } = data;
    const header = document.getElementById('calendar-header');
    const body = document.getElementById('calendar-body');
    const monthDisplay = document.getElementById('current-month');

    monthDisplay.innerText = `${startDate.getFullYear()}年 ${startDate.getMonth() + 1}月`;

    let headerHtml = '<th class="p-4 w-20 border-r border-gray-50 font-bold">TIME</th>';
    const dateArray = [];
    const dayNames = ['SUN', 'MON', 'TUE', 'WED', 'THU', 'FRI', 'SAT'];

    for (let i = 0; i < 7; i++) {
        const d = new Date(startDate);
        d.setDate(d.getDate() + i);
        const dStr = d.toISOString().split('T')[0];
        dateArray.push(dStr);
        const isToday = today.toISOString().split('T')[0] === dStr;
        const dayColor = d.getDay() === 0 ? 'text-red-400' : (d.getDay() === 6 ? 'text-blue-400' : 'text-gray-400');

        headerHtml += `
    <th class="p-5 border-r border-gray-50 ${isToday ? 'bg-red-50/10' : ''}">
        <div class="${dayColor} mb-1">${dayNames[d.getDay()]}</div>
        <div class="text-lg font-light ${isToday ? 'text-red-600 font-medium' : 'text-gray-900'}">${d.getDate()}</div>
    </th>`;
    }
    header.innerHTML = headerHtml;

    let bodyHtml = '';
    for (let h = 10; h <= 18; h++) {
        ['00', '30'].forEach(m => {
            const time = `${h.toString().padStart(2, '0')}:${m}`;
            bodyHtml += `<tr>`;
            bodyHtml += `<td class="p-3 border-r border-gray-50 text-[10px] text-gray-400 bg-gray-50/20">${time}</td>`;

            dateArray.forEach(dStr => {
                const cellDate = new Date(dStr + ' ' + time);
                const isBooked = booked && booked[dStr] && booked[dStr].includes(time);
                // ★ 注意：この holidays の判定はAPI側からのデータに依存します
                const isHoliday = holidays && holidays.includes(new Date(dStr).getDay());
                const isPast = cellDate < new Date();

                if (isHoliday) {
                    bodyHtml += `<td class="is-holiday border-r border-gray-50 text-[10px]">休</td>`;
                } else if (isBooked || isPast) {
                    bodyHtml += `<td class="is-unavailable border-r border-gray-50 text-xs">×</td>`;
                } else {
                    bodyHtml += `
                <td class="p-0 border-r border-gray-50">
                    <button type="button" onclick="selectSlot('${dStr}', '${time}', this)"
                        class="slot-btn w-full h-full py-5 flex items-center justify-center transition hover:bg-red-50 group/btn">
                        <span class="status-icon text-red-400/60 text-lg group-hover/btn:scale-125 transition">○</span>
                    </button>
                </td>`;
                }
            });
            bodyHtml += `</tr>`;
        });
    }
    body.innerHTML = bodyHtml;
}

// windowを追加して、HTML側のonclickから呼べるようにする
window.selectSlot = function(date, time, btn) {
    document.querySelectorAll('.slot-btn').forEach(b => b.classList.remove('slot-selected'));
    btn.classList.add('slot-selected');
    document.getElementById('hidden-date').value = date;
    document.getElementById('hidden-time').value = time;
    document.getElementById('display-date').innerText = date.replace(/-/g, '.');
    document.getElementById('display-time').innerText = time;
    const summary = document.getElementById('selection-summary');
    summary.classList.remove('hidden');
    setTimeout(() => summary.classList.add('opacity-100', 'translate-y-0'), 10);
}

window.openModal = function() {
    const date = document.getElementById('hidden-date').value;
    const time = document.getElementById('hidden-time').value;

    if (!date || !time) return;

    document.getElementById('modal-display-dt').innerText = `${date.replace(/-/g, '/')} ${time}`;

    const modal = document.getElementById('confirm-modal');
    const content = document.getElementById('modal-content');

    modal.classList.remove('hidden');
    setTimeout(() => {
        content.classList.remove('scale-95', 'opacity-0');
        content.classList.add('scale-100', 'opacity-100');
    }, 10);
}

window.closeModal = function() {
    const content = document.getElementById('modal-content');
    content.classList.remove('scale-100', 'opacity-100');
    content.classList.add('scale-95', 'opacity-0');

    setTimeout(() => {
        document.getElementById('confirm-modal').classList.add('hidden');
    }, 300);
}
