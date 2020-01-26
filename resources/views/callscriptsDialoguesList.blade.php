@extends('adminlte::page')

@section('content_header')
    <h1 class="content_header__h1">Список диалогов</h1>
@endsection

@section('content')
<div class="container" id="mainContainer">
	<div class="row">
		<div class="col-12">
	@if($callogList->count()>0)
			<table class="table small dataTable">
				<thead>
					<tr>
						<td>Номер диалога</td>
						<td>Время начала диалога</td>
						<td>Оператор</td>
						<td>Кол-во этапов</td>
						<td>Завершение</td>
						<td>Просмотр</td>
					</tr>
				</thead>
				<tbody>
		@foreach($callogList as $thisClLine)
					<tr>
						<td style="vertical-align: middle;text-align:center;">№{{ $thisClLine->call_id }}</td>
						<td style="vertical-align: middle;text-align:center;">{{ $thisClLine->created_at_string }}</td>
						<td style="vertical-align: middle;text-align:center;">{{ $thisClLine->operateur_name }}</td>
						<td style="vertical-align: middle;text-align:center;">{{ $thisClLine->questionsCount }}</td>
						<td style="vertical-align: middle;text-align:center;">
							{{ $thisClLine->dialogueEnd }}
			@if($thisClLine->dialogueResultExplained == -1)
							<span class="label label-danger" style="float:right;">Провал</span>
			@elseif($thisClLine->dialogueResultExplained == -2)
							<span class="label label-success" style="float:right;">Успешно</span>
			@elseif($thisClLine->dialogueResultExplained == 0)
							<span class="label label-warning" style="float:right;">Обрыв</span>
			@else
							<span class="label label-info" style="float:right;">Неведома фантасмагория</span>
			@endif
						</td>
						<td>
							<a href="{{ route('callscriptsReplayDialogue', $thisClLine->call_id) }}" class="btn btn-info">Просмотр</a>
						</td>
					</tr>
		@endforeach
				</tbody>
			</table>
	@endif
			{{ $callogList->links() }}
		</div>
	</div>
</div>

@endsection
