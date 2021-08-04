<?php
// routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

Breadcrumbs::for('customer_type.index', function (BreadcrumbTrail $trail) {
    $trail->push(__('app.customer_types.title'), route('customer_type.index'));
});

Breadcrumbs::for('customer_type.create', function (BreadcrumbTrail $trail) {
    $trail->parent('customer_type.index');
    $trail->push(__('app.customer_types.create.title'), route('customer_type.index'));
});

Breadcrumbs::for('customer_type.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('customer_type.index');
    $trail->push(__('app.customer_types.edit.title', ['title' => $data->name]), route('customer_type.index'));
});
