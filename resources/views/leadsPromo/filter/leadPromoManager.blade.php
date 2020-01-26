<div class="filter__manager man_filter">
      <span class='filter--title'>Менеджер</span>
      <label>
          <select name="user_id[]" id="leadsUserId" class="filter-manager multiselect-ui form-control form-control-sm" multiple="multiple">
              @if(\Illuminate\Support\Facades\Auth::user()->role_id == 1 || \Illuminate\Support\Facades\Auth::user()->role_id == 5)
                  @foreach(\App\User::userManager() as $manager)
                      <option value="{{$manager['id']}}">{{$manager['name']}}</option>
                  @endforeach
              @elseif(\Illuminate\Support\Facades\Auth::user()->role_id == 2)
                  @foreach(\App\User::userManager() as $manager)
                      @if(\Illuminate\Support\Facades\Auth::user()->group_id == $manager['group_id'])
                          <option value="{{$manager['id']}}">{{$manager['name']}}</option>
                      @endif
                  @endforeach
              @elseif(\Illuminate\Support\Facades\Auth::user()->role_id == 4)
                  @foreach(\App\UserRm::getRmManagers()->get() as $manager)
                      <option value="{{$manager->id}}">{{$manager->name}}</option>
                  @endforeach
              @elseif(\Illuminate\Support\Facades\Auth::user()->role_id == 3)
                  <option value="{{\Illuminate\Support\Facades\Auth::user()->id}}">{{\Illuminate\Support\Facades\Auth::user()->name}}</option>
              @endif
              <option value="0">Не распределен</option>
          </select>
      </label>
  </div>