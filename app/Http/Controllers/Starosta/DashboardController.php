@extends('layouts.admin')

@section('title', 'Dashboard')
@section('page-title', 'Dashboard')
@section('page-subtitle', 'Umumiy statistika')

@section('content')

{{-- Asosiy kartochkalar --}}
<div style="display: flex; justify-content: center; align-items: center; min-height: 45vh;">
    <div style="display: grid; grid-template-columns: repeat(3, 300px); gap: 24px; width: 100%; max-width: 980px;">

        {{-- O'quvchilar kartochkasi --}}
        <div onclick="openModal('studentsModal')" style="background: var(--bg-1); border: 1px solid var(--line); border-radius: var(--radius); padding: 28px; cursor: pointer; transition: transform 0.2s, border-color 0.2s; height: 180px; display: flex; flex-direction: column; justify-content: space-between;" onmouseover="this.style.borderColor='#5b8cff'; this.style.transform='translateY(-4px)';" onmouseout="this.style.borderColor='var(--line)'; this.style.transform='translateY(0)';">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="color: var(--grey-2); font-size: 13px; text-transform: uppercase; font-weight: 600;">O'quvchilar</div>
                <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg,#5b8cff,#8b5cf6); display: flex; align-items: center; justify-content: center; color: #fff;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M22 10v6M2 10l10-5 10 5-10 5z"></path><path d="M6 12v5c3 3 9 3 12 0v-5"></path></svg>
                </div>
            </div>
            <div style="font-family: 'Space Grotesk'; font-size: 42px; font-weight: 700;">{{ $stats['students'] ?? 0 }}</div>
            <div style="font-size: 12px; color: #5b8cff; font-weight: 500;">📊 Grafiklarni ko'rish uchun bosing</div>
        </div>

        {{-- Ustozlar kartochkasi --}}
        <div style="background: var(--bg-1); border: 1px solid var(--line); border-radius: var(--radius); padding: 28px; height: 180px; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="color: var(--grey-2); font-size: 13px; text-transform: uppercase; font-weight: 600;">Ustozlar</div>
                <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg,#4ade80,#22c55e); display: flex; align-items: center; justify-content: center; color: #fff;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"></path><circle cx="12" cy="7" r="4"></circle></svg>
                </div>
            </div>
            <div style="font-family: 'Space Grotesk'; font-size: 42px; font-weight: 700;">{{ $stats['teachers'] ?? 0 }}</div>
            <div style="font-size: 12px; color: var(--grey-2);">Faol o'qituvchilar</div>
        </div>

        {{-- Tashriflar kartochkasi --}}
        <div style="background: var(--bg-1); border: 1px solid var(--line); border-radius: var(--radius); padding: 28px; height: 180px; display: flex; flex-direction: column; justify-content: space-between;">
            <div style="display: flex; justify-content: space-between; align-items: center;">
                <div style="color: var(--grey-2); font-size: 13px; text-transform: uppercase; font-weight: 600;">Tashriflar</div>
                <div style="width: 40px; height: 40px; border-radius: 10px; background: linear-gradient(135deg,#fbbf24,#f59e0b); display: flex; align-items: center; justify-content: center; color: #fff;">
                    <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"><path d="M1 12s4-8 11-8 11 8 11 8-4 8-11 8-11-8-11-8z"></path><circle cx="12" cy="12" r="3"></circle></svg>
                </div>
            </div>
            <div style="font-family: 'Space Grotesk'; font-size: 42px; font-weight: 700;">{{ number_format($stats['visits'] ?? 0) }}</div>
            <div style="font-size: 12px; color: var(--grey-2);">Saytga kirishlar</div>
        </div>

    </div>
</div>

{{-- MODAL OYNA (HTML / CSS ORQALI CHIZILGAN GRAFIK) --}}
<div id="studentsModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0,0,0,0.8); z-index: 1000; justify-content: center; align-items: center;">
    <div style="background: #1e1e2d; border: 1px solid var(--line); border-radius: 12px; width: 750px; max-height: 85vh; display: flex; flex-direction: column; padding: 28px; box-shadow: 0 15px 35px rgba(0,0,0,0.7);">
        
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; border-bottom: 1px solid rgba(255,255,255,0.1); padding-bottom: 12px;">
            <h3 style="font-size: 18px; font-weight: 600; color: #fff; margin: 0;">O'quvchilar davomati (Grafik tahlil)</h3>
            <button onclick="closeModal('studentsModal')" style="background: transparent; border: none; color: #aaa; font-size: 26px; cursor: pointer;">&times;</button>
        </div>

        {{-- Scroll qilinadigan qism --}}
        <div style="overflow-y: auto; max-height: 65vh; padding-right: 8px; display: flex; flex-direction: column; gap: 20px;">
            
            {{-- CHIZILGAN GRAFIK BLOKI (Rasmdagi kabi baland-past ustunlar) --}}
            <div style="background: rgba(255,255,255,0.02); border: 1px solid rgba(255,255,255,0.06); border-radius: 8px; padding: 20px;">
                <div style="font-size: 14px; font-weight: 600; color: #fff; margin-bottom: 20px;">Choraklar kesimida davomat faolligi</div>
                
                <div style="display: flex; align-items: flex-end; justify-content: space-around; height: 180px; border-bottom: 2px solid rgba(255,255,255,0.1); padding-bottom: 10px;">
                    {{-- 1-Ustun --}}
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                        <span style="font-size: 11px; color: #4ade80;">85%</span>
                        <div style="width: 45px; height: 90px; background: #4ade80; border-radius: 4px 4px 0 0;"></div>
                        <span style="font-size: 12px; color: #aaa;">1-Chorak</span>
                    </div>
                    {{-- 2-Ustun (Balandroq) --}}
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                        <span style="font-size: 11px; color: #3b82f6;">98%</span>
                        <div style="width: 45px; height: 150px; background: #3b82f6; border-radius: 4px 4px 0 0;"></div>
                        <span style="font-size: 12px; color: #aaa;">2-Chorak</span>
                    </div>
                    {{-- 3-Ustun --}}
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                        <span style="font-size: 11px; color: #f59e0b;">75%</span>
                        <div style="width: 45px; height: 110px; background: #f59e0b; border-radius: 4px 4px 0 0;"></div>
                        <span style="font-size: 12px; color: #aaa;">3-Chorak</span>
                    </div>
                    {{-- 4-Ustun --}}
                    <div style="display: flex; flex-direction: column; align-items: center; gap: 8px;">
                        <span style="font-size: 11px; color: #ef4444;">90%</span>
                        <div style="width: 45px; height: 135px; background: #ef4444; border-radius: 4px 4px 0 0;"></div>
                        <span style="font-size: 12px; color: #aaa;">4-Chorak</span>
                    </div>
                </div>
            </div>

        </div>

    </div>
</div>

<script>
function openModal(modalId) {
    document.getElementById(modalId).style.display = 'flex';
}
function closeModal(modalId) {
    document.getElementById(modalId).style.display = 'none';
}
window.onclick = function(event) {
    if (event.target.style.position === 'fixed') {
        event.target.style.display = 'none';
    }
}
</script>

@endsection