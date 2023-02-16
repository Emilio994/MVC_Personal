<?php

namespace Frame\Controllers;
use Frame\Http\Request;
use Frame\Models\LicensePlateModel;

class LicensePlateController extends BaseController{

    private LicensePlateModel $plate;

    public function __construct(LicensePlateModel $licensePlate)
    {
        $this->plate = $licensePlate;
    }

    public function validateSubscription(Request $request) {
        

        \header('Content-Type: application/json');

        // Verifico che l'iscrizione non sia già attiva tra gli abbonamenti comprati nella vecchia piattaforma
        if( (bool) $this->plate->lookIntoOldSubscriptions( $request->params['plate'] , $request->params['park'] ) ) {
            return \json_encode([
                'error' => [
                    'status' => false ,
                    'message' => ''
                ]
            ]);
        }

        /** Verifico l'esistenza di una targa tra gli abbonamenti del plugin Car Booking Parking System
         *  se trovo una targa, verifico la corrispondenza tra quella targa e il parcheggio corrente
        */
        if(! (bool) $subscriptions = $this->plate->checkPlate( trim($request->params['plate']) , $request->params['park'] ) ) {
            return \json_encode([
                'error' => [
                    'status' => true,
                    'message' => 'Non è presente nessun abbonamento attivo per la targa indicata nel parcheggio corrente'
                ],
            ]);
        }

        // Verifico il periodo di validità degli abbonamenti trovati
        if(! (bool) $activeSubscriptions = $this->plate->isSubscriptionActive( $subscriptions ) ) {
            return \json_encode([
                'error' => [
                    'status' => true,
                    'message' => 'Il veicolo con la targa indicata non ha un abbonamento attivo in questo istante'
                ],
            ]);
        }

        // Verifico lo stato del pagamento per gli abbonamenti attivi
        if(! (bool) $verifiedSubscriptions = $this->plate->verifyPaymentStatus( $activeSubscriptions ) ) {
            return \json_encode([
                'error' => [
                    'status' => true,
                    'message' => 'Il cliente non ha effettuato il pagamento per l\'abbonamento'
                ],
            ]);
        }

        // Verifico l'esistenza di un veicolo già parcheggiato sotto il medesimo abbonamento *prima di aggiornare la checklist
        if( (bool) $this->hasAlreadyParked( $verifiedSubscriptions , $request->params['plate'] , $request->params['park'] ) ) {
            return \json_encode([
                'error' => [
                    'status' => true,
                    'message' => 'Il cliente ha già un veicolo parcheggiato per questo abbonamento'
                ],
            ]);
        };

        // Aggiorno la checklist
        \array_map( function( $subscription ) use( $request ) {

            $this->upSertResultsToLocalCheckList( trim($request->params['plate']) , $request->params['park'] , $subscription->cpbs_subscription_id );

        } , $verifiedSubscriptions );

        // Esito positivo
        return \json_encode([
            'error' => [
                'status' => false,
                'message' => ''
            ],
        ]);

    }

    private function upSertResultsToLocalCheckList( $plate , $parkName , $subscriptionId ) {
        
        $res = $this->plate->getLocalChecklistMatch( $plate , $parkName , $subscriptionId );

        $italyTimeZone = new \DateTimeZone('Europe/Rome');

        $now = new \DateTime('now', $italyTimeZone );

        if( $res ) {

            $this->plate->updateLocalChecklist($res,$now); 

        } else $this->plate->insertIntoLocalChecklist($subscriptionId,$plate,$parkName,$now);
        
    }

    private function hasAlreadyParked( $subscriptions , $plate , $parkName ) {

        return \array_filter( $subscriptions , function( $subscription ) use ( $plate , $parkName ) {

            return $this->plate->lookForSubscriptionLastCheck( $subscription->cpbs_subscription_id , $plate, $parkName );

        } );
    }   

}