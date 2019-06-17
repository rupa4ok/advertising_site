<?php

namespace App\Http\Controllers\Admin\Adverts;

trait AdvertFilter
{
	public function filter($request, $query)
	{
		if (!empty($value = $request->get('id'))) {
			$query->where('id', $value);
		}
		if (!empty($value = $request->get('title'))) {
			$query->where('title', 'like', '%' . $value . '%');
		}
		if (!empty($value = $request->get('user'))) {
			$query->where('user_id', $value);
		}
		if (!empty($value = $request->get('region'))) {
			$query->where('region_id', $value);
		}
		if (!empty($value = $request->get('category'))) {
			$query->where('category_id', $value);
		}
		if (!empty($value = $request->get('status'))) {
			$query->where('status', $value);
		}
		if (!empty($value = $request->get('name'))) {
			$query->where('name', 'like', '%' . $value . '%');
		}
		if (!empty($value = $request->get('email'))) {
			$query->where('email', 'like', '%' . $value . '%');
		}
		if (!empty($value = $request->get('status'))) {
			$query->where('status', $value);
		}
		if (!empty($value = $request->get('role'))) {
			$query->where('role', $value);
		}
		
		return $query->paginate(20);
	}
}


