<?php

namespace Frame\Models;

use Frame\Exceptions\ParkingsExceptions;


class LicensePlateModel extends WpBaseModel {

    protected string $modelName = 'wp_postmeta';

    public function __construct() {
        parent::__construct();
    }


    /**
     * Dato l'id dell'abbonamento recuperato dalla targa
     * verifica che questo sia associato al parcheggio correntemente selezionato
     * dal controllore
     */

    private function getPark($subscription_id,$parkName) {
    

        if( isset(ParkingsExceptions::$list[$parkName]) ) {

            return $this->retryException($subscription_id,$parkName);

        }

        return $this->exec(
            "SELECT *
            FROM wp_postmeta
            WHERE post_id = :subscription_id
            AND meta_key = 'cpbs_location_name'
            AND meta_value = :park",
            [   
                ':subscription_id' => $subscription_id,
                ':park' => $parkName
            ],
            false
        );

        
        
    }

    /**
     * Data la targa dall'input del controllore,
     * tenta di recuperare match plausibili dal DB in wordpress
     * e ritorna l'id dell'abbonamento attivo per quella targa
     */

    private function verifyPlate($plate) {

        if(! $matches = $this->getRelatableMatches( $plate ) ) return false;

        // Scremtura dei matchplausibili
        return \array_filter($matches ,function($record) use ($plate) {

            $unserialized = \unserialize($record->meta_value);
            
            if(!$unserialized) return false; 

            // Iterazione targhe divise da una virgola
            return (bool)\array_filter(\explode(',',$unserialized[0]['value']),function($splittedPlate) use ($plate) {
                return \trim(\strtolower($splittedPlate)) === \trim(\strtolower($plate));
            }); 

        });

    }

    private function getRelatableMatches($plate) {

        return $this->exec(
            "SELECT post_id AS id,
            meta_value
            FROM wp_postmeta
            WHERE meta_value LIKE :plate
            AND meta_key = 'cpbs_form_element_field'",
            [
                ':plate' => "%targa%$plate%",
            ],
        );
    }

    public function checkPlate($plate,$parkName) {

        if(!$subscriptions = $this->verifyPlate($plate, $parkName)) return false;

        // Scremati i match plausibili effettuo un ulteriore scrematura
        // sulla base del parcheggio corrente
        return \array_filter($subscriptions,function($subscription) use($parkName) {
            return $this->getPark($subscription->id,$parkName);
        });

        return false;
    }

    /**
     * Verifica che esista almeno un abbonamento attivo
     * dati i match recuperati
     */
    public function isSubscriptionActive($subscriptions) {

        $now = new \DateTime('now');


        return \array_filter($subscriptions,function($subscription) use($now){

            $start = new \DateTime($this->getEntryDateTime($subscription->id)->start_date);

            $end = new \DateTime($this->getExitDateTime($subscription->id)->end_date . ' 23:59:59');

            return ($start <= $now && $end >= $now);

        });
    }

    private function getEntryDateTime($subscription_id) {
        
        return $this->exec(
            "SELECT meta_value AS start_date
            FROM wp_postmeta
            WHERE post_id = :subscription_id
            AND meta_key = 'cpbs_entry_date'",
            [
                ':subscription_id' => $subscription_id
            ],
            false
        );
    }

    private function getExitDateTime($subscription_id) {
        
        return $this->exec(
            "SELECT meta_value AS end_date
            FROM wp_postmeta
            WHERE post_id = :subscription_id
            AND meta_key = 'cpbs_exit_date'",
            [
                ':subscription_id' => $subscription_id
            ],
            false
        );
    }

    private function getWooCommerceRelatedOrder($subscription_id) {
        return $this->exec(
            "SELECT meta_value AS wc_booking_id
            FROM wp_postmeta
            WHERE post_id = :subscription_id
            AND meta_key = 'cpbs_woocommerce_booking_id'",
            [
                ':subscription_id' => $subscription_id
            ],
            false
        );
    }

    private function getWooCommerceOrderStatus($order) {

        return $this->exec(
            "SELECT status
            FROM wp_wc_order_stats
            WHERE order_id = :order_id
            AND status = 'wc-completed'",
            [
                ':order_id' => $order->wc_order_id
            ],
            false
        );
    }

    public function verifyPaymentStatus($activeSubscriptions) {

        if(!(bool)$orders = \array_map(function($subscription) {

            if($res = $this->getWooCommerceRelatedOrder($subscription->id)) return (object) [
                'wc_order_id' => $res->wc_booking_id,
                'cpbs_subscription_id' => $subscription->id,
                'plates' => \unserialize($subscription->meta_value)[0]['value']
            ];
            
        },$activeSubscriptions)) return false;


        return \array_filter($orders, function($order) {
            
            if($wooCommerceOrder = $this->getWooCommerceOrderStatus($order)) return $order->status = $wooCommerceOrder->status;

        });
    }

    public function lookIntoOldSubscriptions($plate,$parkName) {

        if(! (bool) $oldSubscriptions= $this->getOldPlates($plate,$parkName)) return false ;

        if (! (bool) $this->isOldSubscriptionActive($oldSubscriptions) ) return false ;

        return true;
    }

    /**
     * Tenta di recuperare gli input del controllore dai vecchi abbonamenti
     * DISPONIBILI MASSIMO FINO AL 31/01/2023
     */
    private function getOldPlates($plate,$parkName) {

        $getLocals = static::$localDb->prepare(
            "SELECT * 
            FROM old_subscriptions
            INNER JOIN parkings ON old_subscriptions.park_id = parkings.id
            WHERE parkings.nome = :parkname"
        );

        $getLocals->execute([
            ':parkname' => $parkName
        ]);

        if(! $oldRecords = $getLocals->fetchAll() ) return false;

        return \array_filter( $oldRecords, function($oldRecord) use ($plate) {

            return \in_array ( \strtolower(\trim($plate)), \array_map(function($oldPlate) {
                
                return \strtolower(\trim($oldPlate));

            },\explode(',',$oldRecord->plate)) );

        });
    }

    private function isOldSubscriptionActive($oldSubscriptions) {
        
        $now = new \DateTime('now');

        return \array_filter($oldSubscriptions,function($subscription) use($now){

            $start = new \DateTime($subscription->entry_date);

            $end = new \DateTime($subscription->exit_date);

            return ($start <= $now && $end >= $now);

        });

    }

    private function retryException($subscription_id, $parkName) {

        $source = $this->exec(
            "SELECT *
            FROM wp_postmeta
            WHERE post_id = :subscription_id
            AND meta_key = 'cpbs_location_name'
            AND meta_value = :park",
            [   
                ':subscription_id' => $subscription_id,
                ':park' => $parkName
            ],
            false
        );

        if( $source ) return $source ;


        return $this->exec(
            "SELECT *
            FROM wp_postmeta
            WHERE post_id = :subscription_id
            AND meta_key = 'cpbs_location_name'
            AND meta_value = :park",
            [   
                ':subscription_id' => $subscription_id,
                ':park' => ParkingsExceptions::$list[$parkName]
            ],
            false
        );
    }

    public function updateLocalChecklist($record,$now) {

        $update = static::$localDb->prepare(
            "UPDATE checklist
            SET last_checked_at = :last_checked_at ,
            user_id = :user_id
            WHERE id = :id"
        );

        $update->execute([
            ':id' => $record->id ,
            ':last_checked_at' => $now->format('Y-m-d H:i:s') ,
            ':user_id' => $_SESSION['user']->id
        ]);
    }

    public function insertIntoLocalChecklist($subscriptionId,$plate,$parkName,$now) {

        $insert = static::$localDb->prepare(
            "INSERT INTO checklist ( subscription_id , plate,park_name , user_id , last_checked_at )
            VALUES( :subscription_id , :plate , :park_name , :user_id , :last_checked_at )"
        );

        $insert->execute([
            ':subscription_id' => $subscriptionId , 
            ':plate' => \strtoupper(\trim($plate)) , 
            ':park_name' => $parkName , 
            ':user_id' => $_SESSION['user']->id , 
            ':last_checked_at' =>  $now->format('Y-m-d H:i:s')
        ]);

    }

    public function getLocalChecklistMatch($plate,$parkName,$subscriptionId) {

        $res = static::$localDb->prepare(
            "SELECT *
            FROM checklist
            WHERE plate = :plate
            AND subscription_id = :subscription_id
            AND park_name = :park_name"
        );

        $res->execute([
            ':plate' => $plate ,
            ':subscription_id' => $subscriptionId ,
            ':park_name' => $parkName
        ]);

        return $res->fetch();

    }

    public function lookForSubscriptionLastCheck( $subscriptionId , $plate, $parkName  ) {

        $italyTimeZone = new \DateTimeZone('Europe/Rome');
        
        $controlPeriod = new \DateTime( $parkName === 'Piazza Castello' ? '-2 hours' : '-1 hour' , $italyTimeZone );

        $query = static::$localDb->prepare(
            "SELECT *
            FROM checklist
            WHERE last_checked_at >= :date
            AND subscription_id = :subscription_id
            AND plate != :plate"
        );

        $query->execute([
            ':date' => $controlPeriod->format('Y-m-d H:i:s') ,
            ':subscription_id' => $subscriptionId ,
            ':plate' => $plate
        ]);

        return $query->fetchAll();
    }

}