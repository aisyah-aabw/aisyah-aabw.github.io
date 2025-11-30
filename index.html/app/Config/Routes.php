<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');
$routes->get('/akun1', 'Akun1::index');
$routes->get('/akun1/new', 'Akun1::new');
$routes->post('/akun1', 'Akun1::store');
$routes->get('akun1/edit/(:num)', 'Akun1::edit/$1');
$routes->put('akun1/edit/(:num)', 'Akun1::update/$1');
$routes->post('akun1/(:num)', 'Akun1::update/$1');
$routes->delete('akun1/(:num)', 'Akun1::destroy/$1');

$routes->get('akun2/new', 'Akun2::new');
$routes->get('akun2/(;segment)/edit', 'Akun2::edit/$1');
$routes->post('akun2/(;any)/edit', 'Akun2::edit/$1');

$routes->get('akun3/new', 'Akun3::new');
$routes->get('akun3/new', 'Akun3::create');
$routes->get('akun3/(;segment)/edit', 'Akun3::edit/$1');


// Transaksi
$routes->get('transaksi/akun3', 'Transaksi::akun3');
$routes->get('transaksi/status', 'Transaksi::status');
$routes->get('transaksi', 'Transaksi::index');
$routes->get('transaksi/new', 'Transaksi::new');
$routes->post('transaksi', 'Transaksi::create');

$routes->get('transaksi/(:num)', 'Transaksi::show/$1');
$routes->get('transaksi/(:num)/edit', 'Transaksi::edit/$1');
$routes->put('transaksi/(:num)', 'Transaksi::update/$1');
$routes->delete('transaksi/(:num)', 'Transaksi::delete/$1');

$routes->get('/posting', 'Posting::index');
$routes->get('/posting/postingpdf', 'Posting::postingpdf');
$routes->get('posting', 'Posting::index');
$routes->post('posting', 'Posting::index');

$routes->get('jurnalpenyesuaian/cetakjupdf', 'Jurnalpenyesuaian::cetakjupdf');
$routes->post('jurnalpenyesuaian/cetakjupdf', 'Jurnalpenyesuaian::cetakjupdf');
$routes->get('jurnalpenyesuaian', 'Jurnalpenyesuaian::index');
$routes->post('jurnalpenyesuaian', 'Jurnalpenyesuaian::index');

$routes->get('neracasaldo/neracasaldopdf', 'NeracaSaldo::neracasaldopdf');
$routes->post('neracasaldo/neracasaldopdf', 'NeracaSaldo::neracasaldopdf');
$routes->get('neracasaldo', 'NeracaSaldo::index');
$routes->post('neracasaldo', 'NeracaSaldo::index');

$routes->get('neracalajur/neracalajurpdf', 'NeracaLajur::neracalajurpdf');
$routes->post('neracalajur/neracalajurpdf', 'n=NeracaLajur::neracalajurpdf');
$routes->get('neracalajur', 'NeracaLajur::index');
$routes->post('neracalajur', 'NeracaLajur::index');

$routes->get('/jurnalumum', 'JurnalUmum::index');
$routes->get('/jurnalumum/cetakjupdf', 'JurnalUmum::cetakjupdf');
$routes->get('jurnalumum', 'JurnalUmum::index');
$routes->post('jurnalumum', 'JurnalUmum::index');

$routes->get('/labarugi', 'LabaRugi::index');
$routes->get('/labarugi/labarugipdf', 'LabaRugi::labarugipdf');
$routes->get('labarugi', 'LabaRugi::index');
$routes->post('labarugi', 'LabaRugi::index');

$routes->get('/pmodal', 'Pmodal::index');
$routes->get('/pmodal/pmodalpdf', 'Pmodal::Pmodalpdf');
$routes->get('pmodal', 'Pmodal::index');
$routes->post('pmodal', 'Pmodal::index');

$routes->get('/neraca', 'Neraca::index');
$routes->get('/neraca/neracapdf', 'Neraca::Neracapdf');
$routes->get('neraca', 'neraca::index');
$routes->post('neraca', 'neraca::index');

$routes->get('/aruskas', 'Aruskas::index');
$routes->get('/aruskas/aruskaspdf', 'Aruskas::Aruskaspdf');
$routes->get('aruskas', 'Aruskas::index');
$routes->post('aruskas', 'Aruskas::index');

$routes->get('/penyesuaian/new', 'Penyesuaian::new');
$routes->get('/penyesuaian', 'Penyesuaian::index');
$routes->post('/penyesuaian/(any)', 'Penyesuaian::delete/$1');
$routes->get('/penyesuaian/(:segment)/edit', 'Penyesuaian::edit/$1');
$routes->get('/penyesuaian/(:any)', 'Penyesuaian::show/$1');

$routes->get('/admin', 'Admin::index',  ['filter' => 'role:admin']);
$routes->get('/admin/index', 'Admin::index',  ['filter' => 'role:admin']);
$routes->get('/home', 'Home::index');

$routes->get('/admin', 'Admin::index');
// $routes->get('/neraca/neracapdf', 'Neraca::Neracapdf');
$routes->get('admin', 'admin::index');
$routes->post('admin', 'admin::index');
$routes->get('admin/(:num)', 'Admin::detail/$1');


$routes->resource('akun2');
$routes->resource('akun3');
$routes->resource('transaksi');
$routes->resource('penyesuaian');
$routes->resource('admin');
