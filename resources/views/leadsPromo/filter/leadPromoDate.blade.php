<div class="filter filter__date">
      <div class="filter__title-container">
          <span class="filter--title">Фильтр / Дата</span>
      </div>
      <div class="filter__wrapper">
          <label class="filter__from-date">
              <div class="label__icon">
                  <span class="label--title">с</span>
                  <i class="label--icon fa fa-angle-down"></i>
              </div>
              <input 
                  value="{{!empty(request()->session()->get('leadDateFrom')) ? \Carbon\Carbon::make(request()->session()->get('leadDateFrom'))->format('Y-m-d') : \Carbon\Carbon::make(\App\Leed::dateFromLead()[0])->format('Y-m-d')}}"
                  type="text" 
                  class="input datepicker" 
                  name="leadDateFrom" 
                  id="leadDateFrom">
          </label>
  
          <label class="filter__to-date">
              <div class="label__icon">
                  <span class="label--title">по</span>
                  <i class="label--icon fa fa-angle-down"></i>
              </div>
              <input 
                  value="{{!empty(request()->session()->get('leadDateTo')) ? \Carbon\Carbon::make(request()->session()->get('leadDateTo'))->format('Y-m-d') : \Carbon\Carbon::make(\App\Leed::dateFromLead()[1])->format('Y-m-d')}}"
                  type="text" 
                  class="input datepicker" 
                  name="leadDateTo" 
                  id="leadDateTo">
          </label>
      </div>
  </div>