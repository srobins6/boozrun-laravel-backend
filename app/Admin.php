<?php
	namespace App;
	
	use Illuminate\Foundation\Auth\User as Authenticatable;
	
	/**
 * App\Admin
 *
 * @property integer        $id
 * @property string         $email
 * @property string         $name
 * @property string         $password
 * @property boolean        $control
 * @property string         $remember_token
 * @property \Carbon\Carbon $created_at
 * @property \Carbon\Carbon $updated_at
 * @method static Admin find($value)
 * @method static \Illuminate\Database\Query\Builder|Admin whereId($value)
 * @method static \Illuminate\Database\Query\Builder|Admin whereEmail($value)
 * @method static \Illuminate\Database\Query\Builder|Admin whereName($value)
 * @method static \Illuminate\Database\Query\Builder|Admin wherePassword($value)
 * @method static \Illuminate\Database\Query\Builder|Admin whereControl($value)
 * @method static \Illuminate\Database\Query\Builder|Admin whereRememberToken($value)
 * @method static \Illuminate\Database\Query\Builder|Admin whereCreatedAt($value)
 * @method static \Illuminate\Database\Query\Builder|Admin whereUpdatedAt($value)
 * @mixin \Eloquent
 * @mixin \Eloquent
 * @mixin \Eloquent
 */
	class Admin extends Authenticatable {
		
		/**
		 * The attributes that are  mass assignable.
		 *
		 * @var array
		 */
		protected $fillable = ["email",
		                       "password",
		                       "name",
		                       "control"];
		
		/**
		 * The attributes excluded from the model's JSON form.
		 *
		 * @var array
		 */
		protected $hidden = ["password",
		                     "remember_token"];
		
		/**
		 * Encrypts the password when it is set.
		 *
		 * @param $password
		 */
		public function setPasswordAttribute($password) {
			$this->attributes["password"] = bcrypt($password);
		}
	}
