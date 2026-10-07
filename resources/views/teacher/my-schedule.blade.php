@extends('layouts.teacher')

@section('title', "Mening jadvalim")
@section('page-title', "Mening jadvalim")
@section('page-subtitle', 'Haftalik dars jadvalingiz')

@section('content')

@php
  $weekDays = ['Du', 'Se', 'Chor', 'Pay', 'Ju', 'Shan'];
@endphp

<div class="grid-wrap">
  <table style="min-width:900px;border-collapse:separate;border-spacing:6px;background:transparent;border:none;">
    <thead>
      <tr>
        <th style="width:50px;background:transparent;border:none;"></th>
        @foreach($periods as $p)
          <th style="background:transparent;border:none;text-align:center;padding:6px 4px;font-size:11px;color:var(--grey-2);text-transform:none;letter-spacing:0;">
            <span style="display:block;font-family:'Space Grotesk';font-size:14px;color:#fff;font-weight:700;">{{ $p->number }}.</span>
            {{ \Carbon\Carbon::parse($p->start_time)->format('H:i') }}–{{ \Carbon\Carbon::parse($p->end_time)->format('H:i') }}
          </th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @foreach($days as $day)
        <tr>
          <td style="border:none;text-align:center;font-family:'Space Grotesk';font-weight:700;font-size:13px;color:#fff;background:transparent;">
            {{ $day->short }}
          </td>

          @foreach($periods as $p)
            @php $lesson = $grid[$day->short][$p->number] ?? null; @endphp
            <td style="vertical-align:top;padding:0;background:transparent;border:none;width:110px;">
              @if($lesson)
                <div style="border-radius:10px;padding:8px 9px;min-height:70px;
                            background:rgba(91,140,255,0.14);border:1px solid rgba(91,140,255,0.35);">
                  <div style="font-size:11.5px;font-weight:700;color:#fff;line-height:1.25;">
                    {{ $lesson->subject->name }}
                  </div>
                  <div style="font-size:10.5px;color:rgba(255,255,255,0.85);margin-top:4px;">
                    {{ $lesson->group->name }}
                  </div>
                  <div style="font-size:10px;color:rgba(255,255,255,0.6);margin-top:2px;">
                    {{ $lesson->room->name }}
                  </div>
                </div>
              @else
                <div style="min-height:70px;"></div>
              @endif
            </td>
          @endforeach
        </tr>
      @endforeach
    </tbody>
  </table>
</div>

@endsection