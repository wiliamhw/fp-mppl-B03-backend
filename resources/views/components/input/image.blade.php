{!! CmsForm::file($variable, [
    'accept' => implode(',', [
        'image/png',
        'image/jpg',
        'image/jpeg'
    ]),
    'required' => false,
])->setTitle($title) !!}
