<?php
	namespace App;
	
	use Illuminate\Database\Eloquent\Model;
	
	/**
 * App\Hours
 *
 * @property integer         $id
 * @property integer         $store_id
 * @property string          $name
 * @property boolean         $active
 * @property string          $mondaystart
 * @property string          $mondayend
 * @property boolean         $mondayopen
 * @property string          $tuesdaystart
 * @property string          $tuesdayend
 * @property boolean         $tuesdayopen
 * @property string          $wednesdaystart
 * @property string          $wednesdayend
 * @property boolean         $wednesdayopen
 * @property string          $thursdaystart
 * @property string          $thursdayend
 * @property boolean         $thursdayopen
 * @property string          $fridaystart
 * @property string          $fridayend
 * @property boolean         $fridayopen
 * @property string          $saturdaystart
 * @property string          $saturdayend
 * @property boolean         $saturdayopen
 * @property string          $sundaystart
 * @property string          $sundayend
 * @property boolean         $sundayopen
 * @property \Carbon\Carbon  $created_at
 * @property \Carbon\Carbon  $updated_at
 * @property-read \App\Store $store
 * @property mixed           $days
 * @method static Hours find($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereStoreId($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereName($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereActive($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereMondaystart($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereMondayend($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereMondayopen($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereTuesdaystart($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereTuesdayend($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereTuesdayopen($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereWednesdaystart($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereWednesdayend($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereWednesdayopen($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereThursdaystart($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereThursdayend($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereThursdayopen($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereFridaystart($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereFridayend($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereFridayopen($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereSaturdaystart($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereSaturdayend($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereSaturdayopen($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereSundaystart($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereSundayend($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereSundayopen($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Hours whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin \Eloquent
 * @mixin \Eloquent
 */
	class Hours extends Model {
		
		/**
		 * The attributes that are not mass assignable.
		 *
		 * @var array
		 */
		protected $guarded = [];
		
		/**
		 * Method runs on model initialization.
		 */
		public static function boot() {
			parent::boot();
			Hours::creating(function ($hours) {
				if (!$hours->name && $hours->store) {
					$hoursNum = ($hours->store->hours->count() + 1);
					$hours->name = "Hours" . $hoursNum;
				}
			});
		}
		
		/**
		 * Set of hours belongs to one store.
		 *
		 * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
		 */
		public function store() {
			return $this->belongsTo('App\Store');
		}
		
		/**
		 * Set the hours for each individual day.
		 *
		 * @param $days
		 */
		public function setDaysAttribute($days) {
			foreach ($days as $day => $dayInfo) {
				if (isset ($dayInfo ["open"])) {
					$dayInfo ["open"] = "1";
				} else {
					$dayInfo ["open"] = "0";
				}
				foreach ($dayInfo as $key => $value) {
					$field = $day . $key;
					$this->$field = $value;
				}
			}
		}
		
		/**
		 * Get the hours for each individual day.
		 *
		 * @return array
		 */
		public function getDaysAttribute() {
			$daynames = ["monday",
			             "tuesday",
			             "wednesday",
			             "thursday",
			             "friday",
			             "saturday",
			             "sunday"];
			$days = [];
			$fieldNames = ["start",
			               "end",
			               "open"];
			foreach ($daynames as $dayname) {
				$day = [];
				foreach ($fieldNames as $fieldName) {
					$field = $dayname . $fieldName;
					$day [$fieldName] = $this->$field;
				}
				$days [$dayname] = $day;
			}
			return $days;
		}
	}
