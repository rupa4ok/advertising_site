<?php

namespace App\Http\Controllers\Admin\Adverts;

trait AdvertFilter
{
	public function filter($request, $query)
	{
		if (!empty($value = $request->get('id'))) {
			return $query->where('id', $value);
		}
		if (!empty($value = $request->get('title'))) {
			return $query->where('title', 'like', '%' . $value . '%');
		}
		if (!empty($value = $request->get('user'))) {
			return $query->where('user_id', $value);
		}
		if (!empty($value = $request->get('region'))) {
			return $query->where('region_id', $value);
		}
		if (!empty($value = $request->get('category'))) {
			return $query->where('category_id', $value);
		}
		if (!empty($value = $request->get('status'))) {
			return $query->where('status', $value);
		}
		if (!empty($value = $request->get('name'))) {
			return $query->where('name', 'like', '%' . $value . '%');
		}
		if (!empty($value = $request->get('email'))) {
			return $query->where('email', 'like', '%' . $value . '%');
		}
		if (!empty($value = $request->get('status'))) {
			return $query->where('status', $value);
		}
		if (!empty($value = $request->get('role'))) {
			return $query->where('role', $value);
		}
	}
}


