{{ Builder::make('profile-form')->setRoute(route('profile.store'), $method ?? null)->setDefaultvalue([
'data' => $data ?? null
])->render() }}
