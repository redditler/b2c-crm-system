<div class="filter filter__date">
    <div class="filter__title">
        <span class="title">Дата</span>
    </div>
    <div class="filter__content">
        <label class="filter__from">
            <div class="filter__icon">
                <span class="icon--title">с</span>
                <i class="icon fa fa-chevron-down"></i>
            </div>
            <input value="{{!empty(request()->session()->get('leadDateFrom')) ? 
                \Carbon\Carbon::make(request()->session()->get('leadDateFrom'))->format('Y-m-d') : 
                \Carbon\Carbon::make(\App\Leed::dateFromLead()[0])->format('Y-m-d')}}" class="filter__input datepicker"
                name="leadDateFrom" id="leadDateFrom" type="text">
        </label>
        <label class="filter__to">
            <div class="filter__icon">
                <span class="icon--title">по</span>
                <i class="icon fa fa-chevron-down"></i>
            </div>
            <input value="{{!empty(request()->session()->get('leadDateTo')) ? 
                \Carbon\Carbon::make(request()->session()->get('leadDateTo'))->format('Y-m-d') : 
                \Carbon\Carbon::make(\App\Leed::dateFromLead()[1])->format('Y-m-d')}}" class="filter__input datepicker"
                name="leadDateTo" id="leadDateTo" type="text">
        </label>
    </div>
</div>