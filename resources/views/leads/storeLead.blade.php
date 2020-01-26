@extends('adminlte::page')

@section('content')

    <section class="content__wrapper title-style" data-id="store-lead">
        <div class="container create-lead">
            <div class="container__title">
                <h1 class="title">Создать новый лид</h1>
            </div>
            <div class="container__content">
                <div class="content__header">
                    <a href="#" class="btn btn--default blue" onclick="history.back();"><i class="fa fa-long-arrow-left"></i>Вернуться</a>
                </div>
                <form action="{{route('createLead')}}" class="container__body form" method="post" id="createLead">
                    {{csrf_field()}}
                    <div class="form__left-part">
                        <label for="leed_name" class="form__input">
                            <span class="input--title">Имя</span>
                            <div class="input__wrapper">
                                <input type="text" name="leed_name" class="input" required>
                            </div>
                        </label>
                        <label for="leed_phone" class="form__input">
                            <span class="input--title">Телефон</span>
                            <div class="input__wrapper">
                                <input type="text" name="leed_phone" class="input" required>
                            </div>
                        </label>
                        <label for="leed_region_id" class="form__select">
                            <span class="input--title">Регион</span>
                            <div class="input__wrapper">
                                <select class="select" name="leed_region_id" required>
                                    <option class="option" selected disabled>Выберите регион</option>
                                    @foreach($regions as $region)
                                        <option class="option" value="{{$region->id}}">{{$region->name}}</option>
                                    @endforeach
                                </select>
                            </div>
                        </label>
                        <label for="label_id" class="form__select">
                            <span class="input--title">Тип заявки</span>
                            <select class="form-control" name="label_id" required >
                                <option class="option" selected value="5">Выберите тип</option>
                                @foreach($labels as $label)
                                    <option class="option" value="{{$label->id}}">{{$label->name}}</option>
                                @endforeach
                            </select>
                        </label>
                    </div>
                    <div class="form__right-part">
                        @if(\Illuminate\Support\Facades\Auth::user()->role_id != 5)
                            <label for="comment" class="form__comment">
                                <span class="textarea--title">Комментарии</span>
                                <textarea type="text" name="comment" class="textarea" placeholder="Комментарии" required></textarea>
                            </label>
                        @else
                            <label for="cm_comment" class="form__comment">
                                <span class="textarea--title">Комментарии Call-Centre</span>
                                <textarea type="text" name="cm_comment" class="textarea" placeholder="Комментарии"></textarea>
                            </label>
                        @endif
                    </div>
                    <div class="form__bottom-part">
                        <button type="submit" class="btn btn--crm">
                            <span class="btn--title">Создать новый Лид</span>
                        </button>
                    </div>
                </form>
                <div class="container__footer">
                    <div class="alert alert-success" role="alert" style="display: none">
                        <strong>Well done!</strong>
                    </div>
                    <div class="alert alert-danger" role="alert" style="display: none">
                        <strong>Oh snap!</strong>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

@section('tmp_js')
    <script>
        $(document).ready(function () {
            $('#createLead').on('submit', function (e) {
                e.preventDefault();
                $.ajax({
                    type: 'POST',
                    url: '{{route('createLead')}}',
                    data: $(this).serializeArray(),
                    success:function (result) {
                        if (!Array.isArray(result)){
                            $('.alert-success').text(result);
                            setTimeout(function (){$('.alert-success').css('display', 'inline-block')}, 500);
                            setTimeout(function (){$('.alert-success').css('display', 'none')}, 2000);
                            setTimeout(function (){ window.location.href = '{{route('leads')}}'}, 2000);
                        }else if(Array.isArray(result)){
                            $('.alert-danger').text(result);
                            setTimeout(function (){$('.alert-danger').css('display', 'inline-block')}, 1000);
                            setTimeout(function (){$('.alert-danger').css('display', 'none')}, 10000);
                        }
                    }
                });
            });
        });
    </script>
@endsection

