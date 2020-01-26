<form id="leadFilter" class='filters__content'>
    <div class="filters__head">
        @if(\Illuminate\Support\Facades\Auth::user()->role_id == 1 )
        <div class="add__filters" id="addFiltersShow">
            <div class="add__icon">
                <i class="fa fa-chevron-down"></i>
            </div>
            <div class="add__title">
                <span class="title">Доп. фильтры</span>
            </div>
        </div>
        @endif
        @include('leads.filter.leadFilterDate')
        @include('leads.filter.leadFilterPhone')
    </div>
   
    <div class="filters__footer">
        @include('leads.filter.leadFilterOption')
    </div>
</form>