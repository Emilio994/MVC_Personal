COME RECUPERARE I PARCHEGGI IN WPDB :
    - tabella wp_post -> col post_type -> cpbs_location -> status = publish

VERIFICA DELL'ABBONAMENTO (le query avvengono nel modello LicensePlateModel e vengono invocate dal LicensePlateController):

    STEP 1 - function getRelatableMatches($inputControllore)
    tabella wp_postmeta -> [
        col => 'meta_key' = 'cpbs_form_element_field',
        col => 'meta_value' => 'input controllore'
    ]
    @return $matches

    STEP 2 - function verifyPlates($inputControllore)
    filtro i match, contenenti il valore reale della targa con l'input dato dal controllore 
    @return $subscription

    STEP 3 - function getPark($subscription_id,$parkName)
    questa funzione viene richiamata ciclando i matches filtrati, 
    quindi di cui realmente esiste una targa con un abbonamento,
    e verificando che quell'abbonamento faccia riferimento a quel parcheggio
    @return boolean

    STEP 4 - function isSubscriptionActive($subscriptions)
    verifica che il periodo dell'abbonamento rientri nell'istante in cui avviene
    il controllo



WPDB STRUCTURE
cpbs_form_element_field -> filter_actual_plates -> 