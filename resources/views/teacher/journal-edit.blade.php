@extends('layouts.teacher')

@section('title', 'Jurnal — ' . $group->name . ' / ' . $subject->name)
@section('page-title', $group->name . ' — ' . $subject->name)
@section('page-subtitle', 'Baholarni kiriting (0 dan 10 gacha)')

@section('content')

<form method="POST" action="{{ route('teacher.journal.save', [$group, $subject]) }}">
  @csrf

  <table>
    <thead>
      <tr>
        <th style="width:50px;">#</th>
        <th>Familiya</th>
        <th>Ism</th>
        <th style="width:120px;">Bugungi baho</th>
      </tr>
    </thead>
    <tbody>
      @foreach($students as $i => $s)
        <tr>
          <td style="color:var(--grey-2);">{{ $i + 1 }}</td>
          <td><strong>{{ $s->last_name }}</strong></td>
          <td>{{ $s->first_name }}</td>
          <td>
            <input type="number" name="grades[{{ $s->id }}]"
                   min="0" max="10"
                   value="{{ old('grades.'.$s->id, $grades[$s->id]->lesson_score ?? '') }}"
                   style="width:80px;background:var(--bg-2);border:1px solid var(--line);
                          border-radius:8px;color:#fff;padding:8px 10px;font-family:inherit;
                          font-size:13.5px;outline:none;">
          </td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <div style="margin-top:20px;display:flex;gap:10px;">
    <button type="submit" class="btn">Saqlash</button>
    <a href="{{ route('teacher.journal') }}" class="btn btn-secondary">Orqaga</a>
  </div>
</form>

@endsection