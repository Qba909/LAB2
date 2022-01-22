<?php
// KONTROLER strony kalkulatora
require_once dirname(__FILE__).'/../config.php';

// W kontrolerze niczego nie wysyła się do klienta.
// Wysłaniem odpowiedzi zajmie się odpowiedni widok.
// Parametry do widoku przekazujemy przez zmienne.

//ochrona kontrolera - poniższy skrypt przerwie przetwarzanie w tym punkcie gdy użytkownik jest niezalogowany
include _ROOT_PATH.'/app/security/check.php';


// 1. pobranie parametrów
function getParams(&$x,&$y,&$z){
    $x = isset($_REQUEST['x']) ? $_REQUEST['x'] : null;
    $y = isset($_REQUEST['y']) ? $_REQUEST['y'] : null;
    $z = isset($_REQUEST['z']) ? $_REQUEST['z'] : null;
}

// 2. walidacja parametrów z przygotowaniem zmiennych dla widoku

function validate(&$x,&$y,&$z,&$messages){
    // sprawdzenie, czy parametry zostały przekazane
    if ( ! (isset($x) && isset($y) && isset($z))) {
            //sytuacja wystąpi kiedy np. kontroler zostanie wywołany bezpośrednio - nie z formularza
            return false;
    }

    // sprawdzenie, czy potrzebne wartości zostały przekazane
    if ( $x == "") {
            $messages [] = 'Nie podano kwoty';
    }
    if ( $y == "") {
            $messages [] = 'Nie podano oprocentowania';
    }
    if ( $z == "") {
            $messages [] = 'Nie podano liczby rat';
    }
    
    //nie ma sensu walidować dalej gdy brak parametrów
    if (count ( $messages ) != 0) return false;
    
    //nie ma sensu walidować dalej gdy brak parametrów
    if (empty( $messages )) {

            // sprawdzenie, czy $x i $y są liczbami całkowitymi
            if (! is_numeric( $x )) {
                    $messages [] = 'Pierwsza wartość nie jest liczbą całkowitą';
            }
            if (! is_numeric( $y )) {
                    $messages [] = 'Druga wartość nie jest liczbą całkowitą';
            }
            if (! is_numeric( $z )) {
                    $messages [] = 'Trzecia wartość nie jest liczbą całkowitą';
            }	
    }
    
    if (count ( $messages ) != 0) return false;
    else return true;
}
// 3. wykonaj zadanie jeśli wszystko w porządku
function process(&$x,&$y,&$z,&$messages,&$result){
    global $role;
    if (empty ( $messages )) { // gdy brak błędów

            //konwersja parametrów na int
            $x = floatval($x);
            $y = floatval($y);
            $z = floatval($z);

            //wykonanie operacji
            $result = ($x + ($x * $y / 100)) / $z;

    }
}

//definicja zmiennych kontrolera
$x = null;
$y = null;
$z = null;
$result = null;
$messages = array();

//pobierz parametry i wykonaj zadanie jeśli wszystko w porządku
getParams($x,$y,$z);
if ( validate($x,$y,$z,$messages) ) { // gdy brak błędów
	process($x,$y,$z,$messages,$result);
}

// 4. Wywołanie widoku z przekazaniem zmiennych
// - zainicjowane zmienne ($messages,$x,$y,$operation,$result)
//   będą dostępne w dołączonym skrypcie
include 'calc_view.php';