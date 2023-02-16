<?php // Lista Parcheggi ?>
<?php //dd($parkings); ?>
<section class="pList mb-5 pb-3" id="parkList">
    
</section>

<template id="listItem">
    <div class="pElement p-4 parkItem">
        <i data-feather="map-pin"></i> {{parking-name}}
    </div>
</template>
