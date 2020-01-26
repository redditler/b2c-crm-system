@extends('adminlte::page')

@section('content_header')
    <h1 class="content_header__h1">Список улучшений</h1>
@endsection

@section('content')
<div class="container" id="mainContainer">
	<div class="row">
		<div class="col-12">
			<table class="table small dataTable">
				<thead>
					<tr>
						<td>Дата предложения</td>
						<td>Скрипт</td>
						<td>Вопрос</td>
						<td>Автор</td>
						<td>Действия</td>
					</tr>
				</thead>
				<tbody>
	@if($improvements->count()>0)
		@foreach($improvements as $thisImprovement)
					<tr>
						<td>{{ $thisImprovement->created_at_string }}</td>
						<td>{{ $thisImprovement->topic_data->topic_name }}</td>
						<td>{{ $thisImprovement->question_data->question_title }}</td>
						<td>{{ $thisImprovement->operateur_name }}</td>
						<td><a href="{{ route('callscriptsViewImprovement', $thisImprovement->id) }}" class="btn btn-info">Просмотр</a></td>
					</tr>
		@endforeach
	@endif
				</tbody>
			</table>
			{{ $improvements->links() }}
		</div>
	</div>
</div>

@endsection
