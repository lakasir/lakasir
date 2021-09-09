{{ Builder::make('profile-form')->setRoute(route('profile.store'), $method ?? null)->setDefaultvalue([
'auth' => $auth ?? null
])->render() }}
