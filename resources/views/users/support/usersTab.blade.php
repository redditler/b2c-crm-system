<div class="row">
    <div class="col-md-10">
        <div class="panel with-nav-tabs panel-default">
            <div class="panel-heading">
                <ul class="nav nav-tabs">
                    <li class="active"><a href="#contentUserWork" data-toggle="tab">Работают</a></li>
                    {{--<li><a href="#contentUserFired" data-toggle="tab">Уволенные</a></li>--}}
                </ul>
            </div>
            <div class="panel-body">
                <div class="tab-content">
                    <div class="tab-pane fade in active" id="contentUserWork">
                        <table class="table table-bordered compact" id="tableUserWork">
                            <thead>
                            <tr>
                                <th class="text-center">ID</th>
                                <th class="text-center">ФИО</th>
                                <th class="text-center">Група</th>
                                <th class="text-center">Роль</th>
                                <th class="text-center">2FA</th>
                                <th class="text-center">Action</th>
                            </tr>
                            </thead>
                        </table>
                    </div>
                    {{--<div class="tab-pane fade" id="contentUserFired">--}}
                        {{--<input type="hidden" name="fired" id="userFired" value="0">--}}
                        {{--<table class="table table-bordered" id="tableUserFired">--}}
                            {{--<thead>--}}
                            {{--<tr>--}}
                                {{--<th class="text-center">ID</th>--}}
                                {{--<th class="text-center">ФИО</th>--}}
                                {{--<th class="text-center">Група</th>--}}
                                {{--<th class="text-center">Роль</th>--}}
                                {{--<th class="text-center">2FA</th>--}}
                                {{--<th class="text-center">Action</th>--}}
                            {{--</tr>--}}
                            {{--</thead>--}}
                        {{--</table>--}}
                    {{--</div>--}}
                </div>
            </div>
        </div>
    </div>
</div>
