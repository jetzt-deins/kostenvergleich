<?php

use CodeIgniter\Router\RouteCollection;

/** @var RouteCollection $routes */
$routes->get('/', 'Home::index');

// Verwaltung Trucker
$routes->get('verwaltung/trucker', 'Verwaltung\Trucker::index');
$routes->get('verwaltung/trucker/neu', 'Verwaltung\Trucker::neu');
$routes->post('verwaltung/trucker/speichern', 'Verwaltung\Trucker::speichern');
$routes->get('verwaltung/trucker/bearbeiten/(:num)', 'Verwaltung\Trucker::bearbeiten/$1');
$routes->post('verwaltung/trucker/aktualisieren/(:num)', 'Verwaltung\Trucker::aktualisieren/$1');
$routes->get('verwaltung/trucker/loeschen/(:num)', 'Verwaltung\Trucker::loeschen/$1');

// Verpackungsarten
$routes->get('verwaltung/trucker/verpackungsart/neu/(:num)', 'Verwaltung\Trucker::verpackungsartNeu/$1');
$routes->post('verwaltung/trucker/verpackungsart/speichern', 'Verwaltung\Trucker::verpackungsartSpeichern');
$routes->get('verwaltung/trucker/verpackungsart/bearbeiten/(:num)', 'Verwaltung\Trucker::verpackungsartBearbeiten/$1');
$routes->post('verwaltung/trucker/verpackungsart/aktualisieren/(:num)', 'Verwaltung\Trucker::verpackungsartAktualisieren/$1');
$routes->get('verwaltung/trucker/verpackungsart/loeschen/(:num)', 'Verwaltung\Trucker::verpackungsartLoeschen/$1');

// Zusatzprodukte
$routes->get('verwaltung/trucker/zusatzprodukt/neu/(:num)', 'Verwaltung\Trucker::zusatzproduktNeu/$1');
$routes->post('verwaltung/trucker/zusatzprodukt/speichern', 'Verwaltung\Trucker::zusatzproduktSpeichern');
$routes->get('verwaltung/trucker/zusatzprodukt/bearbeiten/(:num)', 'Verwaltung\Trucker::zusatzproduktBearbeiten/$1');
$routes->post('verwaltung/trucker/zusatzprodukt/aktualisieren/(:num)', 'Verwaltung\Trucker::zusatzproduktAktualisieren/$1');
$routes->get('verwaltung/trucker/zusatzprodukt/loeschen/(:num)', 'Verwaltung\Trucker::zusatzproduktLoeschen/$1');

// Verwaltung Preistabellen
$routes->get('verwaltung/preistabellen', 'Verwaltung\Preistabellen::index');
$routes->get('verwaltung/preistabellen/anzeigen/(:num)/(:alpha)', 'Verwaltung\Preistabellen::anzeigen/$1/$2');
$routes->get('verwaltung/preistabellen/bearbeiten/(:num)/(:alpha)/(:num)', 'Verwaltung\Preistabellen::bearbeiten/$1/$2/$3');
$routes->post('verwaltung/preistabellen/aktualisieren/(:num)/(:alpha)/(:num)', 'Verwaltung\Preistabellen::aktualisieren/$1/$2/$3');
$routes->get('verwaltung/preistabellen/plz/neu/(:num)/(:alpha)', 'Verwaltung\Preistabellen::plzNeu/$1/$2');
$routes->post('verwaltung/preistabellen/aktualisieren/(:num)/(:alpha)/0', 'Verwaltung\Preistabellen::aktualisieren/$1/$2/0');

// Kalkulation
$routes->get('kalkulation', 'Kalkulation::index');
$routes->post('kalkulation/berechnen', 'Kalkulation::berechnen');

// Gewichtsklassen
$routes->get('verwaltung/gewichtsklassen/(:num)', 'Verwaltung\Gewichtsklassen::index/$1');
$routes->get('verwaltung/gewichtsklassen/neu/(:num)', 'Verwaltung\Gewichtsklassen::neu/$1');
$routes->post('verwaltung/gewichtsklassen/speichern', 'Verwaltung\Gewichtsklassen::speichern');
$routes->get('verwaltung/gewichtsklassen/bearbeiten/(:num)', 'Verwaltung\Gewichtsklassen::bearbeiten/$1');
$routes->post('verwaltung/gewichtsklassen/aktualisieren/(:num)', 'Verwaltung\Gewichtsklassen::aktualisieren/$1');
$routes->get('verwaltung/gewichtsklassen/loeschen/(:num)', 'Verwaltung\Gewichtsklassen::loeschen/$1');