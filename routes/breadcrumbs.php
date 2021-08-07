<?php
// routes/breadcrumbs.php

// Note: Laravel will automatically resolve `Breadcrumbs::` without
// this import. This is nice for IDE syntax and refactoring.
use Diglactic\Breadcrumbs\Breadcrumbs;

// This import is also not required, and you could replace `BreadcrumbTrail $trail`
//  with `$trail`. This is nice for IDE type checking and completion.
use Diglactic\Breadcrumbs\Generator as BreadcrumbTrail;

/**
 * Customer
 *
 */
Breadcrumbs::for('customer.index', function (BreadcrumbTrail $trail) {
    $trail->push(__('app.customers.title'), route('customer.index'));
});

Breadcrumbs::for('customer.create', function (BreadcrumbTrail $trail) {
    $trail->parent('customer.index');
    $trail->push(__('app.customers.create.title'), route('customer.index'));
});

Breadcrumbs::for('customer.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('customer.index');
    $trail->push(__('app.customers.edit.title', ['title' => $data->name]), route('customer.index'));
});


/**
 * Customer Type
 *
 */
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

/**
 * Suppplier
 *
 */
Breadcrumbs::for('supplier.index', function (BreadcrumbTrail $trail) {
    $trail->push(__('app.suppliers.title'), route('supplier.index'));
});

Breadcrumbs::for('supplier.create', function (BreadcrumbTrail $trail) {
    $trail->parent('supplier.index');
    $trail->push(__('app.suppliers.create.title'), route('supplier.index'));
});

Breadcrumbs::for('supplier.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('supplier.index');
    $trail->push(__('app.suppliers.edit.title', ['title' => $data->name]), route('supplier.index'));
});


/**
 * Category
 *
 */
Breadcrumbs::for('category.index', function (BreadcrumbTrail $trail) {
    $trail->push(__('app.categories.title'), route('category.index'));
});

Breadcrumbs::for('category.create', function (BreadcrumbTrail $trail) {
    $trail->parent('category.index');
    $trail->push(__('app.categories.create.title'), route('category.index'));
});

Breadcrumbs::for('category.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('category.index');
    $trail->push(__('app.categories.edit.title', ['title' => $data->name]), route('category.index'));
});

Breadcrumbs::for('group.index', function (BreadcrumbTrail $trail) {
    $trail->push(__('app.groups.title'), route('group.index'));
});

Breadcrumbs::for('group.create', function (BreadcrumbTrail $trail) {
    $trail->parent('group.index');
    $trail->push(__('app.groups.create.title'), route('group.index'));
});

Breadcrumbs::for('group.edit', function (BreadcrumbTrail $trail, $data) {
    $trail->parent('group.index');
    $trail->push(__('app.groups.edit.title', ['title' => $data->name]), route('group.index'));
});
