<?php

use App\Services\QrisService;
use Illuminate\Support\Facades\Http;

test('create invoice success', function () {
    config(['services.qris.api_key' => 'test_api_key']);
    config(['services.qris.m_id' => 123456]);

    Http::fake([
        'qris.interactive.co.id/restapi/qris/show_qris.php*' => Http::response([
            'status' => 'success',
            'data' => [
                'qris_content' => '00020101021226680016ID.CO.PJSP.WWW011893600898025599662702150001952559966270303UMI51440014ID.CO.QRIS.WWW0215ID10200211817450303UMI520457325303360540825578.005502015802ID5916InterActive Corp6013KOTA SURABAYA61056013662130509413255111630439B7',
                'qris_request_date' => '2020-08-07 11:13:42',
                'qris_invoiceid' => '413255111',
                'qris_nmid' => 'ID1020021181745'
            ]
        ], 200)
    ]);

    $service = new QrisService();
    $result = $service->createInvoice('PJ0099', 10000, 'no');

    expect($result)->toBeArray();
    expect($result)->toHaveKey('qris_content');
    expect($result)->toHaveKey('qris_request_date');
    expect($result)->toHaveKey('qris_invoiceid');
    expect($result)->toHaveKey('qris_nmid');
});

test('create invoice failed', function () {
    config(['services.qris.api_key' => 'test_api_key']);
    config(['services.qris.m_id' => 123456]);

    Http::fake([
        'qris.interactive.co.id/restapi/qris/show_qris.php*' => Http::response([
            'status' => 'failed',
            'data' => [
                'qris_status' => 'invalid amount'
            ]
        ], 200)
    ]);

    $service = new QrisService();
    $result = $service->createInvoice('PJ0099', 10000, 'no');

    expect($result)->toBeNull();
});

test('create invoice http error', function () {
    config(['services.qris.api_key' => 'test_api_key']);
    config(['services.qris.m_id' => 123456]);

    Http::fake([
        'qris.interactive.co.id/restapi/qris/show_qris.php*' => Http::response('Server Error', 500)
    ]);

    $service = new QrisService();
    $result = $service->createInvoice('PJ0099', 10000, 'no');

    expect($result)->toBeNull();
});

test('check invoice status success paid', function () {
    config(['services.qris.api_key' => 'test_api_key']);
    config(['services.qris.m_id' => 123456]);

    Http::fake([
        'qris.interactive.co.id/restapi/qris/checkpaid_qris.php*' => Http::response([
            'status' => 'success',
            'data' => [
                'qris_status' => 'paid',
                'qris_payment_customername' => 'EDWIN PERDANA',
                'qris_payment_methodby' => 'BCA'
            ],
            'qris_api_version_code' => '2505011709'
        ], 200)
    ]);

    $service = new QrisService();
    $result = $service->checkInvoiceStatus(413255111, 10000, '2024-12-31');

    expect($result)->toBeArray();
    expect($result)->toHaveKey('qris_status', 'paid');
    expect($result)->toHaveKey('qris_payment_customername');
    expect($result)->toHaveKey('qris_payment_methodby');
});

test('check invoice status failed unpaid', function () {
    config(['services.qris.api_key' => 'test_api_key']);
    config(['services.qris.m_id' => 123456]);

    Http::fake([
        'qris.interactive.co.id/restapi/qris/checkpaid_qris.php*' => Http::response([
            'status' => 'failed',
            'data' => [
                'qris_status' => 'unpaid'
            ]
        ], 200)
    ]);

    $service = new QrisService();
    $result = $service->checkInvoiceStatus(413255111, 10000, '2024-12-31');

    expect($result)->toBeNull();
});

test('check invoice status http error', function () {
    config(['services.qris.api_key' => 'test_api_key']);
    config(['services.qris.m_id' => 123456]);

    Http::fake([
        'qris.interactive.co.id/restapi/qris/checkpaid_qris.php*' => Http::response('Server Error', 500)
    ]);

    $service = new QrisService();
    $result = $service->checkInvoiceStatus(413255111, 10000, '2024-12-31');

    expect($result)->toBeNull();
});