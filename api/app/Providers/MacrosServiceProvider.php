<?php

namespace App\Providers;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class MacrosServiceProvider extends ServiceProvider
{
	public function register()
	{
		Builder::macro('whereLike', function ($column, $value) {
			$this->where($column, 'pgsql' == Model::getConnectionResolver()->getDefaultConnection() ? 'ilike' : 'like', "%{$value}%");
			return $this;
		});

		Builder::macro('orWhereLike', function ($column, $value) {
			$this->orWhere($column, 'pgsql' == Model::getConnectionResolver()->getDefaultConnection() ? 'ilike' : 'like', "%{$value}%");
			return $this;
		});

		Builder::macro('presentWhereIn', function ($column, $value) {
			if ($value) {
				$valuesArray = explode(',', $value);
				$this->whereIn($column, $valuesArray);
			}

			return $this;
		});

		Builder::macro('presentWhereLike', function ($column, $value) {
			if ($value) {
				$this->whereLike($column, $value);
			}

			return $this;
		});

		Builder::macro('presentWhere', function ($column, $operator, $value) {
			if (null !== $value) {
				$this->where($column, $operator, $value);
			}

			return $this;
		});

		Builder::macro('presentWhereDate', function ($column, $operator, $value) {
			if ($value) {
				$this->whereDate($column, $operator, Carbon::parse($value)->toDateString());
			}

			return $this;
		});

		Builder::macro('presentWhereRelation', function ($column, $relate, $value, $operator = '=') {
			if ($value) {
				$this->whereHas($relate, function ($query) use ($value, $column, $operator) {
					$query->where($column, $operator, $value);
				});
			}

			return $this;
		});

		Builder::macro('presentWhereLikeRelation', function ($column, $relate, $value) {
			if ($value) {
				$this->whereHas($relate, function ($query) use ($value, $column) {
					$query->whereLike($column, $value);
				});
			}

			return $this;
		});

		Builder::macro('presentWhereDateRelation', function ($column, $operator, $relate, $value) {
			if ($value) {
				$this->whereHas($relate, function ($query) use ($value, $column, $operator) {
					$query->whereDate($column, $operator, Carbon::parse($value)->toDateString());
				});
			}

			return $this;
		});

		Builder::macro('orWhereLikeRelation', function ($column, $relate, $value) {
			if ($value) {
				$this->orWhereHas($relate, function ($query) use ($value, $column) {
					$query->whereLike($column, $value);
				});
			}

			return $this;
		});

		Builder::macro('whereInRelation', function ($column, $relate, $value) {
			if ($value) {
				$valuesArray = explode(',', $value);
				$this->whereHas($relate, function ($query) use ($valuesArray, $column) {
					$query->whereIn($column, $valuesArray);
				});
			}

			return $this;
		});
	}
}
