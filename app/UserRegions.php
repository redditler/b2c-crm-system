<?php
	
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
    use Illuminate\Support\Facades\Auth;

    class UserRegions extends Model
	{


        public static function getRegions()
        {
            return self::query()
                ->where('user_id', Auth::user()->id)
                ->get()->toArray();
		}

		public static function getRegionsIdByUserId()
        {
            return self::query()
                ->where('user_id', Auth::user()->id)
                ->pluck('region_id')->toArray();
        }

        public static function getUserRegions()
        {
            return self::where('user_id', Auth::user()->id)
                ->get();
        }
	}
