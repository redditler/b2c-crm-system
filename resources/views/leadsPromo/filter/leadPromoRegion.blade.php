<div class="filter__region reg_filter">
      <span class='filter--title'>Регион</span>
      <label>
          <select name="leed_region_id[]" id="leadRegionId" class="multiselect-ui form-control form-control-sm" multiple="multiple">
              @foreach(\App\Regions::getUserRegions() as $region)
                  <option value="{{$region['id']}}">{{$region['name']}}</option>
              @endforeach
          </select>
      </label>
  </div>